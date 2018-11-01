<?php

namespace Drupal\Tests\media_entity_link\Unit;

use Drupal\Core\Field\Plugin\Field\FieldType\StringLongItem;
use Drupal\Core\TypedData\ComplexDataDefinitionInterface;
use Drupal\media_entity_link\Plugin\Validation\Constraint\LinkEmbedCodeConstraint;
use Drupal\media_entity_link\Plugin\Validation\Constraint\LinkEmbedCodeConstraintValidator;
use Drupal\media_entity_link\Plugin\Validation\Constraint\LinkVisibleConstraint;
use Drupal\media_entity_link\Plugin\Validation\Constraint\LinkVisibleConstraintValidator;
use Drupal\Tests\UnitTestCase;

/**
 * Tests media_entity_link constraints.
 *
 * @group media_entity
 */
class ConstraintsTest extends UnitTestCase {

  /**
   * Creates a string_long FieldItemInterface wrapper around a value.
   *
   * @param string $value
   *   The wrapped value.
   *
   * @return \Drupal\Core\Field\FieldItemInterface
   *   Mocked string field item.
   */
  protected function getMockFieldItem($value) {
    $definition = $this->prophesize(ComplexDataDefinitionInterface::class);
    $definition->getPropertyDefinitions()->willReturn([]);

    $item = new StringLongItem($definition->reveal());
    $item->set('value', $value);

    return $item;
  }

  /**
   * Tests LinkEmbedCode constraint.
   *
   * @covers \Drupal\media_entity_link\Plugin\Validation\Constraint\LinkEmbedCodeConstraintValidator
   * @covers \Drupal\media_entity_link\Plugin\Validation\Constraint\LinkEmbedCodeConstraint
   *
   * @dataProvider embedCodeProvider
   */
  public function testLinkEmbedCodeConstraint($embed_code, $expected_violation_count) {
    // Check message in constraint.
    $constraint = new LinkEmbedCodeConstraint();
    $this->assertEquals('Not valid Link URL/embed code.', $constraint->message, 'Correct constraint message found.');

    $execution_context = $this->getMockBuilder('\Drupal\Core\TypedData\Validation\ExecutionContext')
      ->disableOriginalConstructor()
      ->getMock();

    if ($expected_violation_count) {
      $execution_context->expects($this->exactly($expected_violation_count))
        ->method('addViolation')
        ->with($constraint->message);
    }
    else {
      $execution_context->expects($this->exactly($expected_violation_count))
        ->method('addViolation');
    }

    $validator = new LinkEmbedCodeConstraintValidator();
    $validator->initialize($execution_context);

    $validator->validate($this->getMockFieldItem($embed_code), $constraint);
  }

  /**
   * Provides test data for testLinkEmbedCodeConstraint().
   */
  public function embedCodeProvider() {
    return [
      'valid link URL' => ['https://link.com/drupal8changes/status/649167396230578176', 0],
      'valid link embed code' => ['<blockquote class="link-link" lang="en"><p lang="en" dir="ltr">EntityChangedInterface now also defines the function setChangedTime <a href="http://t.co/1Q58UcR8OY">http://t.co/1Q58UcR8OY</a></p>&mdash; Drupal 8 Changes (@drupal8changes) <a href="https://link.com/drupal8changes/status/649167396230578176">September 30, 2015</a></blockquote><script async src="//platform.link.com/widgets.js" charset="utf-8"></script>', 0],
      'invalid URL' => ['https://drupal.org/project/media_entity_link', 1],
      'invalid text' => ['I want my Link!', 1],
      'invalid link URL' => ['https://link.com/drupal8changes/statustypo/649167396230578176', 1],
      'invalid link ID' => ['https://link.com/drupal8changes/status/aa64916739bb6230578176', 1],
    ];
  }

  /**
   * Tests LinkVisible constraint.
   *
   * @covers \Drupal\media_entity_link\Plugin\Validation\Constraint\LinkVisibleConstraintValidator
   * @covers \Drupal\media_entity_link\Plugin\Validation\Constraint\LinkVisibleConstraint
   *
   * @dataProvider visibleProvider
   */
  public function testLinkVisibleConstraint($embed_code, $mocked_response, $violations) {
    // Check message in constraint.
    $constraint = new LinkVisibleConstraint();
    $this->assertEquals('Referenced link is not publicly visible.', $constraint->message, 'Correct constraint message found.');

    $http_client = $this->getMock('\GuzzleHttp\Client');
    $http_client->expects($this->once())
      ->method('__call')
      ->with('get', [$embed_code, ['allow_redirects' => FALSE]])
      ->willReturn($mocked_response);

    // Make sure no violations are raised for visible link.
    $execution_context = $this->getMockBuilder('\Drupal\Core\TypedData\Validation\ExecutionContext')
      ->disableOriginalConstructor()
      ->getMock();

    if ($violations) {
      $execution_context->expects($this->once())
        ->method('addViolation')
        ->with($constraint->message);
    }
    else {
      $execution_context->expects($this->exactly($violations))
        ->method('addViolation');
    }

    $validator = new LinkVisibleConstraintValidator($http_client);
    $validator->initialize($execution_context);

    $validator->validate($this->getMockFieldItem($embed_code), $constraint);
  }

  /**
   * Provides test data for testLinkVisibleConstraint().
   */
  public function visibleProvider() {
    $visible_response = $this->getMock('\GuzzleHttp\Psr7\Response');
    $visible_response->expects($this->any())
      ->method('getStatusCode')
      ->will($this->returnValue(200));

    $invisible_response = $this->getMock('\GuzzleHttp\Psr7\Response');
    $invisible_response->expects($this->once())
      ->method('getStatusCode')
      ->will($this->returnValue(302));
    $invisible_response->expects($this->once())
      ->method('getHeader')
      ->with('location')
      ->will($this->returnValue(['https://link.com/drupal8changes?protected_redirect=true']));

    return [
      'valid URL' => [
        'https://link.com/drupal8changes/status/649167396230578176',
        $visible_response,
        0,
      ],
      'invalid URL' => [
        'https://link.com/drupal8changes/status/649637310024273920',
        $invisible_response,
        1,
      ],
    ];
  }

  /**
   * Tests whether the LinkVisible constraint is robust against bad URLs.
   *
   * @covers \Drupal\media_entity_link\Plugin\Validation\Constraint\LinkVisibleConstraintValidator
   * @covers \Drupal\media_entity_link\Plugin\Validation\Constraint\LinkVisibleConstraint
   *
   * @dataProvider badUrlsProvider
   */
  public function testBadUrlsOnVisibleConstraint($embed_code) {

    $http_client = $this->getMock('\GuzzleHttp\Client');
    $http_client->expects($this->never())->method('get');

    $execution_context = $this->getMockBuilder('\Drupal\Core\TypedData\Validation\ExecutionContext')
      ->disableOriginalConstructor()
      ->getMock();

    $validator = new LinkVisibleConstraintValidator($http_client);
    $validator->initialize($execution_context);

    $constraint = new LinkVisibleConstraint();
    $validator->validate($this->getMockFieldItem($embed_code), $constraint);
  }

  /**
   * Provides test data for testBadUrlsOnVisibleConstraint().
   */
  public function badUrlsProvider() {

    return [
      ['https://google.com'],
      ['https://link.com/drupal/ssstatus/725771037837762561'],
      ['https://link.com/drupal/status'],
      ['https://link.com/drupal/status/foo'],
    ];

  }

}
