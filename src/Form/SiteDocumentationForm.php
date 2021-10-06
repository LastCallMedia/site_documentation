<?php

namespace Drupal\site_documentation\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form controller for Site Documentation edit forms.
 *
 * @ingroup site_documentation
 */
class SiteDocumentationForm extends ContentEntityForm {

  /**
   * The current user account.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $account;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Instantiates this form class.
    $instance = parent::create($container);
    $instance->account = $container->get('current_user');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\site_documentation\Entity\SiteDocumentation $entity */
    $form = parent::buildForm($form, $form_state);

    if (!$this->entity->isNew()) {
      $form['new_revision'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Create new revision'),
        '#default_value' => FALSE,
        '#weight' => 10,
      ];
    }

    $form['publishing_information'] = [
      '#type' => 'details',
      '#title' => $this->t('Publishing Information'),
      '#open' => TRUE,
      '#tree' => TRUE,
      '#weight' => 100,
    ];

    if (isset($form['new_revision'])) {
      $form['new_revision']['#default_value'] = TRUE;
    }

//    foreach (['status', 'new_revision', 'revision_log', 'user_id'] as $weight => $index) {
//      if (isset($form[$index])) {
//        $form['publishing_information'][$index] = $form[$index];
//        $form['publishing_information'][$index]['#weight'] = $weight;
//        unset($form[$index]);
//      }
//    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;

    // Save as a new revision if requested to do so.
    if (!$form_state->isValueEmpty('new_revision') && $form_state->getValue('new_revision') != FALSE) {
      $entity->setNewRevision();

      // If a new revision is created, save the current user as revision author.
      $entity->setRevisionCreationTime($this->time->getRequestTime());
      $entity->setRevisionUserId($this->account->id());
    }
    else {
      $entity->setNewRevision(FALSE);
    }

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Site Documentation.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Site Documentation.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.site_documentation.canonical', ['site_documentation' => $entity->id()]);
  }

}
