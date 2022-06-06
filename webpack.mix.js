const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.styles([
    'public/css/app.css',
    'public/css/argon.min.css',
    'public/css/style.css',],
    'public/css/all.css')
.js([
    'public/js/script.js',
    'resources/js/app.js',],
    'public/js/all-1.2.1.js')
.browserSync('http://hrportalv2new.local/');
