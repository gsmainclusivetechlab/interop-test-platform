const mix = require('laravel-mix');
const path = require('path');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const { BundleAnalyzerPlugin } = require('webpack-bundle-analyzer');

mix.setPublicPath('public/assets');
mix.babelConfig({
    plugins: ['@babel/plugin-syntax-dynamic-import'],
})
    .webpackConfig({
        output: {
            chunkFilename: 'js/chunks/[name].js',
        },
        plugins: [
            new CleanWebpackPlugin({
                cleanOnceBeforeBuildPatterns: ['public/assets/**/*', '!.gitignore'],
            }),
            new BundleAnalyzerPlugin({
                analyzerMode: 'static',
                reportFilename: 'webpack-stats.html',
            }),
        ],
    })
    .js('resources/js/app.js', 'js')
    .sass('resources/sass/vendor.scss', 'css')
    .sass('resources/sass/app.scss', 'css')
    .version();
