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

### Autenticacion de Usuarios del Backend

#### Instalacion de Laravel Sanctum

1. Instalar el paquete `laravel/sanctum` con el comando `composer require laravel/sanctum` que es el paquete que permite autenticar usuarios de un SPA.

2. Ejecutar el comando `php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"` que permite publicar los archivos de configuracion de Sanctum como el archivo `config/sanctum.php` y el archivo de migracion `database/migrations/2019_12_14_000001_create_personal_access_tokens_table.php`.

3. Agregar la llave `SANCTUM_STATEFUL_DOMAINS` en el archivo `.env` que es la lista de dominios que se consideran seguros para autenticar usuarios y que se separan por comas y se agregan sin espacios. Tambien se debe agregar la llave `SESSION_DOMAIN` que es el dominio de la sesion.

4. Agregar el middleware `EnsureFrontendRequestsAreStateful` en el archivo `app/Http/Kernel.php` en la seccion `api` que permite que las peticiones de la aplicacion frontend sean seguras.

5. Configurar los datos de conexion a la base de datos (se debe crear la base de datos) en el archivo `.env` de la siguiente manera:

```bash
...

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=app_inventarios_casegural
DB_USERNAME=root
DB_PASSWORD=

...
```

6. Ejecutar el comando `php artisan migrate` que permite ejecutar las migraciones, de esta manera se crean las tablas de la base de datos:

```bash
$ php artisan migrate

INFO  Preparing database.

Creating migration table ............................................................................................................... 40ms DONE

INFO  Running migrations.

2014_10_12_000000_create_users_table .................................................................................................. 240ms DONE
2014_10_12_100000_create_password_reset_tokens_table ................................................................................... 22ms DONE
2019_08_19_000000_create_failed_jobs_table ............................................................................................ 135ms DONE
2019_12_14_000001_create_personal_access_tokens_table ................................................................................. 151ms DONE
```

#### Instalacion de Laravel Fortify

1. Instalar el paquete `laravel/fortify` con el comando `composer require laravel/fortify` que es el paquete que implementa la autenticacion de usuarios.

2. Ejecutar el comando `php artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"` que permite publicar los archivos de configuracion de Fortify como el archivo `config/fortify.php` y los archivos de acciones de autenticacion que se encuentran en la carpeta `app/Actions/Fortify`.

3. Agregar el `App\Providers\FortifyServiceProvider::class,` en el archivo `config/app.php` en la seccion `providers` que permite registrar el proveedor de servicios de Fortify.

4. Ejecutar el comando `php artisan migrate` que permite ejecutar las migraciones, de esta manera se crean las tablas de la base de datos:

```bash
$ php artisan migrate

INFO  Running migrations.

2014_10_12_200000_add_two_factor_columns_to_users_table ................................................................................ 26ms DONE
```

5. En las configuraciones de Fortify en el archivo `config/fortify.php` se debe configurar:

-   El prefijo en este caso `prefix => 'api'` para las rutas de autenticacion
-   El middleware en este caso `middleware => ['api']` para las rutas de autenticacion
-   Establecer los limites de intentos de inicio de sesion `limiters => ['login' => login]` que por defecto es de 5 intentos en 1 minuto.
-   Deshabilitar las rutas de vistas `views => false` ya que no se van a utilizar.
-   Habilitar o deshabilitar las caracteristicas de autenticacion en la llave `features`.

6. Modificar los middlewares en la carpeta `app/Http/Middleware` los archivos `Authenticate.php` y `RedirectIfAuthenticated.php`.

7. Modificar el archivo `app/Providers/FortifyServiceProvider.php` para quitar los limites de intentos de inicio de sesion y de dos factores.

8. Cambiar la ruta `Home` en el archivo `app/Providers/RouteServiceProvider.php` para que apunte a la ruta `inicio` osea `public const HOME = '/';` y tambien en el archivo `config/fortify.php` en la llave `home`.

9. En el archivo `config/cors.php` cambiar a verdadero `'supports_credentials' => true,`.

10. En `routes/api.php` proteger las rutas con el middleware `auth:sanctum`.

11. Agregar el archivo de `Postman` que se encuentra en la carpeta raiz del proyecto ([App Inventarios Casegural.postman_collection.json](App%20Inventarios%20Casegural.postman_collection.json)) que contiene las rutas de autenticacion.

#### Personalizacion de los Campos de la Tabla de usuarios (antes users) y del Modelo de Usuario (User)

1. Crear y ejecutar las migraciones para:

-   Cambiar el nombre de la tabla de usuarios de `users` a `usuarios`.
-   Cambiar el nombre las columnas de la tabla de usuarios de `name` a `nombre`, `email` a `correo_electronico`, `created_at` a `creado_en` y `updated_at` a `actualizado_en`.

2. Cambios en el modelo de usuario `User`:

-   Agregar el atributo `$table = 'usuarios'` que es el nombre de la tabla de usuarios.
-   Cambiar el atributo `$fillable` que son los campos que se pueden asignar masivamente de `['name', 'email', 'password',]` a `['nombre', 'correo_electronico', 'password',]`.
-   Agregar las constantes `CREATED_AT = 'creado_en'` y `UPDATED_AT = 'actualizado_en'` que son los nombres de las columnas de fecha de creacion y actualizacion respectivamente.

3. Modificar el archivo `config/fortify.php` en la llave `email` para que sea `correo_electronico` y en la llave `username` para que sea `correo_electronico`.

4. Modificar la accion `CreateNewUser.php` en la carpeta `app/Actions/Fortify` para que se cree el usuario con el campo `nombre` en vez de `name` y `correo_electronico` en vez de `email`.
