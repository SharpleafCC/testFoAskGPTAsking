// Let's pull in our required modules
const { series } = require("gulp");
const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const del = require('del');
const imagemin = require('gulp-imagemin');
var concat = require('gulp-concat');
const cleanCSS = require('gulp-clean-css');
const minify = require('gulp-minify');
const rollup = require('gulp-better-rollup');
const babel = require('rollup-plugin-babel');
const resolve = require('rollup-plugin-node-resolve');
const commonjs = require('rollup-plugin-commonjs');
var rename = require('gulp-rename');
const postcss = require( 'gulp-postcss');
const tailwindcss = require( 'tailwindcss');
const player = require('play-sound')(opts = {});
const path = require('path');

// Pull in our config file. 
var config = require('./assets/local/gulp/config.js');

// Build css files from all the sass files in the assets folder
gulp.task('styles', () => {
    return gulp.src(config.src.sass)       
        .pipe(sass().on('error', sass.logError))        
        .pipe( postcss([
            tailwindcss('./tailwind.config.js'),
            require('autoprefixer')
        ]) )
        .pipe(cleanCSS({
            compatibility: 'ie9'
        }))
        .pipe(rename({ suffix: '-min' }))
        .pipe(gulp.dest(config.dest.css));
});

const glob = require('glob');

const jsFiles = {};

// Automatically get all files under pages and cpts
['pages', 'cpts'].forEach(folder => {
    glob.sync(config.jsAuto[folder]).forEach((filePath) => {
        const filename = path.basename(filePath, '.js');
        jsFiles[filename] = filePath;
    });
});

// Manually specify certain files
Object.assign(jsFiles, config.jsManual);

// Use jsFiles to create tasks
Object.keys(jsFiles).forEach(function(taskName) {
    gulp.task(taskName, function() {
        const srcPath = jsFiles[taskName];
        const filename = path.basename(srcPath, '.js');
        const relativeDir = path.relative('./assets/local/js/', path.dirname(srcPath));
        
        return gulp.src(srcPath)
            .pipe(rollup({ plugins: [babel(), resolve(), commonjs()] }, 'umd'))
            .on('error', console.log)
            .pipe(concat(filename + '.js'))
            .pipe(minify())
            .pipe(gulp.dest(path.join(config.dest.js, relativeDir)));
    });
});

// Add custom libs that need to be included on the website in the below array. Keep in mind these are currently loaded globally. 

gulp.task('libsJS', () => {
    /*
    return gulp.src([
            'node_modules/slick-carousel/slick/slick.min.js',
            'node_modules/magnific-popup/dist/jquery.magnific-popup.min.js',
            'node_modules/blazy/blazy.min.js'
        ])
        .pipe(concat('libs.js'))
        .pipe(minify())
        .pipe(gulp.dest(config.dest.js))
        ;
    */
   return Promise.resolve('this task is ignored');
});

// Minify and copy over any images from local to /assets/prod/images
gulp.task('images', () => {
    return gulp.src(config.src.images)
        .pipe(imagemin())
        .pipe(gulp.dest(config.dest.images)
    );
});

//move fonts from local to /assets/prod/fonts
gulp.task('fonts', () => {
    return gulp.src(config.src.fonts)
        .pipe(gulp.dest(config.dest.fonts)
    );
});

// Delete old production files just for piece of mind ;)
gulp.task('clean-js', () => {
    return del([
        config.dest.js
    ]);
});

// Delete old production files just for piece of mind ;)
gulp.task('clean-css', () => {
    return del([
        config.dest.css
    ]);
});

// Watch task. Used for development. 
gulp.task('watch', () => {
    gulp.watch(config.src.sass, (done) => {
        gulp.series(['clean-css', 'styles'])(done);
    });

    gulp.watch(config.src.js, (done) => {
        gulp.series(['clean-js', 'libsJS', ...Object.keys(jsFiles)])(done);
    });

    gulp.watch(config.src.php, gulp.series('clean-css', 'styles'));
});

// Add defualt gulp task and any exports
exports.default = series('clean-css', 'clean-js', 'images', 'fonts', 'styles', 'libsJS', ...Object.keys(jsFiles));