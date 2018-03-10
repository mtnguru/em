<?php

namespace Drupal\Tests\gmedia\Kernel;

/**
 * Tests the access records that are set for group media.
 *
 * @group gmedia
 */
class GroupMediaAccessRecordsTest extends GroupMediaAccessTestBase {

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->installSchema('media', 'media_access');
  }

  /**
   * Tests that no access records are set for ungrouped media.
   */
  public function testUngroupedMedia() {
    $media = $this->entityTypeManager
      ->getStorage('media')
      ->create([
        'type' => 'a',
        'title' => $this->randomMachineName(),
      ]);
    $media->save();

    $records = gmedia_media_access_records($media);
    $this->assertEmpty($records, 'No access records set for an ungrouped media.');
  }

  /**
   * Tests the access records for a published group media.
   */
  public function testPublishedGroupMedia() {
    /** @var \Drupal\media\MediaInterface $media */
    $media = $this->entityTypeManager
      ->getStorage('media')
      ->create([
        'type' => 'a',
        'title' => $this->randomMachineName(),
      ]);
    $media->save();
    $this->groupA1->addContent($media, 'group_media:a');

    $records = gmedia_media_access_records($media);
    $this->assertCount(4, $records, '4 access records set for a published group media.');

    $base = [
      'grant_view' => 1,
      'grant_update' => 1,
      'grant_delete' => 1,
      'priority' => 0,
    ];
    $gid = $this->groupA1->id();
    $uid = $media->getOwnerId();
    $this->assertEquals(['gid' => $gid, 'realm' => 'gmedia:a'] + $base, $records[0], 'General gmedia:NODE_TYPE grant found.');
    $this->assertEquals(['gid' => $gid, 'realm' => "gmedia_author:$uid:a"] + $base, $records[1], 'Author gmedia_author:UID:NODE_TYPE grant found.');
    $this->assertEquals(['gid' => GNODE_MASTER_GRANT_ID, 'realm' => 'gmedia_bypass'] + $base, $records[2], 'Admin gmedia_bypass grant found.');

    $anonymous = [
      'gid' => GNODE_MASTER_GRANT_ID,
      'realm' => 'gmedia_anonymous',
      'grant_view' => 1,
      'grant_update' => 0,
      'grant_delete' => 0,
      'priority' => 0
    ];
    $this->assertEquals($anonymous, $records[3], 'Anonymous catch-all grant found.');
  }

  /**
   * Tests the access records for an unpublished group media.
   */
  public function testUnpublishedGroupMedia() {
    /** @var \Drupal\media\MediaInterface $media */
    $media = $this->entityTypeManager
      ->getStorage('media')
      ->create([
        'type' => 'a',
        'title' => $this->randomMachineName(),
        'status' => NODE_NOT_PUBLISHED,
      ]);
    $media->save();
    $this->groupA1->addContent($media, 'group_media:a');

    $records = gmedia_media_access_records($media);
    $this->assertCount(4, $records, '4 access records set for an unpublished group media.');

    $base = [
      'grant_view' => 1,
      'grant_update' => 1,
      'grant_delete' => 1,
      'priority' => 0,
    ];
    $gid = $this->groupA1->id();
    $uid = $media->getOwnerId();
    $this->assertEquals(['gid' => $gid, 'realm' => 'gmedia_unpublished:a'] + $base, $records[0], 'General gmedia_unpublished:NODE_TYPE grant found.');
    $this->assertEquals(['gid' => $gid, 'realm' => "gmedia_author:$uid:a"] + $base, $records[1], 'Author gmedia_author:UID:NODE_TYPE grant found.');
    $this->assertEquals(['gid' => GNODE_MASTER_GRANT_ID, 'realm' => 'gmedia_bypass'] + $base, $records[2], 'Admin gmedia_bypass grant found.');

    $anonymous = [
      'gid' => GNODE_MASTER_GRANT_ID,
      'realm' => 'gmedia_anonymous',
      'grant_view' => 0,
      'grant_update' => 0,
      'grant_delete' => 0,
      'priority' => 0
    ];
    $this->assertEquals($anonymous, $records[3], 'Anonymous catch-all grant found.');
  }

}
