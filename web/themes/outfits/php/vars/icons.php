<?php

	$icons = array(
		'icon-pencil',
		'icon-briefcase',
		'icon-terminal',
		'icon-facebook',
		'icon-twitter',
		'icon-linkedin',
		'icon-codepen'
	);

	foreach ($icons as $key => $icon) {
		$variables['svg__' . $icon] = svg($icon);
	}
