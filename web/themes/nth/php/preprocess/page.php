<?php

	use \Drupal\paragraphs\Entity\Paragraph;

	// Page preprocessing
	function nth_preprocess_page(&$variables) {

		$node = Drupal::request()->attributes->get('node');
		$type = '';

		if (!empty($node)) {

			// print_r($node->toArray());

			$type = $node->getType();

			switch ($type) {

				case 'home':

					$promoted = new FinderQuery(array());

					$promoted_results = $promoted->results(-1);

					$items = array();
					foreach ($promoted_results as $key => $n) {
						$items[] = collapse(load_node($n['nid'], 'result'));
					}

					$variables['results'] = $items;

					echo '';

					break;

				default:

					break;

			}

		}

	}
