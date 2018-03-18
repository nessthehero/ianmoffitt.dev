<?php

function nth_form_system_theme_settings_alter(&$form, \Drupal\Core\Form\FormStateInterface &$form_state, $form_id = NULL) {

	if (isset($form_id)) {
		return;
	}

	$form['nth_settings'] = array(
		'#type' => 'fieldset',
		'#title' => t('Site settings')
	);

	$form['nth_settings']['twitter'] = array(
		'#type' => 'textfield',
		'#title' => t('Twitter profile URL'),
		'#default_value' => theme_get_setting('twitter'),
		'#description' => t('Leave blank to disable')
	);

}
