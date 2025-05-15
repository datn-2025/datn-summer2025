<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderStatus>
 */
class OrderStatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         $statuses = [
            ['name' => 'Chờ Xử Lý', 'description' => 'Đơn hàng đang chờ xử lý'],
            ['name' => 'Đang Giao', 'description' => 'Đơn hàng đang được giao'],
            ['name' => 'Đã Giao', 'description' => 'Đơn hàng đã được giao'],
            ['name' => 'Đã Hoàn Thành', 'description' => 'Đơn hàng đã hoàn thành'],
            ['name' => 'Đã Hủy', 'description' => 'Đơn hàng đã bị hủy'],
        ];

        return $this->faker->unique()->randomElement($statuses);
    }
}
