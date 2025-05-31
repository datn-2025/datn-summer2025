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
            ['name' => 'Đang giao hàng'],
            ['name' => 'Đã giao hàng'],
            ['name' => 'Đã hủy'],
            ['name' => 'Hoàn trả'],
        ];

        foreach ($statuses as $status) {
            OrderStatus::updateOrCreate(
                ['name' => $status['name']],
                $status
            );
        }
    }
}
