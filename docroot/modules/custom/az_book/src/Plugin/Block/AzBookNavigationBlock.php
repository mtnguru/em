<?php

namespace Drupal\az_book\Plugin\Block;

use Drupal\book\Plugin\Block\BookNavigationBlock;
use Drupal\Core\Database\Connection;

/**
 * Provides a 'Book navigation' block.
 *
 * @Block(
 *   id = "az_book_navigation",
 *   admin_label = @Translation("AZ Book navigation"),
 *   category = @Translation("AZ Menu")
 * )
 */
class AzBookNavigationBlock extends BookNavigationBlock {

  private function buildMenuRecursive($results, $nid, $level) {
    $items = [];

    usort($results[$nid]->children, '\Drupal\ctools_views\Plugin\Display\Block::sortFieldsByWeight');
    foreach ($results[$nid]->children as $num => $child) {
      $classes = ['menu-item'];
      if (!empty($child->activeTrail)) {
        $classes[] = 'menu-item--active';
      }
      if (empty($child->children)) {
        $build = [
          'title' => $child->title,
          'link' => $child->link,
        ];
      }
      else {
        if (!empty($child->activeTrail)) {
          $classes[] = 'menu-item--expanded';
        }
        $classes[] = 'menu-item--children';
        $build = [
          'title' => $child->title,
          'link' => $child->link,
          'children' => $this->buildMenuRecursive($results, $child->nid, $level + 1),
        ];
      }
      $build['class'] = join(' ', $classes);
      $items[] = $build;
    }

    return $items;
  }
  /**
   * {@inheritdoc}
   */
  public function build() {
    if ($node = $this->requestStack->getCurrentRequest()->get('node')) {
      $bid = $node->book['bid'];

      // Query for all pages in this book
      $query = \Drupal::database()->select('book');
      $query->fields('book');
      for ($i = 1; $i <= 9; $i++) {
        $query->orderBy('p' . $i, 'ASC');
      }
      $query->condition('bid', $node->book['bid']);

      // Join node_field_data to get node title.
      $query->join('node_field_data', 'nfd', 'nfd.nid = book.nid');
      $query->addField('nfd', 'title');
      $results = $query->execute()->fetchAllAssoc('nid');

      if (count($results) == 0) return [];

      // Set the active trail
      $result = &$results[$node->id()];
      for ($i = 1; $i <= $result->depth; $i++) {
        $n = 'p' . $i;
        $results[$result->$n]->activeTrail = true;
      }

      // Append children to their parent.
      foreach ($results as &$result) {
        $pid = $result->pid;
        $nid = $result->nid;

        // Create link to book page.
        $options = ['absolute' => TRUE, 'attributes' => ['class' => 'this-class']];
        $node_title = \Drupal\Core\Render\Markup::create('<span>' . $result->title . '</span>');
        $result->link = \Drupal\Core\Link::createFromRoute($node_title, 'entity.node.canonical', ['node' => $nid], $options);

        // Add page to parent page.
        if ($pid && !empty($results[$pid])) {
          $results[$pid]->children[$nid] = $results[$nid];
        }
      }

      return [
        '#theme' => 'az_book_navigation',
        '#title' => $results[$bid]->title,
        '#attributes' => ['class' => ['item-top']],
        '#book_pages' => $this->buildMenuRecursive($results, $bid, 1),
      ];
    }

    return [];
  }
}

