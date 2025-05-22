<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            'NXB Kim Đồng',
            'NXB Trẻ',
            'NXB Giáo Dục',
            'NXB Tổng Hợp TPHCM',
            'NXB Văn Học',
            'NXB Thế Giới'
        ];

        foreach ($brands as $brand) {
            Brand::create([
                'name' => $brand,
                'description' => 'Thông tin về ' . $brand,
                'image' => null
            ]);
        }
    }
}
