const mix = require('laravel-mix');

mix.options({
    fileLoaderDirs: {
        fonts: './assets/css/fonts'
    }
});

mix
    .copy('resources/assets/images/**/*', 'public/assets/images/')
    .copy('resources/assets/js/vue-chartjs.min.js', 'public/assets/js/vendor/')
    .copy('resources/assets/js/datatable-ext/**/*', 'public/assets/plugins/DataTables/extensions/export/')
    .js('resources/assets/js/app.js', 'public/assets/js')
    .postCss('resources/assets/css/fonts.css', 'assets/css')
    .sass('resources/assets/scss/app.scss', 'public/assets/css')
;
