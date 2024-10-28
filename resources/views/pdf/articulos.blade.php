@extends('pdf.layouts.main')

@section('titulo', 'Artículos')

@section('contenido-principal')
    <table class="tabla tabla--datos">
        <tbody>
            <tr>
                <td colspan="6">
                    <h2 class="h2">Artículos</h2>

                    @if ($categoria)
                    <h3 class="h3">Categoría: {{ $categoria->nombre }}</h3>
                    @endif
                </td>
            </tr>

            @if(isset($metadatos))
            <tr>
                <td style="width: 16.66%; text-align: right;"><strong>Página:</strong></td>
                <td style="width: 16.66%; text-align: left;">
                    {{ $metadatos['pagina'] }} de {{ $metadatos['ultima_pagina'] }}
                </td>

                <td style="width: 16.66%; text-align: right;"><strong>Items por Página:</strong></td>
                <td style="width: 16.66%; text-align: left;">{{ $metadatos['items_por_pagina'] }}</td>

                <td style="width: 16.66%; text-align: right;"><strong>Cantidad Items: </strong></td>
                <td style="width: 16.66%; text-align: left;">{{ $metadatos['total'] }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    <table class="tabla tabla--detalles">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 15%;">CÓDIGO</th>
                <th style="width: 20%;">ÁRTICULO</th>
                <th style="width: 15%;">CANTIDAD</th>
                <th style="width: 15%;">CATEGORÍA</th>
                <th style="width: 15%;">UNIDAD</th>
                <th style="width: 15%;">UBICACIÓN</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($articulos as $articulo)
                <tr>
                    <td style="text-align: left;">{{ $loop->iteration }}</td>
                    <td style="text-align: left;">{{ $articulo->codigo }}</td>
                    <td style="text-align: left;">{{ $articulo->nombre }}</td>
                    <td style="text-align: right;">{{ $articulo->cantidad }}</td>
                    <td style="text-align: left;">{{ $articulo->categoria->nombre }}</td>
                    <td style="text-align: left;">{{ $articulo->unidad->nombre }}</td>
                    <td style="text-align: left;">{{ $articulo->ubicacion->nombre }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection