<?php

function build_includes(&$variables) {

	$icons = array();

	$icon_json = json_decode(file_get_contents(dirname(__DIR__) . '../../_files/selection.json'));

	if (!empty($icon_json->icons)) {
		foreach	($icon_json->icons as $key => $icon) {
			$icons[] = $icon->properties->name;
		}
	}

	foreach ($icons as $key => $icon) {
		$variables['_svg'][$icon] = svg($icon);
	}

}
