<?php

/**
 * Command to load stream of content.
 */

namespace Drupal\az_content\Command;
use Drupal\Core\Ajax\CommandInterface;

class GetStreamCommand implements CommandInterface {
  public function __construct($set, $stream) {
    $this->set = $set;
    $this->stream = $stream;
  }

  public function render() {
    return array(
      'command' => 'GetStreamCommand',
      'set' => $this->set,
      'stream' => $this->stream,
    );
  }
}
