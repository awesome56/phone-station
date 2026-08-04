<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $categories = Category::query()->insert([
            [
                'name' => 'Flagships',
                'slug' => 'flagships',
                'description' => 'The peak of hardware. Maximum compute, maximum presence.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Foldables',
                'slug' => 'foldables',
                'description' => 'Engineering that bends physics. And the display.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Budget',
                'slug' => 'budget',
                'description' => 'No compromise machines at a price that makes sense.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gaming',
                'slug' => 'gaming',
                'description' => 'Cooling systems and refresh rates built for the grind.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $categories = Category::query()->get();

        $products = [
            [
                'category' => 'flagships',
                'name' => 'iPhone 15 Pro',
                'brand' => 'Apple',
                'tagline' => 'Titanium. Blistering. Unmistakable.',
                'description' => 'Forged from aerospace-grade titanium, the iPhone 15 Pro is the lightest Pro Apple has ever built. The A17 Pro chip delivers console-class gaming, while the 48MP main camera system shoots in stunning detail. This is the definitive flagship experience.',
                'price' => 109900,
                'compare_at_price' => 119900,
                'specs' => ['chip' => 'A17 Pro', 'display' => '6.1" ProMotion OLED', 'camera' => '48MP Triple', 'battery' => '3,274 mAh', 'storage' => '256GB'],
                'badge' => 'BEST SELLER',
                'featured' => true,
                'stock' => 14,
            ],
            [
                'category' => 'flagships',
                'name' => 'Galaxy S24 Ultra',
                'brand' => 'Samsung',
                'tagline' => 'Galaxy AI. Titanium frame. Zero competition.',
                'description' => 'The S24 Ultra is a titanium-framed powerhouse with Galaxy AI baked into everything. A 200MP camera, a built-in S Pen, and a display that laughs at direct sunlight.',
                'price' => 124900,
                'compare_at_price' => 134900,
                'specs' => ['chip' => 'Snapdragon 8 Gen 3', 'display' => '6.8" QHD+ AMOLED', 'camera' => '200MP Quad', 'battery' => '5,000 mAh', 'storage' => '256GB'],
                'badge' => 'NEW',
                'featured' => true,
                'stock' => 9,
            ],
            [
                'category' => 'flagships',
                'name' => 'Pixel 8 Pro',
                'brand' => 'Google',
                'tagline' => 'AI-native. Camera-first. Everything else follows.',
                'description' => 'Google\'s computational photography reaches its peak. The Tensor G3 powers on-device AI that erases objects, enhances video, and answers calls for you. The Pixel 8 Pro is the smartest phone on the market.',
                'price' => 94900,
                'compare_at_price' => 99900,
                'specs' => ['chip' => 'Tensor G3', 'display' => '6.7" LTPO OLED', 'camera' => '50MP Triple', 'battery' => '5,050 mAh', 'storage' => '128GB'],
                'badge' => null,
                'featured' => false,
                'stock' => 21,
            ],
            [
                'category' => 'flagships',
                'name' => 'OnePlus 12',
                'brand' => 'OnePlus',
                'tagline' => 'Flagship killer. Again.',
                'description' => 'Hasselblad optics, 5400mAh battery, and 100W wired charging that refuels the tank in under 30 minutes. The OnePlus 12 refuses to lose to phones twice its price.',
                'price' => 84900,
                'compare_at_price' => null,
                'specs' => ['chip' => 'Snapdragon 8 Gen 3', 'display' => '6.82" LTPO OLED', 'camera' => '50MP Triple', 'battery' => '5,400 mAh', 'storage' => '256GB'],
                'badge' => null,
                'featured' => false,
                'stock' => 17,
            ],
            [
                'category' => 'foldables',
                'name' => 'Galaxy Z Fold 6',
                'brand' => 'Samsung',
                'tagline' => 'A phone. A tablet. A statement.',
                'description' => 'The Z Fold 6 unfolds into a 7.6-inch canvas with multitasking that feels like a laptop. Thinner and lighter than any Fold before it, powered by Snapdragon 8 Gen 3 for Galaxy.',
                'price' => 179900,
                'compare_at_price' => 189900,
                'specs' => ['chip' => 'Snapdragon 8 Gen 3', 'display' => '7.6" Foldable AMOLED', 'camera' => '50MP Triple', 'battery' => '4,400 mAh', 'storage' => '512GB'],
                'badge' => 'NEW',
                'featured' => true,
                'stock' => 6,
            ],
            [
                'category' => 'foldables',
                'name' => 'Galaxy Z Flip 6',
                'brand' => 'Samsung',
                'tagline' => 'Folds to fit a fist. Unfolds to own the moment.',
                'description' => 'The pocketable foldable. FlexCam lets you shoot hands-free with the camera AI-tuned for the perfect selfie, and the cover screen handles everything on the go.',
                'price' => 104900,
                'compare_at_price' => 109900,
                'specs' => ['chip' => 'Snapdragon 8 Gen 3', 'display' => '6.7" Foldable AMOLED', 'camera' => '50MP Dual', 'battery' => '4,000 mAh', 'storage' => '256GB'],
                'badge' => null,
                'featured' => false,
                'stock' => 12,
            ],
            [
                'category' => 'foldables',
                'name' => 'Motorola Razr 50 Ultra',
                'brand' => 'Motorola',
                'tagline' => 'The flip is back. Better than ever.',
                'description' => 'A 4-inch external display that fits any app, a 64MP camera with night vision, and the most refined hinge in the business. The Razr 50 Ultra is style with substance.',
                'price' => 84900,
                'compare_at_price' => null,
                'specs' => ['chip' => 'Snapdragon 8s Gen 3', 'display' => '6.9" Foldable OLED', 'camera' => '64MP Dual', 'battery' => '4,000 mAh', 'storage' => '256GB'],
                'badge' => null,
                'featured' => false,
                'stock' => 8,
            ],
            [
                'category' => 'budget',
                'name' => 'Nothing Phone (2)',
                'brand' => 'Nothing',
                'tagline' => 'The glyphs speak. The software follows.',
                'description' => 'Nothing\'s transparent design with the Glyph interface turns notifications into light shows. A clean, bloat-free Android experience with a flagship-grade Snapdragon 8+ Gen 1 inside.',
                'price' => 57900,
                'compare_at_price' => 62900,
                'specs' => ['chip' => 'Snapdragon 8+ Gen 1', 'display' => '6.7" OLED', 'camera' => '50MP Dual', 'battery' => '4,700 mAh', 'storage' => '256GB'],
                'badge' => null,
                'featured' => false,
                'stock' => 25,
            ],
            [
                'category' => 'budget',
                'name' => 'Redmi Note 13 Pro',
                'brand' => 'Xiaomi',
                'tagline' => 'Flagship looks. Budget reality.',
                'description' => 'A 200MP camera, 120Hz AMOLED display, and 67W fast charging for a fraction of the flagship price. The Redmi Note 13 Pro is the smartest money move in the phone world.',
                'price' => 34900,
                'compare_at_price' => 39900,
                'specs' => ['chip' => 'Snapdragon 7s Gen 2', 'display' => '6.67" AMOLED', 'camera' => '200MP Triple', 'battery' => '5,100 mAh', 'storage' => '256GB'],
                'badge' => 'BEST VALUE',
                'featured' => false,
                'stock' => 32,
            ],
            [
                'category' => 'budget',
                'name' => 'iPhone SE (3rd Gen)',
                'brand' => 'Apple',
                'tagline' => 'Classic body. Modern engine.',
                'description' => 'The A15 Bionic chip in a classic form factor with Touch ID and wireless charging. The most affordable entry point into the Apple ecosystem, and it still flies.',
                'price' => 42900,
                'compare_at_price' => null,
                'specs' => ['chip' => 'A15 Bionic', 'display' => '4.7" Retina LCD', 'camera' => '12MP Single', 'battery' => '2,018 mAh', 'storage' => '128GB'],
                'badge' => null,
                'featured' => false,
                'stock' => 19,
            ],
            [
                'category' => 'gaming',
                'name' => 'ROG Phone 8 Pro',
                'brand' => 'ASUS',
                'tagline' => 'Built for the grind. Built to win.',
                'description' => 'An active cooling system, 165Hz AMOLED display, and AirTrigger shoulder buttons make the ROG Phone 8 Pro the definitive esports machine. RGB optional. Victory mandatory.',
                'price' => 99900,
                'compare_at_price' => 109900,
                'specs' => ['chip' => 'Snapdragon 8 Gen 3', 'display' => '6.78" 165Hz AMOLED', 'camera' => '50MP Triple', 'battery' => '5,500 mAh', 'storage' => '512GB'],
                'badge' => 'GAMING',
                'featured' => false,
                'stock' => 7,
            ],
            [
                'category' => 'gaming',
                'name' => 'Red Magic 9S Pro',
                'brand' => 'Nubia',
                'tagline' => 'A fan inside your phone. Yes, really.',
                'description' => 'The only phone with a built-in turbofan cooler. Snapdragon 8 Gen 3 Leading Version, 6500mAh battery, and under-display camera. Pure gaming performance with zero distractions.',
                'price' => 69900,
                'compare_at_price' => null,
                'specs' => ['chip' => 'Snapdragon 8 Gen 3', 'display' => '6.8" 120Hz AMOLED', 'camera' => '50MP Dual', 'battery' => '6,500 mAh', 'storage' => '256GB'],
                'badge' => null,
                'featured' => false,
                'stock' => 11,
            ],
        ];

        foreach ($products as $data) {
            $category = $categories->firstWhere('slug', $data['category']);

            Product::query()->create([
                'category_id' => $category->id,
                'name' => $data['name'],
                'slug' => str($data['name'])->slug(),
                'brand' => $data['brand'],
                'tagline' => $data['tagline'],
                'description' => $data['description'],
                'price' => $data['price'],
                'compare_at_price' => $data['compare_at_price'],
                'specs' => $data['specs'],
                'badge' => $data['badge'],
                'featured' => $data['featured'],
                'in_stock' => $data['stock'] > 0,
                'stock' => $data['stock'],
            ]);
        }
    }
}
