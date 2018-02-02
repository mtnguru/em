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
    if (isset($set['fields'])) {
      foreach ($set['fields'] as $name => $fields) {
        $query->fields(($name == 'null') ? null : $name, $fields);
      }
    } else {
      $query->fields('nfd', ['nid', 'title', 'status']);
    }

    //\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    //FILTERS
    //\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    ////////// Filter on core Drupal published value
    if (isset($set['status'])) {
      $query->condition('nfd.status', $set['status'], '=');
    }

    ////////// Content Types
    if (isset($set['types'])) {
      $query->condition('nfd.type', $set['types'], (is_array($set['types'])) ? 'IN' : '=');
    }

    ////////// Exclude NID's
    if (isset($set['exclude'])) {
      $query->condition('nfd.nid', $set['exclude'], (is_array($set['exclude'])) ? 'NOT IN' : '!=');
    }

    ////////// Author
    if (isset($set['author'])) {
      $query->condition('nfd.uid', $set['author'], (is_array($set['author'])) ? 'IN' : '=');
    }

    ////////// Ticket - Assigned To
    if (isset($set['assigned'])) {
      $query->join('node__field_assigned_to', 'nfat', 'nfd.nid = nfat.entity_id');
      $query->condition('nfat.field_assigned_to_target_id', $set['assigned'], (is_array($set['assigned'])) ? 'IN' : '=');
    }

    ////////// Topics
    if (isset($set['topics'])) {
      $query->join('node__field_topics', 'nft', 'nfd.nid = nft.entity_id');
      $query->condition('nft.field_topics_target_id', $set['topics'], (is_array($set['topics'])) ? 'IN' : '=');
    }

    ////////// Page - Ticket CT only - Tickets record which page they relate to.
    if (isset($set['pages'])) {
      $query->join('node__field_page', 'nfp', 'nfd.nid = nfp.entity_id');
      $query->condition('nfp.field_page_target_id', $set['pages'], (is_array($set['pages'])) ? 'IN' : '=');
    }

    // If requested query for total rows matching all filters.
    if (isset($set['count']) && $set['count']) {
      return $query->countQuery()->execute()->fetchField();
    }
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

  static public function commentQuery(Array $set) {
    $sorted = false; // Prevent duplicate sorts.

    ////////// Connect to database - root table comment_field_data
    $query = \Drupal::database()->select('comment_field_data', 'cfd');

    ////////// Set the output fields
    if (isset($set['fields'])) {
      foreach ($set['fields'] as $name => $fields) {
        $query->fields(($name == 'null') ? null : $name, $fields);
      }
    } else {
      $query->fields('cfd', ['cid']);
//    $query->fields('nfd', ['nid', 'title', 'status']);
    }

    //\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    // COMMENT FILTERS
    //\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    ////////// Filter on core Drupal published value
    if (isset($set['statusComment'])) {
      $query->condition('cfd.status', $set['statusComment'], (is_array($set['statusComment'])) ? 'IN' : '=');
    }

    ////////// Author
    if (isset($set['authorComment'])) {
      $query->condition('cfd.uid', $set['authorComment'], (is_array($set['authorComment'])) ? 'IN' : '=');
    }

    //\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    // PARENT NODE FILTERS
    //\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    ////////// Join in the parent node table.
    $query->join('node_field_data', 'nfd', 'nfd.nid = cfd.entity_id');

    ////////// Filter on core Drupal published value
    if (isset($set['status'])) {
      $query->condition('nfd.status', $set['status'], '=');
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

    //\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    // EXECUTE COUNT QUERY
    //\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    if ((isset($set['getTotalRows']) && $set['getTotalRows']) ||
        (isset($set['count']) && $set['count'])) {
      $totalRows = $query->countQuery()->execute()->fetchField();
      if (isset($set['count'])) {
        return $totalRows;
      }
    } else {
      $totalRows = -1;
    }

    //\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    // SORTS
    //\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    if (!$sorted) {
      $sort = (isset($set['sort'])) ? $set['sort'] : 'changed';
      $order = (isset($set['sortOrder'])) ? $set['sortOrder'] : 'DESC';
      switch ($sort) {
        case 'none':
          $sorted = true;
          break;
        case 'changed':
          $query->orderBy('cfd.changed', $order);
          $sorted = true;
          break;
        case 'created':
          $query->orderBy('cfd.created', $order);
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
        $results = $result->fetchAllAssoc('cid');

        break;

      case 'pager':
        $result = $query->extend('Drupal\Core\Database\Query\PagerSelectExtender')
          ->limit((isset($set['pageNumItems'])) ? $set['pageNumItems'] : 20)
          ->element((isset($set['pagerId'])) ? $set['pagerId'] : 0)
          ->execute();
        $results = $result->fetchAllAssoc('cid');
        break;
    }
    return [
      'results' => $results,
      'numRows' => count($results),
      'totalRows' => (int)$totalRows,
    ];
  }
}
