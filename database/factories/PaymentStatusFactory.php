<?php

namespace Database\Factories;

use App\Models\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentStatusFactory extends Factory
{
    protected $model = PaymentStatus::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Chờ Xử Lý',
                'Chưa thanh toán',
                'Đã Thanh Toán',
                'Thất Bại',
            ])
        ];
    }
}
