// Require dependencies.
var autoprefixer = require('autoprefixer');
var concat = require('gulp-concat');
var cssnano = require('gulp-cssnano');
var del = require('del');
var gulp = require('gulp');
var notify = require('gulp-notify');
var plumber = require('gulp-plumber');
var postcss = require('gulp-postcss');
var rename = require('gulp-rename');
var sass = require('gulp-sass');
var sort = require('gulp-sort');
var uglify = require('gulp-uglify');
var shell = require('gulp-shell');

//set assets paths.
var paths = {
	css: ['./*.css', '!*.min.css'],
	php: ['./*.php', './**/*.php'],
	sass: 'css/*.scss',
	scripts: 'js/*.js',
};

/**
 * Handle errors and alert the user.
 */
function handleErrors () {
	var args = Array.prototype.slice.call(arguments);

	notify.onError({
		title: 'Task Failed [<%= error.message %>',
		message: 'See console.',
		sound: 'Sosumi' // See: https://github.com/mikaelbr/node-notifier#all-notification-options-with-their-defaults
	}).apply(this, args);

	gutil.beep(); // Beep 'sosumi' again

	// Prevent the 'watch' task from stopping
	this.emit('end');
}

/**
 * Delete cptui.css and cptui.min.css before we minify and optimize
 */
gulp.task('clean:styles', function() {
	return del(['css/cptui.css', 'css/cptui.min.css'])
});

/**
 * Run our documentation generation.
 */
gulp.task('docsgen', shell.task([
	'apigen generate',
	'php apigen/hook-docs.php',
]));

/**
 * Compile Sass and run stylesheet through PostCSS.
 *
 * https://www.npmjs.com/package/gulp-sass
 * https://www.npmjs.com/package/gulp-postcss
 * https://www.npmjs.com/package/gulp-autoprefixer
 */
gulp.task('postcss', ['clean:styles'], function() {
	return gulp.src('css/*.scss', paths.css)

	.pipe(plumber({ errorHandler: handleErrors }))

	// Compile Sass using LibSass.
	.pipe(sass({
		errLogToConsole: true,
		outputStyle: 'expanded' // Options: nested, expanded, compact, compressed
	}))

	.pipe(postcss([
		autoprefixer({
			browsers: ['last 2 version']
		}),
	]))

	// Create style.css.
	.pipe(gulp.dest('./css'));
});

/**
 * Minify and optimize style.css.
 *
 * https://www.npmjs.com/package/gulp-cssnano
 */
gulp.task('cssnano', ['postcss'], function() {
	return gulp.src('./css/cptui.css')
	.pipe(plumber({ errorHandler: handleErrors }))
	.pipe(cssnano({
		safe: true // Use safe optimizations
	}))
	.pipe(rename('cptui.min.css'))
	.pipe(gulp.dest('./css'))
});

/**
 * Delete scripts before we concat and minify
 */
gulp.task('clean:scripts', function() {
	return del(['js/cptui.min.js']);
});

/**
 * Concatenate and minify javascripts.
 *
 * https://www.npmjs.com/package/gulp-uglify
 * https://www.npmjs.com/package/gulp-concat
 */
gulp.task('uglify', ['clean:scripts'], function() {
	return gulp.src(paths.scripts)
	.pipe(plumber({ errorHandler: handleErrors }))
	.pipe(uglify({
		mangle: false
	}))
	.pipe(concat('cptui.min.js'))
	.pipe(gulp.dest('js'))
});

/**
 * Process tasks and reload browsers on file changes.
 *
 * https://www.npmjs.com/package/browser-sync
 */
gulp.task('watch', function () {

	// Run tasks when files change.
	gulp.watch(paths.sass, ['styles']);
	gulp.watch(paths.scripts, ['scripts']);
});

/**
 * Create indivdual tasks.
 */
gulp.task('scripts', ['uglify']);
gulp.task('styles', ['cssnano']);
gulp.task('default', ['styles', 'scripts']);
gulp.task('release', ['styles', 'scripts', 'docsgen']);
