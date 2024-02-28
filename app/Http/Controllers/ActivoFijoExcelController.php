<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubirArchivoExcelRequest;
use App\Models\Articulo;
use App\Models\Categoria;
use App\Models\Institucion;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Spatie\SimpleExcel\SimpleExcelReader;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ActivoFijoExcelController extends Controller
{
    public function importar(SubirArchivoExcelRequest $request)
    {
        $archivoExcel = $request->file('archivo');
        
        /**
         * Variable que almacena las categorías.
         *
         * @var \App\Models\Categoria[]
        */
        $categorias = [];

        /**
         * Variable que almacena las instituciones.
         *
         * @var \App\Models\Institucion[]
        */
        $instituciones = [];

        /**
         * Variable que almacena los artículos.
         *
         * @var array
         */
        $activosFijos = [];

        try {
            $rows = SimpleExcelReader::create($archivoExcel, 'xlsx')->getRows();
            $ahora = now();

            $rows->each(function (array $row) use ($categorias, $instituciones, &$activosFijos, $ahora) {
                if (
                    !isset($row['categoria']) ||
                    !isset($row['institucion']) ||
                    !isset($row['codigo']) ||
                    !isset($row['nombre']) ||
                    !isset($row['descripcion']) ||
                    !isset($row['estado_activo_fijo']) ||
                    ($row['estado_activo_fijo'] ==
                        Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA && !isset($row['fecha_baja'])) ||
                    ($row['estado_activo_fijo'] == Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA && !isset($row['razon_baja']))
                ) {
                    throw new BadRequestHttpException(
                        'El archivo no tiene el formato correcto. ' .
                        'Verifica que el archivo tenga el formato correcto y vuelve a intentarlo. ' .
                        'Descarga el formato correcto haciendo clic en el botón "Formato de Ejemplo".'
                    );
                }
                
                $nombreCategoria = $row['categoria'];
                $nombreInsitucion = $row['institucion'];

                if (!isset($categorias[$nombreCategoria])) {
                    $categorias[$nombreCategoria] = Categoria::where('nombre', $nombreCategoria)->firstOrFail();
                }

                if (!isset($instituciones[$nombreInsitucion])) {
                    $instituciones[$nombreInsitucion] = Institucion::where('nombre', $nombreInsitucion)->firstOrFail();
                }

                $articulo = [
                    'categoria_id' => $categorias[$nombreCategoria]->id,
                    'institucion_id' => $instituciones[$nombreInsitucion]->id,
                    'codigo' => $row['codigo'],
                    'nombre' => $row['nombre'],
                    'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                    'descripcion' => $row['descripcion'] == '' ? null : $row['descripcion'],
                    'estado_activo_fijo' => $row['estado_activo_fijo'],
                    'fecha_baja' => $row['fecha_baja'] == '' ? null : $row['fecha_baja'],
                    'razon_baja' => $row['razon_baja'] == '' ? null : $row['razon_baja'],
                    'creado_en' => $ahora,
                    'actualizado_en' => $ahora,
                ];

                $activosFijos[] = $articulo;
            });

            Articulo::insert($activosFijos);

            return response()->jsonResponse('Artículos importados correctamente', null, 200);
        } catch (\Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                throw new NotFoundHttpException(
                    'No se encontró alguna de las categorías o instituciones. ' .
                    'Verifica que los datos sean correctos y vuelve a intentarlo.'
                );
            }

            if ($e instanceof UniqueConstraintViolationException) {
                throw new BadRequestHttpException(
                    'Ya existe un activo fijo con el mismo código. ' .
                    'Verifica que los datos sean correctos y vuelve a intentarlo.'
                );
            }

            if ($e instanceof QueryException) {
                throw new BadRequestHttpException(
                    'El código, nombre o descripción de los activos fijos es demasiado largo. ' .
                    'Verifica que los datos sean correctos y vuelve a intentarlo.'
                );
            }

            throw $e;
        }
    }

    public function formatoImportacion()
    {
        $writer = SimpleExcelWriter::streamDownload('Formato de importación de activos fijos.xlsx');
        
        $writer->nameCurrentSheet('Activos Fijos');
        $writer->addHeader(['categoria',
            'institucion',
            'codigo',
            'nombre',
            'descripcion',
            'estado_activo_fijo',
            'fecha_baja',
            'razon_baja',
        ]);
        $writer->addRow([
            'Nombre de la categoría (tiene que estar registrada en el sistema)',
            'Nombre de la institución (tiene que estar registrada en el sistema)',
            'Código del artículo (debe ser único y maximo 255 caracteres)',
            'Nombre del artículo (maximo 255 caracteres)',
            'Descripción del artículo (opcional, maximo 65535 caracteres)',
            'Estado del activo fijo (activo, de baja)',
            'Fecha de baja (si el estado del activo fijo es "de baja" sino se produce un error, ' .
            'formato: AAAA-MM-DD)',
            'Razón de baja (si el estado del activo fijo es "de baja" sino se produce un error, ' .
            'maximo 1000 caracteres)',
        ]);

        return $writer->toBrowser();
    }
}
