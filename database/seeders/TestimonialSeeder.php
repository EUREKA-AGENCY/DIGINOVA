<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Testimonial;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'author_name' => 'Sophie Martin',
                'author_role' => 'DG',
                'company' => 'Alphatech',
                'content' => 'Équipe très réactive et résultats au rendez-vous. Notre site e-commerce a doublé ses ventes.',
                'rating' => 5,
                'is_published' => true,
            ],
            [
                'author_name' => 'Karim Bensaïd',
                'author_role' => 'DSI',
                'company' => 'NovaCorp',
                'content' => 'Location de serveurs simple et fiable, support pro.',
                'rating' => 5,
                'is_published' => true,
            ],
        ];

        foreach ($items as $t) {
            Testimonial::create($t);
        }
    }
}

