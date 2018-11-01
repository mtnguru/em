<?php

namespace Drupal\Tests\media_entity_link\Kernel;

use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\KernelTests\KernelTestBase;
use Drupal\media\Entity\Media;
use Drupal\media\Entity\MediaType;
use Drupal\media_entity_link\Plugin\media\Source\Link;
use Drupal\media_entity_link\LinkFetcherInterface;

/**
 * Tests SVG thumbnail generation from Link API responses.
 *
 * @group media_entity_link
 */
class ThumbnailTest extends KernelTestBase {

  /**
   * The mocked link fetcher.
   *
   * @var \Drupal\media_entity_link\LinkFetcherInterface
   */
  protected $linkFetcher;

  /**
   * The plugin under test.
   *
   * @var \Drupal\media_entity_link\Plugin\media\Source\Link
   */
  protected $plugin;

  /**
   * A link media entity.
   *
   * @var \Drupal\media\MediaInterface
   */
  protected $entity;

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'field',
    'file',
    'image',
    'media',
    'media_entity_link',
    'system',
    'text',
    'user',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->installEntitySchema('file');
    $this->installEntitySchema('media');
    $this->installConfig(['media_entity_link', 'system']);

    $this->linkFetcher = $this->getMock(LinkFetcherInterface::class);
    $this->container->set('media_entity_link.link_fetcher', $this->linkFetcher);

    MediaType::create([
      'id' => 'link',
      'source' => 'link',
      'source_configuration' => [
        'source_field' => 'link',
        'use_link_api' => TRUE,
        'consumer_key' => $this->randomString(),
        'consumer_secret' => $this->randomString(),
        'oauth_access_token' => $this->randomString(),
        'oauth_access_token_secret' => $this->randomString(),
      ],
    ])->save();

    FieldStorageConfig::create([
      'field_name' => 'link',
      'entity_type' => 'media',
      'type' => 'string_long',
    ])->save();

    FieldConfig::create([
      'field_name' => 'link',
      'entity_type' => 'media',
      'bundle' => 'link',
    ])->save();

    $this->entity = Media::create([
      'bundle' => 'link',
      'link' => 'https://link.com/foobar/status/12345',
    ]);

    $this->plugin = Link::create(
      $this->container,
      MediaType::load('link')->get('source_configuration'),
      'link',
      MediaType::load('link')->getSource()->getPluginDefinition()
    );

    $dir = $this->container
      ->get('config.factory')
      ->get('media_entity_link.settings')
      ->get('local_images');

    file_prepare_directory($dir, FILE_CREATE_DIRECTORY | FILE_MODIFY_PERMISSIONS);
  }

  /**
   * Tests that an existing local image is used as the thumbnail.
   */
  public function testLocalImagePresent() {
    $this->linkFetcher
      ->method('fetchLink')
      ->willReturn([
        'extended_entities' => [
          'media' => [
            [
              'media_url' => 'https://drupal.org/favicon.ico',
            ],
          ],
        ],
      ]);

    $uri = 'public://link-thumbnails/12345.ico';
    touch($uri);
    $this->assertEquals($uri, $this->plugin->getMetadata($this->entity, 'thumbnail_uri'));
  }

  /**
   * Tests that a local image is downloaded if available but not present.
   */
  public function testLocalImageNotPresent() {
    $this->linkFetcher
      ->method('fetchLink')
      ->willReturn([
        'extended_entities' => [
          'media' => [
            [
              'media_url' => 'https://drupal.org/favicon.ico',
            ],
          ],
        ],
      ]);

    $this->plugin->getMetadata($this->entity, 'thumbnail_uri');
    $this->assertFileExists('public://link-thumbnails/12345.ico');
  }

  /**
   * Tests that the default thumbnail is used if no local image is available.
   */
  public function testNoLocalImage() {
    $this->assertEquals(
      '/link.png',
      $this->plugin->getMetadata($this->entity, 'thumbnail_uri')
    );
  }

  /**
   * Tests that thumbnail is generated if enabled and local image not available.
   */
  public function testThumbnailGeneration() {
    $configuration = $this->plugin->getConfiguration();
    $configuration['generate_thumbnails'] = TRUE;
    $this->plugin->setConfiguration($configuration);

    $uri = $this->plugin->getMetadata($this->entity, 'thumbnail_uri');
    $this->assertFileExists($uri);
  }

}
