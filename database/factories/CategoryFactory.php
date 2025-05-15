<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
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
            'name' => $this->faker->unique()->word(),
            'slug' => Str::slug($this->faker->unique()->word()),
            'parent_id' => null, // sẽ cập nhật lại trong Seeder nếu cần danh mục con
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
