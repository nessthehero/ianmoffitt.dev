<?php

	namespace Drupal\nth_helper;

	use Twig\Extension\AbstractExtension;
	use Twig\TwigFilter;
	use Twig\TwigFunction;

	class MenuTree extends AbstractExtension
	{

		public function getFunctions()
		{
			return [
				new TwigFunction('get_menu_tree', [$this, 'getMenuTree']),
			];
		}

		public function getMenuTree($items)
		{

			$return = [];

			foreach ($items as $item) {

				if ($item['in_active_trail']) {

					$return = self::getParentPage($item);

				}

			}

			return $return;

		}

		private function getParentPage($item)
		{

			$parent = $item;
			$children = $item['below'];

			$menu = array(
				'parent' => $parent,
				'children' => $children
			);

			// The item has children
			if ($item['below']) {

				foreach ($children as $child) {

					// One of the children is in the active trail...
					if ($child['in_active_trail']) {

						// And it has children, so we make it the parent.
						if (count($child['below']) > 0) {

							$menu = self::getParentPage($child);

						}

					}

					// If no children in the active trail, we're fine with the structure we have.

				}

			}

			return $menu;

		}

	}
