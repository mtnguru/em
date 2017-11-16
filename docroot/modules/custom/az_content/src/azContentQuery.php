<?php
/**
* @file
* Display top Navigation menu for groups depending on the URL.
*/


/**
 * Displays children pages as a block
 */

namespace Drupal\az_content;

/**
 * Provides query for recent content 
 *   Array $settings configures query parameters.
 */
class azContentQuery {

  static public function queryContent(Array $settings) {
    $query = \Drupal::database()->select('group_content_field_data', 'gcfd');
    $query->fields('gcfd', ['gid', 'entity_id', 'type']);
    $query->condition('gcfd.entity_id', $node->id());
    $query->condition('gcfd.type', 'theories-group_node-' . $node->getType());
    $results = $query->execute()->fetchAll();
    return (count($results)) ? $results[0]->gid : null;
  }
}

