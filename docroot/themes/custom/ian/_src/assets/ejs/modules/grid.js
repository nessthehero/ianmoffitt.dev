/**
 * Grid component.
 * @module components/grid
 */
import {Foundation} from 'foundation-sites';
import debounce from './debounce';

const $ = window.$ || window.jQuery || {};

const grid = {

	$base: $('#base'),

	brickHeight: 10,
	brickWidth: 10,

	hoverInterval: null,
	hoverStep: 5,
	hoverDelay: 10,

	gridIsActive: false,

	software: 'ianmoffitt.dev',

	init() {

		if (Foundation.MediaQuery.atLeast('medium')) {

			this.drawGrid();

		}

		this.bindEvents();

	},

	drawGrid: function () {
		
		console.log('running draw');

		let numberOfBricksAcross = Math.ceil(
			(this.$base.width() + 2 * this.brickWidth) / this.brickWidth
		);
		let numberOfBricksDown = Math.ceil(this.$base.height() / this.brickHeight);

		this.$base.html('');

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

		$('#base')
			.on('mouseover', '.b', function () {
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
			.on('mouseout', '.b', function () {
				let $this = $(this);

				clearInterval(_this.hoverInterval);

				_this.gridIsActive = false;
				$this.attr('data-disabled', null);

				setTimeout(_this.save.bind(_this), 1000);
			})
			.on('click', '.b', function () {
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
			
			// let pxon = this.coordsToPxon(parsedData);
			// let pdata = this.pxonToCoords(pxon);

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

	},

	coordsToPxon: function (coords) {

		let pxon = {
			'exif': {
				'software': grid.software,
				'artist': '',
				'imageDescription': '',
				'userComment': '',
				'copyright': '',
				'dateTime': ''
			},
			'pxif': {
				'pixels': []
			}
		};

		let data = {};
		if (typeof coords.data !== 'undefined') {
			data = coords.data;
		} else {
			data = coords;
		}

		let odata = Object.entries(data);

		for (const [yx, color] of odata) {

			let clean = yx.replace('b', '').split('-');
			let yy = clean[0];
			let xx = clean[1];

			pxon.pxif.pixels.push({
				'x': xx,
				'y': yy,
				'color': color
			});

		}

		return pxon;

	},

	pxonToCoords: function (pxon) {

		let data = {};
		
		if (typeof pxon.pxif !== 'undefined') {
			if (typeof pxon.pxif.pixels !== 'undefined') {

				for (let pixel in pxon.pxif.pixels) {
					if (pxon.pxif.pixels.hasOwnProperty(pixel)) {
						let p = pxon.pxif.pixels[pixel];
						let key = 'b' + p.y + '-' + p.x;
						data[key] = p.color;
					}
				}
				
			}
		}
		
		return data;

	}

};

export default grid;

// to use:
//
// import grid from './modules/grid';
//
// grid.init();
