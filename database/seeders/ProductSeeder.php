<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            [
                'name' => 'Classic Wristwatch',
                'description' => 'An elegant timepiece for every occasion. Perfect blend of style and functionality.',
                'affiliate_url' => 'https://amzn.to/3XYZ123',
                'price' => 129.99,
                'currency' => 'USD',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Wireless Headphones',
                'description' => 'Immerse yourself in high-quality audio with these premium wireless headphones.',
                'affiliate_url' => 'https://amzn.to/4ABC456',
                'price' => 199.99,
                'currency' => 'USD',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'Running Shoes',
                'description' => 'Lightweight and comfortable running shoes for your fitness journey.',
                'affiliate_url' => 'https://amzn.to/5DEF789',
                'price' => 89.99,
                'currency' => 'USD',
                'is_active' => true,
                'sort_order' => 3
            ]
        ];

        foreach ($products as $product) {
            \App\Models\Product::create($product);
        }
    }
}
