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
            ['id' => 1, 'nombre' => 'Categoría 1'],
            ['id' => 2, 'nombre' => 'Categoría 2'],
            ['id' => 3, 'nombre' => 'Categoría 3'],
            ['id' => 4, 'nombre' => 'Categoría 4'],
            ['id' => 5, 'nombre' => 'Categoría 5'],
        ]);

        DB::table('categorias')->insert([
            ['id' => 6, 'nombre' => 'Categoría 6', 'categoria_padre_id' => 1],
            ['id' => 7, 'nombre' => 'Categoría 7', 'categoria_padre_id' => 1],
            ['id' => 8, 'nombre' => 'Categoría 8', 'categoria_padre_id' => 2],
            ['id' => 9, 'nombre' => 'Categoría 9', 'categoria_padre_id' => 6],
            ['id' => 10, 'nombre' => 'Categoría 10', 'categoria_padre_id' => 3],
            ['id' => 11, 'nombre' => 'Categoría 11', 'categoria_padre_id' => 6],
            ['id' => 12, 'nombre' => 'Categoría 12', 'categoria_padre_id' => 4],
            ['id' => 13, 'nombre' => 'Categoría 13', 'categoria_padre_id' => 4],
            ['id' => 14, 'nombre' => 'Categoría 14', 'categoria_padre_id' => 4],
            ['id' => 15, 'nombre' => 'Categoría 15', 'categoria_padre_id' => 14],
        ]);
    }
}
