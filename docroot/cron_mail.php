#!/usr/bin/env php

<?php

/**
 * @file
 * A command line application to generate proxy classes.
 */

use Drupal\az_content\AzContentQuery;
use Drupal\Core\Command\GenerateProxyClassApplication;
use Drupal\Core\DrupalKernel;
use Drupal\Core\ProxyBuilder\ProxyBuilder;
use Drupal\Core\Serialization\Yaml;
use Drupal\Core\Site\Settings;
use Drupal\node\Entity\Node;
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
$set = [
  'types' => ['article', 'book'],
  'status' => NODE_PUBLISHED,
  'publish_date' => 1440,
];

$nodes = AzContentQuery::nodeQuery($set);
print ("Total Rows: $nodes[totalRows]\n");
print ("Num Rows: $nodes[numRows]\n");

$count = 0;
foreach ($nodes['results'] as $row) {
  $count++;
  print ('  ' . $count . '  Row ' . $row->nid . "\n");
  $node = \Drupal::entityTypeManager()->getStorage('node')->load($row->nid);
  print ($node->label() . "\n\n");
//$node->save();
}

print ("done\n\n");

