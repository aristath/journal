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
				files: 'sass/**/*.scss',
				tasks: ['sass', 'cssmin'],
			},
		}
	});

	grunt.loadNpmTasks('grunt-contrib-sass');

	grunt.registerTask('default', ['sass']);

};
