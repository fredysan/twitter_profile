<?php

namespace Drupal\zprofile\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the User profile type entity.
 *
 * @ConfigEntityType(
 *   id = "user_profile_type",
 *   label = @Translation("User profile type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\zprofile\userProfileTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\zprofile\Form\userProfileTypeForm",
 *       "edit" = "Drupal\zprofile\Form\userProfileTypeForm",
 *       "delete" = "Drupal\zprofile\Form\userProfileTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\zprofile\userProfileTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "user_profile_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "user_profile",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/user_profile_type/{user_profile_type}",
 *     "add-form" = "/admin/structure/user_profile_type/add",
 *     "edit-form" = "/admin/structure/user_profile_type/{user_profile_type}/edit",
 *     "delete-form" = "/admin/structure/user_profile_type/{user_profile_type}/delete",
 *     "collection" = "/admin/structure/user_profile_type"
 *   }
 * )
 */
class userProfileType extends ConfigEntityBundleBase implements userProfileTypeInterface {

  /**
   * The User profile type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The User profile type label.
   *
   * @var string
   */
  protected $label;

}
