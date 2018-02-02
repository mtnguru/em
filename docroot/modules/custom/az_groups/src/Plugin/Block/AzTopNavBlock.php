<?php
/**
* @file
* Display top Navigation menu for groups depending on the URL.
*/


/**
 * Displays children pages as a block
 */

namespace Drupal\az_groups\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;
use Drupal\Core\Path;
use Drupal\Core\Menu;
use Drupal\az_groups\AzGroupConfig;

/**
 * Provides a 'Next Previous' block.
 *
 * @Block(
 *   id = "az_top_nav_block",
 *   admin_label = @Translation("AZ Top Nav Block"),
 *   category = @Translation("Atomizer")
 * )
 */
class AzTopNavBlock extends BlockBase {

  // @TODO move this into database with config form

  public function build() {
    $menu_tree_service = \Drupal::service('menu.link_tree');

    // Build the typical default set of menu tree parameters.
    $parameters = new \Drupal\Core\Menu\MenuTreeParameters();
    $parameters->setMaxDepth(3);
    $parameters->setMinDepth(1);

    // Load the tree based on this set of parameters.
    $tree = $menu_tree_service->load('top', $parameters);
    $manipulators = [
      ['callable' => 'menu.default_tree_manipulators:checkNodeAccess'],
      ['callable' => 'menu.default_tree_manipulators:checkAccess'],
      ['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
    ];
    $tree = $menu_tree_service->transform($tree, $manipulators);
    $topMenu = $menu_tree_service->build($tree);

    // Load the tree based on this set of parameters.
    $tree = $menu_tree_service->load('account', $parameters);
    $manipulators = [
      ['callable' => 'menu.default_tree_manipulators:checkNodeAccess'],
      ['callable' => 'menu.default_tree_manipulators:checkAccess'],
      ['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
    ];
    $tree = $menu_tree_service->transform($tree, $manipulators);
    $accountMenu = $menu_tree_service->build($tree);

    return [
      '#type' => 'container',
      '#attributes' => ['class' => ['az-top']],
      'top_menu' => $topMenu,
      'account_menu' => $accountMenu,
    ];
  }
}
