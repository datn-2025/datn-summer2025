<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
     public function run()
    {
        $attributes = [
            ['name' => 'Kích Thước'],
            ['name' => 'Ngôn Ngữ'],
            ['name' => 'Loại Bìa'],
        ];

        foreach ($attributes as $attr) {
            Attribute::updateOrCreate(['name' => $attr['name']], $attr);
        }
    }
}
