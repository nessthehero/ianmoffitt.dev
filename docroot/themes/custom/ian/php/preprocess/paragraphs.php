<?php

	use Drupal\media\Entity\Media;
	use Drupal\file\Entity\File;

	// Paragraph component preprocessing
	function ian_preprocess_paragraph(&$variables)
	{

		// Custom configuration value helper, useful for temporarily storing data across different preprocessors.
		//
		// $ianconfig->set('variable', value)->save();
		//
		// $ianconfig->get('variable');
		//
		$ianconfig = \Drupal::service('config.factory')->getEditable('barkleyrei.settings');

		// Store query strings in $_q
		$_q = \Drupal::request()->query->all();

		// Get the paragraph entity, the parent node that owns it, and the content type of that node.
		$paragraph = $variables['paragraph'];
		$parent = $paragraph->getParentEntity();

		if (!empty($parent)) {
			$nodeType = $parent->getType();
		} else {
			$nodeType = '';
		}

		// Get the unique id of the paragraph entity
		$pid = $paragraph->get('id')->value;
		$variables['pid'] = $pid;

		// The view mode can control how this paragraph renders. Changing this value
		// won't affect that, but you can use this to influence your preprocessing.
		if (!empty($variables['view_mode'])) {
			$mode = $variables['view_mode'];
		} else {
			$mode = 'full';
		}

		$data_mode = false;
		if (!empty($_q['data'])) {
			$data_mode = true;
		}

		$variables['data_mode'] = '0';
		if ($data_mode) {
			$variables['data_mode'] = '1';
		}

		// Used for static assets in theme
		$variables['theme_path'] = base_path() . $variables['directory'];

		if (!empty($paragraph)) {

			$type = $paragraph->type->getValue()[0]['target_id'];

			switch ($type) {

				case 'wysiwyg':

					$variables['heading'] = $paragraph->get('field_heading')->value;
					$variables['copy'] = $paragraph->get('field_copy')->value;

					$variables['cta'] = '';

					if (!$paragraph->field_cta->isEmpty()) {
						$cta = $paragraph->get('field_cta')->first();

						$variables['cta_url'] = l_url($cta);
						$variables['cta_title'] = $cta->title;

						$variables['cta'] = ra(lm(
							$variables['cta_title'] . svg('arrow'),
							$variables['cta_url'],
							'',
							array(
								'class' => array(
									'arrow-link'
								)
							)));
					}

					break;

				default:

					break;

			}

		}

	}
