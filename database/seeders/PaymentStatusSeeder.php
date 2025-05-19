<?php

namespace Database\Seeders;

use App\Models\PaymentStatus;
use Illuminate\Database\Seeder;

class PaymentStatusSeeder extends Seeder
{    public function run()
    {
        // Tạo 4 trạng thái thanh toán đã định nghĩa sẵn
        PaymentStatus::factory(4)->create();
    }
}
