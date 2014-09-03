/*global module:false*/
module.exports = function(grunt) {

	// PROJECT CONFIG
	grunt.initConfig({

		// META DATA
		pkg: grunt.file.readJSON('package.json'),
		banner: '/*! <%= pkg.title || pkg.name %> - v<%= pkg.version %> - ' +
			'<%= grunt.template.today("yyyy-mm-dd") %>*/\n',

		// TASK CONFIG

		// concatonate javascript files including any frameworks first, then zurb foundation components, then plugins, and finally site js
		concat: {
			options: {
				banner: '<%= banner %>',
				stripBanners: true
			},
			dist: {
				src: [
					'js/frameworks/*.js',
					'<%= pkg.foundationPath %>/js/foundation/foundation.js',
					//'<%= pkg.foundationPath %>/js/foundation/*.js', // or alternatively just list out the modules you want
					'<%= pkg.foundationPath %>/js/foundation/foundation.topbar.js', // or alternatively just list out the modules you want
					'js/plugins/*.js',
					'js/site/*.js'
					],
				dest: '<%= pkg.jsPath %>/<%= pkg.name %>.js'
			}
		},

		// minify the concatonated javascript and put it in the same location
		uglify: {
			options: {
				banner: '<%= banner %>'
			},
			dist: {
				src: '<%= concat.dist.dest %>',
				dest: '<%= pkg.jsPath %>/<%= pkg.name %>.min.js'
			}
		},

		// helpful hinting of the javascript
		jshint: {
			options: {
				curly: true,
				eqeqeq: true,
				immed: true,
				latedef: true,
				newcap: true,
				noarg: true,
				sub: true,
				undef: true,
				unused: true,
				boss: true,
				eqnull: true,
				browser: true,
				globals: {
					jQuery: true
				}
			},
			gruntfile: {
				src: 'Gruntfile.js'
			}
		},

		// watch the source so i can run the build automatically as i code
		watch: {
			scripts: {
				files: [
					'js/**/*.js'
				],
				tasks: ['jshint', 'concat'],
				options: {
					debounceDelay: 250,
				},
			},
			sass: {
				files: [
					'sass/**/*.scss'
				],
				tasks: ['sass:dev', 'autoprefixer'],
			},
		},

		// the sass build. i use a distrubution and dev config so i can minify my css for production and use source maps for dev.
		sass: {
			dist: {
				options: {
					loadPath: '<%= pkg.foundationPath %>/scss',
					style: 'compressed',
					bundleExec: true
				},
				files: [{
					expand: true,
					cwd: 'sass',
					src: ['*.scss', '!_*.scss'],
					dest: '<%= pkg.cssPath %>',
					ext: '.css'
				}]
			},
			dev: {
				options: {
					loadPath: '<%= pkg.foundationPath %>/scss',
					style: 'expanded',
					sourcemap: true,
					trace: true,
					bundleExec: true,
				},
				files: [{
					expand: true,
					cwd: 'sass',
					src: ['*.scss', '!_*.scss'],
					dest: '<%= pkg.cssPath %>',
					ext: '.css'
				}]
			}
		},

		// never write vendor prefixes again!
		autoprefixer: {
			dist: {
				src: '<%= pkg.cssPath %>/*.css'
			}
		}

	});

	// LOAD NPM MODULES
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-autoprefixer');

	// DEFAULT TASK (dev by default)
	grunt.registerTask('default', ['jshint', 'concat', 'sass:dev', 'autoprefixer', 'watch']);
	// PRODUCTION TASK (minified and distributed)
	grunt.registerTask('production', ['jshint', 'concat', 'uglify', 'sass:dist', 'autoprefixer']);

};
