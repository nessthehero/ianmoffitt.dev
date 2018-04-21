<?php

	function nth_preprocess(&$variables, $hook) {

		build_includes($variables);

	}

	function nth_preprocess_block(&$variables)
	{

		$block = $variables['base_plugin_id'];

		// echo '';

		switch ($block) {

			case 'system_branding_block':
			case 'svglogo':

				$variables['attributes']['class'][] = 'logo';

				break;

			default:
				break;

		}

	}
