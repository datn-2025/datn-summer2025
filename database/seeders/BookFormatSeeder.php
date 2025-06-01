<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BookFormat;
use App\Models\Book;
use Illuminate\Support\Str;

class BookFormatSeeder extends Seeder
{
    public function run()
    {
        $books = Book::all();

        foreach ($books as $book) {
            // Tạo format bìa mềm
            BookFormat::create([
                'id' => (string) Str::uuid(),
                'book_id' => $book->id,
                'name' => 'Bìa mềm',
                'price' => $book->price * 0.8, // Giả sử bìa mềm rẻ hơn 20%
                'stock' => rand(10, 100),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Tạo format bìa cứng
            BookFormat::factory()->create([
                'book_id' => $book->id,
                'name' => 'Bìa cứng',
                'price' => rand(100000, 300000),
                'stock' => rand(10, 100)
            ]);

            // Tạo format ebook
            BookFormat::factory()->create([
                'book_id' => $book->id,
                'name' => 'Ebook',
                'price' => rand(50000, 150000),
                'stock' => 999
            ]);
        }
    }
}
