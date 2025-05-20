<?php

namespace Database\Factories;

use App\Models\BookImage;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookImageFactory extends Factory
{
    protected $model = BookImage::class;

    public function definition(): array
    {
        return [
            'book_id' => Book::factory(),
            'image_url' => $this->faker->imageUrl(800, 600, 'books')
        ];
    }
}
