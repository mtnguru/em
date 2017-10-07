<?php

/**
 * @file
 * Contains \Drupal\atomizer\Plugin\Field\FieldFormatter\CodeFormatter
 */

namespace Drupal\az_components\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'moreSlider' formatter.
 *
 * @FieldFormatter(
 *   id = "more_slider_formatter",
 *   label = @Translation("More slider"),
 *   description = @Translation("Wrap text field in a more slider"),
 *   field_types = {
 *     "text",
 *     "text_long",
 *     "text_with_summary"
 *   }
 * )
 */
//class MoreSliderFormatter extends TextDefaultFormatter {
class MoreSliderFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'height' => '150',
      'overlayHeight' => '75',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element['height'] = [
      '#title' => t('Text Height'),
      '#type' => 'number',
      '#field_suffix' => t('pixels'),
      '#default_value' => $this->getSetting('height'),
      '#description' => t('Height of the text area to display.'),
      '#min' => 1,
      '#required' => TRUE,
    ];
    $element['overlayHeight'] = [
      '#title' => t('Overlay Height'),
      '#type' => 'number',
      '#field_suffix' => t('pixels'),
      '#default_value' => $this->getSetting('overlayHeight'),
      '#description' => t('Height of the overlay that hides bottom of Text Area.'),
      '#min' => 1,
      '#required' => TRUE,
    ];
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $summary[] = t('Text Height: @height  Overlay Height: @overlayHeight',
                   ['@height' => $this->getSetting('height'),
                    '@overlayHeight' => $this->getSetting('overlayHeight')]);
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {

    // The ProcessedText element already handles cache context & tag bubbling.
    // @see \Drupal\filter\Element\ProcessedText::preRenderText()
    $content = [];
    foreach ($items as $delta => $item) {

      $content[$delta] = [
        '#type' => 'processed_text',
        '#text' => $item->value,
        '#format' => $item->format,
        '#langcode' => $item->getLangcode(),
      ];
    }

    if (!sizeof($content)) {
      return $content;
    }

    return [
      '#attached' => [
        'library' => [
          '0' => 'az_components/az-more-slider',
        ],
      ],
      '0' => [
        '#type' => 'container',
        '#attributes' => [ 'class' => ['az-more-wrapper'], ],

        'body' => [
          '#type' => 'container',
          '#attributes' => [ 'class' => ['az-body-wrapper', 'az-more-slider'], ],
          'content' => $content[0],
          'overlay' => [
            '#type' => 'container',
            '#attributes' => [ 'class' => ['az-more-slider-overlay'], ],
          ]
        ],
        'button' => [
          '#type' => 'container',
          '#attributes' => [ 'class' => ['az-more-slider-button'], ],
          'more' => ['#markup' => t('-- more --')],
        ],
      ],
    ];
  }
}

