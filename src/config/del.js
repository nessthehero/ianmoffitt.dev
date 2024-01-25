const config = require('./_ian.json');

exports = module.exports = {
	'dist': [
		{
			'cwd': config.dist,
			'src': [
				'css/**/*',
				'js/**/*',
				'img/**/*'
			]
		},
		{
			'cwd': config.drupal,
			'src': [
				'**/*'
			]
		},
		{
			'cwd': config.deploy,
			'src': [
				'**/*'
			]
		}
	]
};
