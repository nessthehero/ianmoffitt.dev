const config = require('./_brei.json');

const root = __dirname + '/..';

const app = root + '/' + config.app;
const dist = root + '/' + config.dist;
const deploy = root + '/' + config.deploy;
const drupal = root + '/' + config.drupal;

exports = module.exports = {
	'css': [
		{
			'cwd': app,
			'dot': true,
			'dest': dist,
			'src': [
				'css/**/*'
			]
		}
	],
	'dist': [
		{
			'cwd': app,
			'dot': true,
			'dest': dist,
			'src': [
				'components/*.html',
				'*.html'
			]
		},
		{
			'cwd': app,
			'dot': true,
			'dest': dist,
			'src': [
				'js/**/*'
			]
		},
		{
			'cwd': app,
			'dot': true,
			'dest': dist,
			'src': [
				'**',
				'!assemble/**/*',
				'!css/**/*',
				'!ejs/**/*',
				'!js/**/*',
				'!img/**/*',
				'!scss/**/*',
				'!components/*.html',
				'!*.html'
			]
		}
	],
	'deploy': [
		{
			'cwd': dist,
			'dot': true,
			'src': [
				'**'
			],
			'dest': deploy
		},
		{
			'cwd': dist,
			'dot': true,
			'src': [
				'css/**/*',
				'js/**/*',
				'img/**/*'
			],
			'dest': drupal
		}
	]
};
