<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\PaymentStatus;
use App\Models\Voucher;
use Illuminate\Support\Str;

class OrderService
{
    public function createOrder(array $data)
    {
        $cartItems = $data['cart_items'];
        $subtotal = $this->calculateSubtotal($cartItems);

        // Xử lý voucher nếu có
        $discount = 0;
        $voucher = null;
        if (!empty($data['voucher_code'])) {
            $voucher = Voucher::where('code', $data['voucher_code'])->first();
            if ($voucher && $voucher->isValid()) {
                $discount = $this->calculateDiscount($voucher, $subtotal);
            }
        }

        // Tính phí vận chuyển
        $shippingFee = $this->calculateShippingFee($data['address_id'], $data['shipping_method']);

        // Tạo đơn hàng
        $order = Order::create([
            'id' => (string) Str::uuid(),
            'order_code' => $this->generateOrderCode(),
            'user_id' => $data['user_id'],
            'address_id' => $data['address_id'],
            'voucher_id' => $voucher ? $voucher->id : null,
            'total_amount' => $subtotal - $discount + $shippingFee,
            'shipping_fee' => $shippingFee,
            'note' => $data['note'] ?? null,
            'order_status_id' => OrderStatus::where('name', 'Chờ Xác Nhận')->first()->id,
            'payment_method_id' => $data['payment_method_id'],
            'payment_status_id' => PaymentStatus::where('name', 'Chờ Thanh Toán')->first()->id
        ]);

        // Tạo các order items
        foreach ($cartItems as $item) {
            OrderItem::create([
                'id' => (string) Str::uuid(),
                'order_id' => $order->id,
                'book_id' => $item->book_id,
                'book_format_id' => $item->book_format_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'total' => $item->price * $item->quantity
            ]);
        }

        // Nếu có voucher, tạo applied voucher
        if ($voucher) {
            $order->appliedVoucher()->create([
                'id' => (string) Str::uuid(),
                'user_id' => $data['user_id'],
                'voucher_id' => $voucher->id,
                'used_at' => now()
            ]);
        }

        return $order;
    }

    protected function calculateSubtotal($cartItems)
    {
        return $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }

    protected function calculateDiscount(Voucher $voucher, $subtotal)
    {
        if ($subtotal < $voucher->min_order_value) {
            return 0;
        }

        $discount = $subtotal * ($voucher->discount_percent / 100);

        if ($voucher->max_discount && $discount > $voucher->max_discount) {
            $discount = $voucher->max_discount;
        }

        return $discount;
    }

    protected function calculateShippingFee($addressId, $shippingMethod = 'standard')
    {
        // Tính phí vận chuyển dựa trên phương thức
        return $shippingMethod === 'standard' ? 20000 : 40000;
    }

    protected function generateOrderCode()
    {
        return 'ORD' . date('Ymd') . strtoupper(Str::random(4));
    }
}
