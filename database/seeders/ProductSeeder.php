<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure initial categories exist
        $laptops = Category::firstOrCreate(['title' => 'Laptops'], ['status' => true]);
        $headphones = Category::firstOrCreate(['title' => 'Headphones'], ['status' => true]);
        $tablets = Category::firstOrCreate(['title' => 'Tablets'], ['status' => true]);
        $accessories = Category::firstOrCreate(['title' => 'Accessories'], ['status' => true]);
        $smartphones = Category::firstOrCreate(['title' => 'Smartphones'], ['status' => true]);
        $cameras = Category::firstOrCreate(['title' => 'Cameras'], ['status' => true]);

        // Seed 9 dynamic products matching template assets
        $products = [
            [
                'category_id' => $laptops->id,
                'title' => 'MacBook Pro 16-inch M3',
                'keywords' => 'laptop, macbook, apple, m3',
                'description' => 'The ultimate pro laptop, now even better with the M3 chip.',
                'image' => 'frontend-assets/img/product01.png',
                'price' => 1999.00,
                'discount' => 10.00,
                'stock' => 50,
                'min_stock' => 5,
                'status' => true,
            ],
            [
                'category_id' => $headphones->id,
                'title' => 'Sony WH-1000XM5 Headphones',
                'keywords' => 'headphones, sony, noise canceling',
                'description' => 'Industry-leading noise cancellation, exceptional sound quality, and crystal-clear calls.',
                'image' => 'frontend-assets/img/product02.png',
                'price' => 349.00,
                'discount' => 0.00,
                'stock' => 75,
                'min_stock' => 5,
                'status' => true,
            ],
            [
                'category_id' => $laptops->id,
                'title' => 'ASUS ROG Zephyrus G14',
                'keywords' => 'laptop, asus, gaming, rog',
                'description' => 'Powerful and portable gaming laptop with AMD Ryzen and NVIDIA graphics.',
                'image' => 'frontend-assets/img/product03.png',
                'price' => 1399.00,
                'discount' => 0.00,
                'stock' => 30,
                'min_stock' => 3,
                'status' => true,
            ],
            [
                'category_id' => $tablets->id,
                'title' => 'iPad Pro 11-inch M2',
                'keywords' => 'tablet, ipad, apple, m2',
                'description' => 'Astonishing performance, superfast wireless connectivity, and next-generation Apple Pencil experience.',
                'image' => 'frontend-assets/img/product04.png',
                'price' => 799.00,
                'discount' => 0.00,
                'stock' => 45,
                'min_stock' => 4,
                'status' => true,
            ],
            [
                'category_id' => $accessories->id,
                'title' => 'Logitech MX Master 3S',
                'keywords' => 'mouse, logitech, mx master, accessory',
                'description' => 'An iconic mouse remastered for ultimate precision, tactile feel, and performance.',
                'image' => 'frontend-assets/img/product05.png',
                'price' => 99.00,
                'discount' => 0.00,
                'stock' => 100,
                'min_stock' => 10,
                'status' => true,
            ],
            [
                'category_id' => $smartphones->id,
                'title' => 'Samsung Galaxy S23 Ultra',
                'keywords' => 'smartphone, samsung, galaxy, android',
                'description' => 'Our most powerful smartphone camera yet, with built-in S Pen.',
                'image' => 'frontend-assets/img/product06.png',
                'price' => 1199.00,
                'discount' => 0.00,
                'stock' => 60,
                'min_stock' => 5,
                'status' => true,
            ],
            [
                'category_id' => $laptops->id,
                'title' => 'Dell XPS 13 Plus',
                'keywords' => 'laptop, dell, xps, ultrabook',
                'description' => 'Our most powerful 13-inch laptop is twice as powerful as before in the same size.',
                'image' => 'frontend-assets/img/product07.png',
                'price' => 999.00,
                'discount' => 0.00,
                'stock' => 40,
                'min_stock' => 4,
                'status' => true,
            ],
            [
                'category_id' => $cameras->id,
                'title' => 'Sony Alpha 7 IV Camera',
                'keywords' => 'camera, sony, alpha, mirrorless',
                'description' => 'With ground-breaking performance in both still and movie recording.',
                'image' => 'frontend-assets/img/product08.png',
                'price' => 2199.00,
                'discount' => 0.00,
                'stock' => 20,
                'min_stock' => 2,
                'status' => true,
            ],
            [
                'category_id' => $smartphones->id,
                'title' => 'iPhone 15 Pro',
                'keywords' => 'smartphone, iphone, apple, ios',
                'description' => 'Titanium design, powerful A17 Pro chip, custom Action button, and 48MP main camera.',
                'image' => 'frontend-assets/img/product09.png',
                'price' => 999.00,
                'discount' => 0.00,
                'stock' => 80,
                'min_stock' => 8,
                'status' => true,
            ],
        ];

        foreach ($products as $prod) {
            Product::updateOrCreate(
                ['title' => $prod['title']],
                $prod
            );
        }
    }
}
