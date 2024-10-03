<?php

	use Drupal\Core\Url;
	use SpotifyWebAPI\SpotifyWebAPI;

	function _spotify_playing_get_api_object() {

		$config = \Drupal::configFactory()
			->getEditable('spotify_playing.settings');

		$client_id = $config->get('client_id');
		$client_secret = $config->get('client_secret');

		$return = [
			'api' => null,
			'session' => null,
		];

		if (!empty($client_id) && !empty($client_secret)) {

			$access_token = $config->get('access_token');
			$refresh_token = $config->get('refresh_token');

			$session = new \SpotifyWebAPI\Session(
				$client_id,
				$client_secret
			);

			if (!empty($access_token)) {
				$session->setAccessToken($access_token);
				$session->setRefreshToken($refresh_token);
			}
			else {
				$session->refreshAccessToken($refresh_token);
			}

			$options = [
				'auto_refresh' => TRUE,
			];

			$api = new \SpotifyWebAPI\SpotifyWebAPI($options, $session);

			$api->setSession($session);
			$return['api'] = $api;
			$return['session'] = $session;

			$accessToken = $session->getAccessToken();
			$refreshToken = $session->getRefreshToken();

			$config
				->set('access_token', $accessToken)
				->set('refresh_token', $refreshToken)
				->save();


		}

		return $return;

	}