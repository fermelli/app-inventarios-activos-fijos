<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ReportePdfTrait;
use App\Http\Controllers\Traits\SolicitudArticuloControllerTrait;
use Illuminate\Http\Request;

class SalidaArticuloReportePdfController extends Controller
{
    use SolicitudArticuloControllerTrait;
    use ReportePdfTrait;
    
    /**
     * Display the specified resource in PDF.
     */
    public function show(string $id, Request $request)
    {
        $transaccion = $this->showDatos($id);

        $pdf = $this->generarReportePdf('pdf.solicitud-articulos', [
            'transaccion' => $transaccion,
            'usuario' => $request->user(),
            'titulo' => 'Salida de Artículos',
        ]);
        $pdfBase64 = base64_encode($pdf->output());

        return response()->jsonResponse(
            'Reporte de salida de artículos generado exitosamente',
            ['pdf' => $pdfBase64 , 'nombre' => 'Reporte de salida de artículos'],
            200
        );
    }
}
