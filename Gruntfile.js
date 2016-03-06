'use strict';
module.exports = function(grunt) {

	grunt.initConfig({
		// Compile CSS
		sass: {
			dist: {
				files: { 'style.css' : 'sass/style.scss' }
			}
		},
		// Watch task (run with "grunt watch")
  		watch: {
			css: {
				files: [
					'sass/**/*.scss',
					'sass/*.scss'
				],
				tasks: ['sass'],
			},
		}
	});

	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-contrib-watch');

	grunt.registerTask('default', ['sass']);

};
