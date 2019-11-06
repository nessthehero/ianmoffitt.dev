'use strict';

const fs = require('fs');
const path = require('path');
const webpack = require('webpack');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');

const projectDir = __dirname + '/..';
const srcDir = projectDir + '/app/ejs';

const entries = fs.readdirSync(srcDir).filter(function (file) {

	return file.match(/.*\.js$/);

}).reduce(function (element, index, array) {

	const key = index.replace(/.js/i, '');

	element[key] = './' + index;

	return element;

}, {});

let config = {
	mode: 'development',
	context: path.resolve(projectDir, 'app/ejs'),
	entry: entries,
	output: {
		path: path.resolve(projectDir, 'app/js'),
		filename: '[name].js'
	},
	module: {
		rules: [{
			test: /\.js$/,
			include: path.resolve(projectDir, 'app/ejs'),
			use: [
				{
					loader: 'babel-loader',
					options: {
						presets: [
							['es2015', { modules: false }]
						]
					}
				},
				{
					loader: 'eslint-loader',
					options: {
						quiet: true,
						failOnError: true,
						configFile: '_config/.eslintrc.json'
					}
				}
			]
		}]
	},
	plugins: [
		new webpack.SourceMapDevToolPlugin({
			filename: '[name].js.map'
		})
	]
};

module.exports = (env, argv) => {

	// Do neat stuff here
	if (argv.mode === 'development') {

	}

	if (argv.mode === 'production') {
		// config.output.path = path.resolve(projectDir, 'dist/js');
		config.optimization = {
			minimizer: [
				new UglifyJsPlugin({
					cache: true
				})
			]
		};
	}

	return config;

};
