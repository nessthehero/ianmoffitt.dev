<?php

	use Drupal\node\Entity\Node;

	// Region preprocessing
	function nth_preprocess_region(&$variables) {

		$nthconfig = \Drupal::service('config.factory')->getEditable('barkleyrei.settings');

		$node = Drupal::request()->attributes->get('node');
		$revision =  Drupal::request()->attributes->get('node_revision');
		$preview = Drupal::request()->attributes->get('node_preview');

		if (empty($node)) {
			if (!empty($preview)) {
				$node = $preview;
			}
		}

		$type = '';

		$region = $variables['region'];

		$variables['theme_path'] = base_path() . $variables['directory'];

		// Do anything that requires a node in this block. Internal drupal pages, like the user page, are NOT nodes,
		// so any node tasks will throw errors as the $node variable will be null.
		if (!empty($node)) {

			if (gettype($node) == 'string') {
				if ($revision && $revision != '') {
					$node = Drupal::entityTypeManager()->getStorage('node')->loadRevision($revision);
				} else {
					$node = Node::load($node);
				}
			}

			$type = $node->getType();

		}

		switch ($region) {
			case 'header':

				$variables['search'] = theme_get_setting('search');

				break;

			case 'sidebar_menu':

				if (!empty($node)) {

					if (gettype($node) == 'string') {
						if ($revision && $revision != '') {
							$node = Drupal::entityTypeManager()->getStorage('node')->loadRevision($revision);
						} else {
							$node = Node::load($node);
						}
					}

					$variables['current_title'] = $node->get('title')->value;

				}

				$menu_already_rendered = $nthconfig->get('menu_already_rendered');
				if (empty($menu_already_rendered)) {
					$menu_already_rendered = false;
				}

				$variables['rendered_a_menu'] = $menu_already_rendered;

				break;

			case 'footer':

				break;

			default:
				# code...
				break;
		}


	}
