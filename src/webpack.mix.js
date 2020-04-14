const mix = require('laravel-mix');

mix.version();
mix.sourceMaps();
mix.setPublicPath('public/assets');
mix.js('resources/js/app.js', 'js');
mix.sass('resources/sass/app.scss', 'css');
mix.webpackConfig({
    output: {
        chunkFilename: 'js/chunks/[name].js?id=[chunkhash]',
        publicPath: '/assets/',
    },
});
