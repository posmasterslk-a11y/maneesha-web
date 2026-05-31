<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name'        => 'Sarees',
                'slug'        => 'sarees',
                'description' => 'Elegant sarees for every occasion — from daily wear to grand weddings.',
                'is_active'   => true,
                'sort_order'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Frocks',
                'slug'        => 'frocks',
                'description' => 'Beautiful frocks and dresses for women and girls.',
                'is_active'   => true,
                'sort_order'  => 2,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Blouses',
                'slug'        => 'blouses',
                'description' => 'Stylish blouses to complement your sarees and outfits.',
                'is_active'   => true,
                'sort_order'  => 3,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Lehengas',
                'slug'        => 'lehengas',
                'description' => 'Traditional and contemporary lehengas for festivals and celebrations.',
                'is_active'   => true,
                'sort_order'  => 4,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Tops & Tunics',
                'slug'        => 'tops-tunics',
                'description' => 'Casual and office-ready tops and tunics.',
                'is_active'   => true,
                'sort_order'  => 5,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Wedding Collection',
                'slug'        => 'wedding-collection',
                'description' => 'Exclusive bridal and wedding guest wear for the most special occasions.',
                'is_active'   => true,
                'sort_order'  => 6,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ];

        DB::table('categories')->insertOrIgnore($categories);

        $this->command->info('✅ ' . count($categories) . ' categories created.');
    }
}
