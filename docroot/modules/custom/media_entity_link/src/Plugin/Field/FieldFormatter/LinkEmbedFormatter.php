<?php

namespace Drupal\media_entity_link\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\media_entity_link\Plugin\media\Source\Link;

/**
 * Plugin implementation of the 'link_embed' formatter.
 *
 * @FieldFormatter(
 *   id = "link_embed",
 *   label = @Translation("Link embed"),
 *   field_types = {
 *     "link", "string", "string_long"
 *   }
 * )
 */
class LinkEmbedFormatter extends FormatterBase {

  /**
   * Extracts the embed code from a field item.
   *
   * @param \Drupal\Core\Field\FieldItemInterface $item
   *   The field item.
   *
   * @return string|null
   *   The embed code, or NULL if the field type is not supported.
   */
  protected function getEmbedCode(FieldItemInterface $item) {
    switch ($item->getFieldDefinition()->getType()) {
      case 'link':
        return $item->uri;

      case 'string':
      case 'string_long':
        return $item->value;

      default:
        break;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];
    foreach ($items as $delta => $item) {
      $matches = [];

      foreach (Link::$validationRegexp as $pattern => $key) {
        if (preg_match($pattern, $this->getEmbedCode($item), $item_matches)) {
          $matches[] = $item_matches;
        }
      }

      if (!empty($matches)) {
        $matches = reset($matches);
      }

      if (!empty($matches['user']) && !empty($matches['id'])) {
        $element[$delta] = [
          '#theme' => 'media_entity_link_link',
          '#path' => 'https://link.com/' . $matches['user'] . '/statuses/' . $matches['id'],
          '#attributes' => [
            'class' => ['link-link', 'element-hidden'],
            'data-conversation' => 'none',
            'lang' => 'en',
          ],
        ];
      }
    }

    if (!empty($element)) {
      $element['#attached'] = [
        'library' => [
          'media_entity_link/integration',
        ],
      ];
    }

    return $element;
  }

}
