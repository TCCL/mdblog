<?php

namespace Drupal\mdblog\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Extends ConfigFormBase to provide a settings form for Markdown Blog module.
 */
class SettingsForm extends ConfigFormBase {
    public function buildForm(array $form,FormStateInterface $form_state) {
        // Assign form elements.
        $form = parent::buildForm($form,$form_state);
        $form['cmdline'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('The command-line for the markdown backend to employ'),
        );
        $form['stdin'] = array(
            '#type' => 'checkbox',
            '#title' => $this->t('Check if command receives markdown via its stdin. '
                                . 'Otherwise a temporary file is created and appended '
                                . 'to the command-line or replaced from @file.'),
        );
        $form['styledoc'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('The URL to a CSS document to style the compiled Markdown.'),
        );

        // Assign default values.
        $config = $this->config('mdblog.settings');
        foreach ($form as $key => &$value) {
            $currentValue = $config->get("mdblog.$key");
            if (!empty($currentValue)) {
                $value['#default_value'] = $currentValue;
            }
        }

        return $form;
    }

    function submitForm(array &$form,FormStateInterface $form_state) {
        $config = $this->config('mdblog.settings');
        foreach ($form_state->getValues() as $key => $value) {
            $config->set("mdblog.$key",$value);
        }
        $config->save();
        parent::submitForm($form,$form_state);
    }

    function getEditableConfigNames() {
        return array('mdblog.settings');
    }

    function getFormId() {
        return 'mdblog_settings_form';
    }
}
