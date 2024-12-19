let mix = require('laravel-mix')

const path = require('path')
let directory = path.basename(path.resolve(__dirname))

const source = 'platform/plugins/' + directory
const dist = 'public/vendor/core/plugins/' + directory

mix
    .sass(source + '/resources/sass/course.scss', dist + '/css')
    .js(source + '/resources/js/course.js', dist + '/js')

if (mix.inCourseion()) {
    mix
        .copy(dist + '/css/course.css', source + '/public/css')
        .copy(dist + '/js/course.js', source + '/public/js')
}
