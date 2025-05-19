<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{    public function run()
    {
        // Tạo 10 trạng thái đơn hàng đã định nghĩa sẵn
        OrderStatus::factory(10)->create();
    }
}
