/**
 * Grid component.
 * @module components/grid
 */

const grid = {

	$base: document.getElementById('base'),

	ctx: {},
	coords: [],

	brickHeight: 14,
	brickWidth: 14,

	hoverInterval: null,
	hoverStep: 5,
	hoverDelay: 10,

	currentCoord: '',
	start: 0,

	gridIsActive: false,

	software: 'ianmoffitt.dev',

	init() {

		this.drawGrid();

		// this.bindEvents();

	},

	drawGrid: function () {

		let canvas = this.$base;
		let ctx = canvas.getContext('2d');

		ctx.canvas.width = window.innerWidth;
		ctx.canvas.height = window.innerHeight;

		let coords = this.buildCoords(canvas);

		this.coords = coords;
		this.ctx = canvas;

		this.render(coords, canvas);

		window.requestAnimationFrame(this.frame.bind(this));

		canvas
			.addEventListener('mousemove', function (e) {
				this.currentCoord = this.getCoords(e.clientX, e.clientY);
			}.bind(this));

	},

	frame: function (timestamp) {

		window.requestAnimationFrame(this.frame.bind(this));

		if (this.start === 0) {
			this.start = timestamp;
		}

		let elapsed = timestamp - this.start;

		let canvas = this.ctx;
		let coords = this.coords;

		if (elapsed > (1000 / 1)) {
			this.updateItem(this.currentCoord);
			this.render(coords, canvas);
		}

	},

	render: function (coords, ctx) {

		let canvas = ctx.getContext('2d');

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

	},

	buildCoords: function ($base) {

		let pixels = [];

		let numberOfBricksAcross = Math.ceil(
			($base.width + 2 * this.brickWidth) / this.brickWidth
		);
		let numberOfBricksDown = Math.ceil($base.height / this.brickHeight);

		for (let i = 0; i < numberOfBricksDown; i += 1) {
			for (let j = 0; j < numberOfBricksAcross; j += 1) {
				pixels.push({
					'x': i,
					'y': j,
					'color': 'rgb(255,255,255)',
					'changed': 1
				});
			}
		}

		return pixels;

	},

	getCoords: function (x, y) {

		let gx = Math.floor(x / this.brickWidth);
		let gy = Math.floor(y / this.brickHeight);

		let coords = this.coords;
		let rtn = -1;

		for (let i = 0; i < coords.length; i += 1) {

			if (coords[i].x === gx && coords[i].y === gy) {
				rtn = i;
			}

		}

		return rtn;

	},

	updateItem: function (index) {

		if (typeof index !== 'undefined' && index !== '' && index >= 0) {

			let coords = this.coords;

			if (typeof coords[index].color !== 'undefined') {

				let color = coords[index].color;

				let rgb = color
					.replace(/^(rgb|rgba)\(/, '')
					.replace(/\)$/, '')
					.replace(/\s/g, '')
					.split(',');

				let newR = rgb[0] * 1;
				if (newR > 0) {
					if (newR < 5) {
						newR = 0;
					} else {
						newR -= 5;
					}
				}

				let newG = newR;
				let newB = newR;

				coords[index].color = 'rgb(' + newR + ',' + newG + ',' + newB + ')';
				coords[index].changed = 1;

			}

			this.coords = coords;

		}

	}

	// load: function () {
	//
	// 	let key = this.getStorageKey();
	// 	let savedData = window.localStorage.getItem(key);
	// 	let parsedData = JSON.parse(savedData);
	//
	// 	if (parsedData) {
	//
	// 		// let pxon = this.coordsToPxon(parsedData);
	// 		// let pdata = this.pxonToCoords(pxon);
	//
	// 		for (let j in parsedData.data) {
	// 			if (parsedData.data.hasOwnProperty(j)) {
	//
	// 				let theId = '#' + j;
	//
	// 				$(theId).css('background-color', parsedData.data[j]);
	// 			}
	// 		}
	//
	// 	}
	//
	// },
	//
	// save: function () {
	//
	// 	if (!this.gridIsActive) {
	//
	// 		let $rows = this.$base.find('.r');
	// 		let data = {};
	//
	// 		if ($rows.length > 0) {
	//
	// 			$rows.each(function () {
	//
	// 				let $r = $(this);
	//
	// 				let $b = $r.find('.b[style]');
	//
	// 				if ($b.length > 0) {
	//
	// 					$b.each(function () {
	// 						let bId = $(this).attr('id');
	// 						data[bId] = $(this).css('background-color');
	// 					});
	//
	// 				}
	//
	// 			});
	//
	// 			let key = this.getStorageKey();
	//
	// 			let savedData = JSON.stringify({'data': data});
	//
	// 			window.localStorage.setItem(key, savedData);
	//
	// 		}
	//
	// 	}
	//
	// },
	//
	// getStorageKey: function () {
	//
	// 	let hostname = window.location.hostname;
	// 	let pathname = window.location.pathname
	// 		.replace('/', '')
	// 		.replace('.', '');
	//
	// 	let key = hostname + '-' + pathname + '-grid';
	//
	// 	return key;
	//
	// },
	//
	// coordsToPxon: function (coords) {
	//
	// 	let pxon = {
	// 		'exif': {
	// 			'software': grid.software,
	// 			'artist': '',
	// 			'imageDescription': '',
	// 			'userComment': '',
	// 			'copyright': '',
	// 			'dateTime': ''
	// 		},
	// 		'pxif': {
	// 			'pixels': []
	// 		}
	// 	};
	//
	// 	let data = {};
	// 	if (typeof coords.data !== 'undefined') {
	// 		data = coords.data;
	// 	} else {
	// 		data = coords;
	// 	}
	//
	// 	let odata = Object.entries(data);
	//
	// 	for (const [yx, color] of odata) {
	//
	// 		let clean = yx.replace('b', '').split('-');
	// 		let yy = clean[0];
	// 		let xx = clean[1];
	//
	// 		pxon.pxif.pixels.push({
	// 			'x': xx,
	// 			'y': yy,
	// 			'color': color
	// 		});
	//
	// 	}
	//
	// 	return pxon;
	//
	// },
	//
	// pxonToCoords: function (pxon) {
	//
	// 	let data = {};
	//
	// 	if (typeof pxon.pxif !== 'undefined') {
	// 		if (typeof pxon.pxif.pixels !== 'undefined') {
	//
	// 			for (let pixel in pxon.pxif.pixels) {
	// 				if (pxon.pxif.pixels.hasOwnProperty(pixel)) {
	// 					let p = pxon.pxif.pixels[pixel];
	// 					let key = 'b' + p.y + '-' + p.x;
	// 					data[key] = p.color;
	// 				}
	// 			}
	//
	// 		}
	// 	}
	//
	// 	return data;
	//
	// }

};

export default grid;

// to use:
//
// import grid from './modules/grid';
//
// grid.init();
