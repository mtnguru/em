<?php

namespace Drupal\media_entity_link\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Checks if a value is a valid Link embed code/URL.
 *
 * @Constraint(
 *   id = "LinkEmbedCode",
 *   label = @Translation("Link embed code", context = "Validation"),
 *   type = { "link", "string", "string_long" }
 * )
 */
class LinkEmbedCodeConstraint extends Constraint {

  /**
   * The default violation message.
   *
   * @var string
   */
  public $message = 'Not valid Link URL/embed code.';

}
