const assert = require('assert');
const fs = require('fs');

describe('Ensure eleventy.js built the site properly', () => {

	it('homepage exists', () => {
		assert(fs.existsSync('dist/index.html'), true);
	});

	// TODO: Add a way to create posts through Mocha?
	// it('a post from drupal exists', () => {
	// 	assert(fs.existsSync('dist/now/index.html'), true);
	// });

	it('check if assets were copied', () => {
		assert(fs.existsSync('dist/apple-touch-icon.png'), true);
		assert(fs.existsSync('dist/favicon.ico'), true);
		assert(fs.existsSync('dist/favicon.svg'), true);
		assert(fs.existsSync('dist/favicon-96x96.png'), true);
		assert(fs.existsSync('dist/assets/css/main.css'), true);
		assert(fs.existsSync('dist/assets/js/main.js'), true);
	});

});