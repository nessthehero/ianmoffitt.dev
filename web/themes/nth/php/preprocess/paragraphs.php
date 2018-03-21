<?php

	// Html preprocessing
	function nth_preprocess_paragraph(&$variables) {

		$paragraph = $variables['paragraph'];

		if (!empty($paragraph)) {

			$type = $paragraph->type->getValue()[0]['target_id'];

			switch ($type) {

				default:

					break;

			}

		}

	}
