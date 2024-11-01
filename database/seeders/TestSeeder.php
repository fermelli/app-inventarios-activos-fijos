<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            AsignadoSeeder::class,
            CategoriaSeeder::class,
            InstitucionSeeder::class,
            UbicacionSeeder::class,
            ActivoFijoSeeder::class,
        ]);
    }
}
