# Aplicacion de Inventarios y Activos Fijos para el Centro de Salud Sucre

## Descripción

Este proyecto es una aplicación web para el control de inventarios y activos fijos del Centro de Salud Sucre, la cual permitirá llevar un registro de los artículos y activos fijos que se encuentran en el centro de salud, así como también llevar un control de los movimientos que se realicen sobre estos.

Los modulos son los siguientes:

-   **Usuarios**: Permite gestionar los usuarios que tendrán acceso a la aplicación de acuerdo a su rol.
-   **Inventarios**: Permite llevar un control de los artículos o insumos del centro de salud, así como también llevar un registro de los movimientos de entrada, solicitud y salida de los mismos.
-   **Activos Fijos**: Permite llevar un control de los activos fijos que se encuentran en el centro de salud, así como también llevar un registro de asignaciones y bajas de los mismos.

## Requerimientos

-   PHP > 8.1
-   Composer
-   Node.js > 18.0
-   NPM > 8.0

## Instalación

1. Clonar el repositorio

```bash
$ git clone https://github.com/fermelli/app-inventarios-activos-fijos.git
```

2. Instalar las dependencias de PHP y JavaScript

```bash
$ composer install
```

```bash
$ npm install
```

3. Crear el archivo de configuración de entorno

```bash
$ cp .env.example .env
```

4. Generar la clave de la aplicación

```bash
$ php artisan key:generate
```

5. Configurar la conexión a la base de datos en el archivo `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_de_tu_base_de_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contrasena
```

6. Ejecutar las migraciones y seeders

```bash
$ php artisan migrate --seed
```

7. Compilar los assets

```bash
$ npm run dev
```

8. Iniciar el servidor de desarrollo

```bash
$ php artisan serve
```

## Uso

### Acceso a la aplicación

Para acceder a la aplicación, abrir el navegador y visitar la siguiente URL: [http://localhost:8000](http://localhost:8000)

### Credenciales de acceso

-   **Usuario Administrador**

    -   **Usuario**: admin@email.com
    -   **Contraseña**: Password123$

-   **Usuario Personal**
    -   **Usuario**: personal@email.com
    -   **Contraseña**: Password123$

## Documentación del desarrollo de la aplicación

La documentación del desarrollo de la aplicación se encuentra en [Documentacion.md](Documentacion.md).

## Notas de desarrollo

Notas de los primeros pasos en el desarrollo de la aplicación se encuentran en [Notas.md](Notas.md).

## Contacto

Para cualquier duda o consulta, puedes contactarme a través de mi [perfil de GitHub](https://github.com/fermelli).
