<?php

namespace Drupal\az_book\Plugin\Block;

use Drupal\az_groups\azGroupQuery;
use Drupal\book\Plugin\Block\BookNavigationBlock;
use Drupal\Core\Database\Connection;
use Drupal\Core\Url;

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

  /**
   * Compare two pages for usort().  Sort by weight, then title
   *
   * @param $a
   * @param $b
   * @return bool
   */
  private static function comparePages($a, $b) {
    if ($a->weight == $b->weight) {
      return $a->title <=> $b->title;
    }
    return ($a->weight <=> $b->weight);
  }

  /**
   * Recursively go through the book menu and build the render array for each item.
   * @param $results
   * @param $nid
   * @param $level
   * @return array
   */
  private function buildMenuRecursive($results, $nid, $level) {
    $items = [];
    if (empty($results[$nid]->children)) return $items;

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
    $route = $this->requestStack->getCurrentRequest()->get('_route');
    $activePage = 'book';

    // Based on the route, get the $bid and $gid if available
    switch ($route) {
      case 'entity.group.canonical':
        $activePage = 'recent-content';
        $group = $this->requestStack->getCurrentRequest()->get('group');
        if ($value = $group->field_directory_book->getValue()) {
          $bid = $value[0]['target_id'];
        }
        break;

      case 'entity.node.canonical':
        $node = $this->requestStack->getCurrentRequest()->get('node');
        $bid = $node->book['bid'];
        if ($gid = azGroupQuery::inGroup($node)) {
          $group = \Drupal::entityTypeManager()->getStorage('group')->load($gid);
        }
        break;
    }

    // If this is a book page
    if (!empty($bid)) {

      // Query for all pages in this book
      $query = \Drupal::database()->select('book');
      $query->fields('book');
      for ($i = 1; $i <= 9; $i++) {
        $query->orderBy('p' . $i, 'ASC');
      }
      $query->condition('bid', $bid);
      if (\Drupal::currentUser()->hasPermission('show unpublished book pages')) {
        $query->join('node_field_data', 'nfd', 'nfd.nid = book.nid');
      }
      else {
        $query->join('node_field_data', 'nfd', 'nfd.nid = book.nid AND nfd.moderation_state = :published', [':published' => 'published']);
      }
      $query->addField('nfd', 'moderation_state');
      $query->addField('nfd', 'title');
      $results = $query->execute()->fetchAllAssoc('nid');
      if (count($results) < 1) return [];

      // If this is a book page, set the active trail
      if (!empty($node)) {
        $result = &$results[$node->id()];
        for ($i = 1; $i <= $result->depth; $i++) {
          $n = 'p' . $i;
          if (!empty($results[$result->$n])) {
            $results[$result->$n]->activeTrail = TRUE;
          }
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

      $build = [
        '#theme' => 'az_book_navigation',
        '#book_title' => $results[$bid]->title,
        '#book_url' => \Drupal::service('path.alias_manager')->getAliasByPath('/node/'.$bid),
        '#book_id' => $bid,
        '#book_pages' => $this->buildMenuRecursive($results, $bid, 1),
        '#attributes' => ['class' => ['item-top']],
        '#hide_unpublished' => (\Drupal::currentUser()->hasPermission('show unpublished book pages')),
      ];

      // If this is a group page.
      if (!empty($group)) {
        $build['#group_url'] = \Drupal::service('path.alias_manager')->getAliasByPath('/group/' . $group->id());
        $build['#group_name'] = $group->label->value;
        $build['#group_id'] = $group->id();

        // Add the View Recent Content and Submit Ticket links
        $build['#group_links'] = [
          'view_content' => [
            '#type' => 'container',
            'link' => [
              '#type' => 'link',
              '#title' => t('View Recent Content'),
              '#attributes' => ['title' => t('View recent content for this community.')],
              '#url' => Url::fromRoute('entity.group.canonical', ['group' => $group->id()], ['absolute' => TRUE]),
            ],
          ],
          'submit_ticket' => [
            '#type' => 'container',
            'link' => [
              '#type' => 'link',
              '#title' => t('Submit a Ticket'),
              '#attributes' => ['title' => t('Submit a ticket for suggestions, questions, complaints or bugs regarding this community.')],
              '#url' => Url::fromRoute('node.add', ['node_type' => 'ticket'], [
                'absolute' => TRUE,
                'query' => ['group' => $group->id()],
              ]),
            ],
          ],
        ];

        // If this is the group home page then mark View Recent Content as active
        if ($activePage == 'recent-content') {
          $build['#group_links']['view_content']['link']['#attributes']['class'][] = 'menu-item--active';
        }

        // If this is the Structured Atom model add in the atomizer links
        if ($group->label() == 'Structured Atom Model') {
        /*
          $build['#group_links']['atom_viewer'] = [
            '#type' => 'container',
            'link' => [
              '#type' => 'link',
              '#title' => t('Atom Viewer'),
              '#attributes' => ['title' => t('Display atoms built with the Atom Builder in a 3d interactive JavaScript program.')],
              '#url' => Url::fromUri('base:atomizer/atom-viewer', [
                'absolute' => TRUE,
              ]),
            ],
          ];
         */

          if (\Drupal::currentUser()->hasPermission('atomizer display structure')) {
            $build['#group_links']['atom_builder'] = [
              '#type' => 'container',
              'link' => [
                '#type' => 'link',
                '#title' => t('Atom Builder'),
                '#attributes' => ['title' => t('Interactive program to build atoms according to SAM.')],
                '#url' => Url::fromUri('base:atomizer/atom-builder', [
                  'absolute' => TRUE,
                ]),
              ],
            ];
          }
        }
      }

      // If this is a book page then mark the book title as active
      if ($activePage == 'book') {
        $build['#book_title_classes'] = 'menu-item--active';
      }
      return $build;
    }
    return [];
  }
}

