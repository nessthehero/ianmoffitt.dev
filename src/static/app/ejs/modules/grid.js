/**
 * Grid component.
 * @module components/grid
 */
import '../lib/jquery.js';
import {Foundation} from 'foundation-sites';

const grid = {

	$base: $('#base'),

	brickHeight: 10,
	brickWidth: 10,

	hoverInterval: null,
	hoverStep: 5,
	hoverDelay: 10,

	gridIsActive: false,

	init() {

		if (Foundation.MediaQuery.atLeast('medium')) {

			this.drawGrid();

		}

		this.bindEvents();

	},

	drawGrid: function () {

		let numberOfBricksAcross = Math.ceil(
			(this.$base.width() + 2 * this.brickWidth) / this.brickWidth
		);
		let numberOfBricksDown = Math.ceil(this.$base.height() / this.brickHeight);

		for (let i = 0; i < numberOfBricksDown; i += 1) {
			let r = '<span class="r" id="r' + i + '">';
			for (let j = 0; j < numberOfBricksAcross; j += 1) {
				r += '<span class="b" id="b' + i + '-' + j + '"></span>';
			}
			r += '</span>';
			this.$base.append(r);
		}

		this.load();

	},

	bindEvents: function () {

		let _this = this;

		$('.b')
			.on('mouseover', function () {
				let $this = $(this);

				_this.gridIsActive = true;

				clearInterval(_this.hoverInterval);
				_this.hoverInterval = setInterval(function () {
					if (!$this.attr('data-disabled')) {
						let color = $this.css('background-color');

						let rgb = color
							.replace(/^(rgb|rgba)\(/, '')
							.replace(/\)$/, '')
							.replace(/\s/g, '')
							.split(',');

						let newR = rgb[0] * 1;
						if (newR > 0) {
							if (newR < _this.hoverStep) {
								newR = 0;
							} else {
								newR -= _this.hoverStep;
							}
						}

						let newG = newR;
						let newB = newR;

						$this.css('background-color', 'rgb(' + newR.toString() + ', ' + newG.toString() + ', ' + newB.toString() + ')');

					}
				}, _this.hoverDelay);

			})
			.on('mouseout', function () {
				let $this = $(this);

				clearInterval(_this.hoverInterval);

				_this.gridIsActive = false;
				$this.attr('data-disabled', null);

				setTimeout(_this.save.bind(_this), 1000);
			})
			.on('click', function () {
				let $this = $(this);
				$this.css('background-color', 'rgb(255, 255, 255)');
				$this.attr('data-disabled', '1');
			});

		$(window).on('resize', debounce(function () {
			_this.drawGrid();
		}, 300));

	},

	load: function () {

		let key = this.getStorageKey();
		let savedData = window.localStorage.getItem(key);
		let parsedData = JSON.parse(savedData);

		if (parsedData) {

			for (let j in parsedData.data) {
				if (parsedData.data.hasOwnProperty(j)) {

					let theId = '#' + j;

					$(theId).css('background-color', parsedData.data[j]);
				}
			}

		}

	},

	save: function () {

		if (!this.gridIsActive) {

			let $rows = this.$base.find('.r');
			let data = {};

			if ($rows.length > 0) {

				$rows.each(function () {

					let $r = $(this);

					let $b = $r.find('.b[style]');

					if ($b.length > 0) {

						$b.each(function () {
							let bId = $(this).attr('id');
							data[bId] = $(this).css('background-color');
						});

					}

				});

				let key = this.getStorageKey();

				let savedData = JSON.stringify({'data': data});

				window.localStorage.setItem(key, savedData);

			}

		}

	},

	getStorageKey: function () {

		let hostname = window.location.hostname;
		let pathname = window.location.pathname
			.replace('/', '')
			.replace('.', '');

		let key = hostname + '-' + pathname + '-grid';

		return key;

	}

};

export default grid;

// to use:
//
// import grid from './modules/grid';
//
// grid.init();
