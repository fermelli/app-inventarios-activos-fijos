<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatosGenericosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categorias')->insert(['id' => 1, 'nombre' => 'General']);
        DB::table('unidades')->insert(['id' => 1, 'nombre' => 'Unidad']);
        DB::table('ubicaciones')->insert(['id' => 1, 'nombre' => 'General']);
    }
}
