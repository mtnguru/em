<?php
/**
* @file
* Display top Navigation menu for groups depending on the URL.
*/


/**
 * Displays children pages as a block
 */

namespace Drupal\az_groups;

/**
 * Provides a 'Next Previous' block.
 *
 * @Block(
 *   id = "az_top_nav_block",
 *   admin_label = @Translation("AZ Top Nav Block"),
 *   category = @Translation("Atomizer")
 * )
 */
class azGroupQuery {

  static public function inGroup($node) {
    $query = \Drupal::database()->select('group_content_field_data', 'gcfd');
    $query->fields('gcfd', ['gid', 'entity_id', 'type']);
    $query->condition('gcfd.entity_id', $node->id());
    $query->condition('gcfd.type', 'theories-group_node-' . $node->getType());
    $results = $query->execute()->fetchAll();
    return (count($results)) ? $results[0]->gid : null;
  }
}

