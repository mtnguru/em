#!/usr/bin/env php

<?php

/**
 * @file
 * A command line application to generate proxy classes.
 */

use Drupal\az_content\AzContentQuery;
use Drupal\Core\Command\GenerateProxyClassApplication;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\DrupalKernel;
use Drupal\Core\ProxyBuilder\ProxyBuilder;
use Drupal\Core\Render\Renderer;
use Drupal\Core\Serialization\Yaml;
use Drupal\Core\Site\Settings;
use Drupal\node\Entity\Node;
use Mailchimp\Mailchimp;
use Symfony\Component\HttpFoundation\Request;

if (PHP_SAPI !== 'cli') {
  return;
}

// Bootstrap Drupal
$autoloader = require_once 'autoload.php';
require_once 'core/includes/bootstrap.inc';
$request = Request::createFromGlobals();
Settings::initialize(dirname(dirname(__DIR__)), DrupalKernel::findSitePath($request), $autoloader);
$kernel = DrupalKernel::createFromRequest($request, $autoloader, 'prod')->boot();
$kernel->boot();
$kernel->prepareLegacyRequest($request);

// Query for the nodes and the comments
// Then build the daily list from that.
$diffMin = 700;
$querySet = [
  'types' => ['article', 'book'],
  'status' => NODE_PUBLISHED,
  'more' => 'limit',
  'limit' => 5,
//'publishDate' =>  350,
  'fields' => [
    'nfd' => ['nid', 'title', 'status'],
//  'nfpd' => ['field_publish_date_value'],
  ],
];

$subject = 'Daily Update - ' . date();
$settings = [
  'subject_line' => $subject,
  'title' => $subject,
  'from_name' => 'Ethereal Matters',
  'reply_to' => 'info@ecosleuth.com',
];

$listName = 'test list';
$segmentName = 'Daily';
$templateName = 'Weekly Newsletter - Final';
$viewMode = 'email';

/**
 * Search an array of objects by property
 *
 * @param $array
 * @param $index
 * @param $value
 *
 * @return null
 */
function searchArrayOfObjects($array, $index, $value) {
  foreach($array as $arrayInf) {
    if($arrayInf->{$index} == $value) {
      return $arrayInf;
    }
  }
  return null;
}

/**
 * Read in the campaigns and find one of them.
 *
 * @param $campaignName
 *
 * @return null
 */
function findCampaign($campaignName) {
  // CAMPAIGNS
  // Get the MailchimpCampaigns api object
  $mc_campaigns = mailchimp_get_api_object('MailchimpCampaigns');
  // Get all campaigns - not really needed to POC
  $campaigns = $mc_campaigns->getCampaigns([
    'count' => 200,
  ]);
  foreach ($campaigns->campaigns as $campaign) {
    if ($campaign->settings->title == $campaignName) {
      return $campaign;
    }
  }
  return null;
}

/**
 * Read in lists and segments and return them.
 *
 * @param $listName
 * @param $segmentName
 *
 * @return array
 */
function findRecipients($listName, $segmentName) {
  try {
    // Get the MailchimpLists api object
    $mc_lists = mailchimp_get_api_object('MailchimpLists');
    // Get all lists
    $lists = $mc_lists->getLists([
      'filter[lists.name]' => $listName,
      //  'get_all' => true,
      //  'count' => 1,
    ]);

    $list = NULL;
    foreach ($lists->lists as $list) {
      if ($listName == $list->name) {
        break;
      }
    }
    if (!$list) {
      throw new Exception("ERROR: Could not find list: $listName");
    }
    $listId = $list->id;

    // Retrieve the segments for the selected list.
    $segment = NULL;
    $segments = $mc_lists->getSegments($listId);
    foreach ($segments->segments as $segment) {
      if ($segment->name == $segmentName && $segment->type == 'static') {
        break;
      }
    }
    if (!$segment) {
      throw new Exception("ERROR: Could not find segment: $segmentName\n");
    }
    return [
      'list' => $list,
      'segment' => $segment,
    ];
  }
  catch (\Exception $e) {
    $message = $e->getMessage();
    print "$message\n";
    \Drupal::logger('mailchimp_notifications')->error($message);
    exit();
  }
}

/**
 * Read in all the 'user' templates and find a specific one.
 */
function findTemplate($name) {
  $template = null;

  // Get the MailchimpTemplates api object
  $mc_templates = mailchimp_get_api_object('MailchimpTemplates');
  // Get all templates - not really needed to POC
  $templates = $mc_templates->getTemplates([
    'type' => 'user',
    'count' => 100,
  ]);
  $template = searchArrayOfObjects($templates->templates,'name', $name);
  if (!$template) {
    throw new Exception("ERROR: Could not find template: $name\n");
  }
  return $template;
}

function getRenderedNodes($querySet, $viewMode) {
  $nodes = AzContentQuery::nodeQuery($querySet);
  print ("Total Rows: $nodes[totalRows]\n");
  print ("Num Rows: $nodes[numRows]\n");

  $count = 0;
  $rnodes = [];
  foreach ($nodes['results'] as $row) {
    $count++;
    $node = \Drupal::entityTypeManager()->getStorage('node')->load($row->nid);
    $rendered = \Drupal::service('renderer')->renderRoot(\Drupal::entityTypeManager()->getViewBuilder('node')->view($node, $viewMode));
    $label = $node->label();
    $rnodes[$label] = $rendered;
  }
  return $rnodes;
}

function buildTemplateContent($querySet, $viewMode, $template) {
  $rnodes = getRenderedNodes($querySet, $viewMode);

  $content = '';
  foreach ($rnodes as $label => $rnode) {
    $content .= $rnode;
    $content .= '<hr>';
  }

  $templateContent = [
    'top body' => [
      'value' => $content,
      'format' => 'mailchimp_campaign',
    ],
    'left column' => [
      'value' => 'My campaign left column',
      'format' => 'mailchimp_campaign',
    ],
    'right column' => [
      'value' => 'My campaign right column',
      'format' => 'mailchimp_campaign',
    ],
    'bottom body' => [
      'value' => 'My campaign bottom section',
      'format' => 'mailchimp_campaign',
    ],
  ];
  return $templateContent;
}

// ---- Build email and send it.
try {
  $rec = findRecipients($listName, $segmentName);
  $template = findTemplate($templateName);
  $templateContent = buildTemplateContent($querySet, $viewMode, $template);

  $recipients = [
    'list_id' => 'a7880c3277',
    'segment_opts' => (object)['saved_segment_id' => 14149],
  ];

  // Create the campaign
  $campaign_id = mailchimp_campaign_save_campaign(
    $templateContent,
    $recipients,
    $settings,
    $template->id,
   null
  );
  // Send the campaign
  $mc_campaigns->send($campaign_id);
}

catch (\Exception $e) {
  $message = $e->getMessage();
  print "$message\n";
  \Drupal::logger('mailchimp_notifications')->error($message);
  exit();
}


print "/n/n/n" . $output;

print ("done\n\n");

