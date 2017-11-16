<?php

namespace Drupal\az_content\Plugin\Block;

use Drupal\az_groups\azGroupQuery;
use Drupal\Core\Database\Connection;
use Drupal\Core\Url;

/**
 *
 * @Block(
 *   id = "az_content",
 *   admin_label = @Translation("AZ Content Block"),
 *   category = @Translation("AZ")
 * )
 */
class AzContentBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return $build;
  }
}

