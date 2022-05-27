/**
 * Grid component.
 * @module components/grid
 */

import debounce from './debounce';

const grid = {

	$base: document.getElementById('base'),

	ctx: {},
	coords: [],

	brickHeight: 14,
	brickWidth: 14,

	shadeStep: 2,

	currentCoord: {'x': '', 'y': ''},
	start: 0,

	resizeDelay: 250,

	software: 'ianmoffitt.dev',

	mode: 'gray',

	init() {

		this.load();

		this.drawGrid(true);

		window.addEventListener('resize', debounce(this.testDeb, this.resizeDelay).bind(this));

	},

	testDeb: function () {

		this.$base = document.getElementById('base');

		this.resetCoords();

		this.drawGrid(true);

	},

	resetCoords: function () {

		for (let i = 0; i < this.coords.length; i += 1) {
			this.coords[i].changed = 1;
		}

	},

	drawGrid: function (initial) {

		let canvas = this.$base;
		let ctx = canvas.getContext('2d');

		if (typeof initial === 'undefined') {
			initial = false;
		}

		ctx.canvas.width = window.innerWidth;
		ctx.canvas.height = window.innerHeight;

		this.ctx = canvas;

		if (initial) {
			this.initialRender(this.coords, canvas);
		} else {
			this.render(this.coords, canvas);
		}

		window.requestAnimationFrame(this.frame.bind(this));

		canvas
			.addEventListener('mousemove', function (e) {
				this.currentCoord = this.getCoords(e.clientX, e.clientY);
			}.bind(this));

		canvas
			.addEventListener('mouseleave', function () {
				this.currentCoord = '';
				this.save();
			}.bind(this));

	},

	frame: function (timestamp) {

		window.requestAnimationFrame(this.frame.bind(this));

		if (this.start === 0) {
			this.start = timestamp;
		}

		let elapsed = timestamp - this.start;
		let freq = 1; // Times per second

		let canvas = this.ctx;
		let coords = this.coords;

		if (elapsed > (1000 / freq)) {
			this.updateItem(this.currentCoord);
			this.render(coords, canvas);
		}

	},

	initialRender: function (coords, ctx) {
		let canvas = ctx.getContext('2d');

		let numberOfBricksAcross = this.getXDimension(this.$base);
		let numberOfBricksDown = this.getYDimension(this.$base);

		for (let i = 0; i < numberOfBricksDown; i += 1) {
			for (let j = 0; j < numberOfBricksAcross; j += 1) {
				let colorFill = 'rgb(255,255,255)';
				let coord = coords.filter(p => p.x === j && p.y === i && p.changed === 1);

				if (coord.length > 0) {
					colorFill = coord[0].color;
				}

				let left = j * this.brickWidth;
				let top = i * this.brickHeight;
				let right = left + this.brickWidth;
				let bottom = top + this.brickHeight;

				canvas.strokeStyle = 'rgba(221, 221, 221)';

				canvas.stroke();

				canvas.beginPath();
				canvas.moveTo(left, top);
				canvas.lineTo(right, top);
				canvas.lineTo(right, bottom);
				canvas.lineTo(left, bottom);
				canvas.lineTo(left, top);

				canvas.fillStyle = colorFill;

				canvas.fill();
			}
		}
	},

	render: function (coords, ctx) {

		let canvas = ctx.getContext('2d');

		let changedCoords = coords.some(p => p.changed);

		if (changedCoords) {
			for (let i = 0; i < coords.length; i += 1) {

				if (coords[i].changed === 1) {

					let left = coords[i].x * this.brickWidth;
					let top = coords[i].y * this.brickHeight;
					let right = left + this.brickWidth;
					let bottom = top + this.brickHeight;

					canvas.strokeStyle = 'rgba(221, 221, 221)';

					canvas.beginPath();
					canvas.moveTo(left, top);
					canvas.lineTo(right, top);
					canvas.lineTo(right, bottom);
					canvas.lineTo(left, bottom);
					canvas.lineTo(left, top);
					canvas.fillStyle = coords[i].color;
					canvas.fill();
					canvas.stroke();

					coords[i].changed = 0;

				}

			}

			this.coords = coords;
		}

	},

	getCoords: function (x, y) {
		return {
			'x': Math.floor(x / this.brickWidth),
			'y': Math.floor(y / this.brickHeight)
		};
	},

	getXDimension: function ($base) {
		return Math.ceil(($base.width + 2 * this.brickWidth) / this.brickWidth);
	},

	getYDimension: function ($base) {
		return Math.ceil($base.height / this.brickHeight);
	},

	updateItem: function (currentCoord) {

		if (typeof currentCoord !== 'undefined' && currentCoord.x !== '' && currentCoord.y !== '') {

			let foundCoord = false;
			let color = 'rgb(250, 250, 250)';

			for (let i in this.coords) {
				if (this.coords.hasOwnProperty(i)) {
					if (this.coords[i].x === currentCoord.x && this.coords[i].y === currentCoord.y) {

						foundCoord = true;

						color = this.coords[i].color;
						let rgb = this.parseRgb(color);

						if (this.mode === 'color') {

							// let _key = this.randomKey(rgb);
							//
							// if (rgb[_key] > 0) {
							// 	if (rgb[_key] < 5) {
							// 		rgb[_key] = 0;
							// 	} else {
							// 		rgb[_key] -= this.shadeStep;
							// 	}
							// }

							rgb = this.randomRgb();

							this.coords[i].color = 'rgb(' + rgb.r + ',' + rgb.g + ',' + rgb.b + ')';

						} else {

							let newR = rgb.r * 1;
							if (newR > 0) {
								if (newR < 5) {
									newR = 0;
								} else {
									newR -= this.shadeStep;
								}
							}

							let newG = newR;
							let newB = newR;

							this.coords[i].color = 'rgb(' + newR + ',' + newG + ',' + newB + ')';

						}

						this.coords[i].changed = 1;

						break;

					}
				}
			}

			if (!foundCoord) {
				this.coords.push({
					'x': currentCoord.x,
					'y': currentCoord.y,
					'color': color,
					'changed': 1
				});
			}

		}

	},

	load: function () {

		let key = this.getStorageKey();
		let savedData = window.localStorage.getItem(key);
		let parsedData = this.pxonToCoords(JSON.parse(savedData));

		if (parsedData && typeof parsedData !== 'undefined') {
			for (let i = 0; i < parsedData.length; i += 1) {
				parsedData[i].changed = 1;
			}
			this.coords = parsedData;
		}

	},

	save: function () {

		let key = this.getStorageKey();

		if (typeof this.coords !== 'undefined') {
			let savedData = JSON.stringify(this.coordsToPxon(this.coords));
			window.localStorage.setItem(key, savedData);
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

		for (let index in data) {

			pxon.pxif.pixels.push({
				'x': data[index].x,
				'y': data[index].y,
				'color': data[index].color
			});

		}

		return pxon;

	},

	pxonToCoords: function (pxon) {

		let data = [];

		if (pxon && typeof pxon !== 'undefined') {
			if (typeof pxon.pxif !== 'undefined') {
				if (typeof pxon.pxif.pixels !== 'undefined') {

					for (let pixel in pxon.pxif.pixels) {
						if (pxon.pxif.pixels.hasOwnProperty(pixel)) {
							let p = pxon.pxif.pixels[pixel];
							p.changed = 1;
							data.push(p);
						}
					}

				}
			}
		}

		return data;

	},

	parseRgb(rgb) {

		let _rgb = rgb
			.replace(/^(rgb|rgba)\(/, '')
			.replace(/\)$/, '')
			.replace(/\s/g, '')
			.split(',');

		return {
			'r': _rgb[0],
			'g': _rgb[1],
			'b': _rgb[2]
		}

	},

	randomRgb() {

		return {
			'r': Math.floor(Math.random() * 255),
			'g': Math.floor(Math.random() * 255),
			'b': Math.floor(Math.random() * 255),
		};

	},

	randomKey(arr) {

		let keys = Object.keys(arr);
		return keys[Math.floor(keys.length * Math.random())];

	}

};

export default grid;

// to use:
//
// import grid from './modules/grid';
//
// grid.init();
