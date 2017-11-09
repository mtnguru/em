<?php

namespace Drupal\az_book;

use Drupal\book\BookManager;

// Override BookManager service.
//   - Include unpublished books in book outline edit form.

class AzBookManager extends BookManager {
 
  /**
   * {@inheritdoc}
   *
   * Override core and Load books that are not published.
   */
  public function bookTreeCheckAccess(&$tree, $node_links = []) {
    if ($node_links) {
      // @todo Extract that into its own method.
      $nids = array_keys($node_links);

      // @todo This should be actually filtering on the desired node status
      //   field language and just fall back to the default language.
      $nids = \Drupal::entityQuery('node')
        ->condition('nid', $nids, 'IN')
//      ->condition('status', 1)
        ->execute();

      foreach ($nids as $nid) {
        foreach ($node_links[$nid] as $mlid => $link) {
          $node_links[$nid][$mlid]['access'] = TRUE;
        }
      }
    }
    $this->doBookTreeCheckAccess($tree);
  }
}

