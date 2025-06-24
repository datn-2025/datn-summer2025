<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        $authors = [
            ['name' => 'Nguyễn Nhật Ánh'],
            ['name' => 'Tony Buổi Sáng'],
            ['name' => 'Dale Carnegie'],
            ['name' => 'Nguyễn Ngọc Tư'],
            ['name' => 'Paulo Coelho'],
            ['name' => 'Tô Hoài'],
            ['name' => 'Rosie Nguyễn'],
            ['name' => 'Stephen King'],
            ['name' => 'J.K. Rowling'],
            ['name' => 'George R.R. Martin'],
            ['name' => 'Agatha Christie'],
            ['name' => 'Mark Twain'],
            ['name' => 'Ernest Hemingway'],
            ['name' => 'F. Scott Fitzgerald'],
            ['name' => 'Jane Austen'],
            ['name' => 'Charles Dickens'],
            ['name' => 'Leo Tolstoy'],
            ['name' => 'Virginia Woolf'],
            ['name' => 'Haruki Murakami']
        ];
        foreach ($authors as $author) {
            Author::firstOrCreate($author);
        }
        // $authors = [
        //     'Nguyễn Nhật Ánh',
        //     'Dale Carnegie',
        //     'Paulo Coelho',
        //     'Nguyễn Ngọc Tư',
        //     'Tô Hoài',
        //     'Rosie Nguyễn'
        // ];

        // foreach ($authors as $author) {
        //     Author::create([
        //         'name' => $author,
        //         'biography' => 'Tiểu sử của tác giả ' . $author,
        //         'image' => null
        //     ]);
        // }
    }
}
