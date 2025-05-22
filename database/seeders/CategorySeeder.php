<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Sách Văn Học',
            'Sách Kinh Tế',
            'Sách Thiếu Nhi',
            'Sách Kỹ Năng Sống',
            'Sách Ngoại Ngữ',
            'Sách Giáo Khoa'
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
                'slug' => Str::slug($category),
                'image' => null
            ]);
        }
    }
}
