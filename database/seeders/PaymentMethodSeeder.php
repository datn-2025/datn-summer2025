<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            ['id' => (string) Str::uuid(), 'name' => 'COD', 'description' => 'Thanh toán khi nhận hàng'],
            ['id' => (string) Str::uuid(), 'name' => 'VNPAY', 'description' => 'Thanh toán qua cổng VNPAY'],
            ['id' => (string) Str::uuid(), 'name' => 'BANK_TRANSFER', 'description' => 'Chuyển khoản ngân hàng'],
        ];

        foreach ($methods as $method) {
            PaymentMethod::create($method);
        }
    }
}
