<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UbicacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ubicaciones')->insert([
            [ 'id' => 1, 'nombre' => 'BODEGA' ],
            [ 'id' => 2, 'nombre' => 'SALA DE COMPUTO' ],
            [ 'id' => 3, 'nombre' => 'SALA DE PROFESORES' ],
            [ 'id' => 4, 'nombre' => 'SALA DE JUNTAS' ],
            [ 'id' => 5, 'nombre' => 'SALA DE CLASES' ],
            [ 'id' => 6, 'nombre' => 'AUDITORIO' ],
            [ 'id' => 7, 'nombre' => 'BIBLIOTECA' ],
            [ 'id' => 8, 'nombre' => 'CAFETERIA' ],
            [ 'id' => 9, 'nombre' => 'LABORATORIO' ],
            [ 'id' => 10, 'nombre' => 'OFICINA' ],
            [ 'id' => 11, 'nombre' => 'PATIO' ],
            [ 'id' => 12, 'nombre' => 'PASILLO' ],
            [ 'id' => 13, 'nombre' => 'AULA 101' ],
            [ 'id' => 14, 'nombre' => 'AULA 102' ],
            [ 'id' => 15, 'nombre' => 'AULA 103' ],
            [ 'id' => 16, 'nombre' => 'AULA 104' ],
            [ 'id' => 17, 'nombre' => 'AULA 105' ],
            [ 'id' => 18, 'nombre' => 'AULA 201' ],
            [ 'id' => 19, 'nombre' => 'AULA 202' ],
            [ 'id' => 20, 'nombre' => 'AULA 203' ],
            [ 'id' => 21, 'nombre' => 'AULA 204' ],
            [ 'id' => 22, 'nombre' => 'AULA 205' ],
            [ 'id' => 23, 'nombre' => 'AULA 301' ],
            [ 'id' => 24, 'nombre' => 'AULA 302' ],
            [ 'id' => 25, 'nombre' => 'AULA 303' ],
        ]);
    }
}
