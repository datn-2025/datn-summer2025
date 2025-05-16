<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['id' => (string) Str::uuid(), 'name' => 'Chờ xác nhận', 'description' => 'Đơn hàng đang chờ xác nhận'],
            ['id' => (string) Str::uuid(), 'name' => 'Đã xác nhận', 'description' => 'Đơn hàng đã được xác nhận'],
            ['id' => (string) Str::uuid(), 'name' => 'Đang chuẩn bị', 'description' => 'Đơn hàng đang được chuẩn bị'],
            ['id' => (string) Str::uuid(), 'name' => 'Đang giao hàng', 'description' => 'Đơn hàng đang được giao'],
            ['id' => (string) Str::uuid(), 'name' => 'Đã giao thành công', 'description' => 'Đơn hàng đã giao thành công - chờ xác nhận từ người dùng'],
            ['id' => (string) Str::uuid(), 'name' => 'Đã nhận hàng', 'description' => 'Trạng thái này hiển thị phía người dùng'],
            ['id' => (string) Str::uuid(), 'name' => 'Thành công', 'description' => 'Trạng thái này dựa vào trạng thái trên để chuyển đổi'],
            ['id' => (string) Str::uuid(), 'name' => 'Giao thất bại', 'description' => 'Giao hàng thất bại'],
            ['id' => (string) Str::uuid(), 'name' => 'Đã hủy', 'description' => 'Đơn hàng đã bị hủy'],
            ['id' => (string) Str::uuid(), 'name' => 'Đã hoàn tiền', 'description' => 'Đơn hàng đã được hoàn tiền'],
        ];

        foreach ($statuses as $status) {
            OrderStatus::create($status);
        }
    }
}
