<?php

namespace Database\Seeders;

use App\Models\PaymentStatus;
use Illuminate\Database\Seeder;

class PaymentStatusSeeder extends Seeder
{
    public function run()
    {
        $statuses = [
            ['name' => 'Chờ Xử Lý'],
            ['name' => 'Chưa thanh toán'],
            ['name' => 'Đã Thanh Toán'],
            ['name' => 'Thất Bại'],
        ];

        foreach ($statuses as $status) {
            PaymentStatus::updateOrCreate(
                ['name' => $status['name']],
                $status
            );
        }
    }
}
