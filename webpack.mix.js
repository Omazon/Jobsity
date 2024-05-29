let mix = require('laravel-mix');
require('mix-tailwindcss')


mix.setPublicPath('core/includes/assets/');
mix.js('core/includes/assets/src/main.js','js/frontend-scripts.js').vue();
mix.sass('core/includes/assets/src/main.scss','css/frontend-styles.css').tailwind();