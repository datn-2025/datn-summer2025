<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
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
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'name' => $this->faker->company(),
            'slug' => Str::slug($this->faker->company()),
            'website' => $this->faker->optional()->url(),
            'description' => $this->faker->optional()->paragraph(),
            'logo_url' => $this->faker->optional()->imageUrl(100, 100, 'business'),
            'cover_image_url' => $this->faker->optional()->imageUrl(600, 200, 'business'),
            'status' => $this->faker->randomElement(['Chờ Duyệt','Hoạt Động', 'Không Hoạt Động']),
            'address' => $this->faker->optional()->address(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
