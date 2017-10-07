<?php

namespace Drupal\az_maestro\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Serialization\Yaml;

/**
 * Provides a 'Maestro' block.
 *
 * @Block(
 *  id = "maestro_block",
 *  admin_label = @Translation("Block used for most of the EM Website."),
 * )
 */
class MaestroBlock extends BlockBase {

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
      '#title' => $this->t('Maestro Configuration file'),
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

    $config = [
      'id' => 'maestro_block',
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
