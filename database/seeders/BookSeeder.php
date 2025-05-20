<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookFormat;
use App\Models\BookImage;
use App\Models\AttributeValue;
use App\Models\BookAttributeValue;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        Book::factory(20)->create()
            ->each(function ($book) {
                // Create 2-4 formats for each book
                BookFormat::factory(rand(2, 4))->create([
                    'book_id' => $book->id
                ]);

                // Create 1-4 images for each book
                BookImage::factory(rand(1, 4))->create([
                    'book_id' => $book->id
                ]);

                // Attach random attributes to book
                $attributeValues = AttributeValue::inRandomOrder()
                    ->limit(rand(3, 6))
                    ->get();

                foreach ($attributeValues as $value) {
                    BookAttributeValue::create([
                        'book_id' => $book->id,
                        'attribute_value_id' => $value->id
                    ]);
                }
            });
    }
}
