<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ArticuloTrait;
use App\Http\Controllers\Traits\ReportePdfTrait;
use App\Http\Requests\IndexArticuloControllerRequest;
use App\Models\Categoria;

class ArticuloReportePdfController extends Controller
{
    use ArticuloTrait;
    use ReportePdfTrait;

    public function index(IndexArticuloControllerRequest $request)
    {
        $parametros = $request->validated();

        $categoriaId = $parametros['categoria_id'] ?? null;
        $categoria = null;

        if (!is_null($categoriaId)) {
            $categoria = Categoria::find($categoriaId);
        }

        $queryBuilder = $this->indexQueryBuilder($parametros);
        $paginador = $queryBuilder->paginate($parametros['items_por_pagina'], ['*'], 'pagina', $parametros['pagina']);

        $pdf = $this->generarReportePdf('pdf.articulos', [
            'articulos' => $paginador->items(),
            'categoria' => $categoria,
            'metadatos' => [
                'pagina' => $paginador->currentPage(),
                'items_por_pagina' => $paginador->perPage(),
                'total' => $paginador->total(),
                'ultima_pagina' => $paginador->lastPage(),
            ],
            'usuario' => $request->user(),
        ]);
        $pdfBase64 = base64_encode($pdf->output());

        return response()->jsonResponse(
            'Reporte de artículos generado exitosamente',
            ['pdf' => $pdfBase64, 'nombre' => 'Reporte de artículos'],
            200
        );
    }
}
