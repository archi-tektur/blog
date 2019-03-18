let gulp = require('gulp');
let sass = require('gulp-sass');
let postcss = require('gulp-postcss');
let autoprefixer = require('autoprefixer');
let cssnano = require('cssnano');
let sourcemaps = require('gulp-sourcemaps');

let paths = {
  sass: {
    src: 'assets/sass/**/*.sass',
    dest: 'public/build',
  },
};

function transpile() {
  return (
      gulp.src(paths.sass.src).
          pipe(sourcemaps.init()).
          pipe(sass()).
          on('error', sass.logError).
          pipe(postcss([autoprefixer(), cssnano()])).
          pipe(sourcemaps.write()).
          pipe(gulp.dest(paths.sass.dest))
  );
}

function watch() {
  // gulp.watch takes in the location of the files to watch for changes
  // and the name of the function we want to run on change
  gulp.watch(paths.sass.src, transpile);
}

exports.transpile = transpile;
exports.watch = watch;
