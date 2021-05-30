<?php

	use Drupal\taxonomy\Entity\Term;

	function nth_form_alter(&$form, &$form_state, $form_id)
	{

		// Simple detect for webforms
		$webform = false;
		if (strpos($form_id, 'webform') !== false) {
			$webform = true;
		}

		$is_ajax = 0;

		if (!empty($form['#node']->webform['webform_ajax'])) {
			$is_ajax = $form['#node']->webform['webform_ajax'];
		}

		if ($webform) {

			$uniqid = uniqid();

			$node = Drupal::request()->attributes->get('node');

			if (!empty($node)) {

				$elements = $form['elements'];

				foreach ($elements as $key => $element) {

					if (!empty($element['#webform_id'])) {
						$form['elements'][$key]['#attributes']['id'] = 'edit-' . $form['elements'][$key]['#webform_id'] . '-' . $uniqid;
						$form['elements'][$key]['#id'] = 'edit-' . $form['elements'][$key]['#webform_id'] . '-' . $uniqid;
						$form['elements'][$key]['#attributes']['data-drupal-selector'] = 'edit-' . $form['elements'][$key]['#webform_id'] . '-' . $uniqid;
					}

				}

			}

		}

	}
