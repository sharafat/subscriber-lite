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

mix.js('resources/js/app.js', 'public/js')
    .vue()
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
        require('autoprefixer'),
    ])
    .styles([
        'node_modules/@fortawesome/fontawesome-free/css/all.css',
        'node_modules/@jobinsjp/vue3-datatable/dist/style.css',
        'node_modules/ladda/dist/ladda-themeless.min.css',
    ], 'public/css/vendor.css')
    .copy('node_modules/@fortawesome/fontawesome-free/webfonts', 'public/webfonts')
    .copy('resources/images', 'public/images')
    .copy('resources/images/favicon', 'public/images/favicon')
    .version()
    .browserSync({
        proxy: 'localhost',
        open: false,
    });
