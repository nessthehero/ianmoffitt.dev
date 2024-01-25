const imagemin = require('imagemin');

// Lossy Plugins
const imageminMozjpeg = require('imagemin-mozjpeg');
const imageminPngquant = require('imagemin-pngquant');
const imageminGiflossy = require('imagemin-giflossy');
const imageminWebp = require('imagemin-webp');
const imageminSvgo = require('imagemin-svgo');

// Lossyless Plugin
const imageminJpegtran = require('imagemin-jpegtran');
const imageminOptipng = require('imagemin-optipng');
const imageminGifsicle = require('imagemin-gifsicle');

const { lstatSync, readdirSync } = require('fs');
const { join, normalize } = require('path');

// Source directory for images to be optimized
const INPUT_DIR = 'src/assets/img';

// Destiny for compressed images
const OUTPUT_DIR = 'src/public/img';

// Colors for console.log messages
const COLORS = {
	yellow: '\x1b[33m%s\x1b[0m'
};

/**
 * Return true if source is a directory.
 * @param {string} source Directory.
 */
const isDirectory = source => lstatSync(source).isDirectory();

/**
 * Get directories for a given directory.
 * @param {string} source Directory.
 */
const getDirectories = source =>
	readdirSync(source)
		.map(name => join(source, name))
		.filter(isDirectory);

/**
 * Recursive function that get list of all directories and subdirectories for
 * a given directory.
 * @param {string} source Root directory.
 */
const getDirectoriesRecursive = source => [
	normalize(source),
	...getDirectories(source)
		.map(getDirectoriesRecursive)
		.reduce((a, b) => a.concat(b), [])
];

/**
 * Convert Windows backslash paths to slash paths.
 * @param {string} path
 */
const converToSlash = path => {
	const isExtendedLengthPath = /^\\\\\?\\/.test(path);
	const hasNonAscii = /[^\u0000-\u0080]+/.test(path);

	if (isExtendedLengthPath || hasNonAscii) {
		return path;
	}

	return path.replace(/\\/g, '/');
};

console.log(COLORS.yellow, 'Beginning image compression.');

(async () => {
	const imageDirs = getDirectoriesRecursive(INPUT_DIR);
	let imagesOptimized = 0;

	/**
	 * Loop through all subfolders, and recursively run imagemin,
	 * outputting to the same subfolders inside OUTPUT_DIR folder.
	 */
	for (let i in imageDirs) {
		const dir = imageDirs[i];

		/**
		 * imagemin needs paths with forward slashes. converToSlash is needed
		 * on Windows environment.
		 *
		 * Remove INPUT_DIR in OUTPUT_DIR for just getting the part of folder wanted.
		 * If not replaced, the output would be: static/img/static-src/img/**
		 */
		const destiny = converToSlash(join(OUTPUT_DIR, dir)).replace(INPUT_DIR, '');

		const files = await imagemin([`${converToSlash(dir)}/*.{jpg,png,svg,gif}`], {
			destination: normalize(destiny),
			plugins: [
				imageminJpegtran(),
				imageminPngquant({
					quality: [0.6, 0.8]
				}),
				imageminGifsicle(),
				imageminSvgo({
					plugins: [{ removeViewBox: false }]
				})
			]
		});
		imagesOptimized += files.length;
	}

	console.log(COLORS.yellow, `Image compression finished. Total images compressed: ${imagesOptimized}`);
})();
