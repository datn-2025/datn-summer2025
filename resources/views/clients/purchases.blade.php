@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Đơn hàng của tôi</h4>
                </div>

                <div class="card-body">
                    <!-- Tabs điều hướng -->
                    <ul class="nav nav-tabs mb-4" id="purchaseTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ request('type', '1') == '1' ? 'active' : '' }}" 
                               href="{{ route('account.purchase', ['type' => 1]) }}">
                                Tất cả đơn hàng
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ request('type') == '2' ? 'active' : '' }}" 
                               href="{{ route('account.purchase', ['type' => 2]) }}">
                                Chưa đánh giá
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ request('type') == '3' ? 'active' : '' }}" 
                               href="{{ route('account.purchase', ['type' => 3]) }}">
                                Đã đánh giá
                            </a>
                        </li>
                    </ul>

                    <!-- Danh sách đơn hàng -->
                    @forelse($orders as $order)
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0">Đơn hàng #{{ $order->id }}</h5>
                                    <small class="text-muted">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                <span class="badge bg-success">Đã hoàn thành</span>
                            </div>
                            
                            <div class="card-body">
                                @foreach($order->orderItems as $item)
                                    <div class="row mb-4 pb-3 border-bottom">
                                        <div class="col-md-2">
                                            <img src="{{ $item->book->image_url ?? 'https://via.placeholder.com/100' }}" 
                                                 alt="{{ $item->book->name }}" 
                                                 class="img-fluid rounded" 
                                                 style="max-height: 120px; width: auto;">
                                        </div>
                                        <div class="col-md-5">
                                            <h6 class="fw-bold">{{ $item->book->name }}</h6>
                                            <p class="mb-1">Số lượng: {{ $item->quantity }}</p>
                                            <p class="mb-1">Giá: {{ number_format($item->price, 0, ',', '.') }} đ</p>
                                        </div>
                                        <div class="col-md-5">
                                            @php
                                                $review = $order->reviews->where('book_id', $item->book_id)->first();
                                            @endphp
                                            
                                            @if($review)
                                                <!-- Hiển thị đánh giá nếu đã có -->
                                                <div class="card bg-light">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex align-items-center mb-2">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-secondary' }}"></i>
                                                            @endfor
                                                            <span class="ms-2 text-muted small">
                                                                {{ $review->created_at->format('d/m/Y') }}
                                                            </span>
                                                        </div>
                                                        <p class="mb-0">{{ $review->comment }}</p>
                                                    </div>
                                                </div>
                                            @else
                                                <!-- Form đánh giá nếu chưa có -->
                                                <div class="card border-primary">
                                                    <div class="card-body p-3">
                                                        <form action="{{ route('account.review.store') }}" method="POST" class="review-form">
                                                            @csrf
                                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                            <input type="hidden" name="book_id" value="{{ $item->book_id }}">
                                                            
                                                            <div class="mb-2">
                                                                <label class="form-label">Đánh giá của bạn:</label>
                                                                <div class="rating-input">
                                                                    @for($i = 5; $i >= 1; $i--)
                                                                        <input type="radio" 
                                                                               id="star{{ $i }}_{{ $order->id }}_{{ $item->book_id }}" 
                                                                               name="rating" 
                                                                               value="{{ $i }}" 
                                                                               {{ old('rating') == $i ? 'checked' : ($i == 5 ? 'checked' : '') }}>
                                                                        <label for="star{{ $i }}_{{ $order->id }}_{{ $item->book_id }}" 
                                                                               title="{{ $i }} sao">★</label>
                                                                    @endfor
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="mb-2">
                                                                <textarea name="comment" 
                                                                          class="form-control form-control-sm" 
                                                                          rows="2" 
                                                                          placeholder="Nhận xét về sản phẩm..." 
                                                                          required>{{ old('comment') }}</textarea>
                                                            </div>
                                                            
                                                            <button type="submit" class="btn btn-sm btn-primary">
                                                                <i class="fas fa-paper-plane me-1"></i> Gửi đánh giá
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i> 
                            Không có đơn hàng nào để hiển thị.
                        </div>
                    @endforelse

                    <!-- Phân trang -->
                    @if($orders->hasPages())
                        <div class="mt-4">
                            {{ $orders->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Style cho phần đánh giá sao */
.rating-input {
    display: inline-block;
    font-size: 24px;
    line-height: 1;
    margin: 5px 0;
    direction: rtl;
    unicode-bidi: bidi-override;
}

.rating-input input {
    display: none;
}

.rating-input label {
    color: #ddd;
    cursor: pointer;
    padding: 0 2px;
    transition: color 0.2s;
}

.rating-input label:before {
    content: "★";
}

.rating-input input:checked ~ label,
.rating-input:not(:checked) > label:hover,
.rating-input:not(:checked) > label:hover ~ label {
    color: #ffc107;
}

.rating-input input:checked + label:hover,
.rating-input input:checked ~ label:hover,
.rating-input label:hover ~ input:checked ~ label,
.rating-input input:checked ~ label:hover ~ label {
    color: #ffed85;
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý khi submit form đánh giá
    document.querySelectorAll('.review-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            // Thêm loading state nếu cần
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Đang gửi...';
            }
        });
    });
});
</script>
@endpush