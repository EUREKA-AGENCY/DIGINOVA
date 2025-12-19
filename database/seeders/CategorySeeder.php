<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => 'Ordinateurs Portables', 'slug' => 'ordinateurs-portables'],
            ['name' => 'Stations de Travail', 'slug' => 'stations-de-travail'],
            ['name' => 'Serveurs', 'slug' => 'serveurs'],
            ['name' => 'Réseau', 'slug' => 'reseau'],
            ['name' => 'Périphériques', 'slug' => 'peripheriques'],
        ];
        foreach ($data as $d) {
            Category::firstOrCreate(['slug' => $d['slug']], $d);
        }
    }
}

