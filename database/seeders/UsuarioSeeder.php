<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('usuarios')->insert([
            [
                'id' => 1,
                'nombre' => 'admin',
                'correo_electronico' => 'admin@email.com',
                'password' => bcrypt('Password123$'),
                'rol' => User::ROL_ADMINISTRADOR,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'id' => 2,
                'nombre' => 'personal',
                'correo_electronico' => 'personal@email.com',
                'password' => bcrypt('Password123$'),
                'rol' => User::ROL_PERSONAL,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
        ]);
    }
}
