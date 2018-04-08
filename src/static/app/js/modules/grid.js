/*global window:false */
/*global jQuery:false */

var nth = nth || {};

(function ($, Modernizr, Foundation, window, document) {

	'use strict';

	function debounce(func, wait, immediate) {
		var timeout;
		return function() {
			var context = this,
				args = arguments;
			var later = function() {
				timeout = null;
				if (!immediate) {
					func.apply(context, args);
				}
			};
			var callNow = immediate && !timeout;
			clearTimeout(timeout);
			timeout = setTimeout(later, wait);
			if (callNow) {
				func.apply(context, args);
			}
		};
	}

	nth.grid = {

		$base: $('#base'),

		brickHeight: 10,
		brickWidth: 10,

		hoverInterval: null,
		hoverStep: 5,
		hoverDelay: 10,

		gridIsActive: false,

		init: function () {

			var _this = this;

			if (Foundation.MediaQuery.atLeast('medium')) {

				_this.drawGrid();

			}

			_this.bindEvents();

		},

		drawGrid: function () {

			var _this = this;

			var numberOfBricksAcross = Math.ceil(
				(_this.$base.width() + 2 * _this.brickWidth) / _this.brickWidth
			);
			var numberOfBricksDown = Math.ceil(_this.$base.height() / _this.brickHeight);

			for (var i = 0; i < numberOfBricksDown; i += 1) {
				var r = '<span class="r" id="r' + i + '">';
				for (var j = 0; j < numberOfBricksAcross; j += 1) {
					r += '<span class="b" id="b' + i + '-' + j + '"></span>';
				}
				r += '</span>';
				_this.$base.append(r);
			}

			_this.load();

		},

		bindEvents: function () {

			var _this = this;

			$('.b')
				.on('mouseover', function () {
					var $this = $(this);

					_this.gridIsActive = true;

					clearInterval(_this.hoverInterval);
					_this.hoverInterval = setInterval(function () {
						if (!$this.attr('data-disabled')) {
							var color = $this.css('background-color');

							var rgb = color
								.replace(/^(rgb|rgba)\(/, '')
								.replace(/\)$/, '')
								.replace(/\s/g, '')
								.split(',');

							var newR = rgb[0] * 1;
							if (newR > 0) {
								if (newR < _this.hoverStep) {
									newR = 0;
								} else {
									newR -= _this.hoverStep;
								}
							}

							var newG = newR;
							var newB = newR;

							$this.css('background-color', 'rgb(' + newR.toString() + ', ' + newG.toString() + ', ' + newB.toString() + ')');

						}
					}, _this.hoverDelay);

				})
				.on('mouseout', function () {
					var $this = $(this);

					clearInterval(_this.hoverInterval);

					_this.gridIsActive = false;
					$this.attr('data-disabled', null);

					setTimeout(_this.save.bind(_this), 1000);
				})
				.on('click', function () {
					var $this = $(this);
					$this.css('background-color', 'rgb(255, 255, 255)');
					$this.attr('data-disabled', '1');
				});

			$(window).on('resize', debounce(function () {
				_this.drawGrid();
			}, 300));

		},

		load: function () {

			var key = this.getStorageKey();

			var savedData = window.localStorage.getItem(key);

			var parsedData = JSON.parse(savedData);

			console.log('saved', parsedData);

			if (parsedData) {

				for (var j in parsedData.data) {
					if (parsedData.data.hasOwnProperty(j)) {
						console.log(j, parsedData.data[j]);

						var the_id = '#' + j;

						$(the_id).css('background-color', parsedData.data[j]);
					}
				}

			}

		},

		save: function () {

			if (!this.gridIsActive) {

				var $rows = this.$base.find('.r');
				var data = {};

				if ($rows.length > 0) {

					$rows.each(function () {

						var $r = $(this);

						var $b = $r.find('.b[style]');

						if ($b.length > 0) {

							$b.each(function () {
								var b_id = $(this).attr('id');
								data[b_id] = $(this).css('background-color');
							});

						}

					});

					var key = this.getStorageKey();

					console.log(data);

					var savedData = JSON.stringify({'data': data});

					console.log(savedData);

					window.localStorage.setItem(key, savedData);

				}

			}

		},

		getStorageKey: function () {

			var hostname = window.location.hostname;
			var pathname = window.location.pathname
				.replace('/', '')
				.replace('.', '');

			var key = hostname + '-' + pathname + '-grid';

			return key;

		}

	};

})(window.jQuery, window.Modernizr, window.Foundation, window, window.document);

jQuery(function () {

	'use strict';

	nth.grid.init();

});
