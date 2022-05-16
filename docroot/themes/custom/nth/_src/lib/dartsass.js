'use strict';

const sass = require('sass');
const fs = require('fs');
const mkdirp = require('mkdirp');
const getDirName = require('path').dirname;

const options = {
	loadPaths: [
		'node_modules',
		'node_modules/foundation-sites/scss'
	],
	style: 'expanded',
	sourceMap: true,
	sourceMapIncludeSources: false
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
		let result = sass.compile(tmp.file, final);

		// https://github.com/sass/dart-sass/issues/1594
		const sm = JSON.stringify(result.sourceMap);
		const smBase64 = (Buffer.from(sm, 'utf8') || '').toString('base64');
		const smComment = '/*# sourceMappingURL=data:application/json;charset=utf-8;base64,' + smBase64 + ' */';

		let cssString = result.css.toString() + '\n'.repeat(2) + smComment;

		mkdirp(getDirName(cssfilename)).then(dirname => {
				fs.writeFile(cssfilename, cssString, function (er) {
					if (!er) {
					} else {
						console.error(er);
					}
				});
			})
			.catch(err => {
				console.error(err);
			});
	}
}