<?php

namespace Drupal\az_maestro\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Serialization\Yaml;

/**
 * Provides a 'Conductor' block.
 *
 * @Block(
 *  id = "conductor_block",
 *  admin_label = @Translation("Block that displays the conductor block - Pops up the primary selection menu"),
 * )
 */
class ConductorBlock extends BlockBase {

  public function blockForm($form, FormStateInterface $form_state) {
    $directory = drupal_get_path('module', 'az_maestro') . '/config/scenes';
    $files = file_scan_directory($directory, '/\.yml/');
    foreach ($files as $file) {
      $ymlContents = Yaml::decode(file_get_contents($directory . '/' . $file->filename));
      $filelist[$file->filename] = $ymlContents['name'];
    }
    asort($filelist);

    $form['scene_file'] = [
      '#type' => 'select',
      '#title' => $this->t('Conductor Block Configuration file'),
      '#description' => $this->t(''),
      '#default_value' => isset($this->configuration['scene_file']) ? $this->configuration['scene_file'] : 'default',
      '#options' => $filelist,
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['scene_file'] = $form_state->getValue('scene_file');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->getConfiguration();

    // Depending on what's selected display something.
    // What should I display - hell I don't know.
    // Seems like the build command isn't important at this point.
    // Is this really a block - It's going to get displayed
    $config = [
      'id' => 'conductor_block',
      'label' => 'yowzir',
      'provider' => 'az_maestro',
      'label_display' => 'visible',
      'scene_file', 'scene_group.yml',
    ];

    $build = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['az-wrapper'],
        'tabindex' => 1,
      ],
      '#cache' => [
        'max-age' => 0,
      ],
      'content' => [
        '#type' => 'container',
        '#attributes' => [
          'class' => ['az-maestro-wrapper'],
        ],
        'content2' => [
          '#type' => 'container',
          '#weight' => -10,
          '#attributes' => ['class' => ['az-controls-wrapper']],
          'controls' => ['#markup' => 'yo dudette, content here'],
        ],
      ],
    ];

    if (!empty($scene_config['classes'])) {
      foreach ($scene_config['classes'] as $class) {
        $build['#attributes']['class'][] = $class;
      }
    }

    return $build;
  }
}
