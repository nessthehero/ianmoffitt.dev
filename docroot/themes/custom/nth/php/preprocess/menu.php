<?php

	use Drupal\menu_link_content\Entity\MenuLinkContent;
	use \Drupal\node\Entity\Node;
	use Drupal\core\template\Attribute;
	use Drupal\Core\Render\Markup;
  use Nth\Utils;
  use Nth\Helpers;

	function nth_preprocess_menu(&$variables)
	{

    Utils\Shared::icons($variables);

    $variables['uniqueid'] = uniqid();

    $menu_name = $variables['menu_name'];

    $region = '';
    if (isset($variables["attributes"]["region"])) {
      $region = $variables["attributes"]["region"];
    }

    // Get full menu tree
    $variables['menu_array'] = Helpers\Menu::getMenuArray($menu_name);

	}


	/**
	 * theme_suggestions_menu_alter()
	 *
	 * add a template suggestion based on region name
	 * http://kristiankaa.dk/article/drupal8-region-specific-menu-theme-hook-suggestion
	 *
	 * @param array $suggestions
	 * @param array $variables
	 */
	function nth_theme_suggestions_menu_alter(array &$suggestions, array $variables) {
		if (isset($variables["attributes"]["region"])) {
			$suggestions[] = $variables["theme_hook_original"] . "__" . $variables["attributes"]["region"];
		}
	}
  
