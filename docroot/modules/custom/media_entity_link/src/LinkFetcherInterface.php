<?php

namespace Drupal\media_entity_link;

/**
 * Defines a wrapper around the Link API.
 */
interface LinkFetcherInterface {

  /**
   * Retrieves a link by its ID.
   *
   * @param int $id
   *   The link ID.
   *
   * @return array
   *   The link information.
   *
   * @throws \Drupal\media_entity_link\Exception\LinkApiException
   *   If the Link API returns errors in the response.
   */
  public function fetchLink($id);

  /**
   * Returns the current Link API credentials.
   *
   * @return array
   *   The API credentials. Will be an array with consumer_key, consumer_secret,
   *   oauth_access_token, and oauth_access_token_secret elements.
   */
  public function getCredentials();

  /**
   * Sets the credentials for accessing Link's API.
   *
   * @param string $consumer_key
   *   The consumer key.
   * @param string $consumer_secret
   *   The consumer secret.
   * @param string $oauth_access_token
   *   The OAuth access token.
   * @param string $oauth_access_token_secret
   *   The OAuth access token secret.
   */
  public function setCredentials($consumer_key, $consumer_secret, $oauth_access_token, $oauth_access_token_secret);

}
