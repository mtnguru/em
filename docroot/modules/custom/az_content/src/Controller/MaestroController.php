<?php

namespace Drupal\az_content\Controller;
use Drupal\az_content\AzStream;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Controller\ControllerBase;
use Drupal\az_content\Command\GetStreamCommand;

/**
 * Class MaestroController.
 *
 * @package Drupal\az_content\Controller
 */
class MaestroController extends ControllerBase {

  /**
   * Create a stream of content.
   */
  public function getStream() {
    $set = json_decode(file_get_contents("php://input"), true);

    $stream = AzStream::create($set);

    $response = new AjaxResponse();
    $response->addCommand(new GetStreamCommand($set, render($stream)));
    return $response;
  }
}
