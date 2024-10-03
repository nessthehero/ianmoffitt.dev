<?php

	namespace Drupal\spotify_playing\Form;

	require_once __DIR__ . '/../../lib/functions.php';

	use Drupal\Core\Form\ConfigFormBase;
	use Drupal\Core\Form\FormStateInterface;
	use Drupal\Core\Url;

	class SpotifyConfigForm extends ConfigFormBase {

		const SETTINGS = 'spotify_playing.settings';

		public function getFormId() {
			return 'spotify_playing_config_form';
		}

		protected function getEditableConfigNames() {
			return [
				self::SETTINGS,
			];
		}

		public function buildForm(array $form, FormStateInterface $form_state) {

			$config = $this->config(static::SETTINGS);

			$form['client_id'] = [
				'#type' => 'textfield',
				'#title' => $this->t('Client ID'),
				'#required' => TRUE,
				'#default_value' => $config->get('client_id'),
				'#description' => $this->t('Enter the Client ID of the Spotify Playing API client.'),
			];

			$form['client_secret'] = [
				'#type' => 'textfield',
				'#title' => $this->t('Client Secret'),
				'#required' => TRUE,
				'#default_value' => $config->get('client_secret'),
			];

			$redirect_url = Url::fromRoute('spotify_playing.authorization', [], [
				'absolute' => TRUE,
			])->toString();

			$form['redirect_url'] = [
				'#type' => 'html_tag',
				'#tag' => 'p',
				'#value' => '<strong>Redirect URI:</strong> <a href="' . $redirect_url . '">' . $redirect_url . '</a>',
			];

			return parent::buildForm($form, $form_state);

		}

		public function submitForm(array &$form, FormStateInterface $form_state) {
			$config = $this->config(static::SETTINGS);

			$config->set('client_id', $form_state->getValue('client_id'));
			$config->set('client_secret', $form_state->getValue('client_secret'));

			$config->save();

			parent::submitForm($form, $form_state);
		}

	}