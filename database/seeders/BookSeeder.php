<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\BookFormat;
use App\Models\BookImage;
use App\Models\AttributeValue;
use App\Models\BookAttributeValue;
use App\Models\Category;
use App\Models\Author;
use App\Models\Brand;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        $authors = Author::all();
        $brands = Brand::all();
        $categories = Category::all();

        // Tạo 50 cuốn sách
        for ($i = 0; $i < 50; $i++) {
            $title = $faker->sentence(3);
            Book::create([
                'id' => (string) Str::uuid(),
                'title' => $title,
                'slug' => Str::slug($title),
                'description' => $faker->paragraphs(3, true),
                'author_id' => $authors->random()->id,
                'brand_id' => $brands->random()->id,
                'category_id' => $categories->random()->id,
                'status' => $faker->randomElement(['available', 'out_of_stock', 'coming_soon']),
                'cover_image' => 'books/covers/' . Str::random(10) . '.jpg',
                'isbn' => $faker->isbn13(),
                'publication_date' => $faker->dateTimeBetween('-2 years', 'now'),
                'page_count' => $faker->numberBetween(100, 500),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
