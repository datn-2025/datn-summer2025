@extends('layouts.app')
@section('title', 'Giỏ hàng')

@section('content')
<div class="max-w-screen-xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Giỏ hàng của bạn</h1>

    @if(count($cart) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Danh sách sản phẩm --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6 space-y-6">
                        @foreach($cart as $item)
                            <div class="flex items-center space-x-4 py-4 border-b last:border-0" id="cart-item-{{$item->id}}">
                                {{-- Ảnh sản phẩm --}}
                                <div class="flex-shrink-0 w-24 h-32">
                                    <img src="{{ asset('storage/images/' . $item->image) }}" 
                                         alt="{{ $item->title }}" 
                                         class="w-full h-full object-cover">
                                </div>

                                {{-- Thông tin sản phẩm --}}
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold">{{ $item->title }}</h3>
                                    <p class="text-gray-600 text-sm">Tác giả: {{ $item->author_name }}</p>
                                    @if($item->format_name)
                                        <p class="text-gray-600 text-sm">Định dạng: {{ $item->format_name }}</p>
                                    @endif
                                    <p class="text-red-600 font-semibold mt-1">
                                        {{ number_format($item->price_at_addition, 0, ',', '.') }}₫
                                    </p>
                                </div>

                                {{-- Số lượng và actions --}}
                                <div class="flex flex-col items-end space-y-2">
                                    <div class="flex items-center border rounded">
                                        <button class="px-3 py-1 hover:bg-gray-100 update-quantity" 
                                                data-book-id="{{ $item->id }}" 
                                                data-action="decrease">-</button>
                                        <input type="number" 
                                               value="{{ $item->quantity }}" 
                                               min="1" 
                                               class="w-16 text-center border-x quantity-input" 
                                               data-book-id="{{ $item->id }}">
                                        <button class="px-3 py-1 hover:bg-gray-100 update-quantity" 
                                                data-book-id="{{ $item->id }}" 
                                                data-action="increase">+</button>
                                    </div>
                                    <button class="text-red-500 hover:text-red-700 remove-item" 
                                            data-book-id="{{ $item->id }}">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Tóm tắt đơn hàng --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4">Tóm tắt đơn hàng</h2>

                    {{-- Mã giảm giá --}}
                    <div class="mb-6">
                        <div class="flex space-x-2">
                            <input type="text" 
                                   id="voucher-code" 
                                   placeholder="Nhập mã giảm giá" 
                                   class="flex-1 border rounded px-3 py-2">
                            <button id="apply-voucher" 
                                    class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800">
                                Áp dụng
                            </button>
                        </div>
                        <p id="voucher-message" class="text-sm mt-2"></p>
                    </div>

                    {{-- Chi tiết thanh toán --}}
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span>Tạm tính:</span>
                            <span>{{ number_format($total, 0, ',', '.') }}₫</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Giảm giá:</span>
                            <span>-{{ number_format($discount, 0, ',', '.') }}₫</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Phí vận chuyển:</span>
                            <span>{{ number_format($shipping, 0, ',', '.') }}₫</span>
                        </div>
                        <div class="flex justify-between font-bold text-lg pt-3 border-t">
                            <span>Tổng cộng:</span>
                            <span>{{ number_format($finalTotal, 0, ',', '.') }}₫</span>
                        </div>
                    </div>

                    {{-- Nút thanh toán --}}
                    <div class="mt-6 space-y-3">
                        {{-- <a href="{{ route('checkout') }}"  --}}
                        <a href="#"
                           class="block w-full bg-red-500 text-white text-center py-3 rounded-full hover:bg-red-600 transition">
                            Tiến hành thanh toán
                        </a>
                        <a href="{{ route('books.index') }}" 
                           class="block w-full bg-gray-200 text-gray-800 text-center py-3 rounded-full hover:bg-gray-300 transition">
                            Tiếp tục mua hàng
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-12">
            <h2 class="text-2xl font-semibold mb-4">Giỏ hàng trống</h2>
            <p class="text-gray-600 mb-8">Bạn chưa có sản phẩm nào trong giỏ hàng.</p>
            <a href="{{ route('books.index') }}" 
               class="inline-block bg-red-500 text-white px-8 py-3 rounded-full hover:bg-red-600 transition">
                Tiếp tục mua sắm
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cập nhật số lượng
    const updateQuantity = async (bookId, newQuantity) => {
        try {
            const response = await fetch('/cart/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    book_id: bookId,
                    quantity: newQuantity
                })
            });

            if (!response.ok) throw new Error('Lỗi cập nhật giỏ hàng');

            // Reload trang để cập nhật tổng tiền
            window.location.reload();
        } catch (error) {
            alert('Có lỗi xảy ra: ' + error.message);
        }
    };

    // Xử lý nút tăng/giảm số lượng
    document.querySelectorAll('.update-quantity').forEach(button => {
        button.addEventListener('click', function() {
            const bookId = this.dataset.bookId;
            const input = document.querySelector(`input[data-book-id="${bookId}"]`);
            let quantity = parseInt(input.value);

            if (this.dataset.action === 'increase') {
                quantity++;
            } else {
                quantity = Math.max(1, quantity - 1);
            }

            input.value = quantity;
            updateQuantity(bookId, quantity);
        });
    });

    // Xử lý input số lượng
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            const quantity = Math.max(1, parseInt(this.value) || 1);
            this.value = quantity;
            updateQuantity(this.dataset.bookId, quantity);
        });
    });

    // Xóa sản phẩm
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', async function() {
            if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) return;

            const bookId = this.dataset.bookId;
            try {
                const response = await fetch('/cart/remove', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ book_id: bookId })
                });

                if (!response.ok) throw new Error('Lỗi xóa sản phẩm');

                // Xóa phần tử khỏi DOM và reload trang
                document.getElementById(`cart-item-${bookId}`).remove();
                window.location.reload();
            } catch (error) {
                alert('Có lỗi xảy ra: ' + error.message);
            }
        });
    });

    // Áp dụng mã giảm giá
    document.getElementById('apply-voucher').addEventListener('click', async function() {
        const code = document.getElementById('voucher-code').value.trim();
        const messageElement = document.getElementById('voucher-message');

        if (!code) {
            messageElement.textContent = 'Vui lòng nhập mã giảm giá';
            messageElement.className = 'text-sm mt-2 text-red-500';
            return;
        }

        try {
            const response = await fetch('/cart/apply-voucher', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ code })
            });

            const data = await response.json();

            if (response.ok) {
                messageElement.textContent = data.success;
                messageElement.className = 'text-sm mt-2 text-green-500';
                // Reload trang để cập nhật giá
                window.location.reload();
            } else {
                messageElement.textContent = data.error;
                messageElement.className = 'text-sm mt-2 text-red-500';
            }
        } catch (error) {
            messageElement.textContent = 'Có lỗi xảy ra, vui lòng thử lại';
            messageElement.className = 'text-sm mt-2 text-red-500';
        }
    });
});
</script>
@endpush
