<?php

namespace Database\Seeders;

use App\Models\Articulo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivoFijoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('articulos')->insert([
            // MOBILIARIO
            // MESAS
            [
                'categoria_id' => 2,
                'institucion_id' => 1,
                'codigo' => 'MOB-MES-001',
                'nombre' => 'MESA DE MADERA',
                'descripcion' => 'Mesa de madera de 1.20m x 0.80m, color café.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'categoria_id' => 2,
                'institucion_id' => 1,
                'codigo' => 'MOB-MES-002',
                'nombre' => 'MESA DE ESTUDIO',
                'descripcion' => 'Mesa con superficie de melamina blanca, patas de metal, 1.50m x 0.60m.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'categoria_id' => 2,
                'institucion_id' => 1,
                'codigo' => 'MOB-MES-003',
                'nombre' => 'MESA DE REUNIONES',
                'descripcion' => 'Mesa rectangular de 2.00m x 1.00m, ideal para reuniones, color gris oscuro.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            // SILLAS
            [
                'categoria_id' => 3,
                'institucion_id' => 1,
                'codigo' => 'MOB-SIL-001',
                'nombre' => 'SILLA DE OFICINA',
                'descripcion' => 'Silla ergonómica con base de metal y respaldo alto, color negro.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'categoria_id' => 3,
                'institucion_id' => 1,
                'codigo' => 'MOB-SIL-002',
                'nombre' => 'SILLA PLEGABLE',
                'descripcion' => 'Silla plegable de plástico, color azul, con estructura metálica.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'categoria_id' => 3,
                'institucion_id' => 1,
                'codigo' => 'MOB-SIL-003',
                'nombre' => 'SILLA ESCOLAR',
                'descripcion' => 'Silla escolar con asiento de madera y estructura de metal, color marrón.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            // ESTANTES
            [
                'categoria_id' => 4,
                'institucion_id' => 5,
                'codigo' => 'MOB-EST-001',
                'nombre' => 'ESTANTE DE MADERA',
                'descripcion' => 'Estante de madera con cinco niveles, color nogal.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'categoria_id' => 4,
                'institucion_id' => 1,
                'codigo' => 'MOB-EST-002',
                'nombre' => 'ESTANTE METÁLICO',
                'descripcion' => 'Estante de metal con cuatro niveles, resistente, color gris.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'categoria_id' => 4,
                'institucion_id' => 1,
                'codigo' => 'MOB-EST-003',
                'nombre' => 'ESTANTE DE BIBLIOTECA',
                'descripcion' => 'Estante de biblioteca de madera con seis compartimientos, color marrón oscuro.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            // PUPITRES
            [
                'categoria_id' => 5,
                'institucion_id' => 1,
                'codigo' => 'MOB-PUP-001',
                'nombre' => 'PUPITRE INDIVIDUAL',
                'descripcion' => 'Pupitre de madera con asiento fijo y mesa adjunta, color marrón.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'categoria_id' => 5,
                'institucion_id' => 2,
                'codigo' => 'MOB-PUP-002',
                'nombre' => 'PUPITRE CON PALETA',
                'descripcion' => 'Pupitre de madera con asiento y paleta lateral, ideal para exámenes.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'categoria_id' => 5,
                'institucion_id' => 1,
                'codigo' => 'MOB-PUP-003',
                'nombre' => 'PUPITRE INFANTIL',
                'descripcion' =>
                    'Pupitre infantil de plástico, resistente y colorido, para estudiantes de nivel inicial.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            // ARMARIOS
            [
                'categoria_id' => 6,
                'institucion_id' => 4,
                'codigo' => 'MOB-ARM-001',
                'nombre' => 'ARMARIO DE OFICINA',
                'descripcion' => 'Armario de madera con dos puertas y tres estantes internos, color marrón claro.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'categoria_id' => 6,
                'institucion_id' => 1,
                'codigo' => 'MOB-ARM-002',
                'nombre' => 'ARMARIO METÁLICO',
                'descripcion' => 'Armario metálico con cerradura, ideal para almacenar documentos confidenciales.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'categoria_id' => 6,
                'institucion_id' => 4,
                'codigo' => 'MOB-ARM-003',
                'nombre' => 'ARMARIO DE LABORATORIO',
                'descripcion' => 'Armario de metal con estantes para almacenar reactivos químicos, color blanco.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            // EQUIPOS DE TECNOLOGIA
            // COMPUTADORAS
            [
                'categoria_id' => 8,
                'institucion_id' => 1,
                'codigo' => 'TEC-COM-001',
                'nombre' => 'COMPUTADORA DE ESCRITORIO DELL',
                'descripcion' => 'Computadora de escritorio Dell OptiPlex, Intel Core i5, 8GB RAM, 256GB SSD.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'categoria_id' => 8,
                'institucion_id' => 1,
                'codigo' => 'TEC-COM-002',
                'nombre' => 'LAPTOP HP PROBOOK',
                'descripcion' => 'Laptop HP ProBook, Intel Core i7, 16GB RAM, 512GB SSD, 15.6 pulgadas.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'categoria_id' => 8,
                'institucion_id' => 3,
                'codigo' => 'TEC-COM-003',
                'nombre' => 'COMPUTADORA DE ESCRITORIO LENOVO',
                'descripcion' => 'Computadora de escritorio Lenovo ThinkCentre, AMD Ryzen 5, 8GB RAM, 1TB HDD.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            // IMPRESORAS
            [
                'categoria_id' => 9,
                'institucion_id' => 1,
                'codigo' => 'TEC-IMP-001',
                'nombre' => 'IMPRESORA LÁSER HP',
                'descripcion' => 'Impresora láser HP LaserJet Pro M404, monocromática, conexión Wi-Fi.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'categoria_id' => 9,
                'institucion_id' => 2,
                'codigo' => 'TEC-IMP-002',
                'nombre' => 'IMPRESORA MULTIFUNCIONAL CANON',
                'descripcion' => 'Impresora multifuncional Canon PIXMA, impresión a color, escáner y copiadora.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'categoria_id' => 9,
                'institucion_id' => 1,
                'codigo' => 'TEC-IMP-003',
                'nombre' => 'IMPRESORA EPSON ECOTANK',
                'descripcion' => 'Impresora Epson EcoTank L3110, impresión económica, tanques de tinta recargables.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            // PROYECTORES
            [
                'categoria_id' => 10,
                'institucion_id' => 1,
                'codigo' => 'TEC-PRY-001',
                'nombre' => 'PROYECTOR EPSON',
                'descripcion' => 'Proyector Epson PowerLite, 3600 lúmenes, resolución WXGA.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'categoria_id' => 10,
                'institucion_id' => 1,
                'codigo' => 'TEC-PRY-002',
                'nombre' => 'PROYECTOR BENQ',
                'descripcion' => 'Proyector BenQ MW526, 3200 lúmenes, soporte para HDMI y VGA.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'categoria_id' => 10,
                'institucion_id' => 1,
                'codigo' => 'TEC-PRY-003',
                'nombre' => 'PROYECTOR SONY',
                'descripcion' => 'Proyector Sony VPL-EX435, resolución XGA, conexión HDMI, 3200 lúmenes.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            // TABLETAS
            [
                'categoria_id' => 11,
                'institucion_id' => 1,
                'codigo' => 'TEC-TAB-001',
                'nombre' => 'TABLET APPLE IPAD',
                'descripcion' => 'iPad Apple 10.2 pulgadas, 64GB, Wi-Fi, color gris espacial.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'categoria_id' => 11,
                'institucion_id' => 1,
                'codigo' => 'TEC-TAB-002',
                'nombre' => 'TABLET SAMSUNG GALAXY TAB',
                'descripcion' => 'Tablet Samsung Galaxy Tab A7, pantalla de 10.4 pulgadas, 32GB, Wi-Fi.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'categoria_id' => 11,
                'institucion_id' => 1,
                'codigo' => 'TEC-TAB-003',
                'nombre' => 'TABLET LENOVO TAB M10',
                'descripcion' => 'Tablet Lenovo Tab M10 HD, pantalla de 10.1 pulgadas, 32GB, color gris.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            // TELEVISORES
            [
                'categoria_id' => 12,
                'institucion_id' => 1,
                'codigo' => 'TEC-TEV-001',
                'nombre' => 'TELEVISOR LG 55"',
                'descripcion' => 'Televisor LG 55 pulgadas, Smart TV, resolución 4K UHD.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'categoria_id' => 12,
                'institucion_id' => 2,
                'codigo' => 'TEC-TEV-002',
                'nombre' => 'TELEVISOR SAMSUNG 43"',
                'descripcion' => 'Televisor Samsung 43 pulgadas, Smart TV, resolución Full HD.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'categoria_id' => 12,
                'institucion_id' => 1,
                'codigo' => 'TEC-TEV-003',
                'nombre' => 'TELEVISOR TCL 32"',
                'descripcion' => 'Televisor TCL 32 pulgadas, resolución HD, puerto HDMI y USB.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            // TELEFONOS
            [
                'categoria_id' => 13,
                'institucion_id' => 4,
                'codigo' => 'TEC-TEL-001',
                'nombre' => 'TELÉFONO IP CISCO',
                'descripcion' => 'Teléfono IP Cisco 7821, ideal para comunicaciones de oficina.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'categoria_id' => 13,
                'institucion_id' => 1,
                'codigo' => 'TEC-TEL-002',
                'nombre' => 'TELÉFONO INALÁMBRICO PANASONIC',
                'descripcion' => 'Teléfono inalámbrico Panasonic, con identificador de llamadas y contestador.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'categoria_id' => 13,
                'institucion_id' => 3,
                'codigo' => 'TEC-TEL-003',
                'nombre' => 'SMARTPHONE XIAOMI',
                'descripcion' => 'Smartphone Xiaomi Redmi Note 9, 64GB, color gris, dual SIM.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            // EQUIPOS DEPORTIVOS
            // BALONES
            [
                'categoria_id' => 15,
                'institucion_id' => 1,
                'codigo' => 'DEP-BAL-001',
                'nombre' => 'BALÓN DE FÚTBOL',
                'descripcion' => 'Balón de fútbol, tamaño 5, marca Nike.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'categoria_id' => 15,
                'institucion_id' => 2,
                'codigo' => 'DEP-BAL-002',
                'nombre' => 'BALÓN DE BALONCESTO',
                'descripcion' => 'Balón de baloncesto, tamaño 7, marca Spalding.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'categoria_id' => 15,
                'institucion_id' => 1,
                'codigo' => 'DEP-BAL-003',
                'nombre' => 'BALÓN DE VOLEIBOL',
                'descripcion' => 'Balón de voleibol, tamaño oficial, marca Mikasa.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            // RAQUETAS
            [
                'categoria_id' => 16,
                'institucion_id' => 2,
                'codigo' => 'DEP-RAQ-001',
                'nombre' => 'RAQUETA DE TENIS',
                'descripcion' => 'Raqueta de tenis Wilson, tamaño adulto, marco de grafito.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'categoria_id' => 16,
                'institucion_id' => 1,
                'codigo' => 'DEP-RAQ-002',
                'nombre' => 'RAQUETA DE BADMINTON',
                'descripcion' => 'Raqueta de bádminton Yonex, peso ligero, diseño ergonómico.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            // REDES
            [
                'categoria_id' => 17,
                'institucion_id' => 1,
                'codigo' => 'DEP-RED-001',
                'nombre' => 'RED DE VOLEIBOL',
                'descripcion' => 'Red de voleibol reglamentaria, resistente a la intemperie.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'categoria_id' => 17,
                'institucion_id' => 3,
                'codigo' => 'DEP-RED-002',
                'nombre' => 'RED DE TENIS',
                'descripcion' => 'Red de tenis, tamaño oficial, marca Wilson.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            // CONOS
            [
                'categoria_id' => 18,
                'institucion_id' => 1,
                'codigo' => 'DEP-CON-001',
                'nombre' => 'CONOS DE ENTRENAMIENTO',
                'descripcion' => 'Conos de entrenamiento, color naranja, pack de 20 unidades.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'categoria_id' => 18,
                'institucion_id' => 4,
                'codigo' => 'DEP-CON-002',
                'nombre' => 'CONOS PEQUEÑOS',
                'descripcion' => 'Conos pequeños de entrenamiento, color verde, pack de 30 unidades.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            // PELOTAS
            [
                'categoria_id' => 19,
                'institucion_id' => 1,
                'codigo' => 'DEP-PEL-001',
                'nombre' => 'PELOTAS DE TENIS',
                'descripcion' => 'Pelotas de tenis, pack de 3, marca Wilson.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'categoria_id' => 19,
                'institucion_id' => 1,
                'codigo' => 'DEP-PEL-002',
                'nombre' => 'PELOTAS DE BÉISBOL',
                'descripcion' => 'Pelotas de béisbol, pack de 5, costura a mano.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            // AROS
            [
                'categoria_id' => 20,
                'institucion_id' => 1,
                'codigo' => 'DEP-ARO-001',
                'nombre' => 'ARO DE BALONCESTO',
                'descripcion' => 'Aro de baloncesto de acero, con red.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'categoria_id' => 20,
                'institucion_id' => 1,
                'codigo' => 'DEP-ARO-002',
                'nombre' => 'ARO DE ENTRENAMIENTO',
                'descripcion' => 'Aros de plástico para entrenamiento, 40 cm de diámetro, pack de 5.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            // EQUIPOS DE LABORATORIO
            // MICROSCOPIOS
            [
                'categoria_id' => 22,
                'institucion_id' => 1,
                'codigo' => 'LAB-MIC-001',
                'nombre' => 'MICROSCOPIO ÓPTICO',
                'descripcion' => 'Microscopio óptico con aumento de hasta 1000x, marca Zeiss.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'categoria_id' => 22,
                'institucion_id' => 1,
                'codigo' => 'LAB-MIC-002',
                'nombre' => 'MICROSCOPIO ESTEREOSCÓPICO',
                'descripcion' => 'Microscopio estereoscópico de alta resolución, marca Leica.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            // BALANZAS
            [
                'categoria_id' => 23,
                'institucion_id' => 1,
                'codigo' => 'LAB-BAL-001',
                'nombre' => 'BALANZA ANALÍTICA',
                'descripcion' => 'Balanza analítica con precisión de 0.1 mg, marca OHAUS.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'categoria_id' => 23,
                'institucion_id' => 1,
                'codigo' => 'LAB-BAL-002',
                'nombre' => 'BALANZA DE PRECISIÓN',
                'descripcion' => 'Balanza de precisión de 200g con sensibilidad de 0.01g, marca Adam.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => rand(1, 10) === 1
                                        ? Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA
                                        : Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            // TUBOS DE ENSAYO
            [
                'categoria_id' => 24,
                'institucion_id' => 1,
                'codigo' => 'LAB-TUB-001',
                'nombre' => 'TUBOS DE ENSAYO DE VIDRIO',
                'descripcion' => 'Tubos de ensayo de vidrio resistente al calor, pack de 50 unidades.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'categoria_id' => 24,
                'institucion_id' => 1,
                'codigo' => 'LAB-TUB-002',
                'nombre' => 'TUBOS DE ENSAYO DE PLÁSTICO',
                'descripcion' => 'Tubos de ensayo de plástico para uso en pruebas rápidas, pack de 100 unidades.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            // PIPETAS
            [
                'categoria_id' => 25,
                'institucion_id' => 1,
                'codigo' => 'LAB-PIP-001',
                'nombre' => 'PIPETA VOLUMÉTRICA',
                'descripcion' => 'Pipeta volumétrica de 10 ml, alta precisión, marca Eppendorf.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'categoria_id' => 25,
                'institucion_id' => 1,
                'codigo' => 'LAB-PIP-002',
                'nombre' => 'PIPETA GRADUADA',
                'descripcion' => 'Pipeta graduada de 25 ml, vidrio, marca Pyrex.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            // PROBETAS
            [
                'categoria_id' => 26,
                'institucion_id' => 1,
                'codigo' => 'LAB-PRO-001',
                'nombre' => 'PROBETA DE VIDRIO 100 ML',
                'descripcion' => 'Probeta de vidrio, 100 ml, marca Pyrex.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'categoria_id' => 26,
                'institucion_id' => 1,
                'codigo' => 'LAB-PRO-002',
                'nombre' => 'PROBETA DE PLÁSTICO 50 ML',
                'descripcion' => 'Probeta de plástico resistente a impactos, 50 ml.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            // BURETAS
            [
                'categoria_id' => 27,
                'institucion_id' => 1,
                'codigo' => 'LAB-BUR-001',
                'nombre' => 'BURETA DE VIDRIO 50 ML',
                'descripcion' => 'Bureta de vidrio de 50 ml, grifo de precisión, marca Pyrex.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
            [
                'categoria_id' => 27,
                'institucion_id' => 1,
                'codigo' => 'LAB-BUR-002',
                'nombre' => 'BURETA DIGITAL',
                'descripcion' => 'Bureta digital con calibración automática, capacidad 25 ml.',
                'tipo' => Articulo::TIPO_ACTIVO_FIJO,
                'estado_activo_fijo' => Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
                'creado_en' => now(),
                'actualizado_en' => now(),
            ],
        ]);
    }
}
