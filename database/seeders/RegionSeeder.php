<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Region; // Assurez-vous d'avoir le modèle Region
use Illuminate\Support\Str;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $regions = [
            'Bamako', 'Kayes', 'Koulikoro', 'Sikasso', 'Ségou', 
            'Mopti', 'Tombouctou', 'Gao', 'Kidal', 'Taoudénit', 
            'Ménaka', 'Nioro', 'Kita', 'Dioïla', 'Nara', 
            'Bougouni', 'Koutiala', 'San', 'Douentza', 'Bandiagara'
        ];

        foreach ($regions as $name) {
            Region::firstOrCreate([
                'id' => (string) Str::uuid(),
                'name' => $name,
            ]);
        }
    }
}