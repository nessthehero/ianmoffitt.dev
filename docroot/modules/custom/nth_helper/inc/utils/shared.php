<?php

	namespace Nth\Utils;

	use Nth\Helpers;
	use SAML2\Utils;
	use Nth\Helpers\Images;
	use Drupal\media\Entity\Media;

	// Manage variable setting for complex page features here, instead of cluttering up
	// node.php or page.php. This is also useful for repeating variable setting that is common
	// across nodes and pages.

	class Shared {

		/**
		 * @param $variables
		 */
		public static function icons(&$variables) {
			// Create a variable list of SVG icons for use in Twig Templates.

			$icons = array();

			$themeHandler = \Drupal::service('theme_handler');
			$themePath =  DRUPAL_ROOT . '/' . $themeHandler->getTheme($themeHandler->getDefault())->getPath();

			// Pulls from _files/selection.json
			//
			// This is the icomoon provided file. This file should be copied into the
			// _files directory any time it changes.
			$icon_json = @json_decode(file_get_contents($themePath . '/_files/selection.json'));

			if (!empty($icon_json->icons)) {
				foreach	($icon_json->icons as $key => $icon) {
					$icons[] = $icon->properties->name;
				}
			}

			foreach ($icons as $key => $icon) {
				$variables['_svg'][$icon] = Markup::svgr($icon);
			}
		}

		public static function defaults(&$variables) {


		}

	}

