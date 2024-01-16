# Aplicacion de Inventarios para Casegural

## Instrucciones

### Inicio del proyecto

1. Generacion del proyecto de Laravel `composer create-project laravel/laravel app-inventarios-casegural`
   El comando anterior genera un proyecto de Laravel en la carpeta app-inventarios-casegural y se instalan las dependencias de Laravel, que se encuentran en el archivo composer.json las cuales se instalan con el comando `composer install`.

2. Se instala las dependencias de lado del cliente con el comando `npm install`.

3. Copiar el archivo `.env.example` a `.env` y agregar cambiar la configuracion de `APP_URL=http://localhost:8000`

4. Generar la llave de la aplicacion con el comando `php artisan key:generate`

5. Servir la aplicacion con el comando `php artisan serve` que permite acceder a la aplicacion en la direccion `http://localhost:8000`

6. Para compilar los archivos en modo desarrollo se utiliza el comando `npm run dev` que permite que cualquier cambio en los archivos de la carpeta `resources/js` y `resources/css` se compilen automaticamente. Se expone el puerto de escucha de vite en el puerto `5173` y se puede acceder a la aplicacion en la direccion `http://localhost:5173`.

### Instalacion de Vue

Para realizar el empaquetado de los archivos JavaScript y CSS, Laravel 10 utiliza `vite`.

1. Installar el plugin para `vite`, con el comando `npm install -D @vitejs/plugin-vue` que permite utilizar Vue en el proyecto.

2. Instalar el paquete `vue` con el comando `npm install vue`.

3. Generar el archivo `resources/js/main.js` que es el punto de entrada de la aplicacion.

4. Configurar vite en el archivo `vite.config.js` que se encuentra en la raiz del proyecto.

5. Crear el archivo `resources/views/app.blade.php` que es la vista principal de la aplicacion y cambiar la ruta web en el archivo `routes/web.php` para que apunte a la vista principal.

6. Agregar el archivo `resources/js/src/main.js` en el archivo `resources/views/app.blade.php` con el helper de vite de la siguiente manera ` @vite('resources/js/src/main.js')`.

7. Arrancar el servidor de laravel con el comando `php artisan serve` y el servidor de vite con el comando `npm run dev`.

8. Para que las peticiones se redirijan a la vista principal en el archivo `routes/web.php` se agrega la ruta: `Route::view('/{any}', 'app')->where('any', '.*');` para que se redirija a la vista principal.
