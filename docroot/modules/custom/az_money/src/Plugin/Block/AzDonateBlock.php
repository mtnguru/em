<?php

namespace Drupal\az_money\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'Book navigation' block.
 *
 * @Block(
 *   id = "az_donate_block",
 *   admin_label = @Translation("AZ Donate Block"),
 *   category = @Translation("AZ Money")
 * )
 */
class AzDonateBlock extends BlockBase {

  public function blockForm($form, FormStateInterface $form_state) {
    $node = (isset($this->configuration['nid']))
      ? \Drupal::entityTypeManager()->getStorage('node')->load($this->configuration['nid'])
      : NULL;
    $form['nid'] = [
      '#type' => 'entity_autocomplete',
      '#target_type' => 'node',
      '#selection_handler' => 'default',
      '#default_value' => $node,
      '#selection_settings' => [
        'target_bundles' => ['page'],
      ],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['nid'] = $form_state->getValue('nid');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->getConfiguration();
    $node = \Drupal::entityTypeManager()->getStorage('node')->load($config['nid']);

    return [
      '#theme' => 'az_donate_block',
      '#attributes' => ['class' => ['donate-block']],
      '#description' => $node->field_short_description->value,
      '#more_url' => \Drupal::service('path.alias_manager')->getAliasByPath('/node/'.$node->id()),
    ];
  }
}

