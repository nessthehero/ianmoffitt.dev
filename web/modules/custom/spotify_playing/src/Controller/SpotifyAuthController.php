<?php

	namespace Drupal\spotify_playing\Controller;

	require_once __DIR__ . '/../../lib/functions.php';

	use Drupal\Core\Controller\ControllerBase;
	use Drupal\Core\Cache\CacheableJsonResponse;
	use Drupal\Core\Cache\CacheableMetadata;
	use Drupal\Core\Url;
	use SpotifyWebAPI\SpotifyWebAPI;

	class SpotifyAuthController extends ControllerBase {

		const SETTINGS = 'spotify_playing.settings';

		public function content() {

			$config = \Drupal::configFactory()->getEditable(static::SETTINGS);

			$client_id = $config->get('client_id');
			$client_secret = $config->get('client_secret');

			$api_object = _spotify_playing_get_api_object();

			$dump = [
				'client_id' => $client_id,
				'client_secret' => $client_secret,
				'access_token' => $api_object['session']->getAccessToken(),
				'refresh_token' => $api_object['session']->getRefreshToken(),
			];

			$response = new CacheableJsonResponse($dump);

			$response->addCacheableDependency(CacheableMetadata::createFromRenderArray($dump));

			return $response;

		}

		public function authorize() {

			$current_request = \Drupal::request();

			$config = \Drupal::configFactory()->getEditable(static::SETTINGS);

			$client_id = $config->get('client_id');
			$client_secret = $config->get('client_secret');

			$form_uri = Url::fromRoute('spotify_playing.settings')->toString();
			$redirect_uri = Url::fromRoute('spotify_playing.authorization', [], [
				'absolute' => TRUE,
			])->toString();

			if (!empty($client_id) && !empty($client_secret)) {
				$code = $current_request->query->get('code');

				$session = new \SpotifyWebAPI\Session(
					$client_id,
					$client_secret,
					$redirect_uri
				);

				$api = new \SpotifyWebAPI\SpotifyWebAPI([
					'auto_refresh' => TRUE
				]);

				if (!empty($code)) {
					try {
						$session->requestAccessToken($_GET['code']);
						$api->setAccessToken($session->getAccessToken());

						$accessToken = $session->getAccessToken();
						$refreshToken = $session->getRefreshToken();

						$config
							->set('access_token', $accessToken)
							->set('refresh_token', $refreshToken)
							->save();

						header('Location: ' . $redirect_uri);
					} catch (Exception $e) {
						echo 'Spotify API Error: ' . $e->getCode();
						die();
					}
				}

				$accessToken = $config->get('access_token');

				if (empty($accessToken)) {
					$config
						->set('access_token', '')
						->set('refresh_token', '')
						->save();

					$authorizeUrlOptions = [
						'scope' => [
							'user-read-currently-playing',
							'user-read-recently-played',
							'user-read-playback-state',
							'user-modify-playback-state'
						],
					];

					header('Location: ' . $session->getAuthorizeUrl($authorizeUrlOptions));
					die();
				}

			}

			header('Location: ' . $form_uri);
			die();

		}

	}