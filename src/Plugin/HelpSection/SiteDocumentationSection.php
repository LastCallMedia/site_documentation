<?php

namespace Drupal\site_documentation\Plugin\HelpSection;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\help\Plugin\HelpSection\HelpSectionPluginBase;
use Drupal\site_documentation\Entity\SiteDocumentation;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides the help topics list section for the help page.
 *
 * @HelpSection(
 *   id = "site_documentation",
 *   title = @Translation("Site Documentation"),
 *   weight = -100,
 *   description = @Translation("Documentation specific to this site."),
 *   permission = "access site_documentation help section"
 * )
 */
class SiteDocumentationSection extends HelpSectionPluginBase implements ContainerFactoryPluginInterface {

  /**
   * Documentation entity type id.
   */
  const ENTITY_TYPE = 'site_documentation';

  /**
   * Entity storage for site_documentation entities.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $entityStorage;

  /**
   * View builder for site_documentation entities.
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
    return array_map(function (SiteDocumentation $doc) {
      return $this->viewBuilder->view($doc, 'teaser');
    }, $this->getSortedDocumentationEntities());
  }

  /**
   * Sort entities alphabetically.
   */
  protected function getSortedDocumentationEntities() {
    $entities = $this->entityStorage->loadMultiple();
    usort($entities, function (SiteDocumentation $a, SiteDocumentation $b) {
      return strcasecmp($a->label(), $b->label());
    });
    return $entities;
  }

}
