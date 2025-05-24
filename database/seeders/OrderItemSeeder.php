<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Book;
use App\Models\AttributeValue;
use App\Models\OrderItem;
use App\Models\OrderItemAttributeValue;
use App\Models\BookFormat;
use Illuminate\Support\Str;

class OrderItemSeeder extends Seeder
{
    public function run()
    {
        $orders = Order::all();

        foreach ($orders as $order) {
            // Lấy sách ngẫu nhiên
            $book = Book::inRandomOrder()->first();

            // Lấy 2 thuộc tính ngẫu nhiên
            $attributeValueIds = AttributeValue::inRandomOrder()->limit(2)->pluck('id')->toArray();

            // Lấy định dạng sách ngẫu nhiên
            $bookFormat = BookFormat::inRandomOrder()->first();

            $quantity = rand(1, 5);
            $price = 100000; // hoặc lấy giá thực tế từ sách nếu có

            $orderItem = OrderItem::create([
                'id' => Str::uuid()->toString(),
                'order_id' => $order->id,
                'book_id' => $book->id,
                'book_format_id' => $bookFormat ? $bookFormat->id : null,
                'quantity' => $quantity,
                'price' => $price,
                'total' => $price * $quantity,
            ]);

            foreach ($attributeValueIds as $attrValueId) {
                OrderItemAttributeValue::create([
                    'id' => Str::uuid()->toString(),
                    'order_item_id' => $orderItem->id,
                    'attribute_value_id' => $attrValueId,
                ]);
            }
        }
    }
}
