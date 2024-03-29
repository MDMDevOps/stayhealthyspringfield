module.exports = function(grunt) {
    grunt.loadNpmTasks('grunt-newer');
    grunt.loadNpmTasks('grunt-contrib-compass');
    grunt.loadNpmTasks('grunt-autoprefixer');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-openport');
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.initConfig({
        // Reference package.json
        pkg: grunt.file.readJSON('package.json'),

        // Compile SCSS with the Compass Compiler
        compass: {
            dist: {
                options: {
                    sassDir     : 'styles',
                    cssDir      : 'styles/temp',
                    outputStyle : 'compressed',
                    cacheDir    : 'styles/dist/.sass-cache',
                    sourcemap   : true
                }
            }
        },

        // Run Autoprefixer on compiled css
        autoprefixer: {
            options: {
                browsers: ['last 3 version', '> 1%', 'ie 8', 'ie 9', 'ie 10'],
                map: true
            },
            admin : {
                src  : 'styles/temp/admin.css',
                dest : 'styles/dist/admin.min.css'
            }
        },

        // Clean tasks
        clean: {
          folder: ['styles/temp/'],
        },

        // JSHint - Check Javascript for errors
        jshint: {
            options: {
                globals: {
                  jQuery: true
                }
            },
            admin : {
                src: [ 'scripts/admin.js' ],
            }
        },

        // Concat & Minify JS
        uglify: {
            options: {
              sourceMap : true
            },
            admin : {
                files : {
                    'scripts/dist/admin.min.js' : [ 'scripts/vendors/jquery.sumoselect.js', 'scripts/admin.js' ]
                }
            }
        },

        // Watch
        watch: {
            options: {
              livereload: true,
            },
            cssPostProcess: {
                files: 'styles/**/*.scss',
                tasks: ['compass', 'newer:autoprefixer', 'clean'],
            },
            jsPostProcess: {
                files: [ 'scripts/**/*.js', '!scripts/dist/**/*.js' ],
                tasks: ['newer:jshint', 'uglify'],
            },
            livereload: {
                files: ['styles/dist/*.css', 'scripts/dist/*.js', '*.html', 'images/*', '*.php'],
            },
        },
    });
    grunt.registerTask('default', ['openport:watch.options.livereload:35729', 'watch']);
};