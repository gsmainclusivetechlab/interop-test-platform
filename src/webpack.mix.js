const mix = require('laravel-mix');
const path = require('path');

if (mix.inProduction()) {
    mix.version();
}

mix.setPublicPath('public/assets')
    .webpackConfig({
        output: {
            chunkFilename: 'js/chunks/[name].js?id=[chunkhash]',
            publicPath: '/assets/',
        },
        resolve: {
            alias: {
                '@': path.resolve('resources/js'),
            },
        },
    })
    .js('resources/js/app.js', 'js')
    .sass('resources/sass/app.scss', 'css')
    .sourceMaps(!mix.inProduction(), 'source-map')
    .copyDirectory('resources/fonts', 'public/assets/fonts')
    .copyDirectory('resources/images', 'public/assets/images')
    .copy(
        'node_modules/tabler-icons/tabler-sprite.svg',
        'public/assets/images/icons.svg'
    );
