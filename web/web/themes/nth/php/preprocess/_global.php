<?php

	function nth_preprocess(&$variables, $hook) {

		build_includes($variables);

	}

	function nth_preprocess_block(&$variables)
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

	function nth_theme_suggestions_alter(&$suggestions, $variables, $hook)
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

					case 'work':

						$suggestions[] = "page__work";

						break;

					default:

						break;

				}

			}

		}

	}
