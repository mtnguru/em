<?php
/**
* @file
* Display top Navigation menu for groups depending on the URL.
*/


/**
 * Displays children pages as a block
 */

namespace Drupal\az_content;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides query for recent content 
 *   Array $settings configures query parameters.
 */
class AzContentQuery {

  static public function nodeQuery(Array $set) {
    $sorted = false; // Prevent duplicate sorts.

    $query = \Drupal::database()->select('node_field_data', 'nfd');
//  $query->distinct();

    ////////// Set the output fields
    if (isset($ecset['fields'])) {
      foreach ($ecset['fields'] as $name => $fields) {
        $query->fields(($name == 'null') ? null : $name, $fields);
      }
    } else {
      $query->fields('nfd', ['nid', 'title', 'status']);
    }

    //\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    //FILTERS
    //\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    ////////// Filter on core Drupal published value
    if (isset($ecset['status'])) {
      $query->condition('nft.status', $set['status'], '=');
    }

    ////////// Content Types
    if (isset($set['types'])) {
      $query->condition('nfd.type', $set['types'], (is_array($set['types'])) ? 'IN' : '=');
    }

    ////////// Author
    if (isset($set['author'])) {
      $query->condition('nfd.uid', $set['author'], (is_array($set['author'])) ? 'IN' : '=');
    }

    ////////// Topics
    if (isset($set['topics'])) {
      $query->join('node__field_topics', 'nft', 'nfd.nid = nft.entity_id');
      $query->condition('nft.field_topics_target_id', $set['topics'], (is_array($set['topics'])) ? 'IN' : '=');
    }

    /////////// If query is count_only, execute countQuery and return number items
    if (isset($set['countOnly'])) {
      return $query->countQuery()->execute()->fetchField();
    }

    // If requested query for total rows matching all filters.
    if (isset($set['getTotalRows']) && $set['getTotalRows']) {
      $totalRows = $query->countQuery()->execute()->fetchField();
    } else {
      $totalRows = -1;
    }

    //\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    //SORTS
    //\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    if (!$sorted) {
      $sort = (isset($set['sort'])) ? $set['sort'] : 'changed';
      $order = (isset($set['sortOrder'])) ? $set['sortOrder'] : 'DESC';
      switch ($sort) {
        case 'none':
          $sorted = true;
          break;
        case 'changed':
          $query->orderBy('nfd.changed', $order);
          $sorted = true;
          break;
        case 'created':
          $query->orderBy('nfd.created', $order);
          $sorted = true;
          break;
      }
    }

    // Execute the query depending on 'more' setting.
    switch ((isset($set['more'])) ? $set['more'] : 'none') {
      case 'none':
        $results = $query->execute()->fetchAllAssoc('nid');
        break;

      case 'ajax':
        // If using AJAX then we keep track of page number and items per page.
        if (isset($set['pageNum'])) {
          $query->range($set['pageNum'] * $set['pageNumItems'], $set['pageNumItems']);
        }
        $result = $query->execute();
        $results = $result->fetchAllAssoc('nid');

        break;

      case 'pager':
        $result = $query->extend('Drupal\Core\Database\Query\PagerSelectExtender')
          ->limit((isset($set['pageNumItems'])) ? $set['pageNumItems'] : 20)
          ->element((isset($set['pagerId'])) ? $set['pagerId'] : 0)
          ->execute();
        $results = $result->fetchAllAssoc('nid');
        break;
    }
    return [
      'results' => $results,
      'numRows' => count($results),
      'totalRows' => (int)$totalRows,
    ];
  }
}

