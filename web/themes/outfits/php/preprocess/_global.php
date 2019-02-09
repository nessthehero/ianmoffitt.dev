<?php

	function outfits_preprocess(&$variables, $hook) {

		build_includes($variables);

	}

	function outfits_preprocess_block(&$variables)
	{

		$block = $variables['base_plugin_id'];

		switch ($block) {

			case 'system_branding_block':
			case 'svglogo':

				$variables['attributes']['class'][] = 'logo';

				break;

			default:
				break;

		}

	}

	function outfits_theme_suggestions_alter(&$suggestions, $variables, $hook)
	{

		$_q = \Drupal::request()->query->all();

		if ($hook == "html") {

			$node = Drupal::request()->attributes->get('node');

			if (!empty($node)) {

				$type = $node->getType();

				switch ($type) {

					default:

						break;

				}

			}

		}

		if ($hook == "page") {

			$node = Drupal::request()->attributes->get('node');

			if (!empty($node)) {

				$type = $node->getType();

				switch ($type) {

					default:

						break;

				}

			}

		}

	}
