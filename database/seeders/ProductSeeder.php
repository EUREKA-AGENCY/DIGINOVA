<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all()->keyBy('slug');

        $items = [
            [
                'name' => 'Laptop Pro 14"',
                'slug' => 'laptop-pro-14',
                'category' => 'ordinateurs-portables',
                'product_type' => 'sale',
                'sku' => 'LP14-PRO',
                'short_description' => 'Ultrabook 14" pour pros',
                'price' => 1399,
                'stock' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Laptop Air 13"',
                'slug' => 'laptop-air-13',
                'category' => 'ordinateurs-portables',
                'product_type' => 'sale',
                'sku' => 'LA13',
                'short_description' => 'Portable léger et performant',
                'price' => 999,
                'stock' => 15,
                'is_active' => true,
            ],
            [
                'name' => 'Station CAO - Location',
                'slug' => 'station-cao-location',
                'category' => 'stations-de-travail',
                'product_type' => 'rental',
                'sku' => 'ST-LOC-002',
                'short_description' => 'Location station de travail CAO/3D',
                'rent_price' => 149,
                'stock' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Serveur Rack 2U - Location',
                'slug' => 'serveur-2u-location',
                'category' => 'serveurs',
                'product_type' => 'rental',
                'sku' => 'SRV-2U-LOC',
                'short_description' => 'Serveur rackable 2U pour tests ou dev',
                'rent_price' => 199,
                'stock' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($items as $i) {
            $cat = $categories[$i['category']] ?? null;
            $data = $i;
            unset($data['category']);
            $data['category_id'] = $cat?->id;
            Product::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}

