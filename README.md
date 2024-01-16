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

### Instalacion de Vue Router

Vue Router es el enrutador oficial para Vue.js. Permite crear una aplicacion de una sola pagina con multiples componentes.

1. Instalar el paquete `vue-router` con el comando `npm install vue-router`.

2. Crear el archivo `resources/js/src/router/index.js` que es el archivo de configuracion de Vue Router.

3. Crear los archivos `resources/js/src/views/InicioVista.vue`, `resources/js/src/views/SobreNosotrosVista.vue` y `resources/js/src/views/ContactoDesarrolladorVista.vue` que son los componentes que se van a renderizar en las rutas.

4. Crear el archivo `resources/js/src/router/routes.js` que es el archivo donde se definen las rutas de la aplicacion.

5. Usar `vue-router` en el archivo `resources/js/src/main.js` con el siguiente codigo: `app.use(router)`.

6. En el archivo `App.vue` se agrega el componente `router-view` que es el componente que se encarga de renderizar los componentes de las rutas. Tambien se agrega el componente `router-link` que es el componente que se encarga de renderizar los links de las rutas.

### Instalacion de Vuetify

Vuetify es un framework de componentes para Vue.js 3.0 que permite crear aplicaciones rapidamente.

1. Instalar el paquete `vuetify` con el comando `npm install vuetify` y el plugin `vite-plugin-vuetify` con el comando `npm install vite-plugin-vuetify`, que permite utilizar Vuetify con Vite.

2. Configurar el plugin `vite-plugin-vuetify` en el archivo `vite.config.js`.

3. Crear el archivo `resources/js/src/vuetify.js` que es el archivo de configuracion de Vuetify.

4. Usar `vuetify` en el archivo `resources/js/src/main.js` con el siguiente codigo: `app.use(vuetify)`. Se observara que los estilos de Vuetify se aplican a la aplicacion.

5. En el archivo `App.vue` se agrega el componente `v-app` que es el componente que se encarga de renderizar la aplicacion.
