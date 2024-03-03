@extends('pdf.layouts.main')

@section('titulo', 'Entrada de Artículos')

@section('contenido-principal')
    <table class="tabla tabla--datos">
        <tbody>
            <tr>
                <td colspan="6">
                    <h2 class="h2">Entrada de Artículos</h2>
                </td>
            </tr>

            <tr>
                <td style="width: 10.10%; text-align: right;"><strong>Fecha:</strong></td>
                <td style="width: 12.50%; text-align: left;">{{ $transaccion->fecha }}</td>

                <td style="width: 19.35%; text-align: right;"><strong>Comprobante:</strong></td>
                <td style="width: 19.35%; text-align: left;">{{ $transaccion->comprobante }}</td>

                <td style="width: 19.35%; text-align: right;"><strong>N° Comprobante: </strong></td>
                <td style="width: 19.35%; text-align: left;">{{ $transaccion->numero_comprobante }}</td>
            </tr>

            <tr>
                <td style="width: 10.10%; text-align: right;"><strong>Estado: </strong></td>
                <td style="width: 12.50%; text-align: left;">{{ $transaccion->estado_entrada }}</td>

                <td style="width: 19.35%; text-align: right;"><strong>Institución: </strong></td>
                <td style="width: 19.35%; text-align: left;">{{ $transaccion->institucion ? $transaccion->institucion->nombre : '-' }}</td>

                <td style="width: 19.35%; text-align: right;"><strong>Registrado por: </strong></td>
                <td style="width: 19.35%; text-align: left;">{{ $transaccion->usuario->nombre }}</td>
            </tr>
        </tbody>
    </table>

    <table class="tabla tabla--detalles">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 40%;">ARTÍCULO</th>
                <th style="width: 20%;">UNIDAD</th>
                <th style="width: 20%;">FECHA VENC.</th>
                <th style="width: 15%;">CANTIDAD</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($transaccion->detallesTransacciones as $detalleTransaccion)
                <tr>
                    <td style="text-align: left;">{{ $loop->iteration }}</td>
                    <td style="text-align: left;">{{ $detalleTransaccion->articulo->nombre }}</td>
                    <td style="text-align: left;">{{ $detalleTransaccion->articulo->unidad->nombre }}</td>
                    <td style="text-align: right;">{{ $detalleTransaccion->articuloLote->fecha_vencimiento ?? '-' }}</td>
                    <td style="text-align: right;">{{ $detalleTransaccion->cantidad }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($transaccion->observacion)
        <table class="tabla tabla--datos">
            <tbody>
                <tr>
                    <td style="text-align: left;"><strong>Observación:</strong></td>
                </tr>

                <tr>
                    <td style="text-align: left;">{{ $transaccion->observacion }}</td>
                </tr>
            </tbody>
        </table>
    @endif
@endsection