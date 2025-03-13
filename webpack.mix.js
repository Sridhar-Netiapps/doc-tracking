const mix = require('laravel-mix');

mix.js('resources/js/validation.js', 'public/js')
   .postCss('resources/css/app.css', 'public/css', [
      require('postcss-import'),
      require('tailwindcss'),
      require('autoprefixer'),
   ]);
