// Require dependencies.
var autoprefixer = require('autoprefixer');
var cssnano = require('gulp-cssnano');
var del = require('del');
var gulp = require('gulp');
var mqpacker = require('css-mqpacker');
var notify = require('gulp-notify');
var plumber = require('gulp-plumber');
var postcss = require('gulp-postcss');
var rename = require('gulp-rename');
var sass = require('gulp-sass');
var sassLint = require('gulp-sass-lint');
var sort = require('gulp-sort');
var uglify = require('gulp-uglify');
var wpPot = require('gulp-wp-pot');
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
 * https://www.npmjs.com/package/css-mqpacker
 */
gulp.task('postcss', ['clean:styles'], function() {
	return gulp.src('css/*.scss', paths.css)

	// Deal with errors.
	.pipe(plumber({ errorHandler: handleErrors }))

	// Compile Sass using LibSass.
	.pipe(sass({
		includePaths: [],
		errLogToConsole: true,
		outputStyle: 'expanded' // Options: nested, expanded, compact, compressed
	}))

	// Parse with PostCSS plugins.
	.pipe(postcss([
		autoprefixer({
			browsers: ['last 2 version']
		}),
		mqpacker({
			sort: true
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
	return gulp.src('cptui.css')
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
	return del(['js/cptui.js']);
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
 * Delete the theme's .pot before we create a new one
 */
gulp.task('clean:pot', function() {
	return del(['languages/custom-post-type-ui-test.pot']);
});

/**
 * Scan the theme and create a POT file.
 *
 * https://www.npmjs.com/package/gulp-wp-pot
 */
gulp.task('wp-pot', ['clean:pot'], function() {
	return gulp.src(paths.php)
	.pipe(plumber({ errorHandler: handleErrors }))
	.pipe(sort())
	.pipe(wpPot({
		domain: 'custom-post-type-ui',
		destFile:'custom-post-type-ui.pot',
		package: 'Custom Post Type UI',
		bugReport: 'https://wordpress.org/plugins/custom-post-type-ui',
		lastTranslator: 'WebDevStudios <contact@webdevstudios.com>',
		team: 'Team WDS<contact@webdevstudios.com>'
	}))
	.pipe(gulp.dest('languages/'));
});

/**
 * Create indivdual tasks.
 */
gulp.task('i18n', ['wp-pot']);
gulp.task('scripts', ['uglify']);
gulp.task('styles', ['cssnano']);
gulp.task('default', ['i18n', 'styles', 'scripts']);
gulp.task('release', ['i18n', 'styles', 'scripts', 'docsgen']);
