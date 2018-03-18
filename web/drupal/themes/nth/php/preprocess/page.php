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

					break;

				default:

					break;

			}

		}

	}
