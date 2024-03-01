<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\EntradaArticuloControllerTrait;
use App\Http\Requests\SubirArchivoExcelRequest;
use App\Models\Articulo;
use App\Models\Transaccion;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Spatie\SimpleExcel\SimpleExcelReader;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EntradaArticuloExcelController extends Controller
{
    use EntradaArticuloControllerTrait;
    
    public function importar(SubirArchivoExcelRequest $request)
    {
        $archivoExcel = $request->file('archivo');
        
        /**
         * Variable que almacena las artículos.
         *
         * @var \App\Models\Articulo[]
        */
        $articulos = [];
        
        $detallesTransacciones = [];

        DB::beginTransaction();
        
        try {
            $rows = SimpleExcelReader::create($archivoExcel, 'xlsx')->getRows();

            $rows->each(function (array $row) use ($articulos, &$detallesTransacciones) {
                if (
                    !isset($row['id']) ||
                    !isset($row['articulo']) ||
                    !isset($row['cantidad'])
                ) {
                    throw new BadRequestHttpException(
                        'El archivo no tiene el formato correcto. ' .
                        'Verifica que el archivo tenga el formato correcto y vuelve a intentarlo. ' .
                        'Descarga el formato correcto haciendo clic en el botón "Formato de Ejemplo".'
                    );
                }
                
                $idArticulo = $row['id'];

                if (!isset($articulos[$idArticulo])) {
                    $articulos[$idArticulo] = Articulo::findOrFail($idArticulo);
                }

                $detalleTransaccion = [
                    'articulo_id' => (int) $idArticulo,
                    'cantidad' => (float) $row['cantidad'],
                    'articulo_lote' => [
                        'lote' => $row['lote'] == '' ? null : $row['lote'],
                        'fecha_vencimiento' => $row['fecha_vencimiento'] == '' ? null : $row['fecha_vencimiento'],
                    ],
                ];

                $detallesTransacciones[] = $detalleTransaccion;
            });
            
            $entradaArticulos = [
                'institucion_id' => null,
                'fecha' => now(),
                'comprobante' => Transaccion::COMPROBANTE_IMPORTACION_DATOS,
                'numero_comprobante' => Transaccion::where(
                    'comprobante',
                    Transaccion::COMPROBANTE_IMPORTACION_DATOS
                )->count() + 1,
                'observacion' => 'Entrada de artículos por importación de datos',
                'tipo' => Transaccion::TIPO_ENTRADA,
                'usuario_id' => $request->user()->id,
                'estado_entrada' => Transaccion::ESTADO_ENTRADA_VALIDA,
                'detalles_transacciones' => $detallesTransacciones,
            ];

            if (count($detallesTransacciones) === 0) {
                throw new BadRequestHttpException(
                    'No se encontraron artículos en el archivo. '
                );
            }

            $transaccion = $this->guardarEntrada($entradaArticulos);

            DB::commit();

            return response()->jsonResponse(
                'Entrada de artículos por importación de datos creada',
                $transaccion,
                201
            );
        } catch (\Exception $e) {
            DB::rollBack();

            if ($e instanceof ModelNotFoundException) {
                throw new NotFoundHttpException(
                    'No se encontró alguno de los productos. ' .
                    'Verifica que los datos sean correctos y vuelve a intentarlo.'
                );
            }
        
            if ($e instanceof QueryException) {
                throw new BadRequestHttpException(
                    'El formato de la fecha de vencimiento no es correcto. ' .
                    'Verifica que los datos sean correctos y vuelve a intentarlo.'
                );
            }

            throw $e;
        }
    }

    public function formatoImportacion()
    {
        $writer = SimpleExcelWriter::streamDownload('Formato_de_importacion_de_entradas_de_articulos.xlsx');

        $writer->nameCurrentSheet('Entradas de Artículos');
        $writer->addHeader(['id', 'articulo', 'cantidad', 'lote', 'fecha_vencimiento']);
        $writer->addRow([
            'Identificador (id) del artículo (tiene que existir en la base de datos)',
            'Nombre del artículo (no debe estar vacío)',
            'Cantidad del artículo (debe ser un número)',
            'Lote del artículo (opcional)',
            'Fecha de vencimiento del artículo (formato: YYYY-MM-DD, opcional)',
        ]);
    }
}
