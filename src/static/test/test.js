/*global describe, it, require, __dirname*/

'use strict';

const util = require('brei-util');
const u = require('util');

const root = __dirname + '/..';

let valid = [
	{
		'.github': [
			'CONTRIBUTING.md',
			'ISSUE_TEMPLATE.md',
			'PULL_REQUEST_TEMPLATE.md'
		]
	},
	'.travis.yml',
	'.gitignore',
	'README.md',
	{
		_config:
			['.eslintrc.json',
				'.stylelintignore',
				'_brei.json',
				'assemblefile.js',
				'copy.js',
				'del.js',
				'modernizr-config.json',
				'postcss.config.js',
				'webpack.config.js']
	},
	{
		app: [
			{ assemble: [ '.gitkeep' ] },
			{
				ejs: [
					{
						lib: [
							'jquery.js'
						]
					},
					'main.js'
				]
			},
			{ img: [ '.gitkeep' ] },
			{ scss: [ '.gitkeep' ] },
		]
	},
	{
		lib: [
			'browsersync.js',
			'copy.js',
			'del.js',
			'nodesass.js'
		]
	},
	'package.json',
	{
		test: ['test.js']
	}
];

describe('brei-project-scaffold -- Verify file and folder structure', function () {

	it('Deep object comparison check', function () {

		let ttree = util.tree(root);

		let actual = util.ftree(ttree);

		let expected = util.filterObject(valid);

		console.log('\n------- actual --------\n');
		console.log(u.inspect(actual, false, null));

		console.log('\n------- valid --------\n');
		console.log(u.inspect(expected, false, null));

		// console.log('\n------- test --------\n');
		// console.log(u.inspect(util.deepNotOnly(actual, expected), false, null));

		util.assert(util.deep(util.deepNotOnly(actual, expected), {}));

	});

});

