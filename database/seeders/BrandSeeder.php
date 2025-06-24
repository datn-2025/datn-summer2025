<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'NXB Trẻ'],
            ['name' => 'NXB Kim Đồng'],
            ['name' => 'NXB Giáo dục'],
            ['name' => 'NXB Tổng hợp TP.HCM'],
            ['name' => 'NXB Văn học'],
            ['name' => 'NXB Lao động'],
            ['name' => 'NXB Thế giới'],
            ['name' => 'NXB Hội Nhà văn'],
            ['name' => 'NXB Thanh niên'],
            ['name' => 'NXB Phụ nữ'],
            ['name' => 'NXB Chính trị Quốc gia Sự thật'],
            ['name' => 'NXB Đại học Quốc gia Hà Nội'],
            ['name' => 'NXB Đại học Quốc gia TP.HCM'],
            ['name' => 'NXB Hồng Đức'],
            ['name' => 'NXB Văn hóa - Văn nghệ'],
            ['name' => 'NXB Mỹ thuật'],
            ['name' => 'NXB Thông tin và Truyền thông'],
            ['name' => 'NXB Khoa học và Kỹ thuật'],
            ['name' => 'NXB Phụ nữ'],
            ['name' => 'NXB Dân trí'],
        ];
        foreach ($brands as $brand) {
            Brand::firstOrCreate($brand);
        }
        // $brands = [
        //     'NXB Kim Đồng',
        //     'NXB Trẻ',
        //     'NXB Giáo Dục',
        //     'NXB Tổng Hợp TPHCM',
        //     'NXB Văn Học',
        //     'NXB Thế Giới'
        // ];

        // foreach ($brands as $brand) {
        //     Brand::create([
        //         'name' => $brand,
        //         'description' => 'Thông tin về ' . $brand,
        //         'image' => null
        //     ]);
        // }
    }
}
