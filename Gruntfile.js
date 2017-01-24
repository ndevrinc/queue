module.exports = function (grunt) {

    'use strict';
    var banner = '/**\n * <%= pkg.homepage %>\n * Copyright (c) <%= grunt.template.today("yyyy") %>\n * This file is generated automatically. Do not edit.\n */\n';
    // Project configuration
    grunt.initConfig({

        pkg: grunt.file.readJSON('package.json'),

        addtextdomain: {
            options: {
                textdomain: 'queue',
            },
            target: {
                files: {
                    src: ['*.php', '**/*.php', '!node_modules/**', '!php-tests/**', '!bin/**']
                }
            }
        },

        wp_readme_to_markdown: {
            your_target: {
                files: {
                    'README.md': 'readme.txt'
                }
            },
        },

        makepot: {
            target: {
                options: {
                    domainPath: '/languages',
                    mainFile: 'queue.php',
                    potFilename: 'queue.pot',
                    potHeaders: {
                        poedit: true,
                        'x-poedit-keywordslist': true
                    },
                    type: 'wp-plugin',
                    updateTimestamp: true
                }
            }
        },

        sass: {
            dist: {
                options: {
                    sourceMap: true
                },
                files: {
                    'assets/css/custom-admin.css': 'assets/css/partials/admin.scss'
                }
            }
        },

        uglify: {
            targets: {
                files: {
                    'assets/js/build/queue.min.js': ['assets/js/src/*.js']
                }
            }
        },

        phpcs: {
            application: {
                src: ['lib/*.php']
            },
            options: {
                bin: "vendor/bin/phpcs --extensions=php --ignore=\"*/vendor/*,*/node_modules/*\"",
                standard: "WordPress"
            }
        }
    });

    grunt.loadNpmTasks('grunt-wp-i18n');
    grunt.loadNpmTasks('grunt-wp-readme-to-markdown');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-phpcs');
    grunt.loadNpmTasks('grunt-sass');

    grunt.registerTask('i18n', ['addtextdomain', 'makepot']);
    grunt.registerTask('readme', ['wp_readme_to_markdown']);

    grunt.registerTask('phpcs', [ 'phpcs' ]);
    grunt.registerTask('default', [ 'uglify', 'sass' ]);

    grunt.util.linefeed = '\n';

};
