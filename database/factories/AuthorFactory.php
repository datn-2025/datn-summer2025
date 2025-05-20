<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuthorFactory extends Factory
{
    protected $model = Author::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'biography' => $this->faker->paragraphs(3, true),
            'image' => $this->faker->imageUrl(640, 480, 'authors'),
        ];
    }
}
