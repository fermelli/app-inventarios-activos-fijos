# Aplicacion de Inventarios para Casegural

Esta es una aplicacion de inventarios para Casegural.

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

### Instalacion de Herramientas de Desarrollo para el Frontend

Las herramientas de desarrollo para el frontend son las siguientes: ESLint, Prettier y Vue DevTools. Que permitira asegurar la calidad del codigo, formatear el codigo y depurar la aplicacion respectivamente.

1. Instalar el paquete `eslint` y otros paquetes relacionados con el comando `npm install -D eslint eslint-plugin-vue vite-plugin-eslint`. El paquete `eslint` es el linter para JavaScript, el paquete `eslint-plugin-vue` es el plugin para Vue.js y el paquete `vite-plugin-eslint` es el plugin para Vite.

2. Tambien se instala el paquete `prettier` con el comando `npm install -D prettier eslint-config-prettier eslint-plugin-prettier` que es el formateador de codigo para JavaScript. El paquete `eslint-config-prettier` es el plugin para ESLint y el paquete `eslint-plugin-prettier` es el plugin para ESLint que permite utilizar Prettier.

3. Crear el archivo `.eslintrc.json` que es el archivo de configuracion de ESLint y el archivo `.prettierrc.json` que es el archivo de configuracion de Prettier.

4. Configurar el plugin `vite-plugin-eslint` en el archivo `vite.config.js`.

5. Instalar la extension de Vue [Vue DevTools](https://devtools.vuejs.org/guide/installation.html) que permite depurar aplicaciones Vue.js.

6. Instalar las extensiones para el editor de codigo [ESLint](https://marketplace.visualstudio.com/items?itemName=dbaeumer.vscode-eslint) y [Prettier](https://marketplace.visualstudio.com/items?itemName=esbenp.prettier-vscode) que permite utilizar ESLint y Prettier en el editor de codigo. Y tambien instalar la extension [Volar](https://marketplace.visualstudio.com/items?itemName=vue.volar) que es una extension para Vue.js.

7. Se agregar los scripts `lint` y `format` en el archivo `package.json` que permite ejecutar ESLint y Prettier respectivamente. El script `lint` se ejecuta con el comando `npm run lint` y el script `format` se ejecuta con el comando `npm run format`.

### Layout de la Aplicacion y Componentes de Barra de Aplicacion y Menu de Navegacion

1. Crear el archivo `resources/js/src/components/BarraAplicacion.vue` que es el componente de la barra de aplicacion.

2. Crear el archivo `resources/js/src/components/MenuNavegacion.vue` que es el componente del menu de navegacion.

3. Establecer el layout de la aplicacion en el archivo `resources/js/src/views/App.vue`.

### Instalacion de Herramientas de Desarrollo para el Backend

Las herramientas de desarrollo para el backend son las siguientes: [Conjunto de reglas PHP Codesniffer para seguir el estilo de codificación de Laravel](https://github.com/mreduar/laravel-phpcs). Que permitira asegurar la calidad del codigo, analizar el codigo, analizar la calidad del codigo, realizar pruebas unitarias y depurar la aplicacion respectivamente.

1. Instalar el paquete `laravel/phpcs` con el comando `composer require mreduar/laravel-phpcs --dev` que es el linter para PHP. Aceptar la instalacion del plugin de PHP CodeSniffer.

2. Crear el archivo `phpcs.xml` que es el archivo de configuracion de PHP CodeSniffer y agregar las reglas que van a seguirse en el codigo.

3. Instalar las extensiones para el editor de codigo [PHP Extension Pack](https://marketplace.visualstudio.com/items?itemName=xdebug.php-pack) que contiene las extensiones [PHP Debug](https://marketplace.visualstudio.com/items?itemName=xdebug.php-debug) que permite depurar aplicaciones PHP y [PHP IntelliSense](https://marketplace.visualstudio.com/items?itemName=zobo.php-intellisense) que permite autocompletar codigo PHP. Ademas [PHP Intelephense](https://marketplace.visualstudio.com/items?itemName=bmewburn.vscode-intelephense-client) que es un servidor de lenguaje PHP para VS Code. Y tambien instalar la extension [PHP Sniffer](https://marketplace.visualstudio.com/items?itemName=wongjn.php-sniffer) que permite utilizar PHP CodeSniffer en el editor de codigo.

4. Puede ejecutar PHP CodeSniffer con el comando `vendor/bin/phpcs` para mostrar los errores de codigo y con el comando `vendor/bin/phpcbf` para corregir los errores de codigo.
