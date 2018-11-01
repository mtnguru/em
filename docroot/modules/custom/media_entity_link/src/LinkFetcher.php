<?php

namespace Drupal\media_entity_link;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\media_entity_link\Exception\LinkApiException;

/**
 * Fetches (and caches) link data from Link's API.
 */
class LinkFetcher implements LinkFetcherInterface {

  /**
   * The optional cache backend.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  protected $cache;

  /**
   * The current set of Link API credentials.
   *
   * @var array
   */
  protected $credentials = [];

  /**
   * The current API exchange object.
   *
   * @var \LinkAPIExchange
   */
  protected $link;

  /**
   * LinkFetcher constructor.
   *
   * @param \Drupal\Core\Cache\CacheBackendInterface|null $cache
   *   (optional) A cache bin for storing fetched links.
   */
  public function __construct(CacheBackendInterface $cache = NULL) {
    $this->cache = $cache;
  }

  /**
   * {@inheritdoc}
   */
  public function fetchLink($id) {
    // Links don't change much, so pull it out of the cache (if we have one)
    // if this one has already been fetched.
    if ($this->cache && $cached_link = $this->cache->get($id)) {
      return $cached_link->data;
    }

    // Ensure that we have an actual API exchange instance.
    if (empty($this->link)) {
      throw new \UnexpectedValueException('Link API exchange has not been initialized; credentials may not have been set yet.');
    }

    // Query Link's API.
    $response = $this->link
      ->setGetfield('?id=' . $id)
      ->buildOAuth('https://api.link.com/1.1/statuses/show.json', 'GET')
      ->performRequest();

    if (empty($response)) {
      throw new \Exception("Could not retrieve link $id.");
    }
    // Handle errors as per https://dev.link.com/overview/api/response-codes.
    if (!empty($response['errors'])) {
      throw new LinkApiException($response['errors']);
    }

    $response = Json::decode($response);
    // If we have a cache, store the response for future use.
    if ($this->cache) {
      // Links don't change often, so the response should expire from the cache
      // on its own in 90 days.
      $this->cache->set($id, $response, time() + (86400 * 90));
    }

    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function getCredentials() {
    return $this->credentials;
  }

  /**
   * {@inheritdoc}
   */
  public function setCredentials($consumer_key, $consumer_secret, $oauth_access_token, $oauth_access_token_secret) {
    $this->credentials = [
      'consumer_key' => $consumer_key,
      'consumer_secret' => $consumer_secret,
      'oauth_access_token' => $oauth_access_token,
      'oauth_access_token_secret' => $oauth_access_token_secret,
    ];
    $this->link = new \LinkAPIExchange($this->credentials);
  }

}
