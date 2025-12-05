<?php

namespace Database\Seeders;

use App\Models\OauthClient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OauthClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! OauthClient::find('diginova_forum_client')) {
            OauthClient::create([
                'id' => 'diginova_forum_client',
                'name' => 'DIGINOVA Forum External Client',
                // Secret en clair à communiquer au système externe :
                // diginova_forum_secret_2025
                'secret' => Hash::make('diginova_forum_secret_2025'),
                'redirect_uri' => null,
            ]);
        }
    }
}

