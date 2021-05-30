<?php

	use Drupal\menu_link_content\Entity\MenuLinkContent;
	use \Drupal\node\Entity\Node;
	use Drupal\core\template\Attribute;
	use Drupal\Core\Render\Markup;

	function nth_preprocess_menu(&$variables)
	{

		build_includes($variables);

		// This config servic can store values to be used across preprocessing, when passing variables between them is
		// difficult or impossible.
		$nthconfig = \Drupal::service('config.factory')->getEditable('barkleyrei.settings');

		$variables['uniqueid'] = uniqid();

		$menu_name = $variables['menu_name'];

		$region = '';
		if (isset($variables["attributes"]["region"])) {
			$region = $variables["attributes"]["region"];
		}

		// This flag determines if any menu in the sidebar was already rendered. This prevents two menus that may contain
		// the current page from both rendering at the same time. We also check if this flag was never set in order to display
		// the current page by itself if no menu was ever rendered.
		$menu_already_rendered = $nthconfig->get('menu_already_rendered');
		if (empty($menu_already_rendered)) {
			$menu_already_rendered = false;
			$nthconfig->set('menu_already_rendered', false);
		}

		// Add classes or attributes to menus
		switch ($menu_name) {

			case "admin":

				// Do nothing

				break;

			default:

				/*
				 * Note for sample theme / remove for real project
				 *
				 * This code assumes a few menus have been created.
				 * - navigation-primary: Primary header navigation, typically with dropdowns.
				 * - navigation-audience: Audience navigation (students, alumni, faculty, etc.) Typically in header.
				 * - navigation-major-ctas: Major Links that typically appear in footer. (Apply Now, Visit, Contact, etc)
				 * - navigation-footer: Footer navigation, for areas across site, like news, events, etc.
				 *
				 * The cases refer to the machine names of the menus. They don't necessarily have to follow this naming convention.				 *
				 *
				 * END NOTE / REMOVE FOR REAL PROJECT
				 */

				// For non-sidebar menus. Classes and attributes for sidebar menus are part of the template.
				$menu_attributes = array();
				$list_attributes = array();
				$child_attributes = array();

				switch ($menu_name) {

					case "navigation-primary":
						$menu_attributes['id'] = 'js-primary-navigation';
						$menu_attributes['class'] = 'primary-nav';
						$list_attributes['class'] = 'primary-nav__parents';
						$child_attributes['class'] = 'primary-nav__children';
						break;

					case "navigation-audience":
						$menu_attributes['class'] = 'secondary-nav';
						$list_attributes['class'] = 'secondary-nav__parents';
						break;

					case "navigation-major-ctas":
						$menu_attributes['class'] = 'major-links';
						$list_attributes['class'] = 'major-links__parents';
						break;

					case "navigation-footer":
						$menu_attributes['class'] = 'footer-links';
						$list_attributes['class'] = 'footer-links__parents';
						break;

					default:

						break;

				}

				$variables['menu_attributes'] = new Attribute($menu_attributes);
				$variables['list_attributes'] = new Attribute($list_attributes);
				$variables['child_list_attributes'] = new Attribute($child_attributes);

				$squash_duplicates = array();

				// Loop through existing menu items. If two or more items are the same node, we will remove the duplicates.
				// External links are not affected.
				foreach ($variables['items'] as $key => &$item) {

					$nid = get_node_id_from_menu_link($item);

					if (!empty($nid)) {

						if (!in_array($nid, $squash_duplicates)) {

							$squash_duplicates[] = $nid;

							parse_menu_item($item, $menu_name, 0);

						} else {

							unset($variables['items'][$key]);

						}

					} else {
						parse_menu_item($item, $menu_name, 0);
					}

				}

				// Processing for sidebar menus
				if ($region === 'sidebar_menu') {

					$final = null;
					$parent = null;
					$current = null;
					$olink = null;
					$children = array();
					$variables['current_title'] = '';

					if ($node = Drupal::request()->attributes->get('node')) {

						if (gettype($node) == 'string') {
							$node = Node::load($node);
						}

						if (!$menu_already_rendered) {

							if (!isset($variables['items']) && !empty($variables['item'])) {
								$items[] = array_merge(array(), $variables['item']);
							} else {
								$items = array_merge(array(), $variables['items']);
							}

							foreach ($items as $key => $__item) {

								// If we find the active trail, look for the current page...
								if ($__item['in_active_trail']) {
									$parent_or_current = find_current_page($__item, $node);
									break;
								}

							}

							// If we found the parent page or current page...
							if (!empty($parent_or_current)) {

								$nthconfig->set('menu_already_rendered', true)->save();

								// and it has children...
								if (!empty($parent_or_current['below'])) {

									// Parent is what we found, current is what we found, store children...
									$parent = $parent_or_current;
									$current = $parent;
									$children = $parent['below'];

								} else {

									// Current page is what we found, find the parent page, store children of parent...
									$current = $parent_or_current;
									$parent = find_parent_page($items, $current);
									$children = $parent['below'];

								}

							} else {

								$variables['current_title'] = $node->get('title')->value;

							}

							$variables['parent'] = $parent;
							$variables['children'] = $children;
							$variables['current'] = $current;

							$variables['current_is_parent'] = false;
							if ($parent == $current) {
								$variables['current_is_parent'] = true;
							}

						}

					}

				} else {
					$nthconfig->set('menu_already_rendered', false)->save();
				}

				break;

		}

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

	/**
	 * parse_menu_item()
	 *
	 * With a given menu link object, add attributes and classes based on the machine name.
	 *
	 * @param $item
	 * @param $menu_name
	 * @param $level
	 */
	function parse_menu_item(&$item, $menu_name, $level)
	{

		switch ($menu_name) {

			case "navigation-primary":

				if ($level > 0) {
					$item['attributes']['class'] = 'primary-nav__child';
					$item['classes'][] = 'primary-nav__child-link';
					$item['title'] = Markup::create(svgc('arrow') . $item['title']);
				} else {
					$item['attributes']['class'] = 'primary-nav__parent';
					$item['classes'][] = 'primary-nav__parent-link';
					$item['trigger'] = Markup::create('<a href="#" class="primary-nav__trigger" tabindex="-1"><span class="show-for-sr text">Click to open child links</span>' . svg('chevron') . '<svg class="brei-icon brei-icon-chevron"><use xlink:href="#brei-icon-chevron"></use></svg></a>');
				}

				break;

			case "navigation-audience":
				$item['attributes']['class'] = 'secondary-nav__parent';
				$item['classes'][] = 'secondary-nav__link';
				break;

			case "navigation-major-ctas":
				$item['attributes']['class'] = 'major-links__parent';
				$item['classes'][] = 'major-links__link';
				$item['title'] = Markup::create('<span class="text">' . $item['title'] . '</span>' . svgc('arrow'));
				break;

			case "navigation-footer":
				$item['attributes']['class'] = 'footer-links__parent';
				$item['classes'][] = 'footer-links__link';
				break;

			default:
				break;

		}

		if (!empty($item['below'])) {
			$item['attributes']['class'] = 'primary-nav__parent primary-nav__parent--has-children';

			foreach ($item['below'] as $key => &$bitem) {
				parse_menu_item($bitem, $menu_name, $level += 1);
			}
		}
	}

	/**
	 * find_current_page()
	 *
	 * Figure out if this item IS the current page
	 * If not, then loop through below to find next page in active trail, pass to this function.
	 * If yes, return this item.
	 *
	 * @param $item
	 * @param $node
	 *
	 * @return bool|mixed
	 */
	function find_current_page($item, $node)
	{

		$parent_or_current = false;

		$ref = get_entity_from_menu_link($item);

		if (!empty($ref) && !empty($ref->id())) {

			if ($node->id() === $ref->id()) {
				$parent_or_current = $item;
			} else {
				if (!empty($item['below'])) {
					foreach ($item['below'] as $key => $child) {

						$parent_checker = find_current_page($child, $node);

						if (!empty($parent_checker)) {
							$parent_or_current = $parent_checker;
							break;
						}

					}
				}
			}

		}

		return $parent_or_current;

	}

	/**
	 * find_parent_page()
	 *
	 * Loop through menu to find the current page and returns the parent that contains it.
	 *
	 * @param $items
	 * @param $current
	 *
	 * @return mixed
	 */
	function find_parent_page($items, $current)
	{

		foreach ($items as $key => $item) {

			if ($item['in_active_trail']) {

				if (!empty($item['below'])) {

					if (contains_current_page($item['below'], $current)) {
						return $item;
						break;
					} else {
						return find_parent_page($item['below'], $current);
						break;
					}

				}

			}

		}

		return $current;

	}

	/**
	 * contains_current_page()
	 *
	 * Figure out if a list of menu items contains the referenced current page.
	 *
	 * @param $items
	 * @param $current
	 *
	 * @return bool
	 */
	function contains_current_page($items, $current)
	{

		if (!empty($current['url'])) {

			$c_mid = false;
			$c_olink = $current['original_link'];
			$c_mid = $c_olink->getPluginId();

			foreach ($items as $key => $item) {

				$i_mid = false;
				$i_olink = $item['original_link'];
				$i_mid = $i_olink->getPluginId();

				if ($i_mid == $c_mid) {
					return true;
				}

			}

		}

		return false;

	}

	/**
	 * get_entity_from_menu_link()
	 *
	 * Get a referenced entity (if it exists) from a menu link.
	 *
	 * @param $link
	 *
	 * @return |null
	 */
	function get_entity_from_menu_link($link)
	{

		$entity = null;

		$nid = get_node_id_from_menu_link($link);

		if (!empty($nid)) {

			$entity = Node::load($nid);

		}

		return $entity;

	}

	function get_node_id_from_menu_link($link) {

		$nid = null;

		$url = $link['url'];

		if (!$url->isExternal()) {

			$route = $url->getRouteParameters();

			if (!empty($route)) {

				$nid = $route['node'];

			}

		}

		return $nid;

	}

	/**
	 * get_menu_link_from_uuid()
	 *
	 * Get a menu link object from a unique link ID.
	 *
	 * @param $uuid
	 *
	 * @return mixed
	 */
	function get_menu_link_from_uuid($uuid)
	{

		$uuid = str_replace('menu_link_content:', '', $uuid);

		$menulink = \Drupal::service('entity.repository')->loadEntityByUuid('menu_link_content', $uuid);

		return $menulink;

	}

	/**
	 * get_menu_tree_from_mlink()
	 *
	 * Get an entire menu tree from a single menu link. Typically used to get all the children of a menu link.
	 *
	 * @param        $link
	 * @param string $menu_name
	 *
	 * @return array|mixed
	 */
	function get_menu_tree_from_mlink($link, $menu_name = 'page-navigation')
	{

		$root_menu_item = array();

		$route_params = $link->link->getRouteParameters();

		$menu_link_service = \Drupal::getContainer()->get('plugin.manager.menu.link');

		$menu_links = $menu_link_service->loadLinksByRoute('entity.node.canonical', $route_params, $menu_name);
		if (!empty($menu_links)) {
			$root_menu_item = reset($menu_links);
		}

		return $root_menu_item;

	}
