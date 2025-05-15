<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo 5 danh mục cha
        $parents = Category::factory()->count(5)->create();

        // Tạo 2 danh mục con cho mỗi danh mục cha
        // foreach ($parents as $parent) {
        //     Category::factory()->count(2)->create([
        //         'parent_id' => $parent->id
        //     ]);
        // }
    }
}
