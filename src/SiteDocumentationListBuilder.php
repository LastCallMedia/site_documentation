<?php

namespace Drupal\site_documentation;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Site Documentation entities.
 *
 * @ingroup site_documentation
 */
class SiteDocumentationListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Site Documentation ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\site_documentation\Entity\SiteDocumentation $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.site_documentation.canonical',
      ['site_documentation' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  protected function getDefaultOperations(EntityInterface $entity) {
    $ops = parent::getDefaultOperations($entity);
    if ($entity->access('view')) {
      $ops['view'] = [
        'title' => $this->t('View'),
        'weight' => 0,
        'url' => $this->ensureDestination($entity->toUrl('canonical')),
      ];
    }
    return $ops;
  }

  /**
   * {@inheritdoc}
   */
  public function render() {
    $build['#prefix'] = Link::createFromRoute(
      $this->t('Add Site Documentation'),
      'entity.site_documentation.add_form', [], ['attributes' => ['class' => 'button button--action button--primary']])->toString();
    return $build + parent::render();
  }

}
