<?php

namespace Drupal\Tests\zprofile\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\zprofile\Service\Twitter;

/**
 * Twitter service tests.
 */
class TwitterTest extends UnitTestCase {

  /**
   * Twitter service instance.
   *
   * @var \Drupal\zprofile\Service\Twitter
   */
  protected $twitter;

  /**
   * Test setUp.
   */
  public function setUp() {
    $config_factory = $this->getMockBuilder('Drupal\Core\Config\ConfigFactory')
      ->disableOriginalConstructor()
      ->getMock();
    $this->twitter = new Twitter($config_factory);
  }

  /**
   * Test Twitter Timeline.
   */
  public function testTwitterTimeline() {
    $this->twitter->setTwitterUser('fredysante');

    $this->assertEquals(
      'fredysante',
      $this->twitter->getTwitterUser()
    );

    $auth_settings = $this->getAuthSettings();
    $count = 1;
    $tweets = $this->twitter->getTweets($auth_settings, $count);
    foreach ($tweets as $tweet) {
      $this->assertObjectHasAttribute('text', $tweet);
      break;
    }
  }

  /**
   * Returns the Twitter Auth keys.
   */
  protected function getAuthSettings() {
    return [
      'oauth_access_token' => '1220032047516921859-otvXjhExyUTZ5GLxssc9h5ORqtPZja',
      'oauth_access_token_secret' => 'tmJKqM4ORfQW6CH7wIVV8uKNpmSEmeFAP8lYwGb19uYjj',
      'consumer_key' => 'KRy7l0v8wex3w8Sy5zThai3Ea',
      'consumer_secret' => 'X2eBm0Y21kYEuR74W3Frqc2JVIizOj8Q1EVGatDsEVVEJo0ucu',
    ];
  }

}
