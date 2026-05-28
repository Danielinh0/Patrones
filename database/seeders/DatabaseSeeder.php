<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Titular;
use App\Models\Ruta;
use App\Models\Tarjeta;
use App\Models\TarjetaGeneral;
use App\Models\TarjetaEstudiante;
use App\Models\TarjetaAdultoMayor;
use App\Models\TarjetaTurista;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Crear usuario de prueba
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // 2. Crear Titulares
        $titular1 = Titular::create([
            'curp' => 'PEJU900101HDFXXN01',
            'nombre' => 'Juan Pérez',
            'edad' => 34,
        ]);

        $titular2 = Titular::create([
            'curp' => 'MAGE850505MDFXXN02',
            'nombre' => 'María García',
            'edad' => 21,
        ]);

        $titular3 = Titular::create([
            'curp' => 'LOAN501010HDFXXN03',
            'nombre' => 'Andrés López',
            'edad' => 72,
        ]);

        $titular4 = Titular::create([
            'curp' => 'SAMA951212MDFXXN04',
            'nombre' => 'Ana Sánchez',
            'edad' => 28,
        ]);

        // 3. Crear Tarjetas y sus registros específicos correspondientes (Emulando el factory)
        DB::transaction(function () use ($titular1, $titular2, $titular3, $titular4) {
            // Tarjeta General (Activa)
            $tar1 = Tarjeta::create([
                'saldo_actual' => 150,
                'estado' => 'Activa',
                'tipo' => 'GENERAL',
                'id_titular' => $titular1->id_titular,
            ]);
            TarjetaGeneral::create([
                'id_tarjeta_general' => $tar1->id_tarjeta,
            ]);

            // Tarjeta Estudiante (Activa, saldo bajo)
            $tar2 = Tarjeta::create([
                'saldo_actual' => 18,
                'estado' => 'Activa',
                'tipo' => 'ESTUDIANTE',
                'id_titular' => $titular2->id_titular,
            ]);
            TarjetaEstudiante::create([
                'id_tarjeta_estudiante' => $tar2->id_tarjeta,
                'institucion_educativa' => 'Instituto Tecnológico de Oaxaca',
                'vigencia_estudiante' => '2026-12-31',
            ]);

            // Tarjeta Adulto Mayor (Bloqueada)
            $tar3 = Tarjeta::create([
                'saldo_actual' => 50,
                'estado' => 'bloqueada',
                'tipo' => 'ADULTO_MAYOR',
                'id_titular' => $titular3->id_titular,
            ]);
            TarjetaAdultoMayor::create([
                'id_tarjeta_adulto_mayor' => $tar3->id_tarjeta,
                'folio_inapam' => 'INAPAM-998877',
            ]);

            // Tarjeta Turista (Vencida)
            $tar4 = Tarjeta::create([
                'saldo_actual' => 0,
                'estado' => 'vencida',
                'tipo' => 'TURISTA',
                'id_titular' => $titular4->id_titular,
            ]);
            TarjetaTurista::create([
                'id_tarjeta_turista' => $tar4->id_tarjeta,
                'fecha_vigencia_turista' => '2026-05-01',
            ]);
        });

        // 4. Crear Rutas de transporte
        Ruta::create(['nombre' => 'Línea 1 - Norte-Sur']);
        Ruta::create(['nombre' => 'Línea 2 - Álamos-Centro']);
        Ruta::create(['nombre' => 'Línea 3 - Tecnológico']);
        Ruta::create(['nombre' => 'Línea 4 - Central']);
    }
}
