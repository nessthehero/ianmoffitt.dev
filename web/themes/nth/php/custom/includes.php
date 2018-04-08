<?php

function build_includes(&$variables) {

	$icons = array(
		'pencil',
		'briefcase',
		'terminal',
		'facebook',
		'twitter',
		'linkedin',
		'codepen'
	);

	foreach ($icons as $key => $icon) {
		$variables['_svg'][$icon] = svg($icon);
	}

}
