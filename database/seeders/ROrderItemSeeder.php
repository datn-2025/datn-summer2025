<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class ROrderItemSeeder extends Seeder
{
    public function run(): void
    {
        $orderItems = [
            // Đơn hàng 1 - 3 sản phẩm
            [
                'order_id' => 1, // Lấy order_id từ bảng orders
                'book_id' => 1, // Đắc Nhân Tâm
                'book_format_id' => 1, // Bìa mềm
                'quantity' => 2,
                'price' => 150000,
                'discount_price' => 135000,
                'total_price' => 270000,
            ],
            [
                'order_id' => 1,
                'book_id' => 2, // Nhà Giả Kim
                'book_format_id' => 1, // Bìa mềm
                'quantity' => 1,
                'price' => 120000,
                'discount_price' => 108000,
                'total_price' => 108000,
            ],
            [
                'order_id' => 1,
                'book_id' => 3, // Đời Ngắn Đừng Ngủ Dài
                'book_format_id' => 1, // Bìa mềm
                'quantity' => 1,
                'price' => 72000,
                'discount_price' => 72000,
                'total_price' => 72000,
            ],
            
            // Đơn hàng 2 - 2 sản phẩm
            [
                'order_id' => 2,
                'book_id' => 4, // Tôi Tài Giỏi Bạn Cũng Thế
                'book_format_id' => 1, // Bìa mềm
                'quantity' => 1,
                'price' => 120000,
                'discount_price' => 108000,
                'total_price' => 108000,
            ],
            [
                'order_id' => 2,
                'book_id' => 5, // Đừng Bao Giờ Đi Ăn Một Mình
                'book_format_id' => 2, // Bìa cứng
                'quantity' => 1,
                'price' => 242000,
                'discount_price' => 242000,
                'total_price' => 242000,
            ],
            
            // Đơn hàng 3 - 1 sản phẩm (đã hủy)
            [
                'order_id' => 3,
                'book_id' => 6, // Dạy Con Làm Giàu
                'book_format_id' => 1, // Bìa mềm
                'quantity' => 1,
                'price' => 350000,
                'discount_price' => 250000,
                'total_price' => 250000,
            ],
        ];

        foreach ($orderItems as $item) {
            OrderItem::updateOrCreate(
                [
                    'order_id' => $item['order_id'],
                    'book_id' => $item['book_id'],
                    'book_format_id' => $item['book_format_id']
                ],
                $item
            );
        }
    }
}
