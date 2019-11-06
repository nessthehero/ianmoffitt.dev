/*global window:false */

var nth = nth || {};

(function ($) {

	'use strict';

	nth.slider = {

		$media: $('.media--slider'),

		init: function () {

			var _this = this;

			if (this.$media.length > 0) {

				this.$media.slick({
					prevArrow: '<a href="#" class="slick-prev"><svg class=\'icon icon-arrow-left\'><use xlink:href=\'#icon-arrow-left\'></use></svg></a>',
					nextArrow: '<a href="#" class="slick-next"><svg class=\'icon icon-arrow-right\'><use xlink:href=\'#icon-arrow-right\'></use></svg></a>'
				});

			}

			_this.bindEvents();

		},

		bindEvents: function () {}

	};

})(window.jQuery);
