@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Thanh toán</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Form thanh toán -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Thông tin thanh toán</h2>

            <form action="{{ route('orders.store') }}" method="POST" id="checkout-form">
                @csrf

                <!-- Địa chỉ giao hàng -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ giao hàng</label>
                    <select name="address_id" class="w-full border rounded-lg px-3 py-2" required>
                        <option value="">Chọn địa chỉ</option>
                        @foreach($addresses as $address)
                        <option value="{{ $address->id }}">
                            {{ $address->recipient_name }} - {{ $address->phone }} - {{ $address->address_detail }}, {{ $address->ward }}, {{ $address->district }}, {{ $address->city }}
                        </option>
                        @endforeach
                    </select>
                    @error('address_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phương thức vận chuyển -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phương thức vận chuyển</label>
                    <div class="space-y-2">
                        <label class="flex items-center justify-between p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <div class="flex items-center">
                                <input type="radio" name="shipping_method" value="standard" class="mr-2" checked>
                                <div>
                                    <span class="font-medium">Giao hàng tiết kiệm</span>
                                    <p class="text-sm text-gray-600">Giao hàng trong 3-5 ngày</p>
                                </div>
                            </div>
                            <span class="font-medium">20.000đ</span>
                        </label>
                        <label class="flex items-center justify-between p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <div class="flex items-center">
                                <input type="radio" name="shipping_method" value="express" class="mr-2">
                                <div>
                                    <span class="font-medium">Giao hàng nhanh</span>
                                    <p class="text-sm text-gray-600">Giao hàng trong 1-2 ngày</p>
                                </div>
                            </div>
                            <span class="font-medium">40.000đ</span>
                        </label>
                    </div>
                    @error('shipping_method')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mã giảm giá -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mã giảm giá</label>
                    <div class="flex space-x-2">
                        <input type="text" name="voucher_code" class="flex-1 border rounded-lg px-3 py-2" placeholder="Nhập mã giảm giá">
                        <button type="button" id="apply-voucher" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                            Áp dụng
                        </button>
                    </div>
                    <div id="voucher-message" class="mt-2 text-sm"></div>
                    @error('voucher_code')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror

                    <!-- Danh sách mã giảm giá có thể sử dụng -->
                    <div class="mt-4">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Mã giảm giá có thể sử dụng:</h3>
                        <div class="space-y-2">
                            @foreach($vouchers as $voucher)
                            <div class="voucher-item flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <div class="flex items-center space-x-2">
                                        <span class="voucher-code font-medium text-blue-600" data-code="{{ $voucher->code }}">{{ $voucher->code }}</span>
                                        <span class="text-sm text-gray-500">({{ $voucher->discount_percent }}% giảm giá)</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Áp dụng cho đơn hàng từ {{ number_format($voucher->min_order_value) }}đ
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Hết hạn: {{ $voucher->valid_to ? $voucher->valid_to->format('d/m/Y') : 'N/A' }}
                                    </p>
                                </div>
                                <button type="button"
                                        class="apply-button text-sm text-blue-600 hover:text-blue-800"
                                        onclick="applySuggestedVoucher('{{ $voucher->code }}')">
                                    Áp dụng
                                </button>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Phương thức thanh toán -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phương thức thanh toán</label>
                    <div class="space-y-2">
                        @foreach($paymentMethods as $method)
                        <label class="flex items-center">
                            <input type="radio" name="payment_method_id" value="{{ $method->id }}" class="mr-2" required>
                            <span>{{ $method->name }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('payment_method_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ghi chú -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ghi chú</label>
                    <textarea name="note" rows="3" class="w-full border rounded-lg px-3 py-2" placeholder="Nhập ghi chú (nếu có)"></textarea>
                </div>

                <button type="submit" class="w-full bg-green-500 text-white py-3 rounded-lg hover:bg-green-600">
                    Đặt hàng
                </button>
            </form>
        </div>

        <!-- Thông tin đơn hàng -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Thông tin đơn hàng</h2>

            <!-- Danh sách sản phẩm -->
            <div class="space-y-4 mb-6">
                @foreach($cartItems as $item)
                <div class="flex items-center space-x-4">
                    <img src="{{ $item->book->images->first()?->image_url ?? asset('images/no-image.png') }}"
                         alt="{{ $item->book->title }}"
                         class="w-20 h-20 object-cover rounded">
                    <div class="flex-1">
                        <h3 class="font-medium">{{ $item->book->title }}</h3>
                        <p class="text-sm text-gray-600">{{ $item->bookFormat->name }}</p>
                        <p class="text-sm">Số lượng: {{ $item->quantity }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-medium">{{ number_format($item->price * $item->quantity) }}đ</p>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Tổng tiền -->
            <div class="border-t pt-4 space-y-2">
                <div class="flex justify-between">
                    <span>Tạm tính:</span>
                    <span>{{ number_format($subtotal) }}đ</span>
                </div>
                <div class="flex justify-between">
                    <span>Phí vận chuyển:</span>
                    <span id="shipping-fee">20.000đ</span>
                </div>
                <div class="flex justify-between">
                    <span>Giảm giá:</span>
                    <span id="discount-amount">0đ</span>
                </div>
                <div class="flex justify-between font-bold text-lg">
                    <span>Tổng cộng:</span>
                    <span id="total-amount">{{ number_format($subtotal + 20000) }}đ</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Hàm hỗ trợ định dạng số tiền
function number_format(number) {
    return new Intl.NumberFormat('vi-VN').format(number);
}

// Hàm cập nhật tổng tiền hiển thị
function updateTotal() {
    const subtotal = {{ $subtotal }};
    const shippingFeeText = document.getElementById('shipping-fee').textContent;
    // Lấy phí vận chuyển hiện tại
    const shippingFee = parseInt(shippingFeeText.replace(/[^0-9-]/g, '')) || 0;

    // Lấy giá trị giảm giá hiện tại (sẽ là số âm nếu có giảm giá)
    const discountText = document.getElementById('discount-amount').textContent;
    const discount = parseInt(discountText.replace(/[^0-9-]/g, '')) || 0;

    // Tính tổng mới: tạm tính + phí vận chuyển + (giá trị giảm giá âm)
    const total = subtotal + shippingFee + discount;
    document.getElementById('total-amount').textContent = `${number_format(total)}đ`;
}

// Cập nhật phí vận chuyển khi thay đổi phương thức
document.querySelectorAll('input[name="shipping_method"]').forEach(input => {
    input.addEventListener('change', function() {
        const shippingFee = this.value === 'standard' ? 20000 : 40000;
        document.getElementById('shipping-fee').textContent = `${number_format(shippingFee)}đ`;

        // Cập nhật tổng tiền sau khi phí vận chuyển thay đổi
        updateTotal();
    });
});

// Áp dụng mã giảm giá khi click nút
document.getElementById('apply-voucher').addEventListener('click', function() {
    const voucherCode = document.querySelector('input[name="voucher_code"]').value;
    const messageEl = document.getElementById('voucher-message');
    const discountEl = document.getElementById('discount-amount');

    if (!voucherCode) {
        messageEl.textContent = 'Vui lòng nhập mã giảm giá';
        messageEl.className = 'mt-2 text-sm text-red-500';
        discountEl.textContent = '0đ';
        updateTotal();
        return;
    }

    const currentSubtotal = {{ $subtotal }};

    fetch(`{{ route('orders.apply-voucher') }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ voucher_code: voucherCode, subtotal: currentSubtotal })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            messageEl.textContent = 'Áp dụng mã giảm giá thành công';
            messageEl.className = 'mt-2 text-sm text-green-500';

            // Cập nhật hiển thị giảm giá
            const discount = data.discount || 0;
            discountEl.textContent = `-${number_format(discount)}đ`;

            // Reset trạng thái tất cả voucher
            const voucherElements = document.querySelectorAll('.voucher-item');
            voucherElements.forEach(element => {
                element.classList.remove('border-2', 'border-green-500');
                const badge = element.querySelector('.voucher-badge');
                if (badge) badge.remove();
                const applyButton = element.querySelector('.apply-button');
                if (applyButton) applyButton.style.display = 'block';
            });

            // Cập nhật giao diện voucher được chọn
            const selectedVoucher = document.querySelector(`.voucher-code[data-code="${voucherCode}"]`).closest('.voucher-item');
            if (selectedVoucher) {
                // Thêm viền xanh
                selectedVoucher.classList.add('border-2', 'border-green-500');

                // Thêm badge "Đã áp dụng"
                const badge = document.createElement('span');
                badge.className = 'voucher-badge px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full';
                badge.textContent = 'Đã áp dụng';
                selectedVoucher.querySelector('.voucher-code').parentNode.appendChild(badge);

                // Ẩn nút áp dụng
                const applyButton = selectedVoucher.querySelector('.apply-button');
                if (applyButton) applyButton.style.display = 'none';
            }

            updateTotal();
        } else {
            messageEl.textContent = data.message || 'Có lỗi xảy ra';
            messageEl.className = 'mt-2 text-sm text-red-500';
            discountEl.textContent = '0đ';
            updateTotal();
        }
    })
    .catch(error => {
        messageEl.textContent = 'Có lỗi xảy ra';
        messageEl.className = 'mt-2 text-sm text-red-500';
        discountEl.textContent = '0đ';
        updateTotal();
    });
});

// Hàm áp dụng mã giảm giá được gợi ý
function applySuggestedVoucher(code) {
    const input = document.querySelector('input[name="voucher_code"]');
    input.value = code;

    // Kích hoạt sự kiện click nút áp dụng
    document.getElementById('apply-voucher').click();
}

// Cập nhật tổng tiền lần đầu khi trang load
// updateTotal(); // Có thể bỏ comment nếu muốn tổng tiền hiển thị đúng ngay khi load trang với phí ship mặc định

</script>
@endpush
@endsection
