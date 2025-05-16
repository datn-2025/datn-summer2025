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
            ['name' => 'Chờ xác nhận', 'description' => 'Đơn hàng đang chờ xác nhận'],
            ['name' => 'Đã xác nhận', 'description' => 'Đơn hàng đã được xác nhận'],
            ['name' => 'Đang chuẩn bị', 'description' => 'Đơn hàng đang được chuẩn bị'],
            ['name' => 'Đang giao hàng', 'description' => 'Đơn hàng đang được giao'],
            ['name' => 'Đã giao thành công', 'description' => 'Đơn hàng đã giao thành công - chờ xác nhận từ người dùng'],
            ['name' => 'Đã nhận hàng', 'description' => 'Trạng thái này hiển thị phía người dùng'],
            ['name' => 'Thành công', 'description' => 'Trạng thái này dựa vào trạng thái trên để chuyển đổi'],
            ['name' => 'Giao thất bại', 'description' => 'Giao hàng thất bại'],
            ['name' => 'Đã hủy', 'description' => 'Đơn hàng đã bị hủy'],
            ['name' => 'Đã hoàn tiền', 'description' => 'Đơn hàng đã được hoàn tiền'],
        ];

        return $this->faker->unique()->randomElement($statuses);
    }
}
