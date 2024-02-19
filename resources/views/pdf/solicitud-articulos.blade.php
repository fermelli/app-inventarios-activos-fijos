@extends('pdf.layouts.main')

@section('titulo', $titulo)

@section('contenido-principal')
    <table class="tabla tabla--datos">
        <tbody>
            <tr>
                <td colspan="6">
                    <h2 class="h2">{{ $titulo }}</h2>
                </td>
            </tr>

            <tr>
                <td style="width: 11.30%; text-align: right;"><strong>N° Solicitud:</strong></td>
                <td style="width: 11.30%; text-align: left;">{{ $transaccion->numero_solicitud }}</td>

                <td style="width: 19.35%; text-align: right;"><strong>Observación</strong></td>
                <td style="width: 19.35%; text-align: left;" colspan="3">{{ $transaccion->observacion }}</td>
            </tr>

            <tr>
                <td style="width: 11.30%; text-align: right;"><strong>Estado: </strong></td>
                <td style="width: 11.30%; text-align: left;">{{ $transaccion->estado_solicitud }}</td>

                <td style="width: 19.35%; text-align: right;"><strong>Solicitado por:</strong></td>
                <td style="width: 19.35%; text-align: left;">{{ $transaccion->solicitante->nombre }}</td>

                <td style="width: 19.35%; text-align: right;"><strong>Fecha:</strong></td>
                <td style="width: 19.35%; text-align: left;">{{ $transaccion->creado_en }}</td>
            </tr>
            
            @if ($transaccion->estado_solicitud != 'pendiente')
                <tr>
                    <td style="width: 11.30%; text-align: right;"><strong></strong></td>
                    <td style="width: 11.30%; text-align: left;"></td>
                
                    <td style="width: 19.35%; text-align: right;"><strong>Atendido por:</strong></td>
                    <td style="width: 19.35%; text-align: left;">{{ $transaccion->usuario ? $transaccion->usuario->nombre : '-' }}</td>

                    <td style="width: 19.35%; text-align: right;"><strong>Fecha Hora Atención: </strong></td>
                    <td style="width: 19.35%; text-align: left;">{{ $transaccion->fecha_hora_atencion ?? '-' }}</td>
                </tr>
            @endif

            @if ($transaccion->estado_solicitud == 'anulada')
                <tr>
                    <td style="width: 11.30%; text-align: right;"><strong></strong></td>
                    <td style="width: 11.30%; text-align: left;"></td>

                    <td style="width: 19.35%; text-align: right;"><strong>Anulado por:</strong></td>
                    <td style="width: 19.35%; text-align: left;">{{ $transaccion->anulador ? $transaccion->anulador->nombre : '-' }}</td>

                    <td style="width: 19.35%; text-align: right;"><strong>Fecha Hora Anulación: </strong></td>
                    <td style="width: 19.35%; text-align: left;">{{ $transaccion->fecha_hora_anulacion ?? '-' }}</td>
                </tr>
            @endif

            @if ($transaccion->estado_solicitud == 'entregada')
                <tr>
                    <td style="width: 11.30%; text-align: right;"><strong></strong></td>
                    <td style="width: 11.30%; text-align: left;"></td>

                    <td style="width: 19.35%; text-align: right;"><strong>Entregado por:</strong></td>
                    <td style="width: 19.35%; text-align: left;">{{ $transaccion->despachante ? $transaccion->despachante->nombre : '-' }}</td>

                    <td style="width: 19.35%; text-align: right;"><strong>Fecha Hora Entrega: </strong></td>
                    <td style="width: 19.35%; text-align: left;">{{ $transaccion->fecha_hora_entrega ?? '-' }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <table class="tabla tabla--detalles">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 45%;">ARTÍCULO</th>
                <th style="width: 25%;">UNIDAD</th>
                <th style="width: 25%;">CANTIDAD</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($transaccion->detallesTransacciones as $detalleTransaccion)
                <tr>
                    <td style="text-align: left;">{{ $loop->iteration }}</td>
                    <td style="text-align: left;">{{ $detalleTransaccion->articulo->nombre }}</td>
                    <td style="text-align: left;">{{ $detalleTransaccion->articulo->unidad->nombre }}</td>
                    <td style="text-align: right;">{{ $detalleTransaccion->cantidad }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection