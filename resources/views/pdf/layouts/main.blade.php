<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>@yield('titulo')</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');

        .page-break {
            page-break-after: always;
        }

        .body {
            font-family: 'Roboto', sans-serif; 
            font-weight: 400; 
            font-style: normal;
        }

        .h1,
        .h2 {
            font-weight: 700;
        }
        
        .h1 {
            font-size: 1rem;
            text-align: left;
            margin: 0;
        }

        .h2 {
            font-size: 0.875rem;
            text-align: center;
            margin: 0 0 1rem 0;
        }

        .h3 {
            font-size: 0.75rem;
            text-align: center;
            margin: 0 0 1rem 0;
        }

        .tabla {
            width: 100%;
            font-size: 0.75rem;
            margin: 1rem 0;
        }

        .tabla--cabecera {
            border-collapse: collapse;
        }

        .subtitulo {
            font-size: 0.75rem;
            font-weight: 400;
            margin: 0;
        }


        .tabla--datos td {
            padding: 0.5rem 0.125rem;
        }

        .tabla--detalles {
            border-collapse: collapse;
        }

        .tabla--detalles th,
        .tabla--detalles td {
            border: 1px solid #4c4c4c;
            padding: 0.5rem;
        }

        .tabla--detalles th {
            background-color: #1867c0d1;
            border-color: #1867c0;
            font-weight: 700;
            text-align: center;
        }
    </style>
</head>

<body class="body">
    <table class="tabla tabla--cabecera">
        <tbody>
            <tr>
                <td style="width: 70%; text-align: left;">
                    <h1 class="h1">HOSPITAL MUNICIPAL CASEGURAL</h1>
                    <p class="subtitulo">
                        ATENCIONES GRATUITAS EN EL MARCO DEL SEGURO MUNICIPAL DE SALUD "SI-SALUD"
                    </p>
                </td>

                <td style="width: 30%; text-align: center;">
                    <strong>Dirección: </strong>Barrio Casegural, Distrito 3, zona media
                </td>
            </tr>

            <tr>
                <td style="width: 80%; text-align: left;">
                    Consulta Externa, Enfermería, Odontología, Medicina Interna, Pediatría, Laboratorio y Ecografía
                </td>

                <td style="width: 20%; text-align: center;">
                    <strong>Teléfono: </strong>64-38942
                </td>
            </tr>
        </tbody>
    </table>

    @section('contenido-principal')
        Aquí va el contenido principal del PDF
    @show

    <div class="page-break"></div>
</body>

</html>