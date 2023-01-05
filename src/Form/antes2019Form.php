<?php
/*
|--------------------------------------------------------------------------
| antes2019Form.php
|--------------------------------------------------------------------------|
| author Gianlucca Augusto <gianlucca.augusto@extreme.digital>
| version 1.0
| copyright Proderj 2022.
*/
 
namespace Drupal\antes2019\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class antes2019Form extends FormBase {
    public function getFormId() {
      return 'antes2019_form_id';
    }

    public function buildForm(array $form, FormStateInterface $form_state) {

      $form['tipodedeclaracao'] = array(
        '#title' => t('Tipo de declaracao'),
        '#type' => 'select',
        '#required' => TRUE,
        '#description' => '',
        '#options' => array(
          t('Declaração de Conclusão'), t('Declaração de Pagamento')
        ),
        '#attributes'=> [
          'class' => ['inputs']
        ]
      );

      $form['curso'] = array(
        '#type' => 'textfield',
        '#title' => t('Informações do Curso realizado'),
        '#size' => 60,      
        '#maxlength' => 60,
        '#required' => TRUE,
        '#attributes'=> [
          'placeholder' => 'Curso Realizado',
          'class' => ['inputs']
        ]
      );

      $form['ano'] = array(
        '#type' => 'textfield',
        '#title' => t(''),
        '#size' => 60,      
        '#maxlength' => 60,
        '#required' => FALSE,
        '#attributes'=> [
          'placeholder' => 'Ano',
          'class' => ['inputs']
        ]
      );

      $form['nome'] = array(
        '#type' => 'textfield',
        '#title' => t('Nome Completo'),
        '#size' => 60,      
        '#maxlength' => 60,
        '#required' => TRUE,
        '#attributes'=> [
          'placeholder' => 'Nome Completo',
          'class' => ['inputs']
        ]
      );

      $form['email'] = array(
        '#type' => 'email',
        '#title' => t('E-mail'),
        '#size' => 60,
        '#maxlength' => 100,
        '#required' => TRUE,
        '#attributes'=> [
          'placeholder' => '',
          'class' => ['inputs']
        ]
      );

      $form['telefone'] = array(
        '#type' => 'textfield',
        '#title' => t('Telefone com DDD'),
        '#size' => 60,
        '#maxlength' => 100,
        '#required' => TRUE,
        '#attributes'=> [
          'placeholder' => '',
          'class' => ['inputs']
        ]
      );

      $form['RG'] = array(
        '#type' => 'textfield',
        '#title' => t('RG'),
        '#size' => 60,
        '#maxlength' => 11,
        '#required' => TRUE,
        '#attributes'=> [
          'placeholder' => 'RG',
          'class' => ['inputs']
        ]
      );

      $form['orgao'] = array(
        '#type' => 'textfield',
        '#title' => t(''),
        '#size' => 60,      
        '#maxlength' => 60,
        '#required' => FALSE,
        '#attributes'=> [
          'placeholder' => 'Órgão Expeditor',
          'class' => ['inputs']
        ]
      );

      $form['CPF'] = array(
        '#type' => 'textfield',
        '#title' => t('CPF'),
        '#size' => 60,
        '#maxlength' => 11,
        '#required' => TRUE,
        '#attributes'=> [
          'placeholder' => '',
          'class' => ['inputs']
        ]
      );

      $form['description'] = [
        '#type' => 'markup',
        '#markup' => '<h3>Declaração de consentimento</h3>',
        '<a href="?page_id=2072" target="_blank" role="link">Ler Declaração de consentimento para tratamento de dados pessoais</a>',
      ];

      $form['declaracao'] = [
        '#type' => 'text',
        '#markup' => '<a href="/declaracao-consentimento">Ler Declaração de consentimento para tratamento de dados pessoais</a>',
      ];

      $form['ciencia'] = [
        '#type' => 'checkbox',
        '#title' => 'Estou ciente'
      ];

      $form['#attributes']['enctype'] = 'multipart/form-data';

      $form['submit'] = array(
        '#type' => 'submit',
        '#value' => t('Enviar'),
        '#attributes'=> [
          'class' => ['botao']
        ]
      );
      return $form;
    }

   /**
   * @return array
   */
  private function getAllowedFileExtensions(){
    return array('pdf');
  }

  /**
   * @param $entity_type
   * @return string
   */
  public function buildFileLocaton($entity_type){
    // Build file location
    return $entity_type.'/'.date('Y_m_d');
  }

    /**
     * {@inheritdoc}
    */
    public function validateForm(array &$form, FormStateInterface $form_state) {

        //Valida nome 
        $nome = trim($form_state->getValue('nome'));
        
        if (!preg_match("/^([a-zA-Z ]+)$/", $nome)) {
            $form_state->setErrorByName('nome', $this->t('Carateres inválidos no seu nome'));
        }
        
        //Valida email
        $email = trim($form_state->getValue('email'));
    
        if ($email !== '' && !\Drupal::service('email.validator')->isValid($email)) {
        $form_state->setErrorByName('email', $this->t('Endereço de email inválido'));  
        }

        // CPF com caracter não numero
        if (!preg_match("/^([0-9]+)$/", trim($form_state->getValue('CPF')))) {
          $form_state->setErrorByName('CPF', $this->t('CPF Apenas números'));
        }

        // RG com caracter não numero
        if (!preg_match("/^([0-9]+)$/", trim($form_state->getValue('RG')))) {
          $form_state->setErrorByName('RG', $this->t('RG Apenas números'));
        }

        // TELEFONE APENAS NUMEROS
        if (!preg_match("/^([0-9]+)$/", trim($form_state->getValue('telefone')))) {
          $form_state->setErrorByName('telefone', $this->t('Telefone Apenas números'));
        }

    }

    /**
     * {@inheritdoc}
    */

    public function submitForm(array &$form, FormStateInterface $form_state) {
        /**
         * Pega os dados do Imput
         */
        $curso = trim($form_state->getValue('curso'));
        $ano = trim($form_state->getValue('ano'));
        $nome = trim($form_state->getValue('nome'));
        $email = trim($form_state->getValue('email'));
        $telefone = trim($form_state->getValue('telefone'));
        $RG = trim($form_state->getValue('RG'));
        $orgao = trim($form_state->getValue('orgao'));
        $CPF = trim($form_state->getValue('CPF'));
        $ciencia = trim($form_state->getValue('ciencia'));
    
        $files = $form_state->getValue('files');

        /**
        * Pegando os arquivos
        */
        $filenames = array();
        foreach ($files as $fid) {
        $file = File::load($fid);
        $file->setPermanent();
        $file->save();
        $name = $file->getFilename();
        $url = file_create_url($file->getFileUri());
        $filenames [] = [$name, $url];
        
        }
        /**
         * Pega o email que será enviado 
         */
        $config = $this->config('antes2019.adminsettings');
        $antes2019_admin_email = trim($config->get('antes2019_admin_email'));
        
        if ($antes2019_admin_email) {
            /**
             * Envia email
             */
            $this->logger($str);
            $mail_manager = \Drupal::service('plugin.manager.mail');
            $langcode = \Drupal::currentUser()->getPreferredLangcode();

            $params['message']['curso'] = $curso;
            $params['message']['ano'] = $ano;
            $params['message']['nome'] = $nome;
            $params['message']['email'] = $email;
            $params['message']['telefone'] = $telefone;
            $params['message']['RG'] = $RG;
            $params['message']['orgao'] = $orgao;
            $params['message']['CPF'] = $CPF;
            $params['message']['ciencia'] = $ciencia;

            $params['message']['antes2019files'] = $filenames;

            
            $to = $antes2019_admin_email;
            //envia email para o email que foi salvo no painel de administrativo
            $result = $mail_manager->mail('antes2019', 'antes2019_notificacao', $to, $langcode, $params, NULL, 'true');
            //envia protocolo para o usuário que solicitou o email
            $result = $mail_manager->mail('antes2019', 'antes2019_protocolo', $email, $langcode, $params, NULL, 'true');
        }

        /**
         * Retorna mensagem Sucesso
         */
        \Drupal::messenger()->addStatus(t('Obrigado ' . $nome . ',sua mensagem foi enviada com sucesso, para seu email!'));
    }
}
