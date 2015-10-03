/*global define */
define(function () {
	'use strict';

	var _gaq = [];

	(function(d,t){
		var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
		g.src = '//www.google-analytics.com/ga.js';
		s.parentNode.insertBefore(g,s);
	}(document,'script'));

	return _gaq;

});