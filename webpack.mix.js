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

let scripts = [
	'jquery',
	'axios',
	'jquery-ui',
	'popper.js',
	'vue',
	'toastr',
	'socket.io-client',
	'laravel-echo'
];

let autoload = [
	{'jquery': ['$', 'window.jQuery',"jQuery","window.$","jquery","window.jquery"], 'jQuery': 'jquery'},
];

mix.js('resources/js/app.js', 'public/mix/js/app.js').vue({version:2});
mix.js(['node_modules/bootstrap/dist/js/bootstrap.bundle.min.js'], 'public/mix/js/bootstrap.bundle.min.js').sourceMaps();
mix.js(['node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js'], 'public/mix/js/dataTables.bootstrap4.min.js');
mix.copy(['node_modules/summernote/dist/summernote.min.js'], 'public/mix/js/summernote.min.js');
mix.js(['resources/js/vueBoard.js'], 'public/mix/js/vueBoard.js');
mix.js(['resources/js/axiosOption.js'], 'public/mix/js/axiosOption.js');
mix.sass('resources/sass/app.scss', 'public/mix/css/app.css');
mix.sass('node_modules/toastr/toastr.scss', 'public/mix/css/toastr.css');
mix.copy('node_modules/summernote/dist/summernote.min.css', 'public/mix/css/summernote.min.css');
mix.copyDirectory('node_modules/summernote/dist/font', 'public/mix/css/font');
mix.copy('resources/sass/main.css', 'public/mix/css/main.css');
mix.copy('node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css', 'public/mix/css/dataTables.bootstrap4.min.css');
mix.extract(scripts, 'public/mix/js/vendor').sourceMaps();
mix.autoload(autoload);
