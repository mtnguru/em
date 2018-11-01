<?php

namespace Drupal\az_content;

use Drupal\Component\Serialization\Yaml;
use Drupal\Component\Utility\Xss;

/**
 * Class AzMaestroInit.
 *
 * @package Drupal\az_content
 */
class AzMaestroInit {

  static public function start($set, &$element) {

    $element['#attached']['drupalSettings']['azmaestro'] = [
      $set['id'] => $set,
    ];

    $loaded = &drupal_static('maestroLoaded', false);
    if (!$loaded) {
      $loaded = true;
      $element['#attached']['library'] = 'az_content/az-maestro';
    }
  }
}

