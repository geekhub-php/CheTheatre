var gulp        = require('gulp'),
    del         = require('del'),
    env         = process.env.GULP_ENV;

var Config = {
    swaggerUi: {
        'path': {
          'build': 'web/swagger-ui'
        },
        'files': [
            'bower_components/swagger-ui/dist/*css/**',
            'bower_components/swagger-ui/dist/*fonts/**',
            'bower_components/swagger-ui/dist/*images/**',
            'bower_components/swagger-ui/dist/*lib/**',
            'bower_components/swagger-ui/dist/o2c.html',
            'bower_components/swagger-ui/dist/swagger-ui.min.js'
        ]
    }
};

// Clean task: Delete all
gulp.task('clean', function () {
    del.sync(['web/swagger-ui']);
});

// SwaggerUI task: Pipe SwaggerUI files to public web folder
gulp.task('swaggerUi', function() {
    return gulp.src(Config.swaggerUi.files)
        .pipe(gulp.dest(Config.swaggerUi.path.build));
});

// Default task when running 'gulp' command
gulp.task('default', ['clean'], function () {
    gulp.start(['swaggerUi'])
});
