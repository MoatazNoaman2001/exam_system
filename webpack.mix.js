// webpack.mix.js - Laravel Mix configuration

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

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   
   // Register page specific assets
   .sass('resources/sass/register.scss', 'public/css')
   .js('resources/js/register.js', 'public/js')
   
   // Other page assets can be added here
   // .sass('resources/sass/login.scss', 'public/css')
   // .js('resources/js/login.js', 'public/js')
   
   .options({
       processCssUrls: false
   });

// Enable versioning in production
if (mix.inProduction()) {
    mix.version();
}