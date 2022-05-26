<?php

namespace Drupal\user_manual\Plugin\HelpSection;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\help\Plugin\HelpSection\HelpSectionPluginBase;
use Drupal\user_manual\Entity\UserManual;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides the help topics list section for the help page.
 *
 * @HelpSection(
 *   id = "user_manual",
 *   title = @Translation("User Manual"),
 *   weight = -100,
 *   description = @Translation("User Manual specific to this site."),
 *   permission = "access user_manual help section"
 * )
 */
class UserManualSection extends HelpSectionPluginBase implements ContainerFactoryPluginInterface {

  /**
   * User Manual entity type id.
   */
  const ENTITY_TYPE = 'user_manual';

  /**
   * Entity storage for user_manual entities.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $entityStorage;

  /**
   * View builder for user_manual entities.
   *
   * @var \Drupal\Core\Entity\EntityViewBuilderInterface
   */
  protected $viewBuilder;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager) {
    $this->entityStorage = $entity_type_manager->getStorage(self::ENTITY_TYPE);
    $this->viewBuilder = $entity_type_manager->getViewBuilder(self::ENTITY_TYPE);
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function listTopics() {
    // View nodes individually because of the rendering optimizations of
    // viewMultiple do not play nicely with the simple array format needed
    // for help content.
    return array_map(function (UserManual $doc) {
      return $this->viewBuilder->view($doc, 'teaser');
    }, $this->getSortedManualEntities());
  }

  /**
   * Sort entities alphabetically.
   */
  protected function getSortedManualEntities() {
    $entities = $this->entityStorage->loadMultiple();
    usort($entities, function (UserManual $a, UserManual $b) {
      return strcasecmp($a->label(), $b->label());
    });
    return $entities;
  }

}
