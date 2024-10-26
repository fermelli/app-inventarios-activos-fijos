@extends('pdf.layouts.main')

@section('titulo', 'Activos Fijos')

@section('contenido-principal')
    <table class="tabla tabla--datos">
        <tbody>
            <tr>
                <td colspan="6">
                    <h2 class="h2">Activos Fijos</h2>

                    @if ($categoria)
                    <h3 class="h3">Categoría: {{ $categoria->nombre }}</h3>
                    @endif
                </td>
            </tr>

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
        </tbody>
    </table>

    <table class="tabla tabla--detalles">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 10%;">CÓDIGO</th>
                <th style="width: 25%;">ACTIVO FIJO</th>
                <th style="width: 15%;">CATEGORÍA</th>
                <th style="width: 15%;">INSTITUCIÓN</th>
                <th style="width: 30%;">DESCRIPCIÓN</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($activosFijos as $activoFijo)
                <tr>
                    <td style="text-align: left;">{{ $loop->iteration }}</td>
                    <td style="text-align: left;">{{ $activoFijo->codigo }}</td>
                    <td style="text-align: left;">{{ $activoFijo->nombre }}</td>
                    <td style="text-align: left;">{{ $activoFijo->categoria->nombre }}</td>
                    <td style="text-align: left;">{{ $activoFijo->institucion->nombre }}</td>
                    <td style="text-align: left;">{{ $activoFijo->descripcion }}</td>
            @endforeach
        </tbody>
    </table>

@endsection