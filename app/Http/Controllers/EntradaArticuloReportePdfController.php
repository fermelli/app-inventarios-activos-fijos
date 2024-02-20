<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\EntradaArticuloControllerTrait;
use App\Http\Controllers\Traits\ReportePdfTrait;
use Illuminate\Http\Request;

class EntradaArticuloReportePdfController extends Controller
{
    use EntradaArticuloControllerTrait;
    use ReportePdfTrait;

    /**
     * Display the specified resource in PDF.
     */
    public function show(string $id, Request $request)
    {
        $transaccion = $this->showDatos($id);

        $pdf = $this->generarReportePdf('pdf.entrada-articulos', [
            'transaccion' => $transaccion,
            'usuario' => $request->user(),
        ]);
        $pdfBase64 = base64_encode($pdf->output());

        return response()->jsonResponse(
            'Reporte de entrada de artículos generado exitosamente',
            ['pdf' => $pdfBase64 , 'nombre' => 'Reporte de entrada de artículos'],
            200
        );
    }
}
