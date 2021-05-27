<?php

	use Drupal\core\Url;
	use Drupal\node\Entity\Node;
	use Drupal\taxonomy\Entity\Term;

	// Page preprocessing
	function ian_preprocess_page(&$variables)
	{

		$ianconfig = \Drupal::service('config.factory')->getEditable('barkleyrei.settings');
		$_q = \Drupal::request()->query->all();

		$route_provider = \Drupal::service('router.route_provider');

		$attributes = Drupal::request()->attributes;

		$node = Drupal::request()->attributes->get('node');
		$revision =  Drupal::request()->attributes->get('node_revision');
		$preview = Drupal::request()->attributes->get('node_preview');

		if (empty($node)) {
			if (!empty($preview)) {
				$node = $preview;
			}
		}

		$type = '';

		if (!empty($node)) {

			if (gettype($node) == 'string') {
				if ($revision && $revision != '') {
					$node = Drupal::entityTypeManager()->getStorage('node')->loadRevision($revision);
				} else {
					$node = Node::load($node);
				}
			}

			$variables['nid'] = $node->get('nid')->value;

			$alias = \Drupal::service('path_alias.manager')->getAliasByPath('/node/' . $variables['nid']);

			$type = $node->getType();

			// Place common fields here. See README for examples on parsing certain types of fields
			$variables['the_title'] = $node->get('title')->value;

			if ($node->hasField('field_heading')) {
				$variables['heading'] = $node->get('field_heading')->value;
			} else {
				$variables['heading'] = $node->get('title')->value;
			}

			build_masthead($node, $variables);
			switch ($type) {

				case 'home':



					break;

				default:

					break;

			}

		}

	}
