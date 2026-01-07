<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Champ;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class TestAgriDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Créer un utilisateur de test 
        $user = User::updateOrCreate(
            ['email' => 'test@agrialert.com'],
            [
                'nom' => 'Agriculteur Test',
                'telephone' => '70000000',
                'motdepasse' => Hash::make('password'), 
                'role' => 'Agriculteur',                
                'fcm_token' => 'fake_token_fcm_12345',
                'is_active' => true
            ]
        );

        // 2. Créer un champ à Bamako
        Champ::updateOrCreate(
            ['nom' => 'Champ de Maïs Bamako'],
            [
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'latitude' => 12.6392,
                'longitude' => -8.0029,
                'aire' => 2.5
            ]
        );

        $this->command->info('Données de test créées avec succès !');
    }
}