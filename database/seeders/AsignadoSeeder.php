<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AsignadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('usuarios')->insert([
            [
                'id' => 3,
                'nombre' => 'Prof. Juan Perez',
                'correo_electronico' => 'juan.perez@gmail.com',
                'password' => bcrypt(env('PASSWORD_POR_DEFECTO', 'Password123$')),
                'rol' => User::ROL_PERSONAL,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'id' => 4,
                'nombre' => 'Prof. Maria Lopez',
                'correo_electronico' => 'maria.lopez@gmail.com',
                'password' => bcrypt(env('PASSWORD_POR_DEFECTO', 'Password123$')),
                'rol' => User::ROL_PERSONAL,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'id' => 5,
                'nombre' => 'Sr. Pedro Rodriguez',
                'correo_electronico' => 'pedro.rodriguez@gmail.com',
                'password' => bcrypt(env('PASSWORD_POR_DEFECTO', 'Password123$')),
                'rol' => User::ROL_PERSONAL,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'id' => 6,
                'nombre' => 'Sr. Jose Martinez',
                'correo_electronico' => 'jose.martinez@gmail.com',
                'password' => bcrypt(env('PASSWORD_POR_DEFECTO', 'Password123$')),
                'rol' => User::ROL_PERSONAL,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'id' => 7,
                'nombre' => 'Dir. Ana Garcia',
                'correo_electronico' => 'ana.garcia@gmail.com',
                'password' => bcrypt(env('PASSWORD_POR_DEFECTO', 'Password123$')),
                'rol' => User::ROL_PERSONAL,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'id' => 8,
                'nombre' => 'Sr. Carlos Sanchez',
                'correo_electronico' => 'carlos.sanchez@gmail.com',
                'password' => bcrypt(env('PASSWORD_POR_DEFECTO', 'Password123$')),
                'rol' => User::ROL_PERSONAL,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'id' => 9,
                'nombre' => 'Sr. Luis Ramirez',
                'correo_electronico' => 'luis.ramirez@gmail.com',
                'password' => bcrypt(env('PASSWORD_POR_DEFECTO', 'Password123$')),
                'rol' => User::ROL_PERSONAL,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'id' => 10,
                'nombre' => 'Sr. Rocio Flores',
                'correo_electronico' => 'rocio.flores@gmail.com',
                'password' => bcrypt(env('PASSWORD_POR_DEFECTO', 'Password123$')),
                'rol' => User::ROL_PERSONAL,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
        ]);
    }
}
