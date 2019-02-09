<?php

	// Node preprocessing
	function outfits_preprocess_node(&$variables)
	{

		$node = $variables['node'];
		$type = $node->getType();
		// $lang = $node->language();
		$mode = $variables['view_mode'];
		$_q = \Drupal::request()->request->all();
		$_embed = array();

		if ($node->hasField('field_heading')) {
			$variables['heading'] = $node->get('field_heading')->value;
		} else {
			$variables['heading'] = $node->get('title')->value;
		}

		if ($node->hasField('field_image') && !$node->field_image->isEmpty()) {
			$variables['image'] = image_url($node, 'field_image', 'medium');
		}

		switch ($type) {

			case 'finder':

				$outfits = array();

				$_shorts = new FinderQuery([], array('shorts'));
				$_shorts->shuffle();
				$shorts = $_shorts->results(5);

				$found_shirts = array();

				foreach ($shorts as $short) {

					$the_shirt = array();

					$_shirts = new FinderQuery([], array('shirts'));
					$_shirts->remove($found_shirts);
					$_shirts->shuffle();
					$the_shirt = $_shirts->results(1);

					$found_shirts[] = $the_shirt[0];
					$outfits[] = array(
						'shirt' => $the_shirt[0],
						'short' => $short
					);

				}

				foreach ($outfits as $outfit) {
					$variables['outfits'][] = array(
						'shirt' => load_node($outfit['shirt']['nid'], 'selection'),
						'short' => load_node($outfit['short']['nid'], 'selection')
					);
				}

				break;

			case 'shirts':



				break;

			case 'shorts':

				break;

			default:

				break;

		}

	}
