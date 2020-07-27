<?php

namespace Drupal\zprofile\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining User profile entities.
 *
 * @ingroup zprofile
 */
interface userProfileInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the User profile name.
   *
   * @return string
   *   Name of the User profile.
   */
  public function getName();

  /**
   * Sets the User profile name.
   *
   * @param string $name
   *   The User profile name.
   *
   * @return \Drupal\zprofile\Entity\userProfileInterface
   *   The called User profile entity.
   */
  public function setName($name);

  /**
   * Gets the User profile creation timestamp.
   *
   * @return int
   *   Creation timestamp of the User profile.
   */
  public function getCreatedTime();

  /**
   * Sets the User profile creation timestamp.
   *
   * @param int $timestamp
   *   The User profile creation timestamp.
   *
   * @return \Drupal\zprofile\Entity\userProfileInterface
   *   The called User profile entity.
   */
  public function setCreatedTime($timestamp);

}
