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

### Agregado Layouts para App y en Blanco

1. Crear el archivo `resources/js/src/layouts/AppLayout.vue` que es el layout de la aplicacion y el archivo `resources/js/src/layouts/BlankLayout.vue` que es el layout en blanco.

2. Definir las rutas en el archivo `resources/js/src/router/routes.js` para que utilicen los layouts en el la propiedad `meta` de la siguiente manera:

```js
{
    path: '/',
    name: 'inicio',
    component: InicioVista,
    meta: {
        layout: 'app-layout',
    },
},
```

3. Refactorizacion de `App.vue` para la utilizacion de los layouts.

### Instalacion y configuracion de Vuex

`Vuex` es un patron de administracion de estado + biblioteca para aplicaciones Vue.js. Sirve como un almacen centralizado para todos los componentes de una aplicacion, con reglas que aseguran que el estado solo pueda mutarse de manera predecible.

1. Instalar el paquete `vuex` con el comando `npm install vuex` que es el paquete que permite manejar el estado de la aplicacion.

2. Crear el archivo `resources/js/src/store/index.js` que es el archivo de configuracion de Vuex.

3. Crear el modulo `resources/js/src/store/modules/autenticacion.js` que es el modulo de autenticacion, que por ahora realiza la autenticacion falsa de usuarios.

4. Usar `vuex` en el archivo `resources/js/src/main.js` con el siguiente codigo: `app.use(store)`.

### Login de Usuarios del Frontend

1. Crear el archivo `resources/js/src/views/LoginVista.vue` que es la vista de login donde se encuentra el formulario de login con los campos `correo_electronico` y `password` que son validados para que sean requeridos y que el campo `correo_electronico` sea un correo electronico valido.

2. Se agrega la ruta `login` en el archivo `resources/js/src/router/routes.js` que apunta a la vista de login. Y se agrega la ruta de `no-encontrado` que muestra la vista de `NoEncontradoVista.vue` cuando no se encuentra la ruta.

3. Se agrega en la definicion de las rutas en el archivo `resources/js/src/router/routes.js` la propiedad `meta` la llave `requiresAuth` que es un booleano que indica si la ruta requiere autenticacion o no.

4. Se agregar variables de entorno en el archivo `.env` que son `VITE_API_URL` que es la url del backend y `VITE_APP_URL` que es la url del frontend.

5. Se agrega la instancia principal de axios en el archivo `resources/js/src/services/index.js` que es la que se utiliza para realizar las peticiones al backend, donde se configura la `baseURL`, `WithCredentials` y `withXSRFToken` y las cabeceras `Accept`, `Content-Type` y `X-Requested-With` que se envian en las peticiones.

6. Se crear el servicio para la autenticacion en `resources/js/src/services/autenticacion.js` que donde esta la funcion `login`, `usuario-autenticado` y `logout` que son las funciones que se utilizan para realizar la autenticacion de usuarios.

7. Se implementa las `actions` para recuperar el usuario autenticado y el logout en la store la `autenticacion` de usuarios en `resources/js/src/store/modules/autenticacion.js`.

8. Se crea un interceptor de respuesta de axios en el archivo `resources/js/src/services/index.js` que es el que se encarga de verificar si el usuario esta autenticado osea que no haya errores con codigo `401` y `419` si es asi procede a realizar el logout del usuario.

9. Se crea un guard de navegacion en el archivo `resources/js/src/router/index.js` que comprueba antes de acceder a una ruta si el usuario esta autenticado, si no lo esta redirige a la ruta de `login`.

10. Se implementa el `login` en el componente `LoginVista.vue` y se redirige a la ruta de `inicio` si el usuario se autentica correctamente.

11. Se implementa el `logout` en el componente `BarraAplicacion.vue` y se muestra el nombre del usuario autenticado en el componente.

### Notificaciones: Instalacion de Vue Toastification

1. Instalar el paquete `vue-toastification` con el comando `npm install vue-toastification` que es el paquete que permite mostrar notificaciones en la aplicacion.

2. Configurar la utilizacion de `vue-toastification` en el archivo `resources/js/src/main.js`, como plugin de Vue.

3. Agregar notificaciones en el interceptor de respuesta de axios en el archivo `resources/js/src/services/index.js` para errores de la aplicacion.

### Agregar Roles de Usuarios

1. Crear la migracion para agregar el campo `rol` a la tabla de usuarios, con los roles `administrador` y `personal` y el campo `rol` por defecto `personal` y ejecutar la migracion.

2. Modificar el modelo de usuario `User` para agregar el campo `rol` que es el rol del usuario.

3. En la definicion de las rutas en el archivo `resources/js/src/router/routes.js` se agrega la propiedad `meta` la llave `rolesAutorizados` que es un array de strings que contiene los roles autorizados para acceder a la ruta.

4. Modificar el guard de navegacion en el archivo `resources/js/src/router/index.js` para que compruebe si el usuario esta autorizado para acceder a la ruta dependiendo de su rol.

5. Agregar el modulo `rutasMenuNavegacion` en el archivo `resources/js/src/store/modules/rutasMenuNavegacion.js` que es el modulo que contiene las rutas del menu de navegacion y rutas autorizadas (`rutasPermitidasPorRol`) para cada rol.

6. Modificar el componente `MenuNavegacion.vue` para que muestre las rutas del menu de navegacion dependiendo del rol del usuario.

7. Agregar las vistas:

-   `NoAutorizadoVista.vue` que es la vista que se muestra cuando el usuario no esta autorizado para acceder a la ruta.
-   `DashboardVista.vue` que es la vista del dashboard de la aplicacion, que se muestra cuando el usuario esta autorizado con el rol `administrador`.

## Iteraciones

### Iteración 1: Desarrollar un modulo de gestion para el ingreso y salida del material del almacen

#### Historia de Usuarios

##### Historia de Usuario 01: Gestionar Categorias

|                         |                                                                                              |
| ----------------------- | -------------------------------------------------------------------------------------------- |
| Numero                  | 01                                                                                           |
| Nombre                  | Gestionar Categorias                                                                         |
| Usuario                 | Administrador                                                                                |
| Descripción             | Como administrador quiero gestionar categorias para la correcta organizacion del almacen     |
| Criterios de Aceptación | - Poder listar categorias con su categoria padre                                             |
|                         | - Poder listar categorias con sus categorias hijas                                           |
|                         | - Poder mostrar una categoria especifica con su categoria padre y sus categorias hijas       |
|                         | - Poder crear categorias con los campos: nombre y que pueda pertenecer a una categoria padre |
|                         | - Poder editar categorias                                                                    |
|                         | - Poder desactivar y activar categorias                                                      |

###### Tareas de la Historia de Usuario 01

-   Tareas del Backend

    -   [x] Crear la migracion para crear la tabla de `categorias` con el comando `php artisan make:migration crear_tabla_categorias --create=categorias` que contiene los campos:
        -   `id`, de tipo `biginteger`, autoincrementable (`auto_increment`), clave primaria (`primary_key`).
        -   `nombre`, de tipo `varchar`, longitud `100` y campo `unique` que es el nombre de la categoria.
        -   `eliminado_en`, de tipo `timestamp` que es la fecha de `desactivacion` de la categoria.
        -   `categoria_padre_id`, de tipo `biginteger` y `unsigned` que es la categoria padre de la categoria.
        -   Y establecer las relaciones de la tabla de `categorias` con la misma tabla de `categorias` para la relacion de `categoria_padre_id` con `id`.
        -   Ejecutar la migracion con el comando `php artisan migrate`.
    -   [x] Crear el `seeder` para rellenar la tabla `categorias` con el comando `php artisan make:seeder CategoriaSeeder` y ejecutar el seeder con el comando `php artisan db:seed --class=CategoriaSeeder`.
    -   [x] Crear el modelo de `Categoria` con el comando `php artisan make:model Categoria`:
        -   establecer el nombre de la tabla con el atributo `$table` que es `categorias`.
        -   establecer los campos que se pueden asignar masivamente con la propiedad `$fillable` que son `nombre` y `categoria_padre_id`
        -   quitamos los campos `created_at` y `updated_at` con el atributo `$timestamps` a `false`.
        -   establecer la relacion de la tabla de `categorias` con la misma tabla de `categorias` para la relacion de `categoria_padre_id` con `id`.
        -   configurar el campo `eliminado_en` como una fecha de tipo `timestamp` con el atributo `$dates`.
    -   [x] Crear los `FormRequest` para la validacion de los campos para crear `CrearCategoriaRequest` y para actualizar `ActualizarCategoriaRequest`, con los comandos `php artisan make:request CrearCategoriaRequest` y `php artisan make:request ActualizarCategoriaRequest` respectivamente y crear las reglas de validacion para los campos.
        -   creamos un regla de validacion personalizada para validar que la categoria padre este activa con el comando `php artisan make:rule CategoriaPadreActivaRule`.
    -   [x] Crear el controlador de `CategoriaController` relacionado con el modelo `Categoria` con el comando `php artisan make:controller CategoriaController --api --model=Categoria` que contiene los metodos:
        -   `index`, que `lista` las categorias.
        -   `store`, que `crea` una categoria utilizando el `CrearCategoriaRequest` para validar los campos.
        -   `show`, que `muestra` una categoria especifica por su `id`.
        -   `update`, que `actualiza` una categoria utilizando el `ActualizarCategoriaRequest` para validar los campos.
        -   `destroy`, que `elimina` una categoria de la base de datos por su `id`.
        -   `reactivar`, que `activa` una categoria que fue `desactivada` (eliminada de manera logica), por su `id`, osea establece la fecha de desactivacion (`eliminado_en`) a `null`.
        -   `desactivar`, que `desactiva` una categoria (eliminacion logica), por su `id`, osea establece la fecha de eliminacion (`eliminado_en`) a la fecha actual.
    -   [x] Agregar las rutas de `categorias` en `routes/api.php` para todos los metodos del controlador de `CategoriaController` con la linea `Route::apiResource('categorias', CategoriaController::class);` que creara las rutas (que puede visualizar las rutas con el comando `php artisan route:list`):
        -   `GET /categorias` que lista las categorias.
        -   `POST /categorias` que crea una categoria.
        -   `GET /categorias/{categoria}` que muestra una categoria especifica.
        -   `PUT/PATCH /categorias/{categoria}` que actualiza una categoria especifica.
        -   `DELETE /categorias/{categoria}` que elimina una categoria especifica.
    -   [x] Agregue las rutas para los metodos `indexPadresConHijas`, `softDestroy` y `restore` del controlador `CategoriaController`:
        -   `GET /categorias/padres-con-hijas` que lista las categorias padres con sus categorias hijas.
        -   `PUT/PATCH /categorias/{categoria}/desactivar` que `desactiva` (elimina de manera logica) una categoria especifica.
        -   `PUT/PATCH /categorias/{categoria}/activar` que `activa` (restaura) una categoria especifica.
    -   [x] Crear la coleccion de postman y probar los endpoints de `categorias`.
    -   [x] Crear un Gate para la autorizacion del rol `administrador` en el archivo `app/Providers/AuthServiceProvider.php` en el metodo `boot`, y utilizarlo en la definicion de rutas en el archivo `routes/api.php` para los metodos del controlador de `CategoriaController` que son `store`, `update`, `destroy`, `softDestroy` y `restore`.
    -   [x] Volver a probar los endpoints de `categorias` con la coleccion de postman y verificar que solo los usuarios autorizados por el rol `administrador` puedan acceder a los endpoints.
