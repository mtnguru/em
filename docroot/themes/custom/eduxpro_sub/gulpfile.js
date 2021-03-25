var gulp = require('gulp');
var gutil = require('gulp-util');
var sass = require('gulp-sass');
var watch = require('gulp-watch');
var shell = require('gulp-shell');
var notify = require('gulp-notify');
var sourcemaps = require('gulp-sourcemaps');
var uglify = require('gulp-uglify');
var fs = require("fs");
var runSequence = require('run-sequence');
var sassGlob = require('gulp-sass-glob');

/**
 * This task generates CSS from all SCSS files and compresses them down.
 */
gulp.task('sass', function () {
  return gulp.src('./scss/**/*.scss')
    .pipe(sassGlob())
    .pipe(sourcemaps.init())
    .pipe(sass({
      noCache: true,
      outputStyle: "compressed",
      lineNumbers: false,
      loadPath: './css/*',
      sourceMap: true
    })).on('error', function(error) {
      gutil.log(error);
      this.emit('end');
    })
    .pipe(sourcemaps.write('./maps'))
    .pipe(gulp.dest('./css'))
    .pipe(notify({
      title: "SASS Compiled",
      message: "All SASS files have been recompiled to CSS.",
      onLast: true
    }));
});

/**
 * Defines the watcher task.
 */
gulp.task('watch', function() {
  let watcher;
  // watch scss for changes and clear drupal theme cache on change
  gulp.watch(['scss/**/*.scss'], gulp.parallel('sass'));

//// watch js for changes and clear drupal theme cache on change
//gulp.watch(['js/js-src/**/*.js'], gulp.parallel('sass'));

  // If user has specified an override, rebuild Drupal cache
//if (!config.twig.useCache) {
//  gulp.watch(['templates/**/*.html.twig'], ['flush']);
//}
});

gulp.task('default', gulp.series('sass', 'watch'));
