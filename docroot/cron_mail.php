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
// I could also get the

//---------------------------------------------------------
// do my query for all atoms
$diffMin = 700;
$set = [
  'types' => ['article', 'book'],
  'status' => NODE_PUBLISHED,
  'publishDate' =>  350,
  'fields' => [
    'nfd' => ['nid', 'title', 'status'],
    'nfpd' => ['field_publish_date_value'],
  ],
];

$listName = 'test list';
$segmentName = 'Immediate';
$templateName = 'Weekly Newsletter - Final';
$viewMode = 'email';

try {
  // Get the MailchimpLists api object
  $mc_lists = mailchimp_get_api_object('MailchimpLists');
  // Get all lists
  $lists = $mc_lists->getLists([
    'filter[lists.name]' => $listName,
//  'get_all' => true,
//  'count' => 1,
  ]);
//$lists = $mc_lists->getLists(['filter%5Bname%5D' => $listName]);
  // Find the list for this email.
  $list = NULL;
  foreach ($lists->lists as $list) {
    if ($listName == $list->name) {break;}
  }
  if (!$list) {
    throw new Exception("ERROR: Could not find list: $listName");
  }
  $listId = $list->id;

  // Retrieve the segments for the selected list.
  $segment = NULL;
  $segments = $mc_lists->getSegments($listId);
  foreach ($segments->segments as $segment) {
    if ($segment->name == $segmentName && $segment->type == 'saved') {break;}
  }
  if (!$segment) {
    throw new Exception("ERROR: Could not find segment: $segmentName\n");
  }

  // CAMPAIGNS

  // Get the MailchimpCampaigns api object
  $mc_campaigns = mailchimp_get_api_object('MailchimpCampaigns');
  // Get all campaigns - not really needed to POC
  $campaigns = $mc_campaigns->getCampaigns([
    'count' => 200,
  ]);
  foreach ($campaigns->campaigns as $campaign) {
    $title = $campaign->settings->title;
    print ("Campaign $campaign->id   $title\n");
  }

  // TEMPLATES

  // Get the MailchimpTemplates api object
  $mc_templates = mailchimp_get_api_object('MailchimpTemplates');
  // Get all templates - not really needed to POC
  $templates = $mc_templates->getTemplates([
//  'page%5Bsize%5D' => 50]);
//  'fields' => 'id',
    'count' => 200,
  ]);
  foreach ($templates->templates as $template) {
    if ($template->name == $templateName) {
      break;
    }
  }
  if (!$template) {
    throw new Exception("ERROR: Could not find template: $templateName\n");
  }



  $recipients = new stdClass();
  $recipients->list_id = $listId;
  $recipients->list_is_active = true;
  $recipients->list_name = $listName;
  $recipients->segment_text = '<p class="!margin--lv0">Contacts match <strong>all</strong> of the following conditions:</p><ol id="conditions" class="small-meta"><li class="margin--lv1 !margin-left-right--lv0">Tags contact is tagged <strong>Daily</strong></li></ol><span>For a total of <strong>0</strong> emails sent.</span>';
  $recipients->recipient_count = 0;

  $recipients->segment_opts = new stdClass();
  $recipients->segment_opts->match = "all";
  $recipients->segment_opts->conditions = [];
  $recipients->segment_opts->conditions[0] = new stdClass();
  $recipients->segment_opts->conditions[0]->condition_type = "StaticSegment";
  $recipients->segment_opts->conditions[0]->field = "static_segment";
  $recipients->segment_opts->conditions[0]->op = "static_js";
  $recipients->segment_opts->conditions[0]->value = "14153";

//$result = $mc_campaigns->addCampaign(\Mailchimp\MailchimpCampaigns::CAMPAIGN_TYPE_REGULAR, $recipients, $settings);

//if (!empty($result->id)) {
//  $campaign_id = $result->id;
//  $mc_campaigns->setCampaignContent($campaign_id, $content_parameters);
//}
}
catch (\Exception $e) {
  $message = $e->getMessage();
  print "$message\n";
  \Drupal::logger('mailchimp_notifications')->error($message);
  exit();
}


$nodes = AzContentQuery::nodeQuery($set);
print ("Total Rows: $nodes[totalRows]\n");
print ("Num Rows: $nodes[numRows]\n");

$count = 0;
$output = '';
foreach ($nodes['results'] as $row) {
  $count++;
  $node = \Drupal::entityTypeManager()->getStorage('node')->load($row->nid);
  $output += render(\Drupal::entityTypeManager()->getViewBuilder('node')->view($node, $viewMode));
  $label = $node->label();
  print (" $count    $row->nid    $row->field_publish_date_value    $row->title\n");
  $output += '<hr>';
}

print "/n/n/n" . $output;

print ("done\n\n");

