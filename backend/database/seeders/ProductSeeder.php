<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Get category IDs
        $categories = DB::table('categories')->pluck('id', 'slug');

        $products = [
            // ── SAREES ───────────────────────────────────────────────
            [
                'category_id'       => $categories['sarees'] ?? null,
                'name'              => 'Silk Georgette Saree',
                'slug'              => 'silk-georgette-saree',
                'short_description' => 'Premium silk georgette with intricate border work.',
                'description'       => 'Crafted from the finest silk georgette fabric, this saree features delicate embroidery along the border. Perfect for evening events, receptions, and festive occasions. Comes with an unstitched blouse piece.',
                'base_price'        => 4500.00,
                'stock'             => 20,
                'main_image'        => 'images/products/saree_silk_georgette.png',
                'fabric'            => 'Silk Georgette',
                'care_instructions' => 'Dry clean only',
                'is_active'         => true,
                'is_featured'       => true,
                'sort_order'        => 1,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'category_id'       => $categories['sarees'] ?? null,
                'name'              => 'Cotton Batik Saree',
                'slug'              => 'cotton-batik-saree',
                'short_description' => 'Comfortable everyday batik saree with vibrant prints.',
                'description'       => 'A stunning cotton batik saree ideal for daily wear and casual outings. Features authentic Sri Lankan batik prints with a comfortable drape.',
                'base_price'        => 2200.00,
                'stock'             => 35,
                'main_image'        => 'images/products/saree_cotton_batik.png',
                'fabric'            => 'Cotton',
                'care_instructions' => 'Hand wash cold, dry in shade',
                'is_active'         => true,
                'is_featured'       => false,
                'sort_order'        => 2,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            // ── FROCKS ───────────────────────────────────────────────
            [
                'category_id'       => $categories['frocks'] ?? null,
                'name'              => 'Floral Chiffon Frock',
                'slug'              => 'floral-chiffon-frock',
                'short_description' => 'Elegant floral print chiffon frock for formal occasions.',
                'description'       => 'A beautiful chiffon frock featuring a bold floral print, fitted bodice, and flared skirt. Available in multiple sizes with custom tailoring options.',
                'base_price'        => 3200.00,
                'stock'             => 15,
                'main_image'        => 'images/products/frock_floral_chiffon.png',
                'fabric'            => 'Chiffon',
                'care_instructions' => 'Machine wash gentle, hang dry',
                'is_active'         => true,
                'is_featured'       => true,
                'sort_order'        => 3,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'category_id'       => $categories['frocks'] ?? null,
                'name'              => 'Embroidered Evening Gown',
                'slug'              => 'embroidered-evening-gown',
                'short_description' => 'Stunning full-length gown with hand embroidery.',
                'description'       => 'Make a statement at any event with this gorgeous hand-embroidered evening gown. Features a sweetheart neckline, floor-length silhouette, and intricate thread work.',
                'base_price'        => 8500.00,
                'stock'             => 8,
                'main_image'        => 'images/products/gown_embroidered_evening.png',
                'fabric'            => 'Net with lining',
                'care_instructions' => 'Dry clean only',
                'is_active'         => true,
                'is_featured'       => true,
                'sort_order'        => 4,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            // ── BLOUSES ──────────────────────────────────────────────
            [
                'category_id'       => $categories['blouses'] ?? null,
                'name'              => 'Designer Embroidered Blouse',
                'slug'              => 'designer-embroidered-blouse',
                'short_description' => 'Custom stitched designer blouse with mirror work.',
                'description'       => 'A beautifully crafted blouse with mirror work and embroidery. Fully customizable — choose your neck style, sleeve length, and back design. Perfect to pair with any saree.',
                'base_price'        => 1800.00,
                'stock'             => 25,
                'main_image'        => 'images/products/blouse_designer_embroidered.png',
                'fabric'            => 'Brocade',
                'care_instructions' => 'Hand wash only',
                'is_active'         => true,
                'is_featured'       => false,
                'sort_order'        => 5,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            // ── LEHENGAS ─────────────────────────────────────────────
            [
                'category_id'       => $categories['lehengas'] ?? null,
                'name'              => 'Bridal Lehenga Set',
                'slug'              => 'bridal-lehenga-set',
                'short_description' => 'Exquisite 3-piece bridal lehenga with dupatta.',
                'description'       => 'A breathtaking 3-piece bridal lehenga set including choli, lehenga, and dupatta. Adorned with heavy zardozi embroidery and stone work. Custom sizing available.',
                'base_price'        => 18500.00,
                'stock'             => 5,
                'main_image'        => 'images/products/lehenga_bridal_set.png',
                'fabric'            => 'Silk with net dupatta',
                'care_instructions' => 'Dry clean only',
                'is_active'         => true,
                'is_featured'       => true,
                'sort_order'        => 6,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            // ── TOPS & TUNICS ─────────────────────────────────────────
            [
                'category_id'       => $categories['tops-tunics'] ?? null,
                'name'              => 'Casual Linen Kurti',
                'slug'              => 'casual-linen-kurti',
                'short_description' => 'Breathable linen kurti for everyday comfort.',
                'description'       => 'Stay cool and stylish with this breathable linen kurti. Features side slits, 3/4 sleeves, and a relaxed fit. Perfect for both office and casual outings.',
                'base_price'        => 1500.00,
                'stock'             => 50,
                'main_image'        => 'images/products/kurti_casual_linen.png',
                'fabric'            => 'Linen',
                'care_instructions' => 'Machine wash cold',
                'is_active'         => true,
                'is_featured'       => false,
                'sort_order'        => 7,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            // ── WEDDING COLLECTION ────────────────────────────────────
            [
                'category_id'       => $categories['wedding-collection'] ?? null,
                'name'              => 'Kandyan Osariya Set',
                'slug'              => 'kandyan-osariya-set',
                'short_description' => 'Traditional Sri Lankan Kandyan bridal saree set.',
                'description'       => 'A magnificent traditional Kandyan Osariya set for the discerning Sri Lankan bride. Includes the full saree, jacket, and accessories. Hand-crafted with gold thread work and authentic Kandyan design.',
                'base_price'        => 25000.00,
                'stock'             => 4,
                'main_image'        => 'images/products/kandyan_osariya_set.png',
                'fabric'            => 'Gold Tissue / Silk',
                'care_instructions' => 'Dry clean only, store with silica gel',
                'is_active'         => true,
                'is_featured'       => true,
                'sort_order'        => 8,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
        ];

        foreach ($products as $product) {
            $productId = DB::table('products')->insertGetId($product);

            // Insert size variants with per-size pricing
            $this->insertVariants($productId, $product['base_price'], $product['stock']);
        }

        $this->command->info('✅ ' . count($products) . ' products seeded with size variants.');
    }

    private function insertVariants(int $productId, float $basePrice, int $stock): void
    {
        $sizes = [
            ['size' => 'XS',     'adjustment' => -200],
            ['size' => 'S',      'adjustment' => 0],
            ['size' => 'M',      'adjustment' => 0],
            ['size' => 'L',      'adjustment' => 200],
            ['size' => 'XL',     'adjustment' => 400],
            ['size' => 'XXL',    'adjustment' => 600],
            ['size' => 'Custom', 'adjustment' => 1000], // custom tailoring premium
        ];

        foreach ($sizes as $variant) {
            DB::table('product_variants')->insert([
                'product_id' => $productId,
                'size'       => $variant['size'],
                'price'      => max($basePrice + $variant['adjustment'], $basePrice * 0.8), // never below 80%
                'stock'      => (int) round($stock / count($sizes)),
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
