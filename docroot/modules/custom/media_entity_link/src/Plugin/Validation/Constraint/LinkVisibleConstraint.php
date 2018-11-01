<?php

namespace Drupal\media_entity_link\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Checks if a Link is publicly visible.
 *
 * @Constraint(
 *   id = "LinkVisible",
 *   label = @Translation("Link publicly visible", context = "Validation"),
 *   type = { "entity", "entity_reference", "string", "string_long" }
 * )
 */
class LinkVisibleConstraint extends Constraint {

  /**
   * The default violation message.
   *
   * @var string
   */
  public $message = 'Referenced link is not publicly visible.';

}
