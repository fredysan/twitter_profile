<?php

namespace Drupal\zprofile\Service;

use Drupal\twitter_tweets\twitter_api_php\TwitterAPIExchange;

/**
 * Class Twitter.
 */
class Twitter {

  /**
   * The config instance.
   *
   * @var Drupal\Core\Config\ImmutableConfig
   */
  protected $config;

  /**
   * Constructor.
   */
  public function __construct($config_manager) {
    $this->config = $config_manager->get('twitter_tweets.credentials');
  }

  /**
   * Twitter user name.
   *
   * @var string
   */
  protected $twitterUser;

  /**
   * Sets the twitter user property.
   *
   * @param string $user_name
   *   Twitter user name.
   */
  public function setTwitterUser($user_name) {
    $this->twitterUser = $user_name;
  }

  /**
   * Sets the twitter user property.
   */
  public function getTwitterUser() {
    return $this->twitterUser;
  }

  /**
   * Gets the twitter API auth keys.
   */
  protected function getAuthKeys() {
    $config = \Drupal::config('twitter_tweets.credentials');
    $settings = [];
    $settings['oauth_access_token'] = $config->get('oauth_access_token');
    $settings['oauth_access_token_secret'] = $config->get('oauth_access_token_secret');
    $settings['consumer_key'] = $config->get('consumer_key');
    $settings['consumer_secret'] = $config->get('consumer_secret');
    return $settings;
  }

  /**
   * Gets the themed twitter tweets timeline.
   */
  public function getTimelineBlock() {
    $settings = $this->getAuthKeys();
    $tweet_count = $this->config->get('tweet_count') ?: 5;
    $tweets = $this->getTweets($settings, $tweet_count);

    $cleanTweets = [];
    foreach ($tweets as $tweet) {
      if (is_object($tweet) && property_exists($tweet, 'text')) {
        $tweet->text = check_markup($tweet->text, 'full_html');
        $cleanTweets[] = $tweet;
      }
    }

    $params = ['tweets' => $cleanTweets];
    $tweet_template = ['#theme' => 'tweet_listing', '#params' => $params];
    return $tweet_template;
  }

  /**
   * Gets Twitter tweets from the API.
   *
   * @param array $settings
   *   Twitter auth settings.
   * @param int $count
   *   Number of retrieved tweets.
   *
   * @return array
   *   List of twitter tweets.
   */
  public function getTweets(array $settings, int $count) {
    $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
    $requestMethod = 'GET';
    $getfield = '?screen_name=' . $this->twitterUser . '&count=' . $count;
    $twitter = new TwitterAPIExchange($settings);
    $tweets = $twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest();
    return json_decode($tweets);
  }

}
