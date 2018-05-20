<?php

	// Region preprocessing
	function nth_preprocess_region(&$variables)
	{

		$node = Drupal::request()->attributes->get('node');
		$type = '';

		$region = $variables['region'];

		$variables['theme_path'] = base_path() . $variables['directory'];

		if (!empty($node)) {

			$type = $node->getType();

		}

		switch ($region) {
			case 'header':

				$variables['attributes']['class'][] = 'global-header';
				$variables['attributes']['class'][] = 'off-canvas';
				$variables['attributes']['class'][] = 'position-left';
				$variables['attributes']['class'][] = 'reveal-for-large';

				$variables['attributes']['id'] = 'global-header';

				$variables['attributes']['data-off-canvas'] = '1';
				$variables['attributes']['data-position'] = 'left';

				break;

			case 'footer':

				$variables['attributes']['class'][] = 'global-footer';

				$variables['attributes']['id'] = 'global-footer';

				break;

			default:
				# code...
				break;
		}

	}
