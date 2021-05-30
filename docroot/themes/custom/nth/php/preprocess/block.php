<?php
	/**
	 * Created by PhpStorm.
	 * User: imoffitt
	 * Date: 2018-12-03
	 * Time: 13:34
	 */

	function nth_preprocess_block(&$variables)
	{

		// Add a region variable to a block.
		// http://kristiankaa.dk/article/drupal8-region-specific-menu-theme-hook-suggestion
		if (isset($variables["elements"]["#id"])) {
			$block_id = $variables["elements"]["#id"];
			$block = \Drupal\block\Entity\Block::load($block_id);

			if ($block) {
				$variables["content"]["#attributes"]["region"] = $block->getRegion();
			}
		}

		$content = $variables["content"];

	}

	function nth_theme_suggestions_block_alter(&$suggestions, $variables) {

		if (isset($variables["elements"]["#id"])) {
			$block_id = $variables["elements"]["#id"];
			$block = \Drupal\block\Entity\Block::load($block_id);
			$region = $block->getRegion();

			$suggestions[] = 'block__' . $region;
			$suggestions[] = 'block__' . $region . '__' . $variables['elements']['#base_plugin_id'];
			$suggestions[] = 'block__' . $region . '__' . $variables['elements']['#id'];
			$suggestions[] = 'block__' . $region . '__' . $variables['elements']['#base_plugin_id'] . '__' . $variables['elements']['#id'];
		}

	}
