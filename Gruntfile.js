// Generated on 2013-07-02 using generator-webapp 0.2.6
'use strict';
var LIVERELOAD_PORT = 35729;
var lrSnippet = require('connect-livereload')({port: LIVERELOAD_PORT});
var mountFolder = function (connect, dir) {
	return connect.static(require('path').resolve(dir));
};

// # Globbing
// for performance reasons we're only matching one level down:
// 'test/spec/{,*/}*.js'
// use this if you want to recursively match all subfolders:
// 'test/spec/**/*.js'

module.exports = function (grunt) {
	// load all grunt tasks
	require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);

	// configurable paths
	var yeomanConfig = {
		app: 'site-static',
		dist: 'site-compiled',
		theme: 'site-theme',
		combine: 'dist',
		wordpress: 'bower_components/wordpress',
		server: 'C:/xampp/htdocs/personal'
	};

	grunt.initConfig({
		yeoman: yeomanConfig,
		watch: {
			compass: {
                files: [
                    '<%= yeoman.app %>/sass/{,*/}*.{scss,sass}',
                    '/img/{,*/}*.png'
                ],
                tasks: ['compass:server', 'autoprefixer']
            },
			styles: {
				files: ['<%= yeoman.app %>/styles/**/*.css'],
				tasks: ['copy:styles', 'autoprefixer']
			},
			scripts: {
				files: '**/*.js',
				tasks: ['jshint']
			},
			livereload: {
				options: {
					livereload: LIVERELOAD_PORT
				},
				files: [
					'<%= yeoman.app %>/*.html',
					'.tmp/styles/**/*.css',
					'{.tmp,<%= yeoman.app %>}/scripts/**/*.js',
					'<%= yeoman.app %>/images/**/*.{png,jpg,jpeg,gif,webp,svg}'
				]
			}
		},
		connect: {
			options: {
				port: 9000,
				// change this to '0.0.0.0' to access the server from outside
				hostname: 'localhost'
			},
			livereload: {
				options: {
					middleware: function (connect) {
						return [
							lrSnippet,
							mountFolder(connect, '.tmp'),
							mountFolder(connect, yeomanConfig.app)
						];
					}
				}
			},
			dist: {
				options: {
					middleware: function (connect) {
						return [
							mountFolder(connect, yeomanConfig.dist)
						];
					}
				}
			}
		},
		open: {
			server: {
				path: 'http://localhost:<%= connect.options.port %>'
			},
			live: {
				path: 'http://localhost/'
			}
		},
		jshint: {
			options: {
				jshintrc: '.jshintrc'
			},
			all: [
				'Gruntfile.js',
				'<%= yeoman.app %>/scripts/**/*.js',
				'!<%= yeoman.app %>/scripts/vendor/*'
			]
		},
		autoprefixer: {
			options: {
				browsers: ['last 2 versions', 'ie 8', 'ie 9']
			},
			dist: {
				files: [{
					expand: true,
					cwd: '.tmp/styles/',
					src: '**/*.css',
					dest: '.tmp/styles/'
				}]
			}
		},
		useminPrepare: {
			options: {
				dest: '<%= yeoman.dist %>'
			},
			html: '<%= yeoman.app %>/index.html'
		},
		usemin: {
			options: {
				dirs: ['<%= yeoman.dist %>']
			},
			html: ['<%= yeoman.dist %>/**/*.html'],
			css: ['<%= yeoman.dist %>/styles/**/*.css']
		},
		imagemin: {
			dist: {
				files: [{
					expand: true,
					cwd: '<%= yeoman.app %>/images',
					src: '**/*.{png,jpg,jpeg}',
					dest: '<%= yeoman.dist %>/images'
				}]
			}
		},
		svgmin: {
			dist: {
				files: [{
					expand: true,
					cwd: '<%= yeoman.app %>/images',
					src: '**/*.svg',
					dest: '<%= yeoman.dist %>/images'
				}]
			}
		},
		cssmin: {
			// This task is pre-configured if you do not wish to use Usemin
			// blocks for your CSS. By default, the Usemin block from your
			// `index.html` will take care of minification, e.g.
			//
			//     <!-- build:css({.tmp,app}) styles/main.css -->
			//
			// dist: {
			//     files: {
			//         '<%= yeoman.dist %>/styles/main.css': [
			//             '.tmp/styles/**/*.css',
			//             '<%= yeoman.app %>/styles/**/*.css'
			//         ]
			//     }
			// }
		},
		htmlmin: {
			dist: {
				options: {
					/*removeCommentsFromCDATA: true,
					// https://github.com/yeoman/grunt-usemin/issues/44
					//collapseWhitespace: true,
					collapseBooleanAttributes: true,
					removeAttributeQuotes: true,
					removeRedundantAttributes: true,
					useShortDoctype: true,
					removeEmptyAttributes: true,
					removeOptionalTags: true*/
				},
				files: [{
					expand: true,
					cwd: '<%= yeoman.app %>',
					src: '*.html',
					dest: '<%= yeoman.dist %>'
				}]
			}
		},
		compass: {
            options: {
                sassDir: '<%= yeoman.app %>/sass',
                cssDir: '.tmp/css',
                generatedImagesDir: '.tmp/images/generated',
                imagesDir: '<%= yeoman.app %>/images',
                javascriptsDir: '<%= yeoman.app %>/scripts',
                fontsDir: '<%= yeoman.app %>/sass/fonts',
                importPath: '<%= yeoman.app %>/bower_components',
                httpImagesPath: '/images',
                httpGeneratedImagesPath: '/images/generated',
                httpFontsPath: '/sass/fonts',
                relativeAssets: false,
                outputStyle: 'compact',
                debugInfo: false
            },
            dist: {
                options: {
                    generatedImagesDir: '<%= yeoman.dist %>/images/generated',
                    debugInfo: false,
                    outputStyle: 'compressed'
                }
            },
            server: {
                options: {
                    debugInfo: true
                }
            }
        },
		// Put files not handled in other tasks here
		clean: {
			options: {
				force: true
			},
			dist: {
				files: [{
					dot: true,
					src: [
						'.tmp',
						'<%= yeoman.dist %>/*',
						'!<%= yeoman.dist %>/.git*'
					]
				}]
			},
			server: '.tmp',
			combine: '<%= yeoman.combine %>',
			deploy: {
				files: [{
					dot: true,
					src: [
						'<%= yeoman.server %>/*.php',
						'<%= yeoman.server %>/*.txt',
						'<%= yeoman.server %>/*.html',
						'<%= yeoman.server %>/wp-admin/*',
						'<%= yeoman.server %>/wp-includes/*',
						'!<%= yeoman.server %>/.htaccess',
						'!<%= yeoman.server %>/wp-config.php'
					]
				}]
			}
		},
		copy: {
			dist: {
				files: [{
					expand: true,
					dot: true,
					cwd: '<%= yeoman.app %>',
					dest: '<%= yeoman.dist %>',
					src: [
						'*.{ico,png,txt}',
						'.htaccess',
						'images/**/*.{webp,gif}'
					]
				}, {
					expand: true,
					cwd: '.tmp/images',
					dest: '<%= yeoman.dist %>/images',
					src: [
						'generated/*'
					]
				}]
			},
			styles: {
				expand: true,
				dot: true,
				cwd: '<%= yeoman.app %>/styles',
				dest: '.tmp/styles/',
				src: '**/*.css'
			},
			wp: {
				expand: true,
				dot: true,
				cwd: '<%= yeoman.dist %>',
				dest: '<%= yeoman.theme %>',
				src: [
					'**',
					'!*.html'
				]
			},
			live: {
				options: {
					processContentExclude: ['**/*.{png,gif,jpg,ico,svg,ttf,eot,woff}']
				},
				expand: true,
				dot: true,
				cwd: '<%= yeoman.wordpress %>',
				dest: '<%= yeoman.combine %>',
				src: '**'
			},
			theme: {
				options: {
					processContentExclude: ['**/*.{png,gif,jpg,ico,svg,ttf,eot,woff}']
				},
				files: [{
					expand: true,
					dot: true,
					cwd: '<%= yeoman.theme %>',
					dest: '<%= yeoman.combine %>/wp-content/themes/nth',
					src: '**'
				}]
			},
			deploy: {
				expand: true,
				dot: true,
				cwd: '<%= yeoman.combine %>',
				dest: '<%= yeoman.server %>',
				src: '**'
			}
		},
		concat: {
			wp: {
				options: {
					seperator: '\n'
				},
				src: [
					'<%= yeoman.theme %>/meta/style_info.txt',
					'<%= yeoman.dist %>/style.css'
				],
				dest: '<%= yeoman.theme %>/style.css'
			}
		},
		concurrent: {
			server: [
				'compass:server',
				'copy:styles'
			],
			dist: [
				'compass:dist',
				'copy:styles',
				'imagemin',
				'svgmin',
				'htmlmin'
			]
		},
		bower: {
			options: {
				exclude: ['modernizr']
			},
			all: {
				rjsConfig: '<%= yeoman.app %>/scripts/main.js'
			}
		}
	});

	grunt.registerTask('server', function (target) {
		if (target === 'dist') {
			return grunt.task.run(['build', 'open', 'connect:dist:keepalive']);
		}

		grunt.task.run([
			'clean:server',
			'concurrent:server',
			'autoprefixer',
			'connect:livereload',
			'open:server',
			'watch'
		]);
	});

	grunt.registerTask('build', [
		'clean:dist',
		'useminPrepare',
		'concurrent:dist',
		'autoprefixer',
		'concat',
		'cssmin',
		'uglify',
		'copy:dist',
		'usemin'
	]);

	grunt.registerTask('default', [
		'jshint',
		'build'
	]);

	grunt.registerTask('wp', [
		'copy:wp',
		'concat:wp'
	]);

	grunt.registerTask('live', [
		'jshint',
		'build',
		'wp',
		'clean:combine',
		'copy:theme',
		'copy:live'
	]);

	grunt.registerTask('deploy', [
		'clean:deploy',
		'copy:deploy'
	]);

	grunt.registerTask('full', [
		'live',
		'deploy'
	]);

};
