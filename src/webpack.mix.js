const mix = require('laravel-mix');
const path = require('path');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const { BundleAnalyzerPlugin } = require('webpack-bundle-analyzer');

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

mix.babelConfig({
    plugins: ['@babel/plugin-syntax-dynamic-import'],
})
    .webpackConfig({
        output: {
            chunkFilename: 'js/chunks/[name].js',
        },
        plugins: [
            new CleanWebpackPlugin({
                cleanOnceBeforeBuildPatterns: [
                    path.resolve(process.cwd(), 'public/js'),
                ],
            }),
            new BundleAnalyzerPlugin({
                analyzerMode: 'static',
                reportFilename: './webpack-stats/report.html',
            }),
        ],
    })
    .js('resources/js/app.js', 'public/js')
    .sass('resources/sass/vendor.scss', 'public/css')
    .sass('resources/sass/app.scss', 'public/css')
    .version();
