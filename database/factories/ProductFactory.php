<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name' => fake()->unique()->catchPhrase(),
            'slug' => fn (array $attributes) => str($attributes['name'])->slug(),
            'brand' => fake()->company(),
            'tagline' => fake()->sentence(4),
            'description' => fake()->paragraphs(3, true),
            'price' => fake()->numberBetween(20000, 180000),
            'compare_at_price' => null,
            'image' => null,
            'specs' => [
                'chip' => fake()->word(),
                'display' => fake()->word(),
                'battery' => fake()->word(),
            ],
            'badge' => null,
            'featured' => false,
            'in_stock' => true,
            'stock' => fake()->numberBetween(0, 40),
        ];
    }
}
