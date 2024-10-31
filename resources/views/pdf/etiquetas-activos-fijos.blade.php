@extends('pdf.layouts.etiqueta')

@section('titulo', 'Etiquetas de Activos Fijos')

@section('contenido-principal')
    <table class="tabla-contenedor">
        <tbody>
            @for ($f = 1; $f <= $maximoFilas; $f++)
                <tr>
                    @for ($c = 1; $c <= $maximoColumnas; $c++)
                        @php
                        $indice = ($f -1) * $maximoColumnas + $c - 1;
                        @endphp

                        @if (isset($datos['activosFijos'][$indice]))
                        
                        @php
                        $activoFijo = $datos['activosFijos'][$indice];
                        @endphp
                        <td style="width: 25%; max-width: 25%; height: 92px;">
                            <table class="tabla-etiqueta">
                                <tbody>
                                    <tr>
                                        <th colspan="3" style="width: 65%; max-width: 65%; text-align: center;">
                                            <h2>U. E. ANICETO ARCE</h2>
                                        </th>
                                    </tr>

                                    <tr>
                                        <td style="width: 15%; max-width: 15%; text-align: right">
                                            <strong>CÓDIGO:</strong>
                                        </td>
                                        
                                        <td style="width: 50%; max-width: 50%; text-align: left">
                                            {{ $activoFijo->codigo }}
                                        </td>

                                        
                                        <th
                                            rowspan="4"
                                            style="width: 35%; max-width: 35%; text-align: center;"
                                        >
                                            <img src="data:image/png;base64,{{$activoFijo->codigoQrBase64}}" style="width: 65px;"/>
                                        </th>
                                    </tr>

                                    <tr>
                                        <td style="width: 15%; max-width: 15%; text-align: right">
                                            <strong>NOMBRE:</strong>
                                        </td>

                                        <td style="width: 50%; max-width: 50%; text-align: left">
                                            {{ $activoFijo->nombre }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="width: 15%; max-width: 15%; text-align: right">
                                            <strong>CATEGORÍA:</strong>
                                        </td>

                                        <td style="width: 50%; max-width: 50%; text-align: left">
                                            {{ $activoFijo->categoria->nombre }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="width: 15%; max-width: 15%; text-align: right">
                                            <strong>UBICACIÓN:</strong> 
                                        </td>
                                        
                                        <td style="width: 50%; max-width: 50%; text-align: left">
                                            {{
                                                $activoFijo->asignacionActivoFijoActual
                                                    ? $activoFijo->asignacionActivoFijoActual->ubicacion->nombre
                                                    : '-'
                                            }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        @else
                        <td style="width: 25%; max-width: 25%; height: 92px;"></td>
                        @endif
                    @endfor
                </tr>
            @endfor
        </tbody>
    </table>
@endsection