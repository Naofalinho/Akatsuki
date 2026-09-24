<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [

            [
                'name' => 'T-shirt Premium Akatsuki',
                'description' => 'T-shirt moderne et confortable.',
                'category' => 'Mode',
                'price' => 15000,
                'old_price' => 20000,
                'image' => 'products/tshirt.jpg',
                'stock' => 25,
                'is_popular' => true,
                'is_active' => true,
            ],

            [
                'name' => 'Montre Smart Pro',
                'description' => 'Montre connectée moderne.',
                'category' => 'Tech',
                'price' => 35000,
                'old_price' => 45000,
                'image' => 'products/montre.jpg',
                'stock' => 12,
                'is_popular' => true,
                'is_active' => true,
            ],

            [
                'name' => 'Casque Bluetooth',
                'description' => 'Casque sans fil haute qualité.',
                'category' => 'Tech',
                'price' => 25000,
                'old_price' => 30000,
                'image' => 'products/casque.jpg',
                'stock' => 18,
                'is_popular' => true,
                'is_active' => true,
            ],

            [
                'name' => 'Sac à main élégant',
                'description' => 'Sac élégant pour femme.',
                'category' => 'Mode',
                'price' => 28000,
                'old_price' => 35000,
                'image' => 'products/sac.jpg',
                'stock' => 10,
                'is_popular' => true,
                'is_active' => true,
            ],

            [
                'name' => 'Parfum Premium',
                'description' => 'Parfum élégant et raffiné.',
                'category' => 'Beauté',
                'price' => 22000,
                'old_price' => 28000,
                'image' => 'products/parfum.jpg',
                'stock' => 15,
                'is_popular' => true,
                'is_active' => true,
            ],

            [
                'name' => 'Lampe LED Design',
                'description' => 'Lampe moderne pour votre intérieur.',
                'category' => 'Maison',
                'price' => 18000,
                'old_price' => null,
                'image' => 'products/lampe.jpg',
                'stock' => 20,
                'is_popular' => true,
                'is_active' => true,
            ],

            [
                'name' => 'Sneakers Urban',
                'description' => 'Sneakers tendance et confortables.',
                'category' => 'Mode',
                'price' => 30000,
                'old_price' => 40000,
                'image' => 'products/sneakers.jpg',
                'stock' => 14,
                'is_popular' => true,
                'is_active' => true,
            ],

            [
                'name' => 'Écouteurs Sans Fil',
                'description' => 'Écouteurs Bluetooth compacts.',
                'category' => 'Tech',
                'price' => 12000,
                'old_price' => 15000,
                'image' => 'products/ecouteurs.jpg',
                'stock' => 30,
                'is_popular' => true,
                'is_active' => true,
            ],

        ];

        foreach ($products as $product) {

            Product::create([
                ...$product,
                'slug' => Str::slug($product['name']),
            ]);

        }
    }
}