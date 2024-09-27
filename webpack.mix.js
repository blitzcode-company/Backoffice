const mix = require('laravel-mix');
require('laravel-mix-purgecss'); 

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/main.scss', 'public/css')
   .purgeCss({
       enabled: mix.inProduction(),
       content: [
           './resources/**/*.blade.php',
           './resources/**/*.js',        
           './resources/**/*.vue',      
           './resources/**/*.html'      
       ],
   });
