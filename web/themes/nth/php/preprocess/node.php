<?php

	// Node preprocessing
	function nth_preprocess_node(&$variables)
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

		if ($node->hasField('field_thumbnail') && !$node->field_thumbnail->isEmpty()) {
			$variables['thumbnail'] = image_url($node, 'field_thumbnail', 'result');
		}

		if ($node->hasField('field_components') && !$node->field_components->isEmpty()) {
			$variables['components'] = load_paragraphs($node->field_components);
		}

		switch ($type) {

			case 'home':

				break;

			case 'blurb':

				$variables['icon'] = 'pencil';
				$variables['type'] = 'Short Blurb or Post';

				break;

			case 'codepen':

				$variables['icon'] = 'codepen';
				$variables['type'] = 'CodePen';

				$url = $node->get('field_url');

				if (!empty($url->uri)) {
					$api_url = 'http://codepen.io/api/oembed?url=' . $url->uri . '&format=json&height=600';
					$codepen = json_decode(file_get_contents($api_url));
				}

				if (!empty($codepen)) {
					$variables['codepen'] = $codepen;
				}

				if (!$node->field_components->isEmpty()) {
					$variables['components'] = load_paragraphs($node->field_components);
				}

				break;

			case 'work':

				$variables['icon'] = 'briefcase';
				$variables['type'] = 'Work Project';
				$variables['masthead'] = image_url($node, 'field_thumbnail', 'masthead');

				if (!$node->field_components->isEmpty()) {
					$variables['components'] = load_paragraphs($node->field_components);
				}

				$variables['skills'] = getTagsArray($node->get('field_skills'));

				break;

			case 'teammate':

				$variables['website'] = '';

				if (!$node->field_url->isEmpty()) {
					$website = $node->get('field_url')->first();

					$variables['website_url'] = l_url($website);
					$variables['website_title'] = $variables['heading'];

					$variables['website'] = ra(lm(
						$variables['website_title'],
						$variables['website_url'],
						'',
						array(
							'target' => '_blank'
						)));
				}

				break;

			default:

				break;

		}

	}
