<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContactRequest;
use App\Models\QuoteRequest;

class DemoRequestsSeeder extends Seeder
{
    public function run(): void
    {
        ContactRequest::create([
            'first_name' => 'Alice',
            'last_name' => 'Durand',
            'email' => 'alice@example.com',
            'topic' => 'Renseignements',
            'message' => 'Bonjour, j’aimerais en savoir plus sur vos offres.',
            'status' => 'new',
        ]);

        QuoteRequest::create([
            'project_name' => 'Refonte site vitrine',
            'project_type' => 'website',
            'budget_range' => '5k-10k',
            'deadline' => '1 à 3 mois',
            'details' => 'Objectif: moderniser notre image. SEO friendly, blog, formulaire contact.',
            'company_name' => 'Beta SAS',
            'contact_name' => 'Marc Petit',
            'email' => 'marc@example.com',
            'status' => 'new',
        ]);
    }
}
