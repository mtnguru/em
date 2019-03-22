<?php

namespace Drupal\az_maestro\Controller;
use Drupal\az_maestro\AzStream;
use Drupal\az_maestro\Command\GetContentCommand;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Controller\ControllerBase;

/**
 * Class MaestroController.
 *
 * @package Drupal\az_maestro\Controller
 */
class MaestroController extends ControllerBase {

  /**
   * Create a stream of content.
   */
  public function getContent() {
    $set = json_decode(file_get_contents("php://input"), true);

    switch ($set['type']) {
      case 'entity-table':
      case 'entity-stream':
        $build = AzStream::create($set);
        break;

      case 'entity-render':
        $entity = \Drupal::entityTypeManager()->getStorage($set['entityType'])->load($set['eid']);
        $build = \Drupal::entityTypeManager()->getViewBuilder($set['entityType'])->view($entity, $set['viewMode']);
        break;
    }

    $response = new AjaxResponse();
    $response->addCommand(new GetContentCommand($set, render($build)));
    return $response;
  }
}
