const mix = require('laravel-mix');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const { BundleAnalyzerPlugin } = require('webpack-bundle-analyzer');

mix.autoload({
    jquery: ['$', 'jQuery', 'window.jQuery'],
});
mix.setPublicPath('public/assets');
mix.babelConfig({
    plugins: ['@babel/plugin-syntax-dynamic-import'],
})
    .webpackConfig({
        output: {
            chunkFilename: 'js/chunks/[name].js',
            publicPath: '/assets/',
        },
        plugins: [
            new CleanWebpackPlugin({
                cleanOnceBeforeBuildPatterns: ['**/*', '!.gitignore'],
            }),
            new BundleAnalyzerPlugin({
                analyzerMode: 'static',
                reportFilename: 'webpack-stats.html',
            }),
        ],
    })
    .js('resources/js/app.js', 'js')
    .js([
        'resources/js/tutorials/tutorials-service-provider-demo.js'
    ], './js/tutorials.js')
    .sass('resources/sass/vendor.scss', 'css')
    .sass('resources/sass/app.scss', 'css')
    .copyDirectory('resources/fonts', 'public/assets/fonts')
    .copyDirectory(
        'node_modules/tabler-ui/dist/assets/fonts',
        'public/assets/fonts',
    )
    .options({
        processCssUrls: false,
    })
    .version();
