<?php

namespace App\Http\Controllers\Traits;

use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDF;

trait ReportePdfTrait
{
    /**
     * Genera el reporte en PDF.
     *
     * @param string $vista
     * @param array<string, mixed> $datos
     * @param string $nombreArchivo
     * @param string $orientacion 'portrait' o 'landscape'
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generarReportePdf(string $vista, array $datos, $orientacion = 'portrait'): DomPDF
    {
        $pdf = Pdf::loadView($vista, $datos);

        $pdf->setPaper('letter', $orientacion);
        $pdf->output();

        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->getCanvas();
        $ancho = $canvas->get_width();
        $alto = $canvas->get_height();

        $canvas->page_text(35, $alto - 35, "Usuario: {$datos['usuario']->nombre}", null, 8, array(0, 0, 0));
        $canvas->page_text(($ancho / 2) - 25, $alto - 35, 'Página {PAGE_NUM} de {PAGE_COUNT}', null, 8, array(0, 0, 0));
        $canvas->page_text($ancho - 135, $alto - 35, 'Fecha: ' . now(), null, 8, array(0, 0, 0));

        return $pdf;
    }
}
