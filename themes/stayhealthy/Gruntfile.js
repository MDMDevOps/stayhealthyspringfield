
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
        pkg : grunt.file.readJSON('package.json'),

        // Compile SCSS with the Compass Compiler
        compass : {
            compile : {
                options : {
                    sassDir     : 'styles',
                    cssDir      : 'styles/temp',
                    outputStyle : 'compressed',
                    fontsPath   : '../../fonts',
                    imagesPath  : '../../images',
                    cacheDir    : 'styles/.sass-cache',
                    sourcemap   : true
                },
            },
        },
        // Run Autoprefixer on compiled css
        autoprefixer: {
            options: {
                browsers: ['last 3 version', '> 1%', 'ie 8', 'ie 9', 'ie 10'],
                map : true
            },
            public : {
                src  : 'styles/temp/public.css',
                dest : 'styles/dist/public.min.css'
            },
            editor : {
                src  : 'styles/temp/editor.css',
                dest : 'styles/dist/editor.min.css'
            }
        },
        // Clean temp files
        clean: {
          temp_css: ['styles/temp/'],
        },
        // JSHint - Check Javascript for errors
        jshint : {
            options : {
                globals  : {
                  jQuery : true,
                },
                smarttabs : true,
            },
            all : [ 'Gruntfile.js', 'scripts/**/*.js', '!scripts/dist/*.js', '!scripts/vendors/*.js' ],
        },
        // Combine & minify JS
        uglify : {
            options : {
              sourceMap : true
            },
            public : {
                files : {
                    'scripts/dist/public.min.js' : [ 'scripts/vendors/inview.min.js', 'scripts/public.js' ]
                }
            }
        },

        // Watch
        watch : {
			options: {
              livereload: true,
            },
            cssPostProcess : {
                files : 'styles/**/*.scss',
                tasks : [ 'compass', 'newer:autoprefixer', 'clean' ]
            },
            jsPostProcess : {
                files : [ 'scripts/**/*.js', '!scripts/dist/*.js' ],
                tasks : [ 'newer:jshint', 'uglify' ],
            },
            livereload : {
                files   : [ 'styles/dist*.css', 'scripts/dist/*.js', '*.html', 'images/*', '*.php' ],
            },
        },
    });
    grunt.registerTask('default', ['openport:watch.options.livereload:35731', 'watch']);
};