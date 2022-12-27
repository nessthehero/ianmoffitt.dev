'use strict';

const fs = require('fs');
const path = require('path');
const webpack = require('webpack');
const TerserPlugin = require('terser-webpack-plugin');
const ESLintPlugin = require('eslint-webpack-plugin');
const SVGSpritemapPlugin = require('svg-spritemap-webpack-plugin');

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
		}),
		new SVGSpritemapPlugin(`../assets/scss/base/icons/**/*.svg`, {
			output: {
				filename: `web/themes/${themeName}/images/sprite.svg`
			},
			sprite: {
				prefix: function (file) {
					// find the index of where sprite_svgs is in the filename path
					const indexOfSubdirectory = file.indexOf('sprite_svgs');
					// slice up the string at that index
					const innerDirFileName = file.slice(indexOfSubdirectory);
					// remove the subdirectory
					const newPrefix = innerDirFileName
						// replace the filename since we're just returing the prefix
						.replace(/[a-zA-Z\-]+\.svg/g, '')
						// remove the directory we're watching
						.replace('sprite_svgs', '')
						// remove the first forward slash
						.replace('/', '')
						// now replace all forward slashes with a dash
						.replace(/\//g, '--');

					return newPrefix;
				}
			}
		}),
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