var gulp        = require('gulp');
var inject      = require('gulp-inject');
var browserSync = require('browser-sync').create();
var sass        = require('gulp-sass');
var minify      = require('gulp-minifier');
var del         = require('del');
var zip         = require('gulp-zip');

// Static Server + watching scss/html files
gulp.task('watch', function () {
  browserSync.init({
      server: './app'
  });

  gulp.watch("./src/scss/**/*.scss", gulp.series('sass'));
  gulp.watch("./src/scripts/**/*.js", gulp.series('js'));
  gulp.watch("./src/assets/**/*", gulp.series('assets'));
  gulp.watch(["./src/*.html","./src/*.php"], gulp.series('html','inject'));
  gulp.watch(["app/**/*"]).on('change', browserSync.reload);
});

// for dev with xampp
gulp.task('watch:xampp', function () {
    gulp.watch("./src/scss/**/*.scss", gulp.series('sass'));
    gulp.watch("./src/scripts/**/*.js", gulp.series('js'));
    gulp.watch("./src/assets/**/*", gulp.series('assets'));
    gulp.watch(["./src/*.html","./src/*.php"], gulp.series('html','inject'));
});

// Compile sass into CSS & auto-inject into browsers
gulp.task('sass', function() {
    return gulp.src("src/scss/**/*.scss")
        .pipe(sass())
        .pipe(minify({
          minify: true,
          minifyCSS: true,
          getKeptComment: function (content, filePath) {
              var m = content.match(/\/\*![\s\S]*?\*\//img);
              return m && m.join('\n') + '\n' || '';
          }
        }))
        .pipe(gulp.dest("app/css"))
        .pipe(browserSync.stream());
});

gulp.task('js', function() {
  return gulp.src('src/scripts/**/*.js').pipe(minify({
    minify: true,
    minifyJS: {
      sourceMap: true
    },
    getKeptComment: function (content, filePath) {
        var m = content.match(/\/\*![\s\S]*?\*\//img);
        return m && m.join('\n') + '\n' || '';
    }
  }))
  .pipe(gulp.dest('app/scripts'))
  .pipe(browserSync.stream());
});

gulp.task('html', function(){
  return gulp.src(["./src/*.html","./src/*.php"])
    .pipe(gulp.dest('app/'))
    .pipe(browserSync.stream());
});

gulp.task('assets', function(){
    return gulp.src('src/assets/**/*')
        .pipe(gulp.dest('app/assets/'))
        .pipe(browserSync.stream());
});

gulp.task('inject', () => {
  return gulp.src(['./app/*.html','./app/*.php'])
          .pipe(inject(gulp.src(['./app/scripts/i18n/*.js'],
                                 {read: false}),{name: 'i18n', relative: true }))
          .pipe(inject(gulp.src(['./app/scripts/i18n.js'], {read: false}), {name: 'other', relative: true}))
          .pipe(inject(gulp.src(['./app/scripts/main.js','./app/**/*.css'], {read: false}), {relative: true}))
          .pipe(gulp.dest('./app'))
          .pipe(browserSync.stream());
});

gulp.task('zip', function(){
    return gulp.src('app/**/*')
        .pipe(zip('ahoy-server.zip'))
        .pipe(gulp.dest('dist'));
})

gulp.task('clean', function () {
  return del(['app/**/*','dist/**/*']);
});

gulp.task('default', gulp.series('clean', 'assets','js','sass','html','inject','watch'));

gulp.task('local', gulp.series('clean', 'assets','js','sass','html','inject','watch:xampp'));

gulp.task('dist', gulp.series('clean', 'assets','js','sass','html','inject','zip'));
