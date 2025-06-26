@extends('layouts.account.layout')

@section('account_content')
<div class="bg-white border border-black shadow mb-8 max-w-2xl mx-auto" style="border-radius:0;">
    <div class="px-8 py-6 border-b border-black bg-black">
        <h1 class="text-2xl font-bold text-white uppercase tracking-wide">Sửa đánh giá sản phẩm</h1>
    </div>
    <div class="p-8">
        <form action="{{ route('account.reviews.update', $review->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="order_id" value="{{ $order->id }}">
            <input type="hidden" name="book_id" value="{{ $item->book->id }}">
            <div class="mb-2">
                <span class="block text-sm font-medium text-black mb-1">Sản phẩm: {{ $item->book->title }}</span>
            </div>
            <div class="mb-2">
                <span class="block text-sm font-medium text-black mb-1">Ảnh: </span>
                <img src="{{ $item->book->cover_image_url }}" alt="{{ $item->book->title }}"
                    class="w-20 h-28 object-cover shadow-sm border border-slate-300" style="border-radius:0;">
            </div>
            <div class="grid grid-cols-2 gap-4 mb-2">
                <div><span class="block text-xs text-gray-500">Mã đơn hàng: {{ $order->order_code }}</span></div>
                <div><span class="block text-xs text-gray-500">Trạng thái: {{ $order->orderStatus->name }}</span></div>
                <div><span class="block text-xs text-gray-500">Phí vận chuyển: {{ number_format($order->shipping_fee, 0, ',', '.') }} đ</span></div>
                <div><span class="block text-xs text-gray-500">Địa chỉ nhận: {{ $order->address->address_detail }}</span></div>
                <div><span class="block text-xs text-gray-500">Phương thức thanh toán: {{ $order->paymentMethod->name ?? 'Chưa xác định' }}</span></div>
                <div><span class="block text-xs text-gray-500">Thời gian đặt hàng: {{ $order->created_at }}</span></div>
            </div>
            <div class="mb-2">
                <span class="block text-xs text-gray-500">Số lượng: </span>
                <span class="font-semibold text-sm text-black">{{ $item->quantity }}</span>
                <span class="block text-xs text-gray-500">Tác giả: </span>
                <span class="font-semibold text-sm text-black">{{ $item->book->author->name ?? 'N/A' }}</span>
                <span class="block text-xs text-gray-500">Nhà xuất bản: </span>
                <span class="font-semibold text-sm text-black">{{ $item->book->brand->name ?? 'N/A' }}</span>
                <span class="block text-xs text-gray-500">Giá: </span>
                <span class="font-semibold text-sm text-black">{{ number_format($item->price, 0, ',', '.') }} đ</span>
            </div>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Tạm tính:</span>
                    <span class="text-gray-800 font-medium">{{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Phí vận chuyển:</span>
                    <span class="text-gray-800 font-medium">{{ number_format($order->shipping_fee, 0, ',', '.') }} đ</span>
                </div>
                @if ($order->discount_amount > 0)
                    <div class="flex justify-between text-green-600">
                        <span class="font-medium">Giảm giá ({{ $order->applied_voucher_code ?? 'Voucher' }}):</span>
                        <span class="font-medium">- {{ number_format($order->discount_amount, 0, ',', '.') }} đ</span>
                    </div>
                @endif
                <div class="border-t pt-2 mt-2"></div>
                <div class="flex justify-between text-xl font-bold text-gray-900">
                    <span>Tổng cộng:</span>
                    <span>{{ number_format($order->total_amount, 0, ',', '.') }} đ</span>
                </div>
            </div>
            <label class="block text-sm font-medium text-black mb-2">Số sao đánh giá:</label>
            <div class="flex items-center space-x-1">
                @for ($i = 5; $i >= 1; $i--)
                    <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" class="sr-only" {{ old('rating', $review->rating) == $i ? 'checked' : '' }}>
                    <label for="star{{ $i }}" class="cursor-pointer text-3xl text-slate-300 hover:text-yellow-400 transition-colors duration-150 {{ $review->rating >= $i ? 'text-yellow-400' : '' }}" title="{{ $i }} sao">★</label>
                @endfor
            </div>
            <textarea name="comment" rows="3"
                class="w-full px-3 py-2 border border-black rounded-none focus:ring-2 focus:ring-black focus:border-black transition-colors duration-200 text-sm resize-none text-black bg-white"
                placeholder="Nhận xét về sản phẩm..." required>{{ old('comment', $review->comment) }}</textarea>
            <button type="submit"
                class="w-full inline-flex items-center justify-center px-4 py-2 bg-black hover:bg-gray-900 text-white text-sm font-medium rounded-none transition-colors duration-200 focus:ring-2 focus:ring-black focus:ring-offset-2">
                Cập nhật đánh giá
            </button>
        </form>
    </div>
</div>
@endsection
