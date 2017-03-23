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
            production : {
                options : {
                    sassDir     : 'styles',
                    cssDir      : 'styles/temp',
                    outputStyle : 'compressed',
                    cacheDir    : 'styles/.sass-cache',
                    environment : 'production',
                    sourcemap   : true
                },
            },
            development : {
                options : {
                    sassDir     : 'styles',
                    cssDir      : 'styles/temp',
                    outputStyle : 'expanded',
                    cacheDir    : 'styles/.sass-cache',
                    environment : 'development',
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
                dest : 'styles/dist/mpress.public.min.css'
            },
            editor : {
                src  : 'styles/temp/editor.css',
                dest : 'styles/dist/mpress.editor.min.css'
            },
            admin : {
                src  : 'styles/temp/admin.css',
                dest : 'styles/dist/mpress.admin.min.css'
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
              sourceMap : true,
              compress : false,
              mangle: false,
            },
            theme : {
                files : {
                    // File to output to
                    'scripts/dist/mpress.public.min.js' :
                    // Array of files to uglify & concatinate
                    [   // Vender Dependencies
                        'scripts/vendors/jquery.waypoints.min.js',
                        // Individual modules
                        'scripts/modules/mpress.app.js',
                        'scripts/modules/mpress.breakpoint.js',
                        'scripts/modules/mpress.togglebuttons.js',
                        'scripts/modules/mpress.jumpscroll.js',
                        'scripts/modules/mpress.scrolltoggle.js',
                        'scripts/public.js',
                    ]
                }
            },
            tinymce : {
                files : {
                    'scripts/dist/mpress.tinymce.presets.min.js' : [ 'scripts/modules/tinymce.presets.js' ]
                }
            },
        },

        // Watch
        watch : {
            options: {
              livereload: true,
            },
            cssPostProcess : {
                files : 'styles/**/*.scss',
                tasks : [ 'compass:development', 'newer:autoprefixer', 'clean' ]
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
    grunt.registerTask('default', ['openport:watch.options.livereload:35729', 'watch']);
};