/* global module, require */
module.exports = function (grunt) {
	'use strict';

    // Load grunt tasks automatically
    require('load-grunt-tasks')(grunt);

	var // config
		sFolderWPRepo = 'wprepo/trunk/'
	;

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json')

		// Watch
		,watch: {
			gruntfile: {
				files: ['Gruntfile.js', '.jshintrc']
				,options: { spawn: false, reload: true }
			}
			,markdown: {
				files: ['readme.txt']
				,tasks: ['markdown']
				,options: { spawn: false }
			}
			,copytosvn: {
				files: ['index.php','README.md','']
				,tasks: ['copy:wprepo']
				,options: { spawn: false }
			}
			,revision: {
				files: ['.git/COMMIT_EDITMSG']
				,tasks: ['version_git']
				,options: { spawn: false }
			}
		}

		// Increment versions
		,version_git: {
			main: {
				files: {src: [
					'package.json'
					,'index.php'
				]}
			}
			,readme: {
				options: {
					regex: /(Stable tag: )(\d+\.\d+\.\d+)/
					,prefix: 'Stable tag: '
				}
				,files: {src: [
					'readme.txt'
				]}
			}
		}

		// README.md
		,wp_readme_to_markdown: {
			txt2md: {
				files: {
				  'README.md': 'readme.txt'
				}
			}
		}

		// Copy all the things!
		,copy: {
			wprepo: {
				files: [
					{
						expand: true
						,cwd: ''
						,src: ['*.php','LICENSE','readme.txt']
						,dest: sFolderWPRepo
						,filter: 'isFile'
					},
					{
						expand: true
						,cwd: ''
						,src: ['lang/**']
						,dest: sFolderWPRepo
						,filter: 'isFile'
					}
				]
			}
		}
	});

	grunt.registerTask('default',['version_git']);
	grunt.registerTask('copyRepo',['copy:wprepo']);
	grunt.registerTask('markdown',['wp_readme_to_markdown']);

};