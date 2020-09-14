'use strict';

const sass = require('node-sass');
const fs = require('fs');
const mkdirp = require('mkdirp');
const getDirName = require('path').dirname;

const options = {
	includePaths: [
		'node_modules/foundation-sites/scss'
	],
	outputStyle: 'expanded',
	sourceMapContents: true
};

let regex = /^([^_])(.+)\.scss$/;

let cssdir = fs.readdirSync('./assets/scss');
let css = cssdir.filter(c => c.match(regex));

for (let i in css) {

	if (css.hasOwnProperty(i)) {

		let cssfilename = './public/css/' + css[i].replace('scss', 'css');
		let cssmapfilename = './public/css/' + css[i].replace('scss', 'css.map');

		let tmp = {
			file: './assets/scss/' + css[i],
			outFile: cssfilename,
			sourceMap: cssmapfilename
		};

		let final = Object.assign({}, tmp, options);

		sass.render(final, function (error, result) {

			if (!error) {
				mkdirp(getDirName(cssfilename)).then(dirname => {
						fs.writeFile(cssfilename, result.css, function (er) {
							if (!er) {

							} else {
								console.error(er);
							}
						});
					})
					.catch(err => {
						console.error(err);
					});

				mkdirp(getDirName(cssfilename)).then(dirname => {
						fs.writeFile(cssmapfilename, result.map, function (err) {
							if (!err) {

							} else {
								console.error(err);
							}
						});
					})
					.catch(err => {
						console.error(err);
					});
			} else {
				console.error(error);
			}

		});

	}

}
