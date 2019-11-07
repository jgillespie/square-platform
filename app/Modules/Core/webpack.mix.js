const mix = require('laravel-mix');

require('laravel-mix-merge-manifest');

mix.setPublicPath('../../../public');

mix.js('Resources/Assets/js/core.js', 'modules/js')
    .sass('Resources/Assets/sass/core.scss', 'modules/css')
    .mergeManifest();

if (mix.inProduction()) {
    mix.version();
}
