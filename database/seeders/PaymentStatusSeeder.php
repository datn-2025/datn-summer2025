<?php

namespace Database\Seeders;

use App\Models\PaymentStatus;
use Illuminate\Database\Seeder;

class PaymentStatusSeeder extends Seeder
{
    public function run()
    {
        $statuses = [
            ['name' => 'Chờ thanh toán'],
            ['name' => 'Đã thanh toán'],
            ['name' => 'Thanh toán thất bại'],
            ['name' => 'Hoàn tiền'],
        ];

        foreach ($statuses as $status) {
            PaymentStatus::updateOrCreate(
                ['name' => $status['name']],
                $status
            );
        }
    }
}
