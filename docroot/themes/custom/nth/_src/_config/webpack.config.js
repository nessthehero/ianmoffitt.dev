'use strict';

const fs = require('fs');
const path = require('path');
const webpack = require('webpack');
const TerserPlugin = require('terser-webpack-plugin');
const ESLintPlugin = require('eslint-webpack-plugin');

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
		rules: [
			{
				test: /\.js$/,
				include: path.resolve(projectDir, 'assets/ejs'),
				use: [
					{
						loader: 'babel-loader',
						options: {
							presets: ['@babel/preset-env']
						}
					}
				]
			},
			{
				test: /\.scss$/,
				include: path.resolve(projectDir, 'assets/scss'),
				use: [
					{
						loader: 'sass-loader',
						options: {
							sassOptions: {
								includePaths: [
									'assets/scss',
									path.resolve(projectDir, './node_modules')
								],
							}
						}
					}
				]
			}
		]
	},
	devtool: false,
	plugins: [
		new ESLintPlugin({
			context: path.resolve(projectDir, 'assets/ejs'),
			extensions: ['js'],
			overrideConfigFile: '_config/.eslintrc.json',
			ignorePath: '_config/.eslintignore'
		}),
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
		config.optimization = {
			minimizer: [
				new TerserPlugin()
			]
		};
	}

	return config;

};