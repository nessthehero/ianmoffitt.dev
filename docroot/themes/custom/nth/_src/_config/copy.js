const config = require('./_brei.json');

const root = __dirname + '/..';

const app = root + '/' + config.app;
const dist = root + '/' + config.dist;
const drupal = root + '/' + config.drupal;

exports = module.exports = {
	'deploy': [
		{
			'cwd': app + '/scss/base/icons',
			'dot': true,
			'src': [
				'selection.json'
			],
			'dest': drupal
		},
		{
			'cwd': dist + '',
			'dot': true,
			'src': [
				'**',
				'!tailwind.css',
				'!components/*.html',
				'!*.html'
			],
			'dest': drupal
		}
	]
};
