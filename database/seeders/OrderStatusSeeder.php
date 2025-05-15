<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['id' => (string) Str::uuid(), 'name' => 'Chờ Xử Lý', 'description' => 'Đơn hàng đang chờ xử lý'],
            ['id' => (string) Str::uuid(), 'name' => 'Đang Giao', 'description' => 'Đơn hàng đang được giao'],
            ['id' => (string) Str::uuid(), 'name' => 'Đã Giao', 'description' => 'Đơn hàng đã được giao'],
            ['id' => (string) Str::uuid(), 'name' => 'Đã Hoàn Thành', 'description' => 'Đơn hàng đã hoàn thành'],
            ['id' => (string) Str::uuid(), 'name' => 'Đã Hủy', 'description' => 'Đơn hàng đã bị hủy']
        ];

        foreach ($statuses as $status) {
            OrderStatus::create($status);
        }
    }
}
