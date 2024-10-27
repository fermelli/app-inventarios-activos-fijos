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

        html {
            margin: 0.5cm;
        }

        .body {
            font-family: 'Roboto', sans-serif;
            font-weight: 400;
            font-style: normal;
        }

        .tabla-contenedor {
            width: 100%;
            margin: 0;
            border-collapse: collapse;
        }

        .tabla-contenedor > tbody > tr > td {
            padding: 0.25rem;
            border: 1px dashed #f5f5f5;
        }

        .tabla-etiqueta {
            width: 100%;
            border: 1px solid #424242;
            border-radius: 0.25rem;
        }
        
        .tabla-etiqueta h2,
        .tabla-etiqueta h3 {
            margin: 0;
            padding: 0;
            text-transform: uppercase;
        }
        
        .tabla-etiqueta h2 {
            font-size: 0.5rem;
        }

        .tabla-etiqueta > tbody > tr > th,
        .tabla-etiqueta > tbody > tr > td,
        .tabla-etiqueta > span {
            padding: 0;
            font-size: 0.35rem;
            text-transform: uppercase;
        }

        .tabla-etiqueta > tbody > tr > td > strong {
            font-size: 0.25rem;
        }
    </style>
</head>

<body class="body">
    @section('contenido-principal')
        Aquí va el contenido principal del PDF
    @show

    <div class="page-break"></div>
</body>
</html>