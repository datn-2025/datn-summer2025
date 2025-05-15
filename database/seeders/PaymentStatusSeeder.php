<?php

namespace Database\Seeders;

use App\Models\PaymentStatus;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PaymentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['id' => (string) Str::uuid(), 'name' => 'Đang Chờ Xử Lý', 'description' => 'Thanh toán đang chờ xử lý'],
            ['id' => (string) Str::uuid(), 'name' => 'Chưa Thanh Toán', 'description' => 'Chưa Thanh Toán'],
            ['id' => (string) Str::uuid(), 'name' => 'Thanh Toán Thành Công', 'description' => 'Thanh toán thành công'],
            ['id' => (string) Str::uuid(), 'name' => 'Thanh toán thất bại', 'description' => 'Thanh toán thất bại'],
        ];

        foreach ($statuses as $status) {
            PaymentStatus::create($status);
        }
    }
}
