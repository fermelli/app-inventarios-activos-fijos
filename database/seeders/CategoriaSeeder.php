<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categorias')->insert([
            [ 'id' => 1, 'categoria_padre_id' => null, 'nombre' => 'MOBILIARIO' ],
            [ 'id' => 2, 'categoria_padre_id' => 1, 'nombre' => 'MESAS' ],
            [ 'id' => 3, 'categoria_padre_id' => 1, 'nombre' => 'SILLAS' ],
            [ 'id' => 4, 'categoria_padre_id' => 1, 'nombre' => 'ESTANTES' ],
            [ 'id' => 5, 'categoria_padre_id' => 1, 'nombre' => 'PUPITRES' ],
            [ 'id' => 6, 'categoria_padre_id' => 1, 'nombre' => 'ARMARIOS' ],

            [ 'id' => 7, 'categoria_padre_id' => null, 'nombre' => 'EQUIPOS DE TECNOLOGIA' ],
            [ 'id' => 8, 'categoria_padre_id' => 7, 'nombre' => 'COMPUTADORAS' ],
            [ 'id' => 9, 'categoria_padre_id' => 7, 'nombre' => 'IMPRESORAS' ],
            [ 'id' => 10, 'categoria_padre_id' => 7, 'nombre' => 'PROYECTORES' ],
            [ 'id' => 11, 'categoria_padre_id' => 7, 'nombre' => 'TABLETS' ],
            [ 'id' => 12, 'categoria_padre_id' => 7, 'nombre' => 'TELEVISORES' ],
            [ 'id' => 13, 'categoria_padre_id' => 7, 'nombre' => 'TELEFONOS' ],

            [ 'id' => 14, 'categoria_padre_id' => null, 'nombre' => 'EQUIPOS DEPORTIVOS' ],
            [ 'id' => 15, 'categoria_padre_id' => 14, 'nombre' => 'BALONES' ],
            [ 'id' => 16, 'categoria_padre_id' => 14, 'nombre' => 'RAQUETAS' ],
            [ 'id' => 17, 'categoria_padre_id' => 14, 'nombre' => 'REDES' ],
            [ 'id' => 18, 'categoria_padre_id' => 14, 'nombre' => 'CONOS' ],
            [ 'id' => 19, 'categoria_padre_id' => 14, 'nombre' => 'PELOTAS' ],
            [ 'id' => 20, 'categoria_padre_id' => 14, 'nombre' => 'AROS' ],

            [ 'id' => 21, 'categoria_padre_id' => null, 'nombre' => 'EQUIPOS DE LABORATORIO' ],
            [ 'id' => 22, 'categoria_padre_id' => 21, 'nombre' => 'MICROSCOPIOS' ],
            [ 'id' => 23, 'categoria_padre_id' => 21, 'nombre' => 'BALANZAS' ],
            [ 'id' => 24, 'categoria_padre_id' => 21, 'nombre' => 'TUBOS DE ENSAYO' ],
            [ 'id' => 25, 'categoria_padre_id' => 21, 'nombre' => 'PIPETAS' ],
            [ 'id' => 26, 'categoria_padre_id' => 21, 'nombre' => 'PROBETAS' ],
            [ 'id' => 27, 'categoria_padre_id' => 21, 'nombre' => 'BURETAS' ],
        ]);
    }
}
