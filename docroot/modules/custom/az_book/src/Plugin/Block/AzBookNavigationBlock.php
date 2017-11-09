<?php

namespace Drupal\az_book\Plugin\Block;

use Drupal\az_groups\azGroupQuery;
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

  private static function comparePages($a, $b) {
    if ($a->weight == $b->weight) {
      return $a->title <=> $b->title;
    }
    return ($a->weight <=> $b->weight);
  }
  private function buildMenuRecursive($results, $nid, $level) {
    $items = [];

    usort($results[$nid]->children, 'self::comparePages');
    foreach ($results[$nid]->children as $num => $child) {
      $classes = ['menu-item'];
      if (!empty($child->activeTrail)) {
        $classes[] = 'menu-item--active';
      }
      if (!empty($child->moderation_state)) {
        if ($child->moderation_state == 'placeholder') {
          $classes[] = 'menu-item--placeholder';
          $classes[] = 'menu-item--unpublished';
        }
        if ($child->moderation_state == 'draft') {
          $classes[] = 'menu-item--draft';
          $classes[] = 'menu-item--unpublished';
        }
        if ($child->moderation_state == 'needs_review') {
          $classes[] = 'menu-item--needs-review';
          $classes[] = 'menu-item--unpublished';
        }
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
    // If this is a node page then find which book it is associated with.
    if ($node = $this->requestStack->getCurrentRequest()->get('node')) {
      $bid = $node->book['bid'];
      if ($gid = azGroupQuery::inGroup($node)) {
        $group = \Drupal::entityTypeManager()->getStorage('group')->load($gid);
      }
    }
    else if ($group = $this->requestStack->getCurrentRequest()->get('group')) {
      if ($value = $group->field_directory->getValue()) {
        $bid = $value[0]['target_id'];
      }
    }

    if (!empty($bid)) {
      // Query for all pages in this book
      $query = \Drupal::database()->select('book');
      $query->fields('book');
      for ($i = 1; $i <= 9; $i++) {
        $query->orderBy('p' . $i, 'ASC');
      }
      $query->condition('bid', $bid);

      // Join node_field_data to get node title.
      if (\Drupal::currentUser()->hasPermission('show unpublished book pages')) {
        $query->join('node_field_data', 'nfd', 'nfd.nid = book.nid');
      }
      else {
        $query->join('node_field_data', 'nfd', 'nfd.nid = book.nid AND nfd.moderation_state = :published', [':published' => 'published']);
      }
      $query->addField('nfd', 'moderation_state');
      $query->addField('nfd', 'title');
      $results = $query->execute()->fetchAllAssoc('nid');

      if (count($results) <= 1) return [];

      // If this is a book page, set the active trail
      if ($node) {
        $result = &$results[$node->id()];
        for ($i = 1; $i <= $result->depth; $i++) {
          $n = 'p' . $i;
          $results[$result->$n]->activeTrail = true;
        }
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

      $roles = \Drupal::currentUser()->getRoles();
      $build = [
        '#theme' => 'az_book_navigation',
        '#book_title' => $results[$bid]->title,
        '#book_url' => \Drupal::service('path.alias_manager')->getAliasByPath('/node/'.$bid),
        '#attributes' => ['class' => ['item-top']],
        '#book_pages' => $this->buildMenuRecursive($results, $bid, 1),
        '#hide_unpublished' => (\Drupal::currentUser()->hasPermission('show unpublished book pages')),
        '#show_atomizer' => (in_array('ninja', \Drupal::currentUser()->getRoles())),
      ];

      if (!empty($group)) {
        $build['#group_url'] = \Drupal::service('path.alias_manager')->getAliasByPath('/group/'.$group->id());
        $build['#group_name'] = $group->label->value;
        $build['#group_url'] = $group->id();
      }
      return $build;
    }

    return [];
  }
}

