<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ActivoFijoTrait;
use App\Http\Controllers\Traits\ReportePdfTrait;
use App\Http\Requests\GenerarEtiquetaRequest;
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
        $parametros = $request->validated();

        $sinPaginacion = $parametros['sin_paginacion'];
        $categoriaId = $parametros['categoria_id'] ?? null;
        $categoria = null;

        if (!is_null($categoriaId)) {
            $categoria = Categoria::find($categoriaId);
        }

        $queryBuilder = $this->indexQueryBuilder($parametros);
        $pdf = null;
        $pdfBase64 = null;
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
        $qrCodeBase64 = base64_encode($qrCode);

        $fila = $parametros['fila'];
        $columna = $parametros['columna'];
        $numeroCeldaSeleccionada = ($fila - 1) * self::MAXIMO_COLUMNAS + $columna;
        $cantidadMaxima = (self::MAXIMO_FILAS * self::MAXIMO_COLUMNAS) - $numeroCeldaSeleccionada + 1;

        $pdf = $this->generarEtiquetaPdf('pdf.etiqueta-activo-fijo', [
            'activoFijo' => $activoFijo,
            'codigoQR' => $qrCodeBase64,
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
}
