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

mix.js('resources/js/app.js', 'public/app/js')
    // .sass('resources/sass/app.scss', 'public/app/css')
    .css('resources/css/app.css', 'public/app/css')
    .sourceMaps();

// "@fortawesome/fontawesome-free": "^6.5.1",
mix.copyDirectory('node_modules/@fortawesome/fontawesome-free/css', 'public/vendor/@fortawesome/fontawesome-free/6.5.1/css');
mix.copyDirectory('node_modules/@fortawesome/fontawesome-free/js', 'public/vendor/@fortawesome/fontawesome-free/6.5.1/js');
mix.copyDirectory('node_modules/@fortawesome/fontawesome-free/webfonts', 'public/vendor/@fortawesome/fontawesome-free/6.5.1/webfonts');
// "axios": "^0.21.4",
mix.copyDirectory('node_modules/axios/dist', 'public/vendor/axios/0.21.4');
// "bootstrap": "^4.6.2",
mix.copyDirectory('node_modules/bootstrap/dist', 'public/vendor/bootstrap/4.6.2');
// "bootstrap-icons": "^1.11.3",
mix.copyDirectory('node_modules/bootstrap-icons/font', 'public/vendor/bootstrap-icons/1.11.3');
// "jquery": "^3.7.1",
mix.copyDirectory('node_modules/jquery/dist', 'public/vendor/jquery/3.7.1');
// "lodash": "^4.17.21",
mix.copy('node_modules/lodash/lodash.js', 'public/vendor/lodash/4.17.21/lodash.js');
mix.copy('node_modules/lodash/lodash.min.js', 'public/vendor/lodash/4.17.21/lodash.min.js');
// "normalize.css": "^8.0.1",
mix.copy('node_modules/normalize.css/normalize.css', 'public/vendor/normalize.css/8.0.1/normalize.css');
// "popper.js": "^1.16.1",
mix.copyDirectory('node_modules/popper.js/dist/umd', 'public/vendor/popper.js/1.16.1');
