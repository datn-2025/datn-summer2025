@extends('layouts.account.layout')

@section('account_content')
    <div class="bg-white border border-black shadow mb-8" style="border-radius:0;">
        <div class="px-8 py-6 border-b border-black bg-black">
            <h1 class="text-2xl font-bold text-white uppercase tracking-wide">Đánh giá của tôi</h1>
        </div>
        <div class="p-8">
            <!-- Tabs -->
            <div class="flex space-x-1 mb-8 border-b border-black">
                @foreach ([1 => 'Tất cả đánh giá', 2 => 'Chưa đánh giá', 3 => 'Đã đánh giá'] as $type => $label)
                    <a href="{{ route('account.purchase', ['type' => $type]) }}"
                        class="flex-1 text-center px-6 py-3 text-base font-semibold border-b-2 transition
                       {{ request('type', '1') == $type ? 'border-black text-black bg-white' : 'border-transparent text-gray-500 hover:text-black hover:bg-gray-100' }}"
                        style="border-radius:0;">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <!-- Orders List -->
            <div class="space-y-6">
                @forelse($orders as $order)
                    <div class="bg-white border border-black shadow transition hover:shadow-lg" style="border-radius:0;">
                        <div
                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between px-6 py-4 bg-gray-50 border-b border-black">
                            <div>
                                <h3 class="text-lg font-bold text-black">Đơn hàng #{{ $order->order_code }}</h3>
                                <p class="text-sm text-gray-600">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="flex flex-col items-end">
                                <span
                                    class="inline-flex items-center px-3 py-1 text-xs font-bold bg-black text-white border border-black mb-1"
                                    style="border-radius:0;">
                                    Đã hoàn thành
                                </span>
                                <span class="text-base font-semibold text-black">Tổng tiền: <span
                                        class="text-red-600">{{ number_format($order->total_amount, 0, ',', '.') }}
                                        đ</span></span>
                            </div>
                        </div>
                        <div class="p-6 space-y-6">
                            @foreach ($order->orderItems as $item)
                                @php
                                    $review = $order
                                        ->reviews()
                                        ->withTrashed()
                                        ->where('book_id', $item->book_id)
                                        ->first();
                                @endphp
                                <div
                                    class="flex flex-col lg:flex-row gap-6 pb-6 border-b border-slate-200 last:border-b-0 last:pb-0">
                                    <div class="lg:w-32 flex-shrink-0 flex items-center justify-center bg-gray-100 border border-black"
                                        style="border-radius:0; min-height: 120px;">
                                        <img src="{{ $item->book->cover_image_url }}" alt="{{ $item->book->title }}"
                                            class="w-20 h-28 object-cover shadow-sm border border-slate-300"
                                            style="border-radius:0;">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-lg font-bold text-black mb-1">{{ $item->book->title }}</h4>
                                        <div class="text-sm text-gray-700 mb-1">
                                            <span class="font-medium">Tác giả:</span>
                                            {{ $item->book->author->name ?? 'Không rõ' }}
                                        </div>
                                        <div class="text-sm text-gray-700 mb-1">
                                            <span class="font-medium">Nhà xuất bản:</span>
                                            {{ $item->book->brand->name ?? 'Không rõ' }}
                                        </div>
                                        <div class="text-sm text-gray-700 mb-1">
                                            <span class="font-medium">Số lượng:</span> {{ $item->quantity }}
                                        </div>
                                    </div>
                                    <div class="lg:w-96 flex flex-col gap-2">
                                        @if ($review && !$review->trashed())
                                            <div class="bg-blue-50 border border-blue-200 p-4" style="border-radius:0;">
                                                <div class="mb-2">
                                                    <span class="text-xs text-slate-500">Cập nhật lần cuối:
                                                        {{ $review->updated_at->format('d/m/Y') }}</span>
                                                </div>
                                                <div class="flex items-center mb-2">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i
                                                            class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-slate-300' }} text-xl"></i>
                                                    @endfor
                                                </div>
                                                <div class="text-sm text-slate-700 mb-2">
                                                    {{ $review->comment ?? 'Không có nhận xét' }}</div>
                                                <div class="flex gap-2">
                                                    @if ($review->user_id === auth()->id())
                                                        <button type="button"
                                                            class="px-3 py-1 bg-black text-white text-xs font-medium rounded-none hover:bg-gray-900 transition-colors duration-150"
                                                            onclick="showEditReviewModal('{{ $review->id }}', '{{ addslashes($item->book->title) }}', '{{ $review->rating }}', '{{ addslashes($review->comment) }}')">Sửa
                                                            đánh giá</button>
                                                        <form action="{{ route('account.reviews.destroy', $review->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa đánh giá này?');"
                                                            class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="px-3 py-1 bg-red-600 text-white text-xs font-medium rounded-none hover:bg-red-700 transition-colors duration-150">Xóa</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <!-- Quick Review Form -->
                                            <form action="{{ route('account.review.store') }}" method="POST"
                                                class="flex items-center gap-2 mb-2 quick-review-form">
                                                @csrf
                                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                <input type="hidden" name="book_id" value="{{ $item->book_id }}">
                                                <div class="flex items-center space-x-1 quick-star-group"
                                                    data-order="{{ $order->id }}" data-book="{{ $item->book_id }}">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <input type="radio"
                                                            id="quick_star{{ $order->id }}_{{ $item->book_id }}_{{ $i }}"
                                                            name="rating_{{ $order->id }}_{{ $item->book_id }}"
                                                            value="{{ $i }}" class="sr-only"
                                                            {{ $i == 5 ? 'checked' : '' }}>
                                                        <label
                                                            for="quick_star{{ $order->id }}_{{ $item->book_id }}_{{ $i }}"
                                                            class="cursor-pointer text-2xl text-slate-300 quick-star-label"
                                                            data-star="{{ $i }}">★</label>
                                                    @endfor
                                                </div>
                                                <input type="hidden" name="rating"
                                                    id="quick_rating_{{ $order->id }}_{{ $item->book_id }}"
                                                    value="5">
                                                <input type="hidden" name="comment" value="">
                                                <button type="submit"
                                                    class="px-3 py-1 bg-black text-white text-xs font-medium rounded-none hover:bg-gray-900 transition-colors duration-150">Gửi
                                                    đánh giá nhanh</button>
                                            </form>
                                            <button type="button"
                                                class="px-3 py-1 bg-white border border-black text-black text-xs font-medium rounded-none hover:bg-gray-100 transition-colors duration-150"
                                                onclick="showReviewModal('{{ $order->id }}', '{{ $item->book_id }}', '{{ addslashes($item->book->title) }}', '', '')">Đánh
                                                giá chi tiết</button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-black text-white mb-4"
                            style="border-radius:0;">
                            <i class="fas fa-box-open text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-black mb-1">Không có đơn hàng nào</h3>
                        <p class="text-gray-600">Bạn chưa có đơn hàng nào để hiển thị.</p>
                    </div>
                @endforelse
                @if ($orders->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $orders->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // JavaScript code for modal functionality and star rating
            function showReviewModal(orderId, bookId, bookName, rating, comment) {
                document.getElementById('reviewModal').classList.remove('hidden');
                document.getElementById('modal_order_id').value = orderId;
                document.getElementById('modal_book_id').value = bookId;
                document.getElementById('modal_book_name').textContent = bookName;
                document.getElementById('modal_comment').value = comment || '';
                // Reset all stars
                document.querySelectorAll('#modal_star_rating input[type=radio]').forEach(r => r.checked = false);
                if (rating) {
                    document.getElementById('modal_star' + rating).checked = true;
                } else {
                    document.getElementById('modal_star5').checked = true;
                }

                // Load thêm thông tin đơn hàng, sản phẩm
                axios.get('/account/orders/' + orderId + '/items/' + bookId)
                    .then(function(response) {
                        var data = response.data;
                        document.getElementById('modal_order_code').textContent = data.order_code;
                        document.getElementById('modal_order_status').textContent = data.status;
                        document.getElementById('modal_shipping_method').textContent = data.shipping_method;
                        document.getElementById('modal_shipping_address').textContent = data.shipping_address;
                        document.getElementById('modal_payment_method').textContent = data.payment_method;
                        document.getElementById('modal_order_created_at').textContent = data.created_at;
                        document.getElementById('modal_order_paid_at').textContent = data.paid_at;
                        document.getElementById('modal_order_completed_at').textContent = data.completed_at;
                        document.getElementById('modal_book_price').textContent = new Intl.NumberFormat('vi-VN', {
                            style: 'currency',
                            currency: 'VND'
                        }).format(data.price);
                        document.getElementById('modal_book_quantity').textContent = data.quantity;
                        document.getElementById('modal_book_author').textContent = data.author;
                        document.getElementById('modal_book_brand').textContent = data.brand;
                        document.getElementById('modal_order_subtotal').textContent = new Intl.NumberFormat('vi-VN', {
                            style: 'currency',
                            currency: 'VND'
                        }).format(data.subtotal);
                        document.getElementById('modal_order_shipping_fee').textContent = new Intl.NumberFormat('vi-VN', {
                            style: 'currency',
                            currency: 'VND'
                        }).format(data.shipping_fee);
                        document.getElementById('modal_order_voucher').textContent = data.voucher ? data.voucher :
                            'Không có';
                        document.getElementById('modal_order_total').textContent = new Intl.NumberFormat('vi-VN', {
                            style: 'currency',
                            currency: 'VND'
                        }).format(data.total);
                    })
                    .catch(function(error) {
                        console.error('Có lỗi xảy ra khi tải thông tin đơn hàng:', error);
                    });
            }

            function closeReviewModal() {
                document.getElementById('reviewModal').classList.add('hidden');
            }

            function showEditReviewModal(reviewId, orderId, bookId, bookName, rating, comment) {
                document.getElementById('editReviewModal').classList.remove('hidden');
                document.getElementById('edit_modal_review_id').value = reviewId;
                document.getElementById('edit_modal_order_id').value = orderId;
                document.getElementById('edit_modal_book_id').value = bookId;
                document.getElementById('edit_modal_book_name').textContent = bookName;
                document.getElementById('edit_modal_comment').value = comment || '';
                // Reset all stars
                document.querySelectorAll('#edit_modal_star_rating input[type=radio]').forEach(r => r.checked = false);
                if (rating) {
                    document.getElementById('edit_modal_star' + rating).checked = true;
                } else {
                    document.getElementById('edit_modal_star5').checked = true;
                }
                // Load thêm thông tin đơn hàng, sản phẩm
                axios.get('/account/orders/' + orderId + '/items/' + bookId)
                    .then(function(response) {
                        var data = response.data;
                        document.getElementById('edit_modal_order_code').textContent = data.order_code;
                        document.getElementById('edit_modal_order_status').textContent = data.status;
                        document.getElementById('edit_modal_shipping_method').textContent = data.shipping_method;
                        document.getElementById('edit_modal_shipping_address').textContent = data.shipping_address;
                        document.getElementById('edit_modal_payment_method').textContent = data.payment_method;
                        document.getElementById('edit_modal_order_created_at').textContent = data.created_at;
                        document.getElementById('edit_modal_order_paid_at').textContent = data.paid_at;
                        document.getElementById('edit_modal_order_completed_at').textContent = data.completed_at;
                        document.getElementById('edit_modal_book_price').textContent = data.price;
                        document.getElementById('edit_modal_book_quantity').textContent = data.quantity;
                        document.getElementById('edit_modal_book_author').textContent = data.author;
                        document.getElementById('edit_modal_book_brand').textContent = data.brand;
                        document.getElementById('edit_modal_order_subtotal').textContent = data.subtotal;
                        document.getElementById('edit_modal_order_shipping_fee').textContent = data.shipping_fee;
                        document.getElementById('edit_modal_order_voucher_discount').textContent = data.voucher_discount;
                        document.getElementById('edit_modal_order_total').textContent = data.total;
                    });
            }

            function closeEditReviewModal() {
                document.getElementById('editReviewModal').classList.add('hidden');
            }

            // Highlight stars on hover and selection
            const starLabels = document.querySelectorAll('#modal_star_rating label');
            starLabels.forEach(label => {
                label.addEventListener('mouseenter', function() {
                    let val = parseInt(this.htmlFor.replace('modal_star', ''));
                    starLabels.forEach(l => {
                        let lval = parseInt(l.htmlFor.replace('modal_star', ''));
                        l.classList.toggle('text-yellow-400', lval <= val);
                        l.classList.toggle('text-slate-300', lval > val);
                    });
                });
                label.addEventListener('mouseleave', function() {
                    let checked = document.querySelector('#modal_star_rating input[type=radio]:checked');
                    let val = checked ? parseInt(checked.id.replace('modal_star', '')) : 5;
                    starLabels.forEach(l => {
                        let lval = parseInt(l.htmlFor.replace('modal_star', ''));
                        l.classList.toggle('text-yellow-400', lval <= val);
                        l.classList.toggle('text-slate-300', lval > val);
                    });
                });
                label.addEventListener('click', function() {
                    let val = parseInt(this.htmlFor.replace('modal_star', ''));
                    document.getElementById('modal_star' + val).checked = true;
                });
            });
            document.querySelectorAll('.quick-star-group').forEach(function(group) {
                var orderId = group.getAttribute('data-order');
                var bookId = group.getAttribute('data-book');
                var radios = group.querySelectorAll('input[type=radio]');
                var labels = group.querySelectorAll('.quick-star-label');
                var hiddenInput = document.getElementById('quick_rating_' + orderId + '_' + bookId);

                function updateStars(val) {
                    labels.forEach(function(label) {
                        var star = parseInt(label.getAttribute('data-star'));
                        if (star <= val) {
                            label.classList.add('text-yellow-400');
                            label.classList.remove('text-slate-300');
                        } else {
                            label.classList.remove('text-yellow-400');
                            label.classList.add('text-slate-300');
                        }
                    });
                }
                var checked = group.querySelector('input[type=radio]:checked');
                updateStars(checked ? checked.value : 5);
                radios.forEach(function(radio) {
                    radio.addEventListener('change', function() {
                        updateStars(this.value);
                        hiddenInput.value = this.value;
                    });
                });
                labels.forEach(function(label) {
                    label.addEventListener('mouseenter', function() {
                        updateStars(this.getAttribute('data-star'));
                    });
                    label.addEventListener('mouseleave', function() {
                        var checked = group.querySelector('input[type=radio]:checked');
                        updateStars(checked ? checked.value : 5);
                    });
                });
            });
        </script>
    @endpush
@endsection
