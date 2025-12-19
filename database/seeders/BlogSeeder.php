<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogPost;
use Carbon\Carbon;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title' => 'Tendances 2025: IA et Productivité',
                'slug' => 'tendances-2025-ia-productivite',
                'excerpt' => 'Comment l’IA accélère les projets IT en PME.',
                'category' => 'Innovation',
                'tags' => ['IA','Productivité'],
                'content' => "# L'IA au service des équipes\n**Automatisation**, analyse prédictive et copilotes dev...",
                'cover_image' => null,
                'published_at' => Carbon::now()->subDays(10),
                'is_published' => true,
            ],
            [
                'title' => 'Guide: Choisir son parc informatique',
                'slug' => 'guide-choisir-parc-informatique',
                'excerpt' => 'Achat vs location: critères de choix.',
                'category' => 'Matériel',
                'tags' => ['Parc','Location','Achat'],
                'content' => "## Location ou achat ?\n- Budget\n- Flexibilité\n- Maintenance",
                'cover_image' => null,
                'published_at' => Carbon::now()->subDays(5),
                'is_published' => true,
            ],
        ];

        foreach ($posts as $p) {
            BlogPost::updateOrCreate(['slug' => $p['slug']], $p);
        }
    }
}

