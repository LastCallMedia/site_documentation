<?php

namespace Drupal\site_documentation;

use Drupal\Core\Field\EntityReferenceFieldItemList;
use Drupal\site_documentation\Entity\SiteDocumentation;
use Drupal\Core\TypedData\ComputedItemListTrait;

/**
 * Computed field value loading _all_ documentation.
 */
class DocsListing extends EntityReferenceFieldItemList {

  use ComputedItemListTrait;

  /**
   * Compute the values.
   */
  protected function computeValue() {
    $site_docs = SiteDocumentation::loadMultiple();

    foreach ($site_docs as $delta => $value) {
      $this->list[$delta] = $this->createItem($delta, $value);
    }

  }

}
