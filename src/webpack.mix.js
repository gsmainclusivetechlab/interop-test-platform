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
                '@locales': path.resolve('resources/locales'),
            },
        },
    })
    .extend(
        'i18n',
        new (class {
            webpackRules() {
                return [
                    {
                        resourceQuery: /blockType=i18n/,
                        type: 'javascript/auto',
                        loader: '@kazupon/vue-i18n-loader',
                    },
                    {
                        test: /\.yml$/,
                        type: 'json',
                        use: 'yaml-loader',
                    },
                ];
            }
        })()
    )
    .i18n()
    .js('resources/js/app.js', 'js')
    .sass('resources/sass/app.scss', 'css')
    .sourceMaps(!mix.inProduction(), 'source-map')
    .copyDirectory('resources/fonts', 'public/assets/fonts')
    .copyDirectory('resources/tutorial', 'public/assets/tutorial')
    .copyDirectory('resources/images', 'public/assets/images')
    .copyDirectory('resources/locales', 'public/assets/locales')
    .copy(
        'node_modules/@tabler/icons/tabler-sprite.svg',
        'public/assets/images/icons.svg'
    );
