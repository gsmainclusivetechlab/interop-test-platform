const mix = require('laravel-mix');
const path = require('path');

mix.version();
mix.sourceMaps();
mix.setPublicPath('public/assets');
mix.js('resources/js/app.js', 'js');
mix.sass('resources/sass/app.scss', 'css');
mix.webpackConfig({
    output: {
        chunkFilename: 'js/chunks/[name].js?id=[chunkhash]',
        publicPath: '/assets/'
    },
    resolve: {
        alias: {
            '@': path.resolve('resources/js')
        }
    }
});
mix.copyDirectory('resources/fonts', 'public/assets/fonts');
mix.copyDirectory('resources/images', 'public/assets/images');
