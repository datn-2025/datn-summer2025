<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Store;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
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
        $name = $this->faker->unique()->words(3, true);
        $store = Store::inRandomOrder()->first() ?? Store::factory()->create();
        $category = Category::inRandomOrder()->first() ?? Category::factory()->create();
        return [
            'id' => (string) Str::uuid(),
            'store_id' => $store->id,
            'category_id' => $category->id,

            'name' => $name,
            'slug' => Str::slug($name) . '-' . Str::random(5),
            'description' => $this->faker->optional()->paragraph(),

            'price' => $this->faker->randomFloat(2, 100, 1000),
            'discount_price' => $this->faker->optional()->randomFloat(2, 50, 999),
            'quantity_in_stock' => $this->faker->numberBetween(0, 100),
            'sku' => strtoupper(Str::random(10)),

            'status' => $this->faker->randomElement(['Còn Hàng', 'Hết Hàng Tồn Kho', 'Ngưng Bán']),
            'image' => $this->faker->optional()->imageUrl(640, 480, 'products'),

            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
