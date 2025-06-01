@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Chi tiết đơn hàng #{{ $order->order_code }}</h1>
            <a href="{{ route('orders.index') }}" class="text-blue-500 hover:text-blue-600">
                <i class="fas fa-arrow-left mr-2"></i>Quay lại
            </a>
        </div>

        <!-- Trạng thái đơn hàng -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold mb-2">Trạng thái đơn hàng</h2>
                    <p class="text-gray-600">{{ $order->orderStatus->name }}</p>
                </div>
                <div>
                    <h2 class="text-lg font-semibold mb-2">Trạng thái thanh toán</h2>
                    <p class="text-gray-600">{{ $order->paymentStatus->name }}</p>
                </div>
            </div>
        </div>

        <!-- Thông tin đơn hàng -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Thông tin đơn hàng</h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600">Ngày đặt:</p>
                    <p class="font-medium">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Phương thức thanh toán:</p>
                    <p class="font-medium">
                        @if($order->payments->isNotEmpty())
                            {{ $order->payments->first()->paymentMethod->name }}
                        @else
                            Chưa có thông tin thanh toán
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Thông tin giao hàng -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Thông tin giao hàng</h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600">Người nhận:</p>
                    <p class="font-medium">{{ $order->address->recipient_name }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Số điện thoại:</p>
                    <p class="font-medium">{{ $order->address->phone }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-gray-600">Địa chỉ:</p>
                    <p class="font-medium">
                        {{ $order->address->address_detail }}, {{ $order->address->ward }},
                        {{ $order->address->district }}, {{ $order->address->city }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Chi tiết sản phẩm -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Chi tiết sản phẩm</h2>

            <div class="space-y-4">
                @foreach($order->orderItems as $item)
                <div class="flex items-center space-x-4">
                    <img src="{{ $item->book->image_url }}" alt="{{ $item->book->title }}" class="w-20 h-20 object-cover rounded">
                    <div class="flex-1">
                        <h3 class="font-medium">{{ $item->book->title }}</h3>
                        <p class="text-sm text-gray-600">{{ $item->bookFormat->name }}</p>
                        <p class="text-sm">Số lượng: {{ $item->quantity }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-medium">{{ number_format($item->total) }} VNĐ</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="border-t mt-4 pt-4">
                @php
                    $subtotal = $order->orderItems->sum('total');
                    $discount = 0;
                    if ($order->voucher) {
                        $discount = $subtotal * ($order->voucher->discount_percent / 100);
                        if ($order->voucher->max_discount && $discount > $order->voucher->max_discount) {
                            $discount = $order->voucher->max_discount;
                        }
                    }
                @endphp
                <div class="flex justify-between mb-2">
                    <span>Tạm tính:</span>
                    <span>{{ number_format($subtotal) }} VNĐ</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span>Phí vận chuyển:</span>
                    <span>{{ number_format($order->shipping_fee) }} VNĐ</span>
                </div>
                @if($order->voucher)
                <div class="flex justify-between mb-2">
                    <span>Giảm giá ({{ $order->voucher->code }}):</span>
                    <span>-{{ number_format($discount) }} VNĐ</span>
                </div>
                @endif
                <div class="flex justify-between font-bold">
                    <span>Tổng tiền:</span>
                    <span>{{ number_format($subtotal + $order->shipping_fee - $discount) }} VNĐ</span>
                </div>
            </div>
        </div>

        <!-- Ghi chú -->
        @if($order->note)
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Ghi chú</h2>
            <p class="text-gray-600">{{ $order->note }}</p>
        </div>
        @endif
    </div>
</div>
@endsection
