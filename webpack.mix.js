const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.options({
    terser: {
        extractComments: false,
    }
});

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/photography.js', 'public/js')
    .js('resources/js/outreach.js', 'public/js')
    .js('resources/js/publications.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');

mix.combine([
    'resources/js/flickr/gallery.css',
    'resources/js/jquery.bxslider/jquery.bxslider.css'
], 'public/css/plugins.css');

if (mix.inProduction()) {
    mix.version();
}
