<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => 'shroom powder',
                'sku' => 'SHR001',
                'price' => 29.99,
                'stock' => 50,
                'category' => 'shroom',
                'image' => 'assets/img/powder.webp',
                'description' => 'Organic Lion\'s Mane mushrooms',
                'is_new' => true,
                'is_on_sale' => false,
                'average_rating' => 0,
                'review_count' => 0,
            ],
            [
                'name' => 'Reishi Capsules',
                'sku' => 'CAP001',
                'price' => 34.99,
                'stock' => 30,
                'category' => 'cap',
                'image' => 'assets/img/shroom1.jpg',
                'description' => 'Pure Reishi extract capsules',
                'is_new' => false,
                'is_on_sale' => true,
                'average_rating' => 0,
                'review_count' => 0,
            ],
            [
                'name' => 'Psilocybin Sheet',
                'sku' => 'SHT001',
                'price' => 49.99,
                'stock' => 20,
                'category' => 'sheet',
                'image' => 'assets/img/shroom2.jpg',
                'description' => 'High-potency psilocybin sheet',
                'is_new' => true,
                'is_on_sale' => false,
                'average_rating' => 0,
                'review_count' => 0,
            ],
            [
                'name' => 'Organic Dog Food',
                'sku' => 'PET001',
                'price' => 19.99,
                'stock' => 100,
                'category' => 'pet_food',
                'image' => 'assets/img/shroom3.jpg',
                'description' => 'Natural dog food',
                'is_new' => true,
                'is_on_sale' => false,
                'average_rating' => 0,
                'review_count' => 0,
            ],
            [
                'name' => 'Marigold',
                'sku' => 'FLW001',
                'price' => 9.99,
                'stock' => 200,
                'category' => 'flowers',
                'image' => 'assets/img/shroom4.webp',
                'description' => 'Vibrant marigold flowers',
                'is_new' => false,
                'is_on_sale' => true,
                'average_rating' => 0,
                'review_count' => 0,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}