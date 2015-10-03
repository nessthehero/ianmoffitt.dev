/*global define */
define(['//www.google-analytics.com/ga.js'], function (g) {
	'use strict';

	var _ = window._gaq;

	return {

		p: function (url) { // Pageview

			if (typeof url !== null) {
				_.push(['_trackPageview', url]);
			} else {
				_.push(['_trackPageview']);
			}
		},

		e: function (category, action, optLabel, optValue, optNoninteraction) { // Event

			if (typeof optNoninteraction === 'undefined') {
				optNoninteraction = false;
			}

			_.push(['_trackPageview', category, action, optLabel, optValue, optNoninteraction]);

		},

		start: function (uid) {

			_.push(['_setAccount', uid]);

		}

	};

});