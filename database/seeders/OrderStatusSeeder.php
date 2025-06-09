<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    public function run()
    {
        $statuses = [
            ['name' => 'Chờ xác nhận'],
            ['name' => 'Đã xác nhận'],
            ['name' => 'Đang chuẩn bị'],
            ['name' => 'Đang giao hàng'],
            ['name' => 'Đã giao thành công'],
            ['name' => 'Đã nhận hàng'],
            ['name' => 'Thành công'],
            ['name' => 'Giao thất bại'],
            ['name' => 'Đã hủy'],
            ['name' => 'Hoàn tiền'],
        ];

        foreach ($statuses as $status) {
            OrderStatus::updateOrCreate(
                ['name' => $status['name']],
                $status
            );
        }
    }
}
