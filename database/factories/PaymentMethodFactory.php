<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentMethod>
 */
class PaymentMethodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $methods = [
            ['name' => 'COD', 'description' => 'Thanh toán khi nhận hàng'],
            ['name' => 'VNPAY', 'description' => 'Thanh toán qua cổng VNPAY'],
            ['name' => 'BANK_TRANSFER', 'description' => 'Chuyển khoản ngân hàng'],
        ];

        return $this->faker->randomElement($methods);
    }
}
