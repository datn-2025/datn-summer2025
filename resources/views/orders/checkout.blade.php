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
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Địa chỉ giao hàng</label>
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
                {{-- Khu vực nhập địa chỉ mới --}}
                <div id="new-address-form" class="mt-6 pt-6 border-t border-gray-200">
                    <label class="text-md font-semibold text-gray-800 mb-4">Hoặc nhập địa chỉ giao hàng mới:</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="new_recipient_name" class="block text-sm font-medium text-gray-700 mb-1">Tên người nhận:</label>
                            <input type="text" name="new_recipient_name" id="new_recipient_name" class="w-full border rounded-lg px-3 py-2 bg-white focus:ring-blue-500 focus:border-blue-500" placeholder="Nguyễn Văn A" value="{{ old('new_recipient_name') }}">
                            @error('new_recipient_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="new_phone" class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại:</label>
                            <input type="text" name="new_phone" id="new_phone" class="w-full border rounded-lg px-3 py-2 bg-white focus:ring-blue-500 focus:border-blue-500" placeholder="09xxxxxxxx" value="{{ old('new_phone') }}">
                            @error('new_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="tinh" class="block text-sm font-medium text-gray-700 mb-1">Tỉnh/Thành phố:</label>
                        <select id="tinh" name="new_address_city_id" class="w-full border rounded-lg px-3 py-2 bg-white focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Chọn Tỉnh/Thành phố</option>
                        </select>
                        <input type="hidden" name="new_address_city_name" id="ten_tinh">
                         @error('new_address_city_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="quan" class="block text-sm font-medium text-gray-700 mb-1">Quận/Huyện:</label>
                        <select id="quan" name="new_address_district_id" class="w-full border rounded-lg px-3 py-2 bg-white focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Chọn Quận/Huyện</option>
                        </select>
                        <input type="hidden" name="new_address_district_name" id="ten_quan">
                        @error('new_address_district_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="phuong" class="block text-sm font-medium text-gray-700 mb-1">Phường/Xã:</label>
                        <select id="phuong" name="new_address_ward_id" class="w-full border rounded-lg px-3 py-2 bg-white focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Chọn Phường/Xã</option>
                        </select>
                        <input type="hidden" name="new_address_ward_name" id="ten_phuong">
                        @error('new_address_ward_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="new_address_detail" class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ cụ thể (Số nhà, tên đường):</label>
                        <input type="text" name="new_address_detail" id="new_address_detail" class="w-full border rounded-lg px-3 py-2 bg-white focus:ring-blue-500 focus:border-blue-500" placeholder="Ví dụ: Số 123, Đường ABC" value="{{ old('new_address_detail') }}">
                        @error('new_address_detail') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Phương thức vận chuyển -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Phương thức vận chuyển</h3>
                    <div class="space-y-2">
                        <label class="flex items-center justify-between p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <div class="flex items-center">
                                <input type="radio" name="shipping_method" value="standard" class="mr-2" checked>
                                <div>
                                    <span class="font-medium">Giao hàng tiết kiệm</span>
                                    <p class="text-sm text-gray-600">Giao hàng trong 3-5 ngày</p>
                                </div>
                            </div>
                            <span class="font-medium">20.000đ</span>
                        </label>
                        <label class="flex items-center justify-between p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
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
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Mã giảm giá</h3>
                    <div class="flex space-x-2">
                        <input type="text" name="voucher_code" class="flex-1 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2" placeholder="Nhập mã giảm giá">
                        <button type="button" id="apply-voucher" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:text-sm">
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
                            <div class="voucher-item flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:shadow-lg transition-shadow duration-200 ease-in-out">
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
                                        class="apply-button text-sm font-medium text-indigo-600 hover:text-indigo-800 bg-indigo-100 hover:bg-indigo-200 px-3 py-1.5 rounded-md transition-colors"
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
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Phương thức thanh toán</h3>
                    <div class="space-y-2">
                        @foreach($paymentMethods as $method)
                        <label class="flex items-center justify-between p-4 border rounded-lg cursor-pointer hover:bg-gray-100 transition-colors duration-150 ease-in-out has-[:checked]:bg-indigo-50 has-[:checked]:border-indigo-500">
                            <span class="font-medium text-gray-700">{{ $method->name }}</span>
                            <input type="radio" name="payment_method_id" value="{{ $method->id }}" class="form-radio h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500 ml-3" required>
                        </label>
                        @endforeach
                    </div>
                    @error('payment_method_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ghi chú -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Ghi chú</h3>
                    <textarea name="note" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3" placeholder="Nhập ghi chú (nếu có)"></textarea>
                </div>

                <button type="submit" class="w-full bg-green-500 text-white py-3 px-6 rounded-lg text-lg font-semibold hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 shadow-md hover:shadow-lg transition-all duration-150 ease-in-out">
                    Đặt hàng
                </button>
            </form>
        </div>

        <!-- Thông tin đơn hàng -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Thông tin đơn hàng</h2>

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
            <div class="mt-6 pt-6 border-t border-gray-200 space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Tạm tính:</span>
                    <span class="text-gray-800 font-medium">{{ number_format($subtotal) }}đ</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Phí vận chuyển:</span>
                    <span id="shipping-fee" class="text-gray-800 font-medium">20.000đ</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Giảm giá:</span>
                    <span id="discount-amount" class="text-gray-800 font-medium">0đ</span>
                </div>
                <div class="flex justify-between items-center font-bold text-lg">
                    <span class="text-gray-900 font-bold text-lg">Tổng cộng:</span>
                    <span id="total-amount" class="text-green-600 font-bold text-xl">{{ number_format($subtotal + 20000) }}đ</span>
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
