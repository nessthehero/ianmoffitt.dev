<?php

	// Region preprocessing
	function outfits_preprocess_region(&$variables)
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
				$variables['attributes']['id'] = 'global-header';

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
