<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ReportePdfTrait;
use App\Http\Controllers\Traits\SolicitudArticuloControllerTrait;
use Illuminate\Http\Request;

class SolicitudArticuloReportePdfController extends Controller
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
            'titulo' => 'Solicitud de Artículos',
        ]);
        $pdfBase64 = base64_encode($pdf->output());

        return response()->jsonResponse(
            'Reporte de solicitud de artículos generado exitosamente',
            ['pdf' => $pdfBase64 , 'nombre' => 'Reporte de solicitud de artículos'],
            200
        );
    }
}
