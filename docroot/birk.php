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

// Bootstrap.
$autoloader = require_once 'autoload.php';
require_once 'core/includes/bootstrap.inc';
$request = Request::createFromGlobals();
Settings::initialize(dirname(dirname(__DIR__)), DrupalKernel::findSitePath($request), $autoloader);
$kernel = DrupalKernel::createFromRequest($request, $autoloader, 'prod')->boot();
$kernel->boot();
$kernel->prepareLegacyRequest($request);

// do my query for all atoms
$set = [
  'types' => 'atom',
];

$atoms = AzContentQuery::nodeQuery($set);

print ("Total Rows: " . $atoms['totalRows']);

$count = 0;
foreach ($atoms['results'] as $row) {
//if ($row->nid != 267 || $row->nid == 248) continue;
//if ($row->nid != 546) continue;
  $count++;
  print ('  ' . $count . '  Row ' . $row->nid . "\n");
  $node = \Drupal::entityTypeManager()->getStorage('node')->load($row->nid);
  $text = $node->field_atomic_structure->value;
  print ($node->label() . "\n\n");
  print ("\nStarting Text \n" . $text . "\n\n");
  $arr = Yaml::decode($text);
  convertParticles($arr['N0']);
  $text = Yaml::encode($arr);
  print ("\nEnd Text \n" . $text . "\n\n");
  $node->set('field_atomic_structure', $text);
  $node->save();
}

print ("done\n\n");

function convertParticles(&$nuclet) {
  $keys = array_keys($nuclet['protons']);
  $values = array_values($nuclet['protons']);
  $stringKeys = array_map('strval', $keys);

  $protons = [];
  foreach ($stringKeys as $key) {
    $protons['P' . $values[$key]] = null;
  }
  unset($nuclet['protons']);
  $nuclet['protons'] = $protons;

  if (isset($nuclet['electrons'])) {
    $keys = array_keys($nuclet['electrons']);
    $values = array_values($nuclet['electrons']);
    $stringKeys = array_map('strval', $keys);
    $electrons = [];
    foreach ($keys as $key) {
      $skey = 'E' . $key;
      $electrons[$skey] = [
        'protons' => [],
      ];
      foreach ($values[$key] as $proton) {
        if (is_numeric($proton)) {
          $electrons[$skey]['protons'][] = 'P' . strval($proton);
        } else {
          $electrons[$skey]['protons'][] = strval($proton);
        }
      }
    }
    unset($nuclet['electrons']);
    $nuclet['electrons'] = $electrons;
  }

  foreach ($nuclet['nuclets'] as &$cnuclet) {
    convertParticles($cnuclet);
  }
}
