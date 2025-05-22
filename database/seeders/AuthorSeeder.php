<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        $authors = [
            'Nguyễn Nhật Ánh',
            'Dale Carnegie',
            'Paulo Coelho',
            'Nguyễn Ngọc Tư',
            'Tô Hoài',
            'Rosie Nguyễn'
        ];

        foreach ($authors as $author) {
            Author::create([
                'name' => $author,
                'biography' => 'Tiểu sử của tác giả ' . $author,
                'image' => null
            ]);
        }
    }
}
