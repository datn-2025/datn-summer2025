<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentStatus>
 */
class PaymentStatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         $statuses = [
            ['name' => 'Đang Chờ Xử Lý', 'description' => 'Thanh toán đang chờ xử lý'],
            ['name' => 'Chưa Thanh Toán', 'description' => 'Chưa Thanh Toán'],
            ['name' => 'Thanh Toán Thành Công', 'description' => 'Thanh toán thành công'],
            ['name' => 'Thanh toán thất bại', 'description' => 'Thanh toán thất bại'],
        ];

        return $this->faker->unique()->randomElement($statuses);
    }
}
