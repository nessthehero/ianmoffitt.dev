<?php

function build_includes(&$variables) {

	$icons = array(
		'alert',
		'plus',
		'logo-full',
		'close',
		'arrow',
		'calendar',
		'cta-chat',
		'cta-pencil',
		'cta-pin',
		'facebook',
		'flickr',
		'instagram',
		'logo-shield',
		'menu',
		'numberone',
		'pinterest',
		'search',
		'twitter',
		'vine',
		'youtube'
	);

	foreach ($icons as $key => $icon) {
		$variables['_svg'][$icon] = svg($icon);
	}

}
