/* global module, require */
module.exports = function (grunt) {
	'use strict';

    // Load grunt tasks automatically
    require('load-grunt-tasks')(grunt);

	var fs = require('fs')
		/*,glob = require('glob')*/
		// config
		,sFolderWPRepo = 'wprepo/trunk/'
		,sFolderTest = 'C:/xampp/htdocs/fcwalvisch/web/wp-content/plugins/lorem-ipsum-article-shortcode'
	;

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json')

		// Watch
		,watch: {
			test: {
				files: ['*.php','LICENSE','readme.txt','lang/**']
				,tasks: ['copyTest']
				,options: {
					spawn: false
				}
			}
			,markdown: {
				files: ['readme.txt']
				,tasks: ['markdown']
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
			your_target: {
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
			,test: {
				files: [
					{
						expand: true
						,cwd: ''
						,src: ['*.php','LICENSE','readme.txt']
						,dest: sFolderTest
						,filter: 'isFile'
					},
					{
						expand: true
						,cwd: ''
						,src: ['lang/**']
						,dest: sFolderTest
						,filter: 'isFile'
					}
				]
			}
		}
	});

	grunt.registerTask('default',['version_git']);
	grunt.registerTask('copyRepo',['copy:wprepo']);
	grunt.registerTask('copyTest',['copy:test']);
	grunt.registerTask('markdown',['wp_readme_to_markdown']);

};