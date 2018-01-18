<?php
/**
* @file
* Create a rendered stream of content.
*/

namespace Drupal\az_content;

/**
 * Provides query for recent content 
 *   Array $set configures query parameters.
 */
class AzStream {

  static public function create(Array &$set) {
    // Query for items
    switch ($set['entityType']) {
      case 'node':
        $result = AzContentQuery::nodeQuery($set);
        break;
      case 'comment':
        $result = AzContentQuery::commentQuery($set);
        break;
      case 'user':
        $result = AzContentQuery::userQuery($set);
        break;
    }
    $set['numRows'] = $result['numRows'];
    if ($result['totalRows'] != -1) {
      $set['totalRows'] = $result['totalRows'];
    }

    $classes = ['stream-container', str_replace('_', '-', $set['viewMode'])];
    if ($result['numRows']) {
      $ids = array_keys($result['results']);
      $entities = \Drupal::entityTypeManager()->getStorage($set['entityType'])->loadMultiple($ids);

      $stream = [];
      foreach ($entities as $entity) {
        $build = \Drupal::entityTypeManager()->getViewBuilder($set['entityType'])->view($entity, $set['viewMode']);
        $stream['row_' . $entity->id()] = [
          '#type' => 'container',
          '#attributes' => ['class' => ['az-row']],
          'stream' => $build,
        ];
      }
    } else {
      $empty = (isset($set['empty'])) ? $set['empty'] : 'No content found';
      $classes[] = 'empty';
      $stream = ['#markup' => (isset($set['empty'])) ? $set['empty'] : 'No content found'];
    }

    $stream = [
      '#type' => 'container',
      '#attributes' => ['class' => $classes],
      'stream' => $stream,
    ];

    // Create either a pager or a more button.
    if (isset($set['more'])) {

      if ($set['more'] == 'pager') {
        $more = [
          '#type' => 'container',
          '#attributes' => ['class' => ['more-container', 'pager']],
          'pager' => [
            '#type' => 'pager',
            '#element' => $set['pagerId'],
          ],
        ];
      }


      if ($set['more'] == 'ajax') {
        $classes = ['more-container', 'ajax'];
        if ($set['pageNum'] * $set['pageNumItems'] + $set['numRows'] >= $set['totalRows']) {
          $status = $set['title'] . ' - All of ' . $set['totalRows'];
          $classes[] = 'all';
        } else {
          $status = $set['title'] . ' - ' . ($set['pageNum'] + 1) * $set['pageNumItems'] . ' of ' . $set['totalRows'];
        }
        $more = [
          '#type' => 'container',
          '#attributes' => ['class' => $classes],
          'status' => [
            '#type' => 'container',
            '#attributes' => ['class' => ['status-container']],
            'markup' => ['#markup' => $status],
          ],
          'more_button' => [
            '#type' => 'button',
            '#value' => 'More',
            '#attributes' => ['class' => ['more-button']],
          ],
        ];
      }
      return [
        'stream' => $stream,
        'more' => $more,
      ];
    }

    return $stream;
  }
}
