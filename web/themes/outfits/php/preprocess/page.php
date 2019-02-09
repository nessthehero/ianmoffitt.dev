<?php

	use \Drupal\paragraphs\Entity\Paragraph;

	// Page preprocessing
	function outfits_preprocess_page(&$variables) {

		$node = Drupal::request()->attributes->get('node');
		$type = '';

		if (!empty($node)) {

			$type = $node->getType();

			switch ($type) {

				default:

					break;

			}

		}

	}
