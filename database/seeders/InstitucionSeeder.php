<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstitucionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('instituciones')->insert([
            [
                'id' => 1,
                'nombre' => 'HONORABLE ALCALDIA MUNICIPAL DE TARABUCO',
                'direccion' => null,
                'telefono' => null,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'id' => 2,
                'nombre' => 'HONORABLE CONCEJO MUNICIPAL DE TARABUCO',
                'direccion' => null,
                'telefono' => null,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'id' => 3,
                'nombre' => 'UNIDAD DE GESTION EDUCATIVA DISTRITAL TARABUCO',
                'direccion' => null,
                'telefono' => null,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'id' => 4,
                'nombre' => 'GOBERNACION DEPARTAMENTAL DE CHUQUISACA',
                'direccion' => null,
                'telefono' => null,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'id' => 5,
                'nombre' => 'UNIDAD EDUCATIVA "ANICETO ARCE"',
                'direccion' => null,
                'telefono' => null,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
        ]);
    }
}
