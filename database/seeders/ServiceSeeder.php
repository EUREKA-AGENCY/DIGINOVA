<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'title' => 'Développement Web',
                'slug' => 'developpement-web',
                'category' => 'web',
                'excerpt' => 'Sites vitrines, e-commerce, applications web',
                'content' => 'Conception et réalisation de solutions web sur-mesure.',
                'icon' => 'bi-code-slash',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Applications Mobiles',
                'slug' => 'applications-mobiles',
                'category' => 'mobile',
                'excerpt' => 'iOS, Android, PWA',
                'content' => 'Apps natives et hybrides, publication stores.',
                'icon' => 'bi-phone',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Infogérance & Support',
                'slug' => 'infogerance-support',
                'category' => 'support',
                'excerpt' => 'Maintenance, supervision, helpdesk',
                'content' => 'Contrats de support et services managés.',
                'icon' => 'bi-life-preserver',
                'order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($services as $s) {
            Service::updateOrCreate(['slug' => $s['slug']], $s);
        }
    }
}

