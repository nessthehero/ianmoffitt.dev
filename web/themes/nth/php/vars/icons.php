<?php

$icons = array(
	'icon-alert',
	'icon-plus',
	'icon-logo-full',
	'icon-close',
	'icon-arrow',
	'icon-calendar',
	'icon-cta-chat',
	'icon-cta-pencil',
	'icon-cta-pin',
	'icon-facebook',
	'icon-flickr',
	'icon-instagram',
	'icon-logo-shield',
	'icon-menu',
	'icon-numberone',
	'icon-pinterest',
	'icon-search',
	'icon-twitter',
	'icon-vine',
	'icon-youtube'
);

foreach ($icons as $key => $icon) {
	$variables['svg__' . $icon] = svg($icon);
}
