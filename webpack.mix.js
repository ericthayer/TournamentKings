let mix = require('laravel-mix')
let exec = require('child_process').exec
let path = require('path')

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

mix.options({
    postCss: [
        require('autoprefixer')({
            browsers: ['last 2 versions', '> 1%', 'not ie < 10'],
            cascade: false,
        }),
    ],
    // TODO: This removes any unused CSS but it's breaking Bootstrap's Collapse :( - EricT
    // purifyCss: true,
})

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/main.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/app-rtl.scss', 'public/css')
    .sass('resources/sass/landing.scss', 'public/css')
    .sass('resources/sass/main.scss', 'public/css')
    .copy('node_modules/sweetalert/dist/sweetalert.min.js', 'public/js/sweetalert.min.js')
    .copy('resources/media', 'public/media')
    .copy('node_modules/bootstrap/dist/css/bootstrap.min.css', 'public/css')
    .then(() => {
        exec('node_modules/rtlcss/bin/rtlcss.js public/css/app-rtl.css ./public/css/app-rtl.css')
    })
    .version()
    .webpackConfig({
        resolve: {
            modules: [path.resolve(__dirname, 'vendor/laravel/spark-aurelius/resources/assets/js'), 'node_modules'],
            alias: {
                vue$: mix.inProduction() ? 'vue/dist/vue.min' : 'vue/dist/vue.js',
            },
        },
    })
