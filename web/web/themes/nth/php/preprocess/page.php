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

					$promoted = new FinderQuery(array(), array());
					$promoted->getOnlyPromoted();

					$promoted_results = $promoted->results(-1);

					$items = array();
					foreach ($promoted_results as $key => $n) {
						$items[] = collapse(load_node($n['nid'], 'result'));
					}

					$variables['results'] = $items;

					break;

				case 'work':

					if (!$node->field_project_dates->isEmpty()) {

						$variables['project_start'] = $node->get('field_project_dates')->value;
						$variables['project_end'] = $node->get('field_project_dates')->end_value;

					}

					$variables['is_active'] = $node->get('field_project_active')->value;

					$variables['summary'] = $node->get('field_summary')->value;
					$variables['teaser'] = $node->get('field_teaser')->value;
					$variables['skills'] = $node->get('field_skills');
					$variables['cms'] = $node->get('field_cms');

					$variables['url'] = ra(l($node->get('field_url')->first(), '', [
						'class' => [],
					]));

					if (!$node->field_components->isEmpty()) {
						$variables['components'] = load_paragraphs($node->field_components);
					}

					$variables['thumbnail'] = image_url($node, 'field_thumbnail', 'result');
					$variables['masthead'] = image_url($node, 'field_thumbnail', 'masthead');
					$variables['alt'] = $node->field_thumbnail->alt;

					break;

				default:

					break;

			}

		}

	}
