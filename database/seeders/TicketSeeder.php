<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tickets')->insert([
            [
                'issue' => 'Error en la página de inicio',
                'client_id' => 4,
                'assigned_to' => 2,
                'description' => 'La página principal no carga correctamente en ciertos navegadores.',
                'system_id' => 1,
                'status' => 'open',
                'priority' => 'high',
                'category' => 'bug',
                'assigned_at' => Carbon::now(),
                'resolved_at' => null,
                'comments' => 'Se requiere revisión en diferentes navegadores.',
                'score' => 0,
                'resolution' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'issue' => 'Nueva funcionalidad de reporte',
                'client_id' => 4,
                'assigned_to' => null,
                'description' => 'Solicitud de un nuevo módulo de reportes para usuarios.',
                'system_id'=>2,
                'status' => 'pending',
                'priority' => 'medium',
                'category' => 'feature_request',
                'assigned_at' => null,
                'resolved_at' => null,
                'comments' => 'Esperando aprobación.',
                'score' => 0,
                'resolution' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'issue' => 'Error en la carga de archivos',
                'client_id' => 4,
                'assigned_to' => null,
                'description' => 'Los archivos de carga no se cargan correctamente.',
                'system_id'=>3,
                'status' => 'pending',
                'priority' => 'high',
                'category' => 'bug',
                'assigned_at' => null,
                'resolved_at' => null,
                'comments' => 'Se requiere revisión.',
                'score' => 0,
                'resolution' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
