'use strict';

const bsync = require('browser-sync');
const exec = require('child_process').exec;
const portfinder = require('portfinder');

let initFlag = true;

portfinder.getPort({
	port: 3000,    // minimum port
	stopPort: 3099 // maximum port
}, function (err, port) {

	if (!err) {

		const serverPort = port;

		bsync.init({
			files: [
				'./app/*.html',
				'./app/css/*.css',
				'./app/js/**/*.js'
			],
			reloadThrottle: 5000,
			reloadDelay: 1000,
			port: serverPort,
			server: {
				baseDir: './app',
				index: 'index.html'
			}
		}, function () {
			setTimeout(function () {
				initFlag = false;
			}, 2000);
		});

		bsync.watch('./app/assemble/**/*.hbs', {
			awaitWriteFinish: {
				stabilityThreshold: 200,
				pollInterval: 500
			}
		}, function (event, file) {
			// console.log(event, file);
			switch (event) {

				case 'add':

					if (!initFlag) {
						console.log('building');
						exec('npm run assemble:build');
					}

					break;

				case 'change':

					exec('npm run assemble:build');

					break;

				default:

					exec('npm run assemble:build');

					break;

			}

		});

		bsync.watch('./app/ejs/**/*.js', {
			awaitWriteFinish: {
				stabilityThreshold: 1000,
				pollInterval: 500
			}
		}, function (event, file) {
			// console.log(event, file);
			switch (event) {

				case 'add':

					if (!initFlag) {
						console.log('building');
						exec('npm run preprocess:js');
					}

					break;

				case 'change':

					exec('npm run preprocess:js');

					break;

				default:

					exec('npm run preprocess:js');

					break;

			}

		});

		bsync.watch('./app/scss/**/*.scss', {
			awaitWriteFinish: {
				stabilityThreshold: 1000,
				pollInterval: 2000
			}
		}, function (event, file) {
			// console.log(event, file);
			switch (event) {

				case 'add':

					if (!initFlag) {
						console.log('building');
						exec('npm run preprocess:css');
					}

					break;

				case 'change':

					exec('npm run preprocess:css');

					break;

				default:

					exec('npm run preprocess:css');

					break;

			}

		});

	}

});