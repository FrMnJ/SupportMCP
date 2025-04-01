<?php

namespace Database\Seeders;

use App\Models\System;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('systems')->insert([
            [
                'name' => 'Leo',
            ],
            [
               'name' => 'SIIAU',
            ],
            [
                'name' => 'SIATCE',
            ]
        ]);
    }
}
