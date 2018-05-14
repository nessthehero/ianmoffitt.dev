<?php

	// Html preprocessing
	function nth_preprocess_paragraph(&$variables) {

		$paragraph = $variables['paragraph'];

		if (!empty($variables['view_mode'])) {
			$mode = $variables['view_mode'];
		} else {
			$mode = 'full';
		}

		if (!empty($paragraph)) {

			$type = $paragraph->type->getValue()[0]['target_id'];

			switch ($type) {

				case 'accordion' :

					$variables['heading'] = $paragraph->get('field_accordion_heading')->value;
					$variables['description'] = $paragraph->get('field_description')->value;
					$accordions = $paragraph->field_accordion_items;
					$variables['items'] = load_paragraphs($accordions);

					break;

				case 'accordion_item' :

					if (!$paragraph->field_accordion_item_content->isEmpty()) {
						$variables['content'] = $paragraph->get('field_accordion_item_content')->value;
					}

					if (!$paragraph->field_accordion_item_heading->isEmpty()) {
						$variables['heading'] = $paragraph->get('field_accordion_item_heading')->value;
					}
					break;

				case 'wysiwyg':

					$variables['heading'] = $paragraph->get('field_heading')->value;
					$variables['copy'] = $paragraph->get('field_copy')->value;

					break;

				default:

					break;

			}

		}

	}
