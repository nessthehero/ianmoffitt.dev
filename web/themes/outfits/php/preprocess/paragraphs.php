<?php

	// Html preprocessing
	function outfits_preprocess_paragraph(&$variables) {

		$paragraph = $variables['paragraph'];

		if (!empty($variables['view_mode'])) {
			$mode = $variables['view_mode'];
		} else {
			$mode = 'full';
		}

		if (!empty($paragraph)) {

			$type = $paragraph->type->getValue()[0]['target_id'];

			switch ($type) {

				default:

					break;

			}

		}

	}
