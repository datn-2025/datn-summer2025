<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookFormat;
use App\Models\BookImage;
use App\Models\AttributeValue;
use App\Models\BookAttributeValue;
use App\Models\Category;
use App\Models\Author;
use App\Models\Brand;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        // Đảm bảo có dữ liệu cần thiết trước khi tạo sách
        $categories = Category::all();
        $authors = Author::all();
        $brands = Brand::all();

        if ($categories->isEmpty() || $authors->isEmpty() || $brands->isEmpty()) {
            throw new \Exception('Cần chạy CategorySeeder, AuthorSeeder và BrandSeeder trước.');
        }

        // Tạo sách cho mỗi danh mục
        foreach ($categories as $category) {
            // Tạo 5 sách cho mỗi danh mục
            for ($i = 0; $i < 5; $i++) {
                $book = Book::factory()->create();

                // 70% sách có bản bìa cứng
                if (fake()->boolean(70)) {
                    BookFormat::factory()->create([
                        'book_id' => $book->id,
                        'format_name' => 'Sách Vật Lý',
                    ]);
                }

                // 50% sách có bản ebook
                if (fake()->boolean(50)) {
                    BookFormat::factory()->create([
                        'book_id' => $book->id,
                        'format_name' => 'Ebook'
                    ]);
                }

                // Tạo 1-3 ảnh cho mỗi sách
                for ($j = 0; $j < rand(1, 3); $j++) {
                    BookImage::create([
                        'book_id' => $book->id,
                        'image_url' => 'books/book-' . fake()->numberBetween(1, 5) . '.jpg'
                    ]);
                }

                // Gắn 3-5 thuộc tính cho mỗi sách
                $attributeValues = AttributeValue::inRandomOrder()
                    ->limit(rand(3, 5))
                    ->get();

                foreach ($attributeValues as $value) {
                    BookAttributeValue::create([
                        'book_id' => $book->id,
                        'attribute_value_id' => $value->id
                    ]);
                }
            }
        }
    }
}
