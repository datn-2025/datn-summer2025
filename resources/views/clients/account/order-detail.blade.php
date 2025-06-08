@extends('layouts.account')

@section('account_content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back button -->
        <div class="mb-6">
            <a href="{{ route('account.orders.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Quay lại danh sách đơn hàng
            </a>
        </div>

        <!-- Order header -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white">Chi tiết đơn hàng</h1>
                        <p class="mt-1 text-blue-100">Mã đơn hàng: {{ $order->code }}</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <span class="px-4 py-2 text-sm font-semibold rounded-full 
                            {{ $order->orderStatus->name === 'Đã hủy' ? 'bg-red-100 text-red-800' : 
                               ($order->orderStatus->name === 'Thành công' ? 'bg-green-100 text-green-800' : 
                               'bg-blue-100 text-blue-800') }}">
                            {{ $order->orderStatus->name }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <!-- Order info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Thông tin đơn hàng</h2>
                        <div class="space-y-2 text-sm text-gray-600">
                            <p><span class="font-medium">Ngày đặt:</span> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                            <p><span class="font-medium">Phương thức thanh toán:</span> {{ $order->paymentMethod->name ?? 'Không xác định' }}</p>
                            <p><span class="font-medium">Trạng thái thanh toán:</span> {{ $order->paymentStatus->name ?? 'Chưa thanh toán' }}</p>
                            <p><span class="font-medium">Tổng tiền:</span> {{ number_format($order->total) }} đ</p>
                        </div>
                    </div>
                    
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Địa chỉ giao hàng</h2>
                        <div class="space-y-2 text-sm text-gray-600">
                            <p class="font-medium">{{ $order->shippingAddress->full_name ?? 'Không có thông tin' }}</p>
                            <p>{{ $order->shippingAddress->phone ?? '' }}</p>
                            <p>{{ $order->shippingAddress->address ?? '' }}</p>
                            <p>{{ $order->shippingAddress->ward ?? '' }}, 
                               {{ $order->shippingAddress->district ?? '' }}, 
                               {{ $order->shippingAddress->city ?? '' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Order items -->
                <div class="border-t border-gray-200 pt-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Sản phẩm đã đặt</h2>
                    <div class="space-y-6">
                        @foreach($order->orderItems as $item)
                            <div class="flex items-start border-b border-gray-100 pb-6">
                                <div class="flex-shrink-0 h-24 w-24 rounded-md overflow-hidden bg-gray-200">
                                    @if($item->book->images->isNotEmpty())
                                        <img src="{{ asset('storage/' . $item->book->images->first()->path) }}" 
                                             alt="{{ $item->book->title }}" 
                                             class="h-full w-full object-cover">
                                    @else
                                        <div class="h-full w-full bg-gray-300 flex items-center justify-center">
                                            <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4 flex-1">
                                    <h3 class="text-base font-medium text-gray-900">
                                        {{ $item->book->title }}
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Số lượng: {{ $item->quantity }}
                                    </p>
                                    <p class="mt-1 text-sm font-medium text-gray-900">
                                        {{ number_format($item->price) }} đ
                                    </p>
                                    @if($item->review)
                                        <div class="mt-2 text-sm text-gray-600">
                                            <p>Đánh giá: {{ $item->review->rating }} sao</p>
                                            <p class="mt-1">{{ $item->review->comment }}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4 text-right">
                                    <p class="text-base font-medium text-gray-900">
                                        {{ number_format($item->price * $item->quantity) }} đ
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order summary -->
                <div class="mt-8 border-t border-gray-200 pt-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Tóm tắt đơn hàng</h2>
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tạm tính</span>
                                <span class="text-gray-900">{{ number_format($order->total_amount) }} đ</span>
                            </div>
                            @if($order->voucher)
                                @php
                                    $discountAmount = 0;
                                    // Tính toán số tiền giảm giá dựa trên phần trăm
                                    $discountByPercent = $order->total_amount * ($order->voucher->discount_percent / 100);
                                    // So sánh với mức giảm giá tối đa được phép
                                    $discountAmount = min($discountByPercent, $order->voucher->max_discount);
                                @endphp
                                <div class="flex justify-between">
                                    <span class="text-gray-600">
                                        Mã giảm giá ({{ $order->voucher->code }})
                                        @if($order->voucher->discount_percent)
                                            - {{ $order->voucher->discount_percent }}%
                                        @endif
                                    </span>
                                    <span class="text-red-600">-{{ number_format($discountAmount) }} đ</span>
                                </div>
                            @elseif($order->discount > 0)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Giảm giá</span>
                                    <span class="text-red-600">-{{ number_format($order->discount) }} đ</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-gray-600">Phí vận chuyển</span>
                                <span class="text-gray-900">{{ number_format($order->shipping_fee) }} đ</span>
                            </div>
                            @php
                                $total = $order->total_amount - (isset($discountAmount) ? $discountAmount : 0) + $order->shipping_fee;
                            @endphp
                            <div class="border-t border-gray-200 pt-4 flex justify-between">
                                <span class="text-lg font-medium text-gray-900">Tổng cộng</span>
                                <span class="text-lg font-bold text-gray-900">{{ number_format($total) }} đ</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order actions -->
                @if(in_array($order->orderStatus->name, ['Chờ xác nhận', 'Đã xác nhận', 'Đang chuẩn bị']))
                    <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end">
                        <form action="{{ route('account.orders.update', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                    onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                                Hủy đơn hàng
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection