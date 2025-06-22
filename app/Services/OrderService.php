<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\PaymentStatus;
use App\Models\Voucher;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\Cart;

class OrderService
{
    public function createOrder(array $data)
    {
        $cartItems = $data['cart_items'];
        $subtotal = $this->calculateSubtotal($cartItems);
        Log::info('Order creation - Subtotal: ' . $subtotal);

        // Xử lý voucher nếu có
        $discount = 0;
        $voucher = null;
        if (!empty($data['voucher_code'])) {
            $voucher = Voucher::where('code', $data['voucher_code'])->first();
            Log::info('Order creation - Voucher found: ' . ($voucher ? $voucher->code : 'null'));

            if ($voucher && $voucher->isValid()) {
                // Kiểm tra giá trị đơn hàng tối thiểu
                if ($subtotal >= $voucher->min_order_value) {
                    // Kiểm tra số lần sử dụng của người dùng
                    $userUsageCount = $voucher->appliedVouchers()
                        ->where('user_id', $data['user_id'])
                        ->count();
                    Log::info('Order creation - User usage count: ' . $userUsageCount);

                    if ($userUsageCount < 1) { // Mỗi người chỉ được dùng 1 lần
                        $discount = $this->calculateDiscount($voucher, $subtotal);
                        Log::info('Order creation - Discount calculated: ' . $discount);
                    }
                }
            }
        }

        // Tính phí vận chuyển
        $shippingFee = $this->calculateShippingFee($data['address_id'], $data['shipping_method']);
        Log::info('Order creation - Shipping fee: ' . $shippingFee);

        // Tính tổng tiền
        $totalAmount = $subtotal - $discount + $shippingFee;
        Log::info('Order creation - Total amount calculation: ' . $subtotal . ' - ' . $discount . ' + ' . $shippingFee . ' = ' . $totalAmount);

        // Tạo đơn hàng
        $order = Order::create([
            'id' => (string) Str::uuid(),
            'order_code' => $this->generateOrderCode(),
            'user_id' => $data['user_id'],
            'address_id' => $data['address_id'],
            'voucher_id' => $voucher ? $voucher->id : null,
            'total_amount' => $totalAmount,
            'shipping_fee' => $shippingFee,
            'note' => $data['note'] ?? null,
            'order_status_id' => OrderStatus::where('name', 'Chờ Xác Nhận')->first()->id,
            'payment_method_id' => $data['payment_method_id'],
            'payment_status_id' => PaymentStatus::where('name', 'Chờ Thanh Toán')->first()->id
        ]);
        Log::info('Order created with ID: ' . $order->id);

        // Tạo các order items
        foreach ($cartItems as $item) {
            $total = $item->price * $item->quantity;
            OrderItem::create([
                'id' => (string) Str::uuid(),
                'order_id' => $order->id,
                'book_id' => $item->book_id,
                'book_format_id' => $item->book_format_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'total' => $total
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

            // Cập nhật số lượng voucher
            $voucher->decrement('quantity');
        }

        // Xóa sản phẩm khỏi giỏ hàng
        foreach ($cartItems as $item) {
            Cart::where('user_id', $data['user_id'])
                ->where('book_id', $item->book_id)
                ->where('book_format_id', $item->book_format_id)
                ->delete();
        }
        Log::info('Cart items deleted for user: ' . $data['user_id']);

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
        // Lấy thông tin địa chỉ
        $address = \App\Models\Address::find($addressId);
        
        if (!$address || !$address->district_id || !$address->ward_code) {
            // Fallback về phí cũ nếu không có thông tin GHN
            return $shippingMethod === 'standard' ? 20000 : 40000;
        }
        
        // Sử dụng GHN service để tính phí thực tế
        $ghnService = app(\App\Services\GhnService::class);
        
        $data = [
            'from_district_id' => config('ghn.from_district_id'),
            'from_ward_code' => config('ghn.from_ward_code'),
            'to_district_id' => $address->district_id,
            'to_ward_code' => $address->ward_code,
            'weight' => config('ghn.default_weight'),
            'length' => config('ghn.default_length'),
            'width' => config('ghn.default_width'),
            'height' => config('ghn.default_height'),
            'insurance_value' => 0
        ];
        
        $result = $ghnService->calculateShippingFee($data);
        
        if ($result && isset($result['data']['total'])) {
            // Lưu service_id để sử dụng cho việc tạo đơn GHN sau này
            if (isset($result['data']['service_id'])) {
                // Có thể lưu service_id vào session hoặc cache để sử dụng sau
                session(['ghn_service_id_' . $addressId => $result['data']['service_id']]);
            }
            return $result['data']['total'];
        }
        
        // Fallback về phí cũ nếu API thất bại
        return $shippingMethod === 'standard' ? 20000 : 40000;
    }

    protected function generateOrderCode()
    {
        return 'ORD' . date('Ymd') . strtoupper(Str::random(4));
    }
}
