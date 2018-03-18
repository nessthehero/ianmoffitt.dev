<?php

	// Html preprocessing
	function nth_preprocess_html(&$variables) {

		$node = Drupal::request()->attributes->get('node');
		$type = '';

		$variables['partial_svg'] = contents('svg');
		$variables['partial_access'] = contents('accessnav');

		$variables['#attached']['library'][] = 'nth/grid';

		if (!empty($node)) {

			$type = $node->getType();

			switch ($type) {

				case 'home':

					break;

				default:

					break;

			}

		}

	}
