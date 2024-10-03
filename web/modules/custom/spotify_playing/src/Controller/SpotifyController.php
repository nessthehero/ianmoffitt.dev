<?php

	namespace Drupal\spotify_playing\Controller;

	require_once __DIR__ . '/../../lib/functions.php';

	use Drupal\Core\Cache\CacheableJsonResponse;
	use Drupal\Core\Cache\CacheableMetadata;
	use Drupal\Core\Controller\ControllerBase;

	class SpotifyController extends ControllerBase {

		const SETTINGS = 'spotify_playing.settings';

		public function content() {

			$api = _spotify_playing_get_api_object()['api'];

			$data = [];

			if ($api) {
				$data['now'] = $api->getMyCurrentTrack() ?? [];
			}

			$data['#cache'] = [
				'max-age' => (1 * 5),
				'tags' => [
					'spotify_playing',
					'spotify_playing:now'
				],
				'contexts' => [
					'url.path',
					'url.query_args',
				],
			];

			$response = new CacheableJsonResponse($data);

			$response->addCacheableDependency(CacheableMetadata::createFromRenderArray($data));

			return $response;

		}

	}