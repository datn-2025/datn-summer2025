<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductImage>
 */
class ProductImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'product_id' => Product::inRandomOrder()->first()?->id ?? Product::factory()->create()->id,
            'image' => $this->faker->imageUrl(640, 480, 'products'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
