<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Author;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(4);
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->paragraphs(3, true),
            'author_id' => Author::factory(),
            'brand_id' => Brand::factory(),
            'category_id' => Category::factory(),
            'status' => $this->faker->randomElement(['Còn Hàng', 'Hết Hàng Tồn Kho', 'Sắp Ra Mắt', 'Ngừng Kinh Doanh']),
            'cover_image' => $this->faker->imageUrl(640, 480, 'books'),
            'isbn' => $this->faker->isbn13(),
            'publication_date' => $this->faker->dateTimeBetween('-5 years', 'now'),
            'page_count' => $this->faker->numberBetween(100, 1000)
        ];
    }
}
