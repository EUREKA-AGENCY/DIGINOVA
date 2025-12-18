<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Utilisateur principal de démonstration pour la connexion à l'interface
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                // Cast "hashed" du modèle User => ce mot de passe sera automatiquement hashé.
                'password' => 'password',
            ],
        );

        // Données de la communauté (forum) + client OAuth par défaut
        $this->call([
            ForumDemoSeeder::class,
            OauthClientSeeder::class,
        ]);
    }
}
