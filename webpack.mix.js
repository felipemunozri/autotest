const mix = require('laravel-mix');
var LiveReloadPlugin = require('webpack-livereload-plugin');

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
 mix.webpackConfig({
    resolve: {
      extensions: ['.js', '.vue', '.json'],
      alias: {
        'vue$': 'vue/dist/vue.esm.js',
        '@': __dirname + '/resources/js'
      },
    },
    plugins: [new LiveReloadPlugin()]
  });

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/embeddables/events/latestEvents.js', 'public/js/embeddables/events')
    .js('resources/js/embeddables/events/eventsPerDay.js', 'public/js/embeddables/events')
    .js('resources/js/embeddables/events/eventsPerHour.js', 'public/js/embeddables/events')
    .js('resources/js/embeddables/events/eventsPerModel.js', 'public/js/embeddables/events')
    .js('resources/js/embeddables/events/eventsStatusResult.js', 'public/js/embeddables/events')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/print.scss', 'public/css');
