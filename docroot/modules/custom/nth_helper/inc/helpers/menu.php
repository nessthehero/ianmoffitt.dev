<?php

	namespace Nth\Helpers;

	class Menu {

		/***
		 * Returns an array representation of a menu tree.
		 *
		 * @param $menu (string)
		 *
		 * @return array.
		 */
		public static function getMenuArray($menu)
		{

			$output = array();

			$sub_nav = \Drupal::menuTree()->load($menu, new \Drupal\Core\Menu\MenuTreeParameters());
			$manipulators = array(
				array('callable' => 'menu.default_tree_manipulators:checkAccess'),
				array('callable' => 'menu.default_tree_manipulators:generateIndexAndSort'),
			);
			$sub_nav = \Drupal::menuTree()->transform($sub_nav, $manipulators);

			return self::subtree($output, $sub_nav);

		}

		/**
		 * Implements self::subtree().
		 *
		 * @param $output (string)
		 * @param $input  (array) Menu array
		 * @param $parent (array) Parent menu
		 *
		 * @return array.
		 */
		private static function subtree(&$output, &$input, $parent = false)
		{
			$input = array_values($input);
			foreach ($input as $key => $item) {
				//If menu element disabled skip this branch
				if ($item->link->isEnabled()) {
					$name = $item->link->getTitle();
					$url = $item->link->getUrlObject();
					$url_string = $url->toString();

					//If not root element, add as child
					if ($parent === false) {
						$output[$key] = [
							'name'    => $name,
							'tid'     => $key,
							'url_str' => $url_string
						];
					} else {
						$parent = 'submenu-' . $parent;
						$output['child'][$key] = [
							'name'    => $name,
							'tid'     => $key,
							'url_str' => $url_string
						];
					}

					if ($item->hasChildren) {
						if ($item->depth == 1) {
							self::subtree($output[$key], $item->subtree, $key);
						} else {
							self::subtree($output['child'][$key], $item->subtree, $key);
						}
					}
				}
			}

			return $output;
		}

	}
