@extends('layouts.account.layout')

@section('account_content')
<div class="bg-white border border-black shadow mb-8" style="border-radius:0;">
    <div class="px-8 py-6 border-b border-black bg-black">
        <h1 class="text-2xl font-bold text-white uppercase tracking-wide">Đánh giá của tôi</h1>
    </div>
    <div class="p-8">
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
        <div class="space-y-6">
            @forelse($orders as $order)
                <div class="bg-white border border-black shadow transition hover:shadow-lg" style="border-radius:0;">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between px-6 py-4 bg-gray-50 border-b border-black">
                        <div>
                            <h3 class="text-lg font-bold text-black">Đơn hàng #{{ $order->order_code }}</h3>
                            <p class="text-sm text-gray-600">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="inline-flex items-center px-3 py-1 text-xs font-bold bg-black text-white border border-black mb-1" style="border-radius:0;">
                                Đã hoàn thành
                            </span>
                            <span class="text-base font-semibold text-black">Tổng tiền: <span class="text-red-600">{{ number_format($order->total_amount, 0, ',', '.') }} đ</span></span>
                        </div>
                    </div>
                    <div class="p-6 space-y-6">
                        @foreach($order->orderItems as $item)
                            @php
                                $review = $order->reviews()->withTrashed()->where('book_id', $item->book_id)->first();
                            @endphp
                            <div class="flex flex-col lg:flex-row gap-6 pb-6 border-b border-slate-200 last:border-b-0 last:pb-0">
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-lg font-bold text-black mb-1">{{ $item->book->title }}</h4>
                                    <div class="text-sm text-gray-700 mb-1">
                                        <span class="font-medium">Tác giả:</span> {{ $item->book->author->name ?? 'Không rõ' }}
                                    </div>
                                    <div class="text-sm text-gray-700 mb-1">
                                        <span class="font-medium">Nhà xuất bản:</span> {{ $item->book->publisher->name ?? 'Không rõ' }}
                                    </div>
                                    <div class="text-sm text-gray-700 mb-1">
                                        <span class="font-medium">Số lượng:</span> {{ $item->quantity }}
                                    </div>
                                </div>
                                <div class="lg:w-96 flex flex-col gap-2">
                                    @if($review && !$review->trashed())
                                        <div class="bg-blue-50 border border-blue-200 p-4" style="border-radius:0;">
                                            <div class="mb-2">
                                                <span class="text-xs text-slate-500">Cập nhật lần cuối: {{ $review->updated_at->format('d/m/Y') }}</span>
                                            </div>
                                            <div class="flex items-center mb-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-slate-300' }} text-xl"></i>
                                                @endfor
                                            </div>
                                            <div class="text-sm text-slate-700 mb-2">{{ $review->comment ?? 'Không có nhận xét' }}</div>
                                        </div>
                                    @else
                                        <form action="{{ route('account.review.store') }}" method="POST" class="flex items-center gap-2 mb-2 quick-review-form">
                                            @csrf
                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                            <input type="hidden" name="book_id" value="{{ $item->book_id }}">
                                            <div class="flex items-center space-x-1 quick-star-group" data-order="{{ $order->id }}" data-book="{{ $item->book_id }}">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <input type="radio" id="quick_star{{ $order->id }}_{{ $item->book_id }}_{{ $i }}" name="rating_{{ $order->id }}_{{ $item->book_id }}" value="{{ $i }}" class="sr-only" {{ $i == 5 ? 'checked' : '' }}>
                                                    <label for="quick_star{{ $order->id }}_{{ $item->book_id }}_{{ $i }}" class="cursor-pointer text-2xl text-slate-300 quick-star-label" data-star="{{ $i }}">★</label>
                                                @endfor
                                            </div>
                                            <input type="hidden" name="rating" id="quick_rating_{{ $order->id }}_{{ $item->book_id }}" value="5">
                                            <input type="hidden" name="comment" value="">
                                            <button type="submit" class="px-3 py-1 bg-black text-white text-xs font-medium rounded-none hover:bg-gray-900 transition-colors duration-150">Gửi đánh giá nhanh</button>
                                        </form>
                                        <button type="button" class="px-3 py-1 bg-white border border-black text-black text-xs font-medium rounded-none hover:bg-gray-100 transition-colors duration-150" onclick="showReviewModal('{{ $order->id }}', '{{ $item->book_id }}', '{{ addslashes($item->book->title) }}', '', '')">Đánh giá chi tiết</button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-black text-white mb-4" style="border-radius:0;">
                        <i class="fas fa-box-open text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-black mb-1">Không có đơn hàng nào</h3>
                    <p class="text-gray-600">Bạn chưa có đơn hàng nào để hiển thị.</p>
                </div>
            @endforelse
            @if($orders->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal for Review Form -->
<div id="reviewModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-40 flex items-center justify-center">
    <div class="bg-white shadow-lg w-full max-w-lg p-8 relative border border-black" style="border-radius:0;">
        <button type="button" class="absolute top-3 right-3 text-gray-400 hover:text-black text-2xl" onclick="closeReviewModal()">&times;</button>
        <h2 class="text-xl font-bold mb-4 text-black">Đánh giá sản phẩm</h2>
        <form id="reviewForm" action="{{ route('account.review.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="order_id" id="modal_order_id">
            <input type="hidden" name="book_id" id="modal_book_id">
            <div class="mb-2">
                <span class="block text-sm font-medium text-black mb-1">Sản phẩm:</span>
                <span id="modal_book_name" class="font-semibold text-base text-black"></span>
            </div>
            <div>
                <label class="block text-sm font-medium text-black mb-2">Đánh giá của bạn:</label>
                <div class="flex items-center space-x-1" id="modal_star_rating">
                    @for($i = 5; $i >= 1; $i--)
                        <input type="radio" id="modal_star{{ $i }}" name="rating" value="{{ $i }}" class="sr-only">
                        <label for="modal_star{{ $i }}" class="cursor-pointer text-3xl text-slate-300 hover:text-yellow-400 transition-colors duration-150" title="{{ $i }} sao">★</label>
                    @endfor
                </div>
            </div>
            <div>
                <textarea name="comment" id="modal_comment" rows="3"
                          class="w-full px-3 py-2 border border-black rounded-none focus:ring-2 focus:ring-black focus:border-black transition-colors duration-200 text-sm resize-none text-black bg-white"
                          placeholder="Nhận xét về sản phẩm..." required></textarea>
            </div>
            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-black hover:bg-gray-900 text-white text-sm font-medium rounded-none transition-colors duration-200 focus:ring-2 focus:ring-black focus:ring-offset-2">
                Gửi đánh giá
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
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
}
function closeReviewModal() {
    document.getElementById('reviewModal').classList.add('hidden');
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
