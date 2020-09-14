'use strict';

const fs = require('fs');
const path = require('path');
const webpack = require('webpack');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');

const projectDir = __dirname + '/..';
const srcDir = projectDir + '/assets/ejs';

const entries = fs.readdirSync(srcDir).filter(function (file) {

	return file.match(/.*\.js$/);

}).reduce(function (element, index, array) {

	const key = index.replace(/.js/i, '');

	element[key] = './' + index;

	return element;

}, {});

let config = {
	mode: 'development',
	context: path.resolve(projectDir, 'assets/ejs'),
	entry: entries,
	output: {
		path: path.resolve(projectDir, 'public/js'),
		filename: '[name].js'
	},
	externals: {
		jquery: 'jQuery'
	},
	module: {
		rules: [{
			test: /\.js$/,
			include: path.resolve(projectDir, 'assets/ejs'),
			use: [
				{
					loader: 'babel-loader',
					options: {
						presets: ['@babel/preset-env']
					}
				},
				{
					loader: 'eslint-loader',
					options: {
						quiet: true,
						failOnError: true,
						configFile: '_config/.eslintrc.json',
						ignorePath: '_config/.eslintignore'
					}
				}
			]
		}]
	},
	devtool: false,
	plugins: [
		new webpack.SourceMapDevToolPlugin({
			filename: '[name].js.map',
			exclude: ['vendor.js', 'lib/**.js']
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
					cache: true,
					sourceMap: true
				})
			]
		};
	}

	return config;

};
