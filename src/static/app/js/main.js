/*global window:false */
/*global jQuery:false */

var nth = nth || {};

(function ($, Modernizr, window, document) {

	'use strict';

	nth.main = {

		init: function () {

			$(document).foundation();

		}

	};

})(window.jQuery, window.Modernizr, window, window.document);

jQuery(function () {

	'use strict';

	nth.main.init();

});
