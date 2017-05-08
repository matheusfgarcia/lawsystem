const { mix } = require('laravel-mix');

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

mix.js('resources/assets/js/app.js', 'public/js')
    .js('resources/assets/js/clients.js', 'public/js')
    .js('resources/assets/js/advocates.js', 'public/js')
    .js('resources/assets/js/process.js', 'public/js')
    .js('resources/assets/js/datatables.js', 'public/js')
    .js('resources/assets/js/libs/respond.min.js', 'public/js/lib')
    .js('resources/assets/js/libs/html5shiv.min.js', 'public/js/lib')
    .js('resources/assets/js/libs/bootstrap.min.js', 'public/js/lib')
    .js('resources/assets/js/libs/jquery.min.js', 'public/js/lib')

    .js('resources/assets/frameworks/inputmask/jquery.inputmask.bundle.js', 'public/frameworks/inputmask')

    .copy('resources/assets/frameworks/bootstrap-select/bootstrap-select.min.css', 'public/frameworks/bootstrap-select')
    .js('resources/assets/frameworks/bootstrap-select/bootstrap-select.min.js', 'public/frameworks/bootstrap-select')

   .less('resources/assets/less/app.less', 'public/css')
   .less('resources/assets/less/clients.less', 'public/css');


