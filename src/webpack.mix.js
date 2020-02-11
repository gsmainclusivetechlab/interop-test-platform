const mix = require('laravel-mix');
const path = require('path');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');

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
    output: {
        chunkFilename: 'js/chunks/[name].chunk.[chunkhash].js',
    },
    plugins: [
        new CleanWebpackPlugin({
            cleanOnceBeforeBuildPatterns: [
                path.resolve(process.cwd(), 'public/js'),
            ],
        }),
    ],
})
    .js('resources/js/app.js', 'public/js')
    .sass('resources/sass/vendor.scss', 'public/css')
    .sass('resources/sass/app.scss', 'public/css')
    .version();
