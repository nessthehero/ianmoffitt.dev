<?php

// next project, rename this file to masthead.php

function feature_masthead($node, &$variables) {

	$variables['masthead'] = array();

	$masthead = nv($node, 'field_masthead');
	if (!empty($masthead['image'])) {
		$masthead = array($masthead);
	}

	foreach ($masthead as $key => $mast) {

		if (!empty($mast['image']['o']['uri'])) {

			$l = '';
			if (!empty($mast['link'])) {
				$l = l(
					svg('chevron-circle-right') . $mast['link'][0]['o']['title'],
					$mast['link'][0]['o']['url'],
					array(
						'html' => true,
						'attributes' => $mast['link'][0]['o']['attributes']
					)
				);
			}

			$caption = '';
			if (!empty($mast['caption'])) {
				$caption = $mast['caption'];
			}

			$variables['masthead'][] = array(
				'image' => image_style_url('level_masthead', $mast['image']['o']['uri']),
				'alt' => $mast['image']['o']['alt'],
				'caption' => $caption,
				'link' => $l
			);

		}

	}

}
