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
            ['name' => 'Tiểu thuyết'],
            ['name' => 'Kinh doanh'],
            ['name' => 'Thiếu nhi'],
            ['name' => 'Tâm lý - Kỹ năng'],
            ['name' => 'Ngoại ngữ'],
            ['name' => 'Khoa học'],
            ['name' => 'Lịch sử'],
            ['name' => 'Truyện tranh'],
        ];
        foreach ($categories as $cat) {
        Category::firstOrCreate(
            ['name' => $cat['name']],
            [
                'slug' => \Illuminate\Support\Str::slug($cat['name']),
            ]
        );
    }
        // $categories = [
        //     'Sách Văn Học',
        //     'Sách Kinh Tế',
        //     'Sách Thiếu Nhi',
        //     'Sách Kỹ Năng Sống',
        //     'Sách Ngoại Ngữ',
        //     'Sách Giáo Khoa'
        // ];

        // foreach ($categories as $index => $category) {
        //     Category::create([
        //         'id' => (string) Str::uuid(),
        //         'name' => $category,
        //         'slug' => Str::slug($category) . '-' . Str::random(4),
        //         'image' => 'categories/category-' . ($index + 1) . '.jpg',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }
    }
}
