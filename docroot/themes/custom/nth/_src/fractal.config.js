'use strict';

/*
* Require the path module
*/
const path = require('path');

const exec = require('child_process').exec;

const config = require('./_config/_brei.json');

/*
 * Require the Fractal module
 */
const fractal = module.exports = require('@frctl/fractal').create();


/*
 * Adjust settings if we are in a server versus a fully built library
 */
let pargs = process.argv;
let isStart = pargs.includes('start');

/*
 * Give your project a title.
 */
fractal.set('project.title', config.title);

/*
 * Change nav from Components to Library
 */
fractal.components.set('label', 'Library');

/*
 * Call all the stuff "Components"
 */
fractal.components.set('title', 'Components');

/*
 * Tell Fractal where to look for components.
 */
fractal.components.set('path', path.join(__dirname, 'components'));

/*
 * Tell Fractal where to look for documentation pages.
 */
fractal.docs.set('path', path.join(__dirname, 'docs'));

/*
 * If we're running a full build, we do not want the development section of docs.
 */
if (!isStart) {
	fractal.docs.set('exclude', '**/development/**');
}

/*
 * Tell the Fractal web preview plugin where to look for static assets.
 */
fractal.web.set('static.path', path.join(__dirname, 'public'));

/*
 * Handlebars Helpers
 */
const breiHelpers = require('./lib/helpers/helpers');
const handlebarsHelpers = require('handlebars-helpers')();

let helpers = Object.assign({}, handlebarsHelpers, breiHelpers);

const hbs = require('@frctl/handlebars')({
	helpers: helpers
});

fractal.components.engine(hbs);
fractal.docs.engine(hbs);

/*
 * Final build destination
 */
fractal.web.set('builder.dest', __dirname + '/' + config.deploy);

/*
 * Theme
 */
const mandelbrot = require('@frctl/mandelbrot');

/*
 * Possible skins:
 * aqua | black | blue | default | fuchsia | green | grey | lime | maroon | navy | olive | orange | purple | red | teal | white | yellow
 *
 * default = blue
 *
 * Possible panels: html | view | context | resources | info | notes
 */
const myCustomisedTheme = mandelbrot({
	skin: 'black',
	format: 'json',
	panels: ['info', 'notes', 'html', 'resources'],
	favicon: '/favicon.ico',
	navigation: 'split',
	highlightStyles: 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.1.2/styles/default.min.css'
});

fractal.web.theme(myCustomisedTheme);

/*
 * BrowserSync options. What should we do when various static assets update?
 */
fractal.web.set('server.sync', true);
fractal.web.set('server.syncOptions', {
	ghostMode: false,
	open: 'local',
	reloadThrottle: 1000,
	// logLevel: "debug",
	logLevel: 'info',
	logConnections: false,
	notify: false,
	minify: false,
	files: [
		{
			match: ['./assets/scss/**/*.scss'],
			fn: function (event, file) {
				// console.log(event, file);
				if (event === 'change') {
					console.log('SCSS Change - ' + file + ' - running preprocess:css:server');
					exec('npm run preprocess:css:server', (error, stdout, stderr) => {
						if (error) {
							console.error(`exec error: ${error}`);
							return;
						}
						if (stderr) {
							console.error(`ERROR:\n ${stderr}`);
						}
					});
				}
				if (event === 'add' || event === 'unlink') {
					if (event === 'add') {
						console.log('SCSS Addition - ' + file + ' - running sass:index');
					}
					if (event === 'unlink') {
						console.log('SCSS Deletion - ' + file + ' - running sass:index');
					}
					exec('npm run preprocess:css', (error, stdout, stderr) => {
						if (error) {
							console.error(`exec error: ${error}`);
							return;
						}
						if (stderr) {
							console.error(`ERROR:\n ${stderr}`);
						}
					});
				}
			},
			options: {
				ignored: '**/_all.scss',
				ignoreInitial: true
			}
		},
		{
			match: ['./assets/ejs/**/*.js'],
			fn: function (event, file) {
				console.log('JS Change - ' + file + ' - running preprocess:js');
				exec('npm run preprocess:js', (error, stdout, stderr) => {
					if (error) {
						console.error(`exec error: ${error}`);
						return;
					}
					if (stderr) {
						console.error(`ERROR:\n ${stderr}`);
					}
				});
			},
			options: {
				ignoreInitial: true
			}
		},
		{
			match: ['./assets/img/**/*'],
			fn: function (event, file) {
				console.log('IMG Change - ' + file + ' - running build:img');
				exec('npm run build:img', (error, stdout, stderr) => {
					if (error) {
						console.error(`exec error: ${error}`);
						return;
					}
					if (stderr) {
						console.error(`ERROR:\n ${stderr}`);
					}
				});
			},
			options: {
				ignoreInitial: true
			}
		}
	]
});
