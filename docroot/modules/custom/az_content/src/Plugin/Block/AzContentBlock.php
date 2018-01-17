<?php

namespace Drupal\az_content\Plugin\Block;

use Drupal\az_content\AzMaestroInit;
use Drupal\az_content\AzStream;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 *
 * @Block(
 *   id = "az_content_block",
 *   admin_label = @Translation("AZ Content Block"),
 *   category = @Translation("AZ")
 * )
 */
class AzContentBlock extends BlockBase {

  public function blockForm($form, FormStateInterface $form_state) {

    // Give the viewer a name so the controls block can connect to it.
    $form['set'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Query settings'),
      '#description' => $this->t('Query settings in yml format.'),
      '#default_value' => isset($this->configuration['set']) ? $this->configuration['set'] : 'default value',
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['set'] = $form_state->getValue('set');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $set = $this->configuration['set'];

    // Initialize settings that haven't been set.
    $set['pageNum'] = (isset($set['pageNum'])) ? $set['pageNum'] : 0;
    $set['pageNumItems'] = (isset($set['pageNumItems'])) ? $set['pageNumItems'] : 10;
    $set['entityType'] = (isset($set['entityType'])) ? $set['entityType'] : 'node';
    $set['viewMode'] = (isset($set['viewMode'])) ? $set['viewMode'] : 'teaser';
    $set['more'] = (isset($set['more'])) ? $set['more'] : 'none';

    // If using a pager set the pager id
    if ($set['more'] == 'pager' && !isset($set['pagerId'])) {
      $pagerId = &drupal_static('azPagerId', 0);
      $set['pagerId'] = $pagerId++;
    }

    // Set up the container classes
    $classes = [
      'maestro-stream',
      'entity-' . $set['entityType'],
      str_replace('_', '-', $set['viewMode'])
    ];
    if (isset($set['class'])) {
      $classes[] = $set['class'];
    }
    if (isset($set['tab'])) {
      $classes[] = 'tab-content';
    }

    // Create the stream container
    $stream = [
      '#type' => 'container',
      '#attributes' => [
        'id' => $set['id'],
        'class' => $classes,
      ],
    ];

    // Add the title if specified
    if (isset($set['title'])) {
      $stream['title'] = [
        '#type' => 'container',
        '#attributes' => ['class' => ['title-container']],
        'markup' => ['#markup' => '<h2>' . $set['title'] . '</h2>'],
      ];
    }

    // Build empty container for the stream content - AJAX fills it.
    $stream['content'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['content-container']],
//      'stream' => AzStream::create($set),      // Load the first page.
    ];

    if (isset($set['tab'])) {
      // Wrap it in a tab
      $id = 'tab-' . $set['id'];
      $build = [
        '#type' => 'container',
        '#prefix' => '<li id="' . $id . '" class="tab">',
        '#suffix' => '</li>',
        '#attributes' => ['class' => ['tab-container']],
        'title' => [
          '#type' => 'container',
          '#attributes' => ['class' => ['tab-title']],
          'markup' => ['#markup' => '<h2>' . $set['tab'] . '</h2>'],
        ],
        'stream' => $stream,
      ];
    } else {
      $build = $stream;
    }

    AzMaestroInit::start($set, $build);
    return $build;
  }
}

