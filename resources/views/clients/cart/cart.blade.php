@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Giỏ hàng của bạn</h1>

    @if(count($cart) > 0)
        <div class="row">
            <!-- Danh sách sản phẩm -->
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        @foreach($cart as $item)
                            <div class="row mb-4 cart-item" data-book-id="{{ $item->book_id }}">
                                <div class="col-md-3">
                                    <img src="{{ asset($item->image) }}" alt="{{ $item->title }}" class="img-fluid rounded">
                                </div>
                                <div class="col-md-9">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h5 class="mb-1">{{ $item->title }}</h5>
                                            <p class="text-muted mb-1">Tác giả: {{ $item->author_name }}</p>
                                            <p class="mb-1">Định dạng: {{ $item->format_name }}</p>
                                            <p class="text-primary mb-2">{{ number_format($item->price_at_addition) }}đ</p>
                                        </div>
                                        <button class="btn btn-link text-danger remove-item" data-book-id="{{ $item->book_id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="quantity-controls">
                                            <button class="btn btn-outline-secondary btn-sm decrease-quantity">-</button>
                                            <input type="number" class="form-control form-control-sm mx-2 quantity-input" 
                                                value="{{ $item->quantity }}" 
                                                min="1" 
                                                max="{{ $item->stock }}"
                                                style="width: 60px">
                                            <button class="btn btn-outline-secondary btn-sm increase-quantity">+</button>
                                        </div>
                                        <p class="ms-3 mb-0">
                                            Tổng: <span class="fw-bold item-total">{{ number_format($item->price_at_addition * $item->quantity) }}đ</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @if(!$loop->last)
                                <hr>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Tổng kết đơn hàng -->
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Tổng kết đơn hàng</h5>
                        
                        <!-- Mã giảm giá -->
                        <div class="mb-3">
                            <div class="input-group">
                                <input type="text" class="form-control" id="voucher-code" placeholder="Nhập mã giảm giá">
                                <button class="btn btn-outline-primary" id="apply-voucher">Áp dụng</button>
                            </div>
                        </div>

                        <!-- Chi tiết thanh toán -->
                        <div class="border-top pt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tạm tính:</span>
                                <span>{{ number_format($total) }}đ</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Giảm giá:</span>
                                <span class="text-success">-{{ number_format($discount) }}đ</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Phí vận chuyển:</span>
                                <span>{{ number_format($shipping) }}đ</span>
                            </div>
                            <div class="d-flex justify-content-between border-top pt-2 mt-2">
                                <span class="fw-bold">Tổng cộng:</span>
                                <span class="fw-bold text-primary">{{ number_format($finalTotal) }}đ</span>
                            </div>
                        </div>

                        <!-- Nút thanh toán -->
                        <div class="mt-4">
                            <a href="{{ route('checkout') }}" class="btn btn-primary w-100">
                                Tiến hành thanh toán
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
            <h3>Giỏ hàng trống</h3>
            <p class="text-muted">Hãy thêm sản phẩm vào giỏ hàng của bạn</p>
            <a href="{{ route('books.index') }}" class="btn btn-primary">
                Tiếp tục mua sắm
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Cập nhật số lượng
    function updateQuantity(bookId, quantity) {
        $.ajax({
            url: '{{ route("cart.update") }}',
            method: 'POST',
            data: {
                book_id: bookId,
                quantity: quantity,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Cập nhật UI
                    location.reload(); // Tạm thời reload để cập nhật toàn bộ
                } else {
                    alert(response.error);
                }
            },
            error: function(xhr) {
                alert('Có lỗi xảy ra. Vui lòng thử lại.');
            }
        });
    }

    // Xử lý nút tăng giảm số lượng
    $('.increase-quantity').click(function() {
        var input = $(this).siblings('.quantity-input');
        var newValue = parseInt(input.val()) + 1;
        if (newValue <= parseInt(input.attr('max'))) {
            input.val(newValue);
            var bookId = $(this).closest('.cart-item').data('book-id');
            updateQuantity(bookId, newValue);
        }
    });

    $('.decrease-quantity').click(function() {
        var input = $(this).siblings('.quantity-input');
        var newValue = parseInt(input.val()) - 1;
        if (newValue >= 1) {
            input.val(newValue);
            var bookId = $(this).closest('.cart-item').data('book-id');
            updateQuantity(bookId, newValue);
        }
    });

    // Xử lý input số lượng
    $('.quantity-input').change(function() {
        var value = parseInt($(this).val());
        var min = parseInt($(this).attr('min'));
        var max = parseInt($(this).attr('max'));
        
        if (value < min) value = min;
        if (value > max) value = max;
        
        $(this).val(value);
        var bookId = $(this).closest('.cart-item').data('book-id');
        updateQuantity(bookId, value);
    });

    // Xử lý xóa sản phẩm
    $('.remove-item').click(function() {
        var bookId = $(this).data('book-id');
        if (confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')) {
            $.ajax({
                url: '{{ route("cart.remove") }}',
                method: 'POST',
                data: {
                    book_id: bookId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.error);
                    }
                },
                error: function(xhr) {
                    alert('Có lỗi xảy ra. Vui lòng thử lại.');
                }
            });
        }
    });

    // Xử lý áp dụng mã giảm giá
    $('#apply-voucher').click(function() {
        var code = $('#voucher-code').val();
        if (!code) {
            alert('Vui lòng nhập mã giảm giá');
            return;
        }

        $.ajax({
            url: '{{ route("cart.apply-voucher") }}',
            method: 'POST',
            data: {
                code: code,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.error);
                }
            },
            error: function(xhr) {
                alert('Có lỗi xảy ra. Vui lòng thử lại.');
            }
        });
    });
});
</script>
@endpush
