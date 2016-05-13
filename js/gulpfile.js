var minify = require('gulp-minify');
var gulp = require('gulp');
 
gulp.task('compress', function() {
  gulp.src('*.js')
    .pipe(minify({
        ext:{
            src:'-debug.js',
            min:'.js'
        },
        noSource: true,
        ignoreFiles: ['.combo.js', '-min.js']
    }))
    .pipe(gulp.dest('dist'))
});
