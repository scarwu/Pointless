'use strict';
/**
 * Gulpfile
 *
 * @package     Pointless
 * @author      Scar Wu
 * @copyright   Copyright (c) Scar Wu (http://scar.tw)
 * @link        https://github.com/scarwu/Pointless
 */

var ENVIRONMENT = 'development';    // production | development | testing
var WEBPACK_NEED_WATCH = false;

var gulp = require('gulp');
var del = require('del');
var run = require('run-sequence');
var $ = require('gulp-load-plugins')();
var process = require('process');
var webpackStream = require('webpack-stream');
var webpack = require('webpack');
var webpackConfig = require('./webpack.config.js');

var postfix = (new Date()).getTime().toString();

function createSrcAndDest(path) {
    var src = path.replace(process.env.PWD + '/', '');
    var dest = src.replace('src/editor/assets', 'src/editor/boot/assets').split('/');

    dest.pop();

    return {
        src: src,
        dest: dest.join('/')
    };
}

function handleCompileError(event) {
    $.util.log($.util.colors.red(event.message), 'error.');
}

// Assets Compile Task
var compileTask = {
    sass: function (src, dest) {
        return gulp.src(src)
            .pipe($.sass().on('error', handleCompileError))
            .pipe($.replace('../fonts/', '../../assets/fonts/vendor/'))
            .pipe($.autoprefixer())
            .pipe($.rename(function (path) {
                path.basename = path.basename.split('.')[0];
                path.extname = '.min.css';
            }))
            .pipe(gulp.dest(dest));
    },
    webpack: function (src, dest) {
        if ('production' === ENVIRONMENT) {
            var definePlugin = new webpack.DefinePlugin({
                'process.env': {
                    'NODE_ENV': JSON.stringify('production')
                }
            });

            webpackConfig.plugins = webpackConfig.plugins || [];
            webpackConfig.plugins.push(definePlugin);
        }

        if (WEBPACK_NEED_WATCH) {
            webpackConfig.watch = true;
        }

        return gulp.src(src)
            .pipe(webpackStream(webpackConfig, webpack).on('error', handleCompileError))
            .pipe(gulp.dest(dest));
    }
};

/**
 * Copy Files & Folders
 */
gulp.task('copy:assets:fonts', function () {
    return gulp.src('src/editor/assets/fonts/*')
        .pipe(gulp.dest('src/editor/boot/assets/fonts'));
});

gulp.task('copy:assets:images', function () {
    return gulp.src('src/editor/assets/images/**/*')
        .pipe(gulp.dest('src/editor/boot/assets/images'));
});

gulp.task('copy:vendor:fonts', function () {
    return gulp.src([
            'node_modules/font-awesome/fonts/*.{otf,eot,svg,ttf,woff,woff2}'
        ])
        .pipe(gulp.dest('src/editor/boot/fonts/vendor'));
});

gulp.task('copy:vendor:scripts', function () {
    return gulp.src([
            'node_modules/modernizr/modernizr.js'
        ])
        .pipe($.rename(function (path) {
            path.basename = path.basename.split('.')[0];
            path.extname = '.min.js';
        }))
        .pipe(gulp.dest('src/editor/boot/scripts/vendor'));
});

/**
 * Styles
 */
gulp.task('style:sass', function() {
    return compileTask.less([
        'src/editor/boot/styles/main.{sass,scss}'
    ], 'src/editor/boot/styles');
});

/**
 * Complex
 */
gulp.task('complex:webpack', function () {
    var result = compileTask.webpack(
        'src/editor/assets/scripts/main.jsx',
        'src/editor/boot/assets/scripts'
    );

    return WEBPACK_NEED_WATCH ? true : result;
});

/**
 * Watching Files
 */
gulp.task('watch', function () {

    // Start LiveReload
    $.livereload.listen();

    gulp.watch([
        'src/editor/boot/**/*',
        '!src/editor/boot/styles/**/*.{sass,scss}',
        '!src/editor/boot/scripts/**/*.jsx'
    ]).on('change', $.livereload.changed);

    // Pre Compile Files
    gulp.watch('src/editor/assets/styles/**/*.{sass,scss}', [
        'style:sass'
    ]);
});

/**
 * Release
 */

// Optimize
gulp.task('release:optimize:scripts', function () {
    return gulp.src('src/editor/boot/scripts/**/*')
        .pipe($.uglify())
        .pipe(gulp.dest('src/editor/boot/scripts'));
});

gulp.task('release:optimize:styles', function () {
    return gulp.src('src/editor/boot/styles/**/*')
        .pipe($.cssnano())
        .pipe(gulp.dest('src/editor/boot/styles'));
});

gulp.task('release:optimize:images', function () {
    return gulp.src('src/editor/boot/images/**/*')
        .pipe($.imagemin())
        .pipe(gulp.dest('src/editor/boot/images'));
});

/**
 * Clean Temp Folders
 */
gulp.task('clean', function (callback) {
    return del([
        'src/editor/boot/styles/main.min.css',
        'src/editor/boot/scripts/main.min.js',
        'src/editor/boot/scripts/vendor',
        'src/editor/boot/fonts/vendor'
    ], callback);
});

gulp.task('clean:all', function (callback) {
    return del([
        'src/editor/boot/styles/main.min.css',
        'src/editor/boot/scripts/main.min.js',
        'src/editor/boot/scripts/vendor',
        'src/editor/boot/fonts/vendor',
        'node_modules',
        'yarn.lock',
        'composer.lock'
    ], callback);
});

/**
 * Bundled Tasks
 */
gulp.task('prepare', function (callback) {
    run('clean', [
        'copy:assets:fonts',
        'copy:assets:images',
        'copy:vendor:fonts',
        'copy:vendor:scripts'
    ], [
        'style:less',
        'complex:webpack'
    ], callback);
});

gulp.task('release', function (callback) {

    // Warrning: Change ENVIRONMENT to Prodctuion
    ENVIRONMENT = 'production';

    run('prepare', [
        'release:optimize:images',
        'release:optimize:scripts',
        'release:optimize:styles'
    ], callback);
});

gulp.task('default', function (callback) {

    // Webpack need watch
    WEBPACK_NEED_WATCH = true;

    run('prepare', 'watch', callback);
});
