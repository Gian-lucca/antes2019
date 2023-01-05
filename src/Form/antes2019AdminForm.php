<?php  
/*
|--------------------------------------------------------------------------
| antes2019AdminForm.php
|--------------------------------------------------------------------------|
| author Gianlucca Augusto <gianlucca.augusto@extreme.digital>
| version 1.0
| copyright Proderj 2022.
*/

/**  
 * @file  
 * Contains Drupal\antes2019\Form\antes2019AdminForm.  
 */  

namespace Drupal\antes2019\Form;  

use Drupal\Core\Form\ConfigFormBase;  
use Drupal\Core\Form\FormStateInterface;  

/**
 * Configuração do formulário de antes2019
 */
class antes2019AdminForm extends ConfigFormBase {  
  /**  
   * {@inheritdoc}  
   */  
  protected function getEditableConfigNames() {  
    return [  
      'antes2019.adminsettings',  
    ];  
  }  

  /**  
   * {@inheritdoc}  
   */  
  public function getFormId() {  
    return 'antes2019_admin_form';  
  }  
  
  /**  
   * {@inheritdoc}  
   */  
  public function buildForm(array $form, FormStateInterface $form_state) {  
    $config = $this->config('antes2019.adminsettings');  

    $form['antes2019_admin_email'] = array(  
      '#type' => 'email',  
      '#title' => $this->t('Email'),  
      '#description' => $this->t('Endereço de e-mail para o qual os dados do formulário de antes2019 devem ser enviados'),  
      '#default_value' => $config->get('antes2019_admin_email'),  
      '#required' => TRUE,
    );
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Salvar'),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    /**
     * Valida email
     */
    $antes2019_admin_email = trim($form_state->getValue('antes2019_admin_email'));
  
    if ($antes2019_admin_email !== '' && !\Drupal::service('email.validator')->isValid($antes2019_admin_email)) {
      $form_state->setErrorByName('antes2019_admin_email', $this->t('E-mail inválido!'));  
    }
  }

  /**  
   * {@inheritdoc}  
   */  
  public function submitForm(array &$form, FormStateInterface $form_state) {  
    $this->config('antes2019.adminsettings')  
      ->set('antes2019_admin_email', trim($form_state->getValue('antes2019_admin_email')))  
      ->save();  
  }    
}
