module.exports = function(grunt, options){
	var yeoman = options.yeoman;
	return {
		dist: {
			files: [{
				expand: true,
				dot: true,
				cwd: yeoman.app,
				dest: yeoman.dist,
				src: [
					'*.{ico,png,txt}',
					'.htaccess',
					'img/{,*/}*.{webp,gif}',
					'css/{,*/}*.{jpg,gif,png,webp}', // if the css is generated into the app directory
					'css/fonts/*', // if the css is generated into the app directory
					'.tmp/concat/css/**/*.{jpg,gif,png,webp}', // if the css is generated into the .tmp directory
					'.tmp/concat/css/**/*.{css}', // if the css is generated into the .tmp directory
					'modules/*'
				]
			}]
		},
		deploy: {
			files: [{
				expand: true,
				dot: true,
				cwd: yeoman.dist,
				dest: yeoman.deploy,
				src: ['./**']
			}]
		},
		drupal: {
			files: [{
				expand: true,
				dot: true,
				cwd: yeoman.dist,
				dest: yeoman.drupal,
				src: [
					'./js/**/*',
					'./css/**/*',
					'./img/**/*'
				]
			}]
		},
		icons: {
			files: [{
				expand: true,
				dot: true,
				cwd: yeoman.app + '/sass/icons',
				dest: yeoman.drupal,
				src: ['./*.json']
			}]
		},
		styles: {
			expand: true,
			flatten: true,
			dest: yeoman.dist + '/css/',
			src: '.tmp/css/*.css'
		}
	};
};
