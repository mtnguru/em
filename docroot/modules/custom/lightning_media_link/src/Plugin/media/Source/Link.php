<?php

namespace Drupal\lightning_media_link\Plugin\media\Source;

use Drupal\lightning_media\InputMatchInterface;
use Drupal\lightning_media\ValidationConstraintMatchTrait;
use Drupal\media_entity_link\Plugin\media\Source\Link as BaseLink;

/**
 * Input-matching version of the Link media source.
 */
class Link extends BaseLink implements InputMatchInterface {

  use ValidationConstraintMatchTrait;

}
