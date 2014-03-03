require.config({
	paths: {
		tracking: 'tracking.js',
		'jquery': '//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min'
	}
});

require([
	'app',
	'modules/tracking',
	'jquery'
], function (app, track, $) {
	'use strict';

	// app.start();

	// Misc jQuery

	// var $menu = $('#menu'),
	$(function() {
		var	$menulink = $('.menu-link'),
			$wrap = $('.container');

		$menulink.on('click', function() {
			$menulink.toggleClass('active');
			$wrap.toggleClass('active');

			return false;
		});
	});

	track.start('UA-10459785-3');

	track.p();

});
