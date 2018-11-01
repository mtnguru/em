<?php

namespace Drupal\Tests\gmedia\Kernel;

/**
 * Tests the grants people receive for group media.
 *
 * @group gmedia
 */
class GroupMediaGrantsTest extends GroupMediaAccessTestBase {

  /**
   * Tests the assignment of the bypass access grant.
   */
  public function testBypassGrant() {
    $account = $this->createUser([], ['bypass group access']);
    $grants = gmedia_media_grants($account, 'view');
    $this->assertEquals(['gmedia_bypass' => [GNODE_MASTER_GRANT_ID]], $grants, 'Users who can bypass group access receive the bypass grant.');
  }

  /**
   * Tests the existence of specific grant realms.
   */
  public function testGrantRealms() {
    $grants = gmedia_media_grants($this->account, 'view');
    $this->assertArrayHasKey('gmedia:a', $grants, 'Grants were handed out for media type a.');
    $this->assertArrayHasKey('gmedia:b', $grants, 'Grants were handed out for media type b.');
    $this->assertArrayNotHasKey('gmedia:c', $grants, 'Grants were not handed out for media type c.');
  }

  /**
   * Tests that a user receives the right view grants for group media.
   */
  public function testViewGrants() {
    $grants = gmedia_media_grants($this->account, 'view');
    $this->assertTrue(in_array($this->groupA1->id(), $grants['gmedia:a']), 'A-group: Member can view A-media.');
    $this->assertTrue(in_array($this->groupA1->id(), $grants['gmedia:b']), 'A-group: Member can view B-media.');
    $this->assertTrue(in_array($this->groupA1->id(), $grants['gmedia_unpublished:a']), 'A-group: Member can view unpublished A-media.');
    $this->assertTrue(in_array($this->groupA2->id(), $grants['gmedia:a']), 'A-group: Outsider can view A-media.');
    $this->assertTrue(in_array($this->groupA2->id(), $grants['gmedia:b']), 'A-group: Outsider can view B-media.');
    $this->assertTrue(in_array($this->groupB2->id(), $grants['gmedia:b']), 'B-group: Outsider can view B-media.');

    // We are testing a bit more specifically here to make sure that the system
    // is only adding those group IDs the user has access in. Seeing as further
    // tests rely on the same system, we are not testing this again.
    $this->assertFalse(in_array($this->groupA2->id(), $grants['gmedia_unpublished:a']), 'A-group: Outsider can not view unpublished A-media.');
    $this->assertFalse(in_array($this->groupB2->id(), $grants['gmedia:a']), 'B-group: Outsider can not view A-media.');
    $this->assertFalse(in_array($this->groupB1->id(), $grants['gmedia:b']), 'B-group: Member can not view B-media.');
  }

  /**
   * Tests that a user receives the right update grants for group media.
   */
  public function testUpdateGrants() {
    $grants = gmedia_media_grants($this->account, 'update');

    // Test 'update any' permissions.
    $this->assertTrue(in_array($this->groupA2->id(), $grants['gmedia:a']), 'A-group: Outsider can update any A-media.');
    $this->assertTrue(in_array($this->groupB1->id(), $grants['gmedia:b']), 'B-group: Member can update any B-media.');

    // Test 'update own' permissions.
    $this->assertTrue(in_array($this->groupA1->id(), $grants['gmedia_author:2:a']), 'A-group: Member can update own A-media.');
    $this->assertTrue(in_array($this->groupB2->id(), $grants['gmedia_author:2:b']), 'B-group: Outsider can update own B-media.');
  }

  /**
   * Tests that a user receives the right delete grants for group media.
   */
  public function testDeleteGrants() {
    $grants = gmedia_media_grants($this->account, 'delete');

    // Test 'delete any' permissions.
    $this->assertTrue(in_array($this->groupA2->id(), $grants['gmedia:a']), 'A-group: Outsider can delete any A-media.');
    $this->assertTrue(in_array($this->groupB1->id(), $grants['gmedia:b']), 'B-group: Member can delete any B-media.');

    // Test 'delete own' permissions.
    $this->assertTrue(in_array($this->groupA1->id(), $grants['gmedia_author:2:a']), 'A-group: Member can delete own A-media.');
    $this->assertTrue(in_array($this->groupB2->id(), $grants['gmedia_author:2:b']), 'B-group: Outsider can delete own B-media.');
  }

}
