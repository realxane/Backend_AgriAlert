<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CultureSeeder extends Seeder
{
    public function run(): void
    {
        $cultures = [
            ['nom' => 'Tomate', 'cycle' => 90],
            ['nom' => 'Fraise', 'cycle' => 120],
            ['nom' => 'Raisin', 'cycle' => 150],
            ['nom' => 'Papaye', 'cycle' => 270],
            ['nom' => 'Citron', 'cycle' => 365],
            ['nom' => 'Orange', 'cycle' => 365],
            ['nom' => 'Mangue', 'cycle' => 365],
            ['nom' => 'Poivre', 'cycle' => 180],
            ['nom' => 'Pomme', 'cycle' => 365],
            ['nom' => 'Pomme de terre', 'cycle' => 110],
        ];

        foreach ($cultures as $culture) {
            DB::table('cultures')->insert([
                'id' => (string) Str::uuid(),
                'nom' => $culture['nom'],
                'cycle_date' => $culture['cycle'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}