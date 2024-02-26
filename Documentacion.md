# Objetivos

## Objetivo General

## Objetivos Específicos

-   Autenticacion de Usuarios

    -   Registro de Usuario
    -   Login de Usuario
    -   Gestion de Usuario

-   Gestion de articulos y activos fijos

    -   Gestion de Categorias
    -   Gestion de Unidades
    -   Gestion de Ubicaciones
    -   Gestion de Instituciones
    -   Gestion de Articulos
    -   Gestion de Activos Fijos

-   Operaciones de Inventario y Activos Fijos

    -   Gestion de Entradas de Articulos
    -   Gestion de Solicitud de Articulos
    -   Gestion de Salidas de Articulos
    -   Gestion de Asignaciones de Activos Fijos

-   Modulo de Reportes

    -   Reporte de Catalogo de Articulos
    -   Reporte de Catalogo de Activos Fijos
    -   Reporte de Entradas de Articulos
    -   Reporte de Solicitud de Articulos
    -   Reporte de Salidas de Articulos
    -   Reporte de Asignaciones de Activos Fijos
    -   Reporte de Inventario de Articulos
    -   Reporte de Inventario de Activos Fijos

-   Aplicacion Movil para Solicitud de Articulo
    -   Gestion de Solicitud de Articulos
    -   Gestion de Salidas de Articulos

# Modelos

Articulo
ArticuloLote
AsignacionActivoFijo
Categoria
DetalleTransaccion
Institucion
Transaccion
Ubicacion
Unidad
User

# Controladores

MySql

PHP - Laravel

Controller
ActivoFijoController
DashboardController
SalidaArticuloReportePdfController
UnidadController
ArticuloController
EntradaArticuloController
SolicitudArticuloController
UsuarioController
AsignacionActivoFijoController
EntradaArticuloReportePdfController
SolicitudArticuloReportePdfController
CategoriaController
InstitucionController
SalidaArticuloController
UbicacionController

# Vistas

JavaScript - Vue - Vuex - Vue Router - Vuetify

ActivosFijosVista
LoginVista
RegistrarseVista
EntradasVista
UbicacionesVista
UsuariosVista
ArticulosVista
CategoriasVista
InstitucionesVista
SalidasTodasVista
SalidasUsuarioVista
SolicitudesTodasVista
SolicitudesUsuarioVista
UnidadesVista

ContactoDesarrolladorVista
DashboardVista
SobreNosotrosVista
NoAutorizadoVista
NoEncontradoVista

# Planificación de Desarrollo del Proyecto por Iteraciones

## Plan de Iteraciones

-   Iteración 1: Desarollar el modulo de autenticacion de usuarios
    -   Registro de Usuario
    -   Login de Usuario
    -   Gestion de Usuario
-   Iteración 2: Desarollar el modulo de gestion de articulos y activos fijos y sus operaciones
    -   Gestion de Categorias
    -   Gestion de Unidades
    -   Gestion de Ubicaciones
    -   Gestion de Instituciones
    -   Gestion de Articulos
    -   Gestion de Activos Fijos
    -   Gestion de Entradas de Articulos
    -   Gestion de Solicitud de Articulos
    -   Gestion de Salidas de Articulos
    -   Gestion de Asignaciones de Activos Fijos
-   Iteración 3: Desarollar el modulo de reportes
-   Iteración 4: Desarollar la aplicacion movil para solicitud de articulos

## Cronograma del Proyecto

| Iteración   | Actividades                              | Fecha de Inicio | Duración | Fecha de Fin |
| ----------- | ---------------------------------------- | --------------- | -------- | ------------ |
| Iteración 1 | Registro de Usuario                      | 06/11/2023      | 6 días   | 13/11/2023   |
| Iteración 1 | Login de Usuario                         | 14/11/2023      | 6 días   | 21/11/2023   |
| Iteración 1 | Gestion de Usuario                       | 22/11/2023      | 6 días   | 29/11/2023   |
| Iteración 2 | Gestion de Categorias                    | 30/11/2023      | 7 días   | 08/12/2023   |
| Iteración 2 | Gestion de Unidades                      | 11/12/2023      | 7 días   | 19/12/2023   |
| Iteración 2 | Gestion de Ubicaciones                   | 20/12/2023      | 7 días   | 28/12/2023   |
| Iteración 2 | Gestion de Instituciones                 | 29/12/2023      | 7 días   | 08/01/2024   |
| Iteración 2 | Gestion de Articulos                     | 09/01/2024      | 7 días   | 17/01/2024   |
| Iteración 2 | Gestion de Activos Fijos                 | 18/01/2024      | 7 días   | 26/01/2024   |
| Iteración 2 | Gestion de Entradas de Articulos         | 29/01/2024      | 10 días  | 09/02/2024   |
| Iteración 2 | Gestion de Solicitud de Articulos        | 12/02/2024      | 10 días  | 23/02/2024   |
| Iteración 2 | Gestion de Salidas de Articulos          | 26/02/2024      | 10 días  | 08/03/2024   |
| Iteración 2 | Gestion de Asignaciones de Activos Fijos | 11/03/2024      | 10 días  | 22/03/2024   |
| Total       |                                          |                 | 106 días |              |

# Iteracion 1: Desarollar el modulo de autenticacion de usuarios

## Planeación de la Iteración

### Historias de Usuario

#### Historia de Usuario 01: Registro de Usuario

Numero: 01
Nombre: Registro de Usuario
Usuario: Usuario
Iteracion: 1
Descripcion: Como Usuario quiero registrarme en el sistema para poder acceder a las funcionalidades del sistema
Criterios de Aceptacion:

-   El usuario debe poder registrarse en el sistema con su nombre, correo electronico y password
-   El usuario al registrarse obtendra el rol de "personal" por defecto
-   El usuario al registrarse obtendra el estado de "activo" por defecto

#### Historia de Usuario 02: Login de Usuario

Numero: 02
Nombre: Login de Usuario
Usuario: Usuario
Iteracion: 1
Descripcion: Como Usuario quiero loguearme en el sistema para poder acceder a las funcionalidades del sistema
Criterios de Aceptacion:

-   El usuario debe poder loguearse en el sistema con su correo electronico y password
-   El usuario debe poder cerrar sesion en el sistema

#### Historia de Usuario 03: Gestion de Usuario

Numero: 03
Nombre: Gestion de Usuario
Usuario: Administrador
Iteracion: 1
Descripcion: Como Administrador quiero gestionar los usuarios del sistema para poder controlar el acceso al sistema
Criterios de Aceptacion:

-   El administrador debe poder ver la lista de usuarios del sistema
-   El administrador debe poder buscar usuarios por nombre, correo electronico
-   El administrador debe poder cambiar el rol de un usuario (personal, administrador)
-   El administrador debe poder cambiar el estado de un usuario (activo, inactivo)
-   El administrador debe poder eliminar un usuario

# Iteracion 2: Desarollar el modulo de gestion de articulos y activos fijos y sus operaciones

## Planeación de la Iteración

### Historias de Usuario

#### Historia de Usuario 04: Gestion de Categorias

Numero: 04
Nombre: Gestion de Categorias
Usuario: Administrador
Iteracion: 2
Descripcion: Como Administrador quiero gestionar las categorias de los articulos para poder organizar los articulos y activos fijos
Criterios de Aceptacion:

-   El administrador debe poder ver la lista de categorias de los articulos
-   El administrador debe poder buscar categorias por nombre
-   El administrador debe poder registrar una categoria con los campos de nombre y categoria padre
-   El administrador debe poder editar una categoria
-   El administrador debe poder eliminar una categoria

#### Historia de Usuario 05: Gestion de Unidades

Numero: 05
Nombre: Gestion de Unidades
Usuario: Administrador
Iteracion: 2
Descripcion: Como Administrador quiero gestionar las unidades de los articulos para poder organizar los articulos
Criterios de Aceptacion:

-   El administrador debe poder ver la lista de unidades de los articulos
-   El administrador debe poder buscar unidades por nombre
-   El administrador debe poder registrar una unidad con el campo de nombre
-   El administrador debe poder editar una unidad
-   El administrador debe poder eliminar una unidad

#### Historia de Usuario 06: Gestion de Ubicaciones

Numero: 06
Nombre: Gestion de Ubicaciones
Usuario: Administrador
Iteracion: 2
Descripcion: Como Administrador quiero gestionar las ubicaciones de los articulos para poder organizar los articulos
Criterios de Aceptacion:

-   El administrador debe poder ver la lista de ubicaciones de los articulos
-   El administrador debe poder buscar ubicaciones por nombre
-   El administrador debe poder registrar una ubicacion con el campo de nombre
-   El administrador debe poder editar una ubicacion
-   El administrador debe poder eliminar una ubicacion

#### Historia de Usuario 07: Gestion de Instituciones

Numero: 07
Nombre: Gestion de Instituciones
Usuario: Administrador
Iteracion: 2
Descripcion: Como Administrador quiero gestionar las instituciones para poder organizar los articulos
Criterios de Aceptacion:

-   El administrador debe poder ver la lista de instituciones
-   El administrador debe poder buscar instituciones por nombre
-   El administrador debe poder registrar una institucion con los campos de nombre, direccion y telefono
-   El administrador debe poder editar una institucion
-   El administrador debe poder eliminar una institucion

#### Historia de Usuario 08: Gestion de Articulos

Numero: 08
Nombre: Gestion de Articulos
Usuario: Administrador, Personal
Iteracion: 2
Descripcion: Como Administrador o Personal quiero gestionar los articulos para poder organizar el inventario
Criterios de Aceptacion:

-   El administrador o personal debe poder ver la lista de articulos
-   El administrador o personal debe poder filtrar articulos por categoria
-   El administrador o personal debe poder buscar articulos por codigo SIGMA, nombre
-   El administrador debe poder registrar un articulo con los campos de codigo SIGMA, nombre, descripcion, categoria, unidad y ubicacion
-   El administrador debe poder editar un articulo
-   El administrador debe poder eliminar un articulo

#### Historia de Usuario 09: Gestion de Activos Fijos

Numero: 09
Nombre: Gestion de Activos Fijos
Usuario: Administrador
Iteracion: 2
Descripcion: Como Administrador quiero gestionar los activos fijos para poder organizar su asignacion
Criterios de Aceptacion:

-   El administrador debe poder ver la lista de activos fijos
-   El administrador debe poder filtrar activos fijos por categoria
-   El administrador debe poder buscar activos fijos por codigo SIGMA, nombre
-   El administrador debe poder registrar un activo fijo con los campos de codigo SIGMA, nombre, descripcion, categoria e institucion
-   El administrador debe poder editar un activo fijo
-   El administrador debe poder eliminar un activo fijo

#### Historia de Usuario 10: Gestion de Entradas de Articulos

Numero: 10
Nombre: Gestion de Entradas de Articulos
Usuario: Administrador
Iteracion: 2
Descripcion: Como Administrador quiero gestionar las entradas de articulos para poder controlar el stock de los articulos
Criterios de Aceptacion:

-   El administrador debe poder ver la lista de entradas de articulos
-   El administrador debe poder buscar entradas de articulos numero de comprobante
-   El administrador debe poder registrar una entrada de articulo con los campos de fecha, institucion, comprobante, numero de comprobante, observación y detalle de articulos con los campos de articulo, lote, fecha de vencimiento y cantidad
-   El administrador debe poder ver el detalle de una entrada de articulos
-   El administrador debe poder generar un reporte en PDF de la entrada de articulos
-   El administrador debe poder anular una entrada de articulos

#### Historia de Usuario 11: Gestion de Solicitud de Articulos

Numero: 11
Nombre: Gestion de Solicitud de Articulos
Usuario: Administrador, Personal
Iteracion: 2
Descripcion: Como Administrador o Personal quiero gestionar las solicitudes de articulos para poder controlar las salidas de articulos
Criterios de Aceptacion:

-   El administrador debe poder ver la lista de todas las solicitudes de articulos
-   El personal debe poder ver la lista de sus solicitudes de articulos
-   El administrador o personal debe poder buscar solicitudes de articulos por numero de solicitud
-   El personal debe poder registrar una solicitud de articulo con los campos de observación y detalle de articulos con los campos de articulo y cantidad
-   El administrador o persanal debe poder ver el detalle de una solicitud de articulos
-   El administrador o personal debe poder generar un reporte en PDF de la solicitud de articulos
-   El administrador debe poder aprobar una solicitud de articulos
-   El administrador debe poder rechazar una solicitud de articulos

#### Historia de Usuario 12: Gestion de Salidas de Articulos

Numero: 12
Nombre: Gestion de Salidas de Articulos
Usuario: Administrador, Personal
Iteracion: 2
Descripcion: Como Administrador o Personal quiero gestionar las salidas de articulos para poder dar respuesta a las solicitudes de articulos
Criterios de Aceptacion:

-   El administrador debe poder ver la lista de todas las salidas de articulos
-   El personal debe poder ver la lista de sus salidas de articulos aprobadas por el administrador asociadas a sus solicitudes de articulos
-   El administrador o personal debe poder buscar salidas de articulos por numero de solicitud
-   El administrador o personal debe poder generar un reporte en PDF de la salida de articulos
-   El administrador debe poder entregar una salida de articulos
-   El administrador debe poder anular una salida de articulos

#### Historia de Usuario 13: Gestion de Asignaciones de Activos Fijos

Numero: 13
Nombre: Gestion de Asignaciones de Activos Fijos
Usuario: Administrador
Iteracion: 2
Descripcion: Como Administrador quiero gestionar las asignaciones de activos fijos para poder controlar los activos fijos
Criterios de Aceptacion:

-   El administrador debe poder ver el historial de asignaciones de un activo fijo
-   El administrador debe poder asignar un activo fijo a un usuario en una ubicacion con los campos de asignado a, ubicacion, fecha de asignacion y observacion de asignacion
-   El administrador debe poder registrar la devolucion de un activo fijo con los campos de fecha de devolucion y observacion de devolucion
-   El administrador debe poder generar un reporte en PDF de la asignacion de activos fijos
-   El administrador debe poder dar de baja un activo fijo
