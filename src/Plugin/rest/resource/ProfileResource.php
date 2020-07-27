<?php

namespace Drupal\zprofile\Plugin\rest\resource;

use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Provides a resource to get and update user profiles.
 *
 * @RestResource(
 *   id = "profile_resource",
 *   label = @Translation("Profile resource"),
 *   serialization_class = "Drupal\zprofile\Entity\userProfile",
 *   entity_type = "user_profile",
 *   uri_paths = {
 *     "canonical" = "/api/v1/profiles/{profileId}",
 *   }
 * )
 */
class ProfileResource extends ResourceBase {

  /**
   * A current user instance.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = parent::create($container, $configuration, $plugin_id, $plugin_definition);
    $instance->logger = $container->get('logger.factory')->get('zprofile');
    $instance->currentUser = $container->get('current_user');
    return $instance;
  }

  /**
   * Responds to GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   The HTTP response object.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function get($profile_id) {

    // You must to implement the logic of your REST Resource here.
    // Use current user after pass authentication to validate access.
    if (!$this->currentUser->hasPermission('access content')) {
      throw new AccessDeniedHttpException();
    }

    $data = \Drupal::service('entity_type.manager')->getStorage('user_profile')->load($profile_id);

    $response = new ResourceResponse($data);
    // In order to generate fresh result every time (without clearing
    // the cache), you need to invalidate the cache.
    $response->addCacheableDependency($data);

    return $response;
  }

  /**
   * Responds to PATCH requests.
   *
   * @param int $entity_id
   *   The requested for update Entity id.
   * @param userProfile $entity
   *   Request's body serialized as an user_profile Entity.
   *
   * @return \Drupal\rest\ModifiedResourceResponse
   *   The HTTP response object.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function patch($entity_id, $entity = NULL) {

    // You must to implement the logic of your REST Resource here.
    // Use current user after pass authentication to validate access.
    if (!$this->currentUser->hasPermission('access content')) {
      throw new AccessDeniedHttpException();
    }

    $original_entity = \Drupal::service('entity_type.manager')->getStorage('user_profile')->load($entity_id);

    if ($entity == NULL) {
      throw new BadRequestHttpException('No entity content received.');
    }
    $definition = $this->getPluginDefinition();
    if ($entity->getEntityTypeId() != $definition['entity_type']) {
      throw new BadRequestHttpException('Invalid entity type');
    }

    // Overwrite the received fields.
    // @todo Remove $changed_fields in https://www.drupal.org/project/drupal/issues/2862574.
    $changed_fields = [];
    foreach ($entity->_restSubmittedFields as $field_name) {
      $field = $entity->get($field_name);
      // It is not possible to set the language to NULL as it is automatically
      // re-initialized. As it must not be empty, skip it if it is.
      // @todo Remove in https://www.drupal.org/project/drupal/issues/2933408.
      if ($entity->getEntityType()->hasKey('langcode') && $field_name === $entity->getEntityType()->getKey('langcode') && $field->isEmpty()) {
        continue;
      }
      $changed_fields[] = $field_name;
      $original_entity->set($field_name, $field->getValue());
    }

    // If no fields are changed, we can send a response immediately!
    if (empty($changed_fields)) {
      return new ModifiedResourceResponse($original_entity, 200);
    }

    try {
      $original_entity->save();
      $this->logger->notice('Updated entity %type with ID %id.', ['%type' => $original_entity->getEntityTypeId(), '%id' => $original_entity->id()]);

      // Return the updated entity in the response body.
      return new ModifiedResourceResponse($original_entity, 200);
    }
    catch (EntityStorageException $e) {
      throw new HttpException(500, 'Internal Server Error', $e);
    }

    return new ModifiedResourceResponse($data, 204);
  }

}
