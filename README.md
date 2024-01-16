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
