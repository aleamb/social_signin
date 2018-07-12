const gulp = require('gulp');
const sass = require('gulp-sass');


gulp.task("sass", () => gulp.src([
    './scss/**/*.scss'
]).pipe(sass()).pipe(gulp.dest("./views/default")));


gulp.task('watch', ['sass']);

gulp.task('watch-sass', function() {
  gulp.watch('./scss/**/*.scss', function () {
    gulp.start('sass');
  });
});
