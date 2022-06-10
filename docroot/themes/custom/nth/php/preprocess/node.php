<?php

	use Drupal\node\Entity\Node;
	use Drupal\taxonomy\Entity\Term;
  use Nth\Helpers\Nodes;
  use Nth\Helpers\Paragraphs;

// Node preprocessing
	function nth_preprocess_node(&$variables)
	{

		// Reference to node object
		$node = $variables['node'];

		// Reference to node ID
		$nid = $node->get('nid')->value;

		// Content Type
		$type = $node->getType();

		// View Mode - Useful for controlling what template renders this node.
		$mode = $variables['view_mode'];

		// Store query strings in $_q
		$_q = \Drupal::request()->query->all();

		// Used if we are retrieving bare data from a node instead of displaying a page
		$data_mode = false;
		if (!empty($_q['data'])) {
			$data_mode = true;
		}
		$variables['data_mode'] = $data_mode;

		// Used for static assets in theme
		$variables['theme_path'] = base_path() . $variables['directory'];

		// Place common fields here. See README for examples on parsing certain types of fields

		$variables['nid'] = $nid;

    $variables['is_current_node'] = Nodes::is_current_node($nid);

		$alias = \Drupal::service('path_alias.manager')->getAliasByPath('/node/' . $variables['nid']);

		$variables['title'] = $node->get('title')->value;

		if ($node->hasField('field_heading')) {
			$variables['heading'] = $node->get('field_heading')->value;
		} else {
			$variables['heading'] = $node->get('title')->value;
		}

		if ($node->hasField('field_teaser') && !$node->field_teaser->isEmpty()) {
			$variables['teaser'] = $node->get('field_teaser')->value;
		}
		if ($node->hasField('field_components') && !$node->field_components->isEmpty()) {
      $variables['components'] = Paragraphs::load_paragraphs($node->field_components);
		}

		if ($node->hasField('field_introduction') && !$node->field_introduction->isEmpty()) {
			$variables['introduction'] = $node->get('field_introduction')->value;
		}

		$variables['display_heading'] = $variables['heading'];
		if ($node->hasField('field_display_heading') && !$node->field_display_heading->isEmpty()) {
			$variables['display_heading'] = $node->get('field_display_heading')->value;
		}

		switch ($type) {

			default:

				break;

		}

	}
