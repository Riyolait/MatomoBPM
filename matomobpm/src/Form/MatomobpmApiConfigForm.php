<?php

namespace Drupal\matomobpm\Form;

use Drupal\Core\Form\FormBase;
class MatomobpmApiConfigForm extends FormBase   {

    const MATOMOBPM_API_CONFIG_FORM = 'matomobpm_api_config_form:values';

    public function getFormId() {
        return 'matomobpm_api_config_form';
    }

    public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state){
        $values = \Drupal::state()->get(self::MATOMOBPM_API_CONFIG_FORM);
        $form = array();
        
        $form['matomobpm_api_base_url'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('API Base URL'),
            '#description' => $this->t('The base URL of the Matomo API.'),
            '#required' => TRUE,
            '#default_value' => $values['matomobpm_api_base_url']
        );

        $form['matomobpm_api_token'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('API Token'),
            '#description' => $this->t('The API token for the Matomo API.'),
            '#required' => TRUE,
            '#default_value' => $values['matomobpm_api_token']
        );
        
        $form['actions']['#type'] = 'actions';
        $form['actions']['submit'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Save'),
            '#button_type' => 'primary'
        );
        return $form;
    }
    public function submitForm(array &$form, \Drupal\Core\Form\FormStateInterface $form_state){
        $submitted_values = $form_state->cleanValues()->getValues();
    
        \Drupal::state()->set(self::MATOMOBPM_API_CONFIG_FORM, $submitted_values);

        $messenger = \Drupal::service('messenger');
        $messenger->addMessage($this->t('The Matomo BPM API configuration has been saved.'));
    }
}