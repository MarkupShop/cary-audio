//Require
var gulp = require('gulp'),
    plumber	= require('gulp-plumber'),
    sass = require('gulp-ruby-sass'),
    jshint = require('gulp-jshint'),
    uglify = require('gulp-uglify'),
    include = require('gulp-include');

//Set paths
var paths =
{
	css:
	{
		src:	'sass/*.scss',
		dest:	'css'
	},
	js:
	{
		src:	'js/*.js',
		dest:	'js/build'
	}
};

//Compile SASS
gulp.task('compile-sass', function()
{
	return gulp
		.src(paths.css.src)
		.pipe(plumber())
		.pipe(sass({
			style: 'compressed',
			precision: 8
		}))
		.pipe(gulp.dest(paths.css.dest));
});

//Lint JS
gulp.task('lint-js', function()
{
    return gulp
    	.src(paths.js.src)
        .pipe(jshint())
        .pipe(jshint.reporter('default'));
});

//Compile JS
gulp.task('compile-js', function()
{
	return gulp
		.src(paths.js.src)
        .pipe(include())
		.pipe(plumber())
		.pipe(gulp.dest(paths.js.dest))
		.pipe(uglify({
          mangle: false
        }))
		.pipe(gulp.dest(paths.js.dest));
});

//Create watch tasks
gulp.task('watch', function()
{
    gulp.watch(paths.js.src, ['lint-js', 'compile-js']);
    gulp.watch(paths.css.src, ['compile-sass']);
});

// Default Task
gulp.task('default', ['compile-sass', 'lint-js', 'compile-js', 'watch']);
