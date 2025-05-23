<?php

namespace Database\Factories;

use App\Models\NewsArticle;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NewsArticle>
 */
class NewsArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = NewsArticle::class;
    public function definition(): array
    {
        return [
            //
             'id' => Str::uuid(),
            'title' => $this->faker->sentence(6),
            'thumbnail' => 'articles/post' . rand(1, 3) . '.jpg',
            'summary' => $this->faker->text(100),
            'content' => $this->faker->paragraph(10),
            'category' => $this->faker->randomElement(['Sách', 'Kinh doanh', 'Sức khoẻ']),
            'is_featured' => $this->faker->boolean(30),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
