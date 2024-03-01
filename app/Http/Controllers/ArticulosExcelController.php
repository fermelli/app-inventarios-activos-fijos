<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ArticuloTrait;
use App\Http\Requests\IndexArticuloControllerRequest;
use App\Http\Requests\SubirArchivoExcelRequest;
use App\Models\Articulo;
use App\Models\Categoria;
use App\Models\Ubicacion;
use App\Models\Unidad;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Spatie\SimpleExcel\SimpleExcelReader;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ArticulosExcelController extends Controller
{
    use ArticuloTrait;
    
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
         * Variable que almacena las unidades.
         *
         * @var \App\Models\Unidad[]
        */
        $unidades = [];

        /**
         * Variable que almacena las ubicaciones.
         *
         * @var \App\Models\Ubicacion[]
        */
        $ubicaciones = [];

        /**
         * Variable que almacena los artículos.
         *
         * @var array
         */
        $articulos = [];

        try {
            $rows = SimpleExcelReader::create($archivoExcel, 'xlsx')->getRows();
            $ahora = now();

            $rows->each(function (array $row) use ($categorias, $unidades, $ubicaciones, &$articulos, $ahora) {
                if (
                    !isset($row['categoria']) ||
                    !isset($row['unidad']) ||
                    !isset($row['ubicacion']) ||
                    !isset($row['codigo']) ||
                    !isset($row['nombre'])
                ) {
                    throw new BadRequestHttpException(
                        'El archivo no tiene el formato correcto. ' .
                        'Verifica que el archivo tenga el formato correcto y vuelve a intentarlo. ' .
                        'Descarga el formato correcto haciendo clic en el botón "Formato de Ejemplo".'
                    );
                }
                
                $nombreCategoria = $row['categoria'];
                $nombreUnidad = $row['unidad'];
                $nombreUbicacion = $row['ubicacion'];

                if (!isset($categorias[$nombreCategoria])) {
                    $categorias[$nombreCategoria] = Categoria::where('nombre', $nombreCategoria)->firstOrFail();
                }

                if (!isset($unidades[$nombreUnidad])) {
                    $unidades[$nombreUnidad] = Unidad::where('nombre', $nombreUnidad)->firstOrFail();
                }

                if (!isset($ubicaciones[$nombreUbicacion])) {
                    $ubicaciones[$nombreUbicacion] = Ubicacion::where('nombre', $nombreUbicacion)->firstOrFail();
                }

                $articulo = [
                    'categoria_id' => $categorias[$nombreCategoria]->id,
                    'unidad_id' => $unidades[$nombreUnidad]->id,
                    'ubicacion_id' => $ubicaciones[$nombreUbicacion]->id,
                    'codigo' => $row['codigo'],
                    'nombre' => $row['nombre'],
                    'tipo' => Articulo::TIPO_ALMACENABLE,
                    'creado_en' => $ahora,
                    'actualizado_en' => $ahora,
                ];

                $articulos[] = $articulo;
            });

            Articulo::insert($articulos);

            return response()->jsonResponse('Artículos importados correctamente', null, 200);
        } catch (\Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                throw new NotFoundHttpException(
                    'No se encontró alguna de las categorías, unidades o ubicaciones. ' .
                    'Verifica que los datos sean correctos y vuelve a intentarlo.'
                );
            }

            if ($e instanceof UniqueConstraintViolationException) {
                throw new BadRequestHttpException(
                    'Ya existe un artículo con el mismo código. ' .
                    'Verifica que los datos sean correctos y vuelve a intentarlo.'
                );
            }

            if ($e instanceof QueryException) {
                throw new BadRequestHttpException(
                    'El código o nombre de algún artículo es demasiado largo. ' .
                    'Verifica que los datos sean correctos y vuelve a intentarlo.'
                );
            }

            throw $e;
        }
    }

    public function formatoImportacion()
    {
        $writer = SimpleExcelWriter::streamDownload('Formato de importación de artículos.xlsx');
        
        $writer->nameCurrentSheet('Artículos');
        $writer->addHeader(['categoria', 'unidad', 'ubicacion', 'codigo', 'nombre']);
        $writer->addRow([
            'Nombre de la categoría (tiene que estar registrada en el sistema)',
            'Nombre de la unidad (tiene que estar registrada en el sistema)',
            'Nombre de la ubicación (tiene que estar registrada en el sistema)',
            'Código del artículo (debe ser único y maximo 255 caracteres)',
            'Nombre del artículo (maximo 255 caracteres)',
        ]);

        return $writer->toBrowser();
    }

    public function exportar(IndexArticuloControllerRequest $request)
    {
        $parametros = $request->validated();
        $articulos = $this->indexQueryBuilder($parametros)->get();

        $writer = SimpleExcelWriter::streamDownload('Articulos.xlsx');
        
        $writer->nameCurrentSheet('Artículos');
        $writer->addHeader(['CÓDIGO', 'ID.', 'NOMBRE', 'STOCK', 'CATEOGRÍA', 'UNIDAD', 'UBICACIÓN']);
        $articulos->each(function (Articulo $articulo) use ($writer) {
            $writer->addRow([
                $articulo->codigo,
                $articulo->id,
                $articulo->nombre,
                (float) $articulo->cantidad,
                $articulo->categoria->nombre,
                $articulo->unidad->nombre,
                $articulo->ubicacion->nombre,
            ]);
        });

        return $writer->toBrowser();
    }
}
