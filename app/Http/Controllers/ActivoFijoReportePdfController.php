<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ActivoFijoTrait;
use App\Http\Controllers\Traits\ReportePdfTrait;
use App\Http\Requests\GenerarEtiquetaRequest;
use App\Http\Requests\GenerarEtiquetasRequest;
use App\Http\Requests\IndexArticuloControllerRequest;
use App\Models\Articulo;
use App\Models\Categoria;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ActivoFijoReportePdfController extends Controller
{
    use ActivoFijoTrait;
    use ReportePdfTrait;

    public const MAXIMO_FILAS = 10;
    public const MAXIMO_COLUMNAS = 4;

    public function index(IndexArticuloControllerRequest $request)
    {
        $datos = $this->obtenerDatos($request);
        
        $pdf = $this->generarReportePdf('pdf.activos-fijos', $datos, 'landscape');
        $pdfBase64 = base64_encode($pdf->output());

        return response()->jsonResponse(
            'Reporte de activos fijos generado exitosamente',
            ['pdf' => $pdfBase64, 'nombre' => 'Reporte de activos fijos'],
            200
        );
    }

    public function generarEtiqueta(string $id, GenerarEtiquetaRequest $request)
    {
        $parametros = $request->validated();
        $activoFijo = Articulo::with([
            'categoria',
            'institucion',
            'asignacionActivoFijoActual.usuario',
            'asignacionActivoFijoActual.ubicacion',
        ])
        ->where('id', $id)
        ->where('tipo', Articulo::TIPO_ACTIVO_FIJO)->firstOrFail();

        $codigoQrBase64 = $this->generarCodigoQr($activoFijo);
        $activoFijo['codigoQrBase64'] = $codigoQrBase64;

        $fila = $parametros['fila'];
        $columna = $parametros['columna'];
        $numeroCeldaSeleccionada = ($fila - 1) * self::MAXIMO_COLUMNAS + $columna;
        $cantidadMaxima = (self::MAXIMO_FILAS * self::MAXIMO_COLUMNAS) - $numeroCeldaSeleccionada + 1;

        $pdf = $this->generarEtiquetasPdf('pdf.etiqueta-activo-fijo', [
            'activoFijo' => $activoFijo,
            'maximoFilas' => self::MAXIMO_FILAS,
            'maximoColumnas' => self::MAXIMO_COLUMNAS,
            'fila' => $fila,
            'columna' => $columna,
            'cantidad' => $parametros['cantidad'],
            'numeroCeldaSeleccionada' => $numeroCeldaSeleccionada,
            'cantidadMaxima' => $cantidadMaxima,
        ]);
        $pdfBase64 = base64_encode($pdf->output());

        return response()->jsonResponse(
            'Etiqueta de activo fijo generada exitosamente',
            ['pdf' => $pdfBase64, 'nombre' => "Etiqueta de Activo Fijo {$activoFijo->codigo} - {$activoFijo->nombre}"],
            200
        );
    }

    protected function generarCodigoQr($activoFijo): string
    {
        $datosQR = [
            'CODIGO' => $activoFijo->codigo,
            'NOMBRE' => $activoFijo->nombre,
            'CATEGORIA' => $activoFijo->categoria->nombre,
            'INSTITUCION' => $activoFijo->institucion->nombre,
            'ASIGNADO A' => $activoFijo->asignacionActivoFijoActual
                                ? $activoFijo->asignacionActivoFijoActual->usuario->nombre
                                : '-',
            'FECHA DE ASIGNACION' => $activoFijo->asignacionActivoFijoActual
                                        ? $activoFijo->asignacionActivoFijoActual->fecha_asignacion
                                        : '-',
            'UBICACION' => $activoFijo->asignacionActivoFijoActual
                            ? $activoFijo->asignacionActivoFijoActual->ubicacion->nombre
                            : '-',
        ];

        $qrCode = QrCode::size(200)
                    ->margin(0)
                    ->generate(json_encode($datosQR));
        $codigoQrBase64 = base64_encode($qrCode);

        return $codigoQrBase64;
    }

    protected function obtenerDatos(IndexArticuloControllerRequest | GenerarEtiquetasRequest $request)
    {
        $parametros = $request->validated();

        $sinPaginacion = $parametros['sin_paginacion'];
        $categoriaId = $parametros['categoria_id'] ?? null;
        $categoria = null;

        $queryBuilder = $this->indexQueryBuilder($parametros);

        if (!is_null($categoriaId)) {
            $categoria = Categoria::find($categoriaId);
        }

        $datos = [
            'activosFijos' => null,
            'categoria' => $categoria,
            'usuario' => $request->user(),
            'metadatos' => null,
        ];

        if ($sinPaginacion) {
            $activosFijos = $queryBuilder->get();
            $datos['activosFijos'] = $activosFijos;
        } else {
            $paginador = $queryBuilder->paginate(
                $parametros['items_por_pagina'],
                ['*'],
                'pagina',
                $parametros['pagina']
            );
            $activosFijos = $paginador->items();
            $datos['activosFijos'] = $activosFijos;
            $datos['metadatos'] = [
                'pagina' => $paginador->currentPage(),
                'items_por_pagina' => $paginador->perPage(),
                'total' => $paginador->total(),
                'ultima_pagina' => $paginador->lastPage(),
            ];
        }

        return $datos;
    }

    public function generarEtiquetas(GenerarEtiquetasRequest $request)
    {
        $parametros = $request->validated();
        $datos = $this->obtenerDatos($request);
        $activosFijos = $datos['activosFijos'];

        foreach ($activosFijos as $indice => $activoFijo) {
            $codigoQrBase64 = $this->generarCodigoQr($activoFijo);

            $datos['activosFijos'][$indice]['codigoQrBase64'] = $codigoQrBase64;
        }

        $fila = $parametros['fila'];
        $columna = $parametros['columna'];
        $numeroCeldaSeleccionada = ($fila - 1) * self::MAXIMO_COLUMNAS + $columna;
        $cantidadActivosFijos = count($activosFijos);
        $maximoFilas = intdiv($cantidadActivosFijos + $numeroCeldaSeleccionada - 2, self::MAXIMO_COLUMNAS)
            + (($cantidadActivosFijos % self::MAXIMO_COLUMNAS) === 0 ? 0 : 1);

        for ($i = 0; $i < $numeroCeldaSeleccionada - 1; $i++) {
            array_unshift($datos['activosFijos'], null);
        }

        $pdf = $this->generarEtiquetasPdf('pdf.etiquetas-activos-fijos', [
            'datos' => $datos,
            'maximoFilas' => $maximoFilas,
            'maximoColumnas' => self::MAXIMO_COLUMNAS,
            'fila' => $fila,
            'columna' => $columna,
            'numeroCeldaSeleccionada' => $numeroCeldaSeleccionada,
        ]);
        $pdfBase64 = base64_encode($pdf->output());

        return response()->jsonResponse(
            'Etiquetas de activos fijos generadas exitosamente',
            ['pdf' => $pdfBase64, 'nombre' => 'Etiquetas de activos fijos'],
            200
        );
    }
}
