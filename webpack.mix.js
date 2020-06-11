const mix = require('laravel-mix');
const path = require('path');

mix.version();
mix.sourceMaps();
mix.setPublicPath('src/public/assets');
mix.js('src/resources/js/app.js', 'js');
mix.sass('src/resources/sass/app.scss', 'css');
mix.webpackConfig({
    output: {
        chunkFilename: 'js/chunks/[name].js?id=[chunkhash]',
        publicPath: '/assets/'
    },
    resolve: {
        alias: {
            '@': path.resolve('src/resources/js')
        }
    }
});
mix.copyDirectory('src/resources/fonts', 'src/public/assets/fonts');
mix.copyDirectory('src/resources/images', 'src/public/assets/images');
mix.copy(
    'node_modules/tabler-icons/tabler-sprite.svg',
    'src/public/assets/images/icons.svg'
);
