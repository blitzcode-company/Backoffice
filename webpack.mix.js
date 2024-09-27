const mix = require('laravel-mix');
const path = require('path'); // Importa path para las rutas
require('laravel-mix-purgecss'); // Importa el plugin PurgeCSS

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/main.scss', 'public/css')
   .purgeCss({
       enabled: mix.inProduction(), // Activa PurgeCSS solo en producción
       paths: [
           path.join(__dirname, 'resources/**/*.blade.php'),
           path.join(__dirname, 'resources/js/**/*.js'),
           path.join(__dirname, 'resources/sass/**/*.scss'),
       ],
   });
