<?php

namespace Drupal\site_documentation;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\help\HelpSectionManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Create permission for each help section.
 */
class HelpSectionPermissions implements ContainerInjectionInterface {

  use StringTranslationTrait;

  /**
   * Help Section Manager.
   *
   * @var \Drupal\help\HelpSectionManager
   */
  protected $helpSectionManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(HelpSectionManager $help_section_manager) {
    $this->helpSectionManager = $help_section_manager;
  }

  /**
   * Returns an array of help section permissions.
   *
   * @return array
   *   The node type permissions.
   *   @see \Drupal\user\PermissionHandlerInterface::getPermissions()
   */
  public function helpSectionPermissions() {
    $perms = [];
    $plugins = $this->helpSectionManager->getDefinitions();
    // Generate node permissions for all node types.
    foreach ($plugins as $plugin) {
      $perms['access ' . $plugin['id'] . ' help_section'] = [
        'title' => $this->t('Help Section: Access %section', ['%section' => $plugin['title']]),
      ];
    }

    return $perms;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('plugin.manager.help_section'));
  }

}
