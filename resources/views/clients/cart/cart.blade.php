@extends('layouts.app')

@section('title', 'Giỏ hàng')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
@endpush

@section('content')
<div class="container py-5">
    <!-- Modern Page Header -->
    <div class="page-header mb-5">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center">
                    <div class="header-icon-wrapper me-4">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div>
                        <h1 class="page-title mb-2">Giỏ hàng của bạn</h1>
                        <p class="page-subtitle mb-0">Quản lý các sản phẩm bạn muốn mua</p>
                    </div>
                </div>
            </div>
            @if(count($cart) > 0)
                <div class="col-md-4">
                    <div class="action-buttons-container">
                        <div class="d-flex gap-2 justify-content-end">
                            <button class="btn modern-action-btn btn-outline-primary" id="add-wishlist-btn">
                                <i class="fas fa-heart me-2"></i>Thêm từ yêu thích
                            </button>
                            <button class="btn modern-action-btn btn-outline-danger" id="clear-cart-btn">
                                <i class="fas fa-trash-alt me-2"></i>Xóa tất cả
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if(count($cart) > 0)
        <div class="row">
            <!-- Danh sách sản phẩm -->
            <div class="col-lg-8">
                <div class="cart-container">
                    <div class="p-4">
                        <div class="d-flex align-items-center mb-4">
                            <i class="fas fa-shopping-cart text-primary me-3 fs-4"></i>
                            <h4 class="mb-0 fw-bold">Sản phẩm trong giỏ ({{ count($cart) }})</h4>
                        </div>
                        
                        @foreach($cart as $item)
                            <div class="cart-item-card cart-item" 
                                data-book-id="{{ $item->book_id }}"
                                data-price="{{ $item->price ?? 0 }}"
                                data-stock="{{ $item->stock ?? 0 }}">
                                <div class="row g-0">
                                    <div class="col-md-3">
                                        <div class="product-image-container">
                                            @if($item->image)
                                                <img src="{{ asset($item->image) }}" 
                                                     alt="{{ $item->title ?? 'Book image' }}" 
                                                     class="product-image">
                                                <div class="image-overlay"></div>
                                            @else
                                                <div class="product-image d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
                                                    <i class="fas fa-book fa-3x text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="product-info">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div class="flex-grow-1">
                                                    <h5 class="product-title">{{ $item->title ?? 'Không có tiêu đề' }}</h5>
                                                    
                                                    <div class="product-meta">
                                                        <span class="meta-badge">
                                                            <i class="fas fa-user me-1"></i>{{ $item->author_name ?? 'Chưa cập nhật' }}
                                                        </span>
                                                        <span class="meta-badge">
                                                            <i class="fas fa-bookmark me-1"></i>{{ $item->format_name ?? 'Chưa cập nhật' }}
                                                        </span>
                                                    </div>
                                                    
                                                    @if($item->attribute_value_ids && $item->attribute_value_ids !== '[]')
                                                        @php
                                                            $attributeIds = json_decode($item->attribute_value_ids, true);
                                                            if ($attributeIds && is_array($attributeIds) && count($attributeIds) > 0) {
                                                                $attributes = DB::table('attribute_values')
                                                                    ->join('attributes', 'attribute_values.attribute_id', '=', 'attributes.id')
                                                                    ->whereIn('attribute_values.id', $attributeIds)
                                                                    ->select('attributes.name as attr_name', 'attribute_values.value as attr_value')
                                                                    ->get();
                                                            }
                                                        @endphp
                                                        @if(isset($attributes) && $attributes->count() > 0)
                                                            <div class="mb-3">
                                                                <small class="text-muted fw-medium mb-2 d-block">
                                                                    <i class="fas fa-tags me-1"></i>Thuộc tính đã chọn:
                                                                </small>
                                                                <div class="d-flex flex-wrap gap-2">
                                                                    @foreach($attributes as $attr)
                                                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">
                                                                            {{ $attr->attr_name }}: {{ $attr->attr_value }}
                                                                        </span>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                                
                                                <button class="btn remove-item ms-3" data-book-id="{{ $item->book_id }}" title="Xóa sản phẩm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            
                                            <div class="row align-items-center">
                                                <div class="col-md-6">
                                                    <div class="price-display">
                                                        <i class="fas fa-tag me-2"></i>{{ number_format($item->price ?? 0) }}đ
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center justify-content-end">
                                                        <div class="quantity-wrapper me-3">
                                                            <div class="input-group quantity-controls">
                                                                <button class="btn decrease-quantity" type="button">
                                                                    <i class="fas fa-minus"></i>
                                                                </button>
                                                                <input type="number" 
                                                                       class="form-control quantity-input" 
                                                                       value="{{ $item->quantity }}" 
                                                                       min="1" 
                                                                       max="{{ $item->stock ?? 1 }}">
                                                                <button class="btn increase-quantity" type="button">
                                                                    <i class="fas fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="text-end">
                                                            <div class="fw-bold text-success fs-5 item-total">
                                                                {{ number_format(($item->price ?? 0) * $item->quantity) }}đ
                                                            </div>
                                                            <small class="text-muted">
                                                                <i class="fas fa-boxes me-1"></i>Còn <span class="stock-amount">{{ $item->stock ?? 0 }}</span> sản phẩm
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>


            <!-- Tổng kết đơn hàng -->
            <div class="col-lg-4">
                <div class="cart-container sticky-top" style="top: 2rem;">
                    <div class="p-4">
                        <div class="d-flex align-items-center mb-4">
                            <i class="fas fa-calculator text-success me-3 fs-4"></i>
                            <h4 class="mb-0 fw-bold text-dark">Tổng kết đơn hàng</h4>
                        </div>
                        
                        <!-- Mã giảm giá -->
                        <div class="mb-4">
                            <div class="voucher-section">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="voucher-icon-wrapper me-3">
                                        <i class="fas fa-ticket-alt text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold">Mã giảm giá</h6>
                                        <small class="text-muted">Nhập mã để nhận ưu đãi</small>
                                    </div>
                                </div>
                                
                                <div class="voucher-input-container position-relative">
                                    @php
                                        $appliedVoucher = session()->get('applied_voucher');
                                        $hasVoucher = $appliedVoucher !== null;
                                    @endphp
                                    <div class="input-group modern-input-group">
                                        <input type="text" 
                                               id="voucher-code" 
                                               class="form-control voucher-input {{ $hasVoucher ? 'voucher-applied' : '' }}" 
                                               placeholder="Nhập mã giảm giá..."
                                               value="{{ $hasVoucher ? $appliedVoucher['code'] : '' }}"
                                               {{ $hasVoucher ? 'disabled' : '' }}>
                                        <div class="voucher-button-container">
                                            @if($hasVoucher)
                                                <button class="btn btn-danger voucher-btn remove-voucher-btn" 
                                                        type="button" 
                                                        id="remove-voucher-btn">
                                                    <i class="fas fa-times me-1"></i>
                                                    <span class="btn-text">Xóa</span>
                                                </button>
                                            @else
                                                <button class="btn btn-primary voucher-btn apply-voucher-btn" 
                                                        type="button" 
                                                        id="apply-voucher">
                                                    <i class="fas fa-check me-1"></i>
                                                    <span class="btn-text">Áp dụng</span>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                    @if($hasVoucher)
                                        <div class="voucher-success-indicator mt-2">
                                            <small class="text-success fw-medium">
                                                <i class="fas fa-check-circle me-1"></i>
                                                Mã giảm giá đã được áp dụng thành công!
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Chi tiết thanh toán -->
                        <div class="payment-breakdown">
                            <div class="breakdown-header mb-3">
                                <h6 class="fw-semibold text-dark mb-0">
                                    <i class="fas fa-receipt me-2 text-info"></i>Chi tiết thanh toán
                                </h6>
                            </div>
                            
                            <div class="breakdown-item">
                                <div class="d-flex justify-content-between align-items-center py-2">
                                    <span class="text-muted">
                                        <i class="fas fa-shopping-bag me-2"></i>Tạm tính:
                                    </span>
                                    <span class="fw-semibold text-dark" id="subtotal">{{ number_format($total) }}đ</span>
                                </div>
                            </div>
                            
                            <div class="breakdown-item">
                                <div class="d-flex justify-content-between align-items-center py-2">
                                    <span class="text-muted">
                                        <i class="fas fa-percentage me-2"></i>Giảm giá:
                                    </span>
                                    <span class="fw-semibold" id="discount-amount" style="color: #dc3545;">
                                        {{ $hasVoucher ? '- ' . number_format($appliedVoucher['discount_amount']) . 'đ' : '0đ' }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="breakdown-divider my-3"></div>
                            
                            <div class="breakdown-total">
                                <div class="d-flex justify-content-between align-items-center py-3">
                                    <span class="fs-5 fw-bold text-dark">
                                        <i class="fas fa-coins me-2 text-warning"></i>Tổng cộng:
                                    </span>
                                    <span class="fs-4 fw-bold text-success" id="total-amount">
                                        {{ number_format($hasVoucher ? $total - $appliedVoucher['discount_amount'] : $total) }}đ
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Checkout Button -->
                        <div class="mt-4">
                            <a href="#" class="btn modern-checkout-btn w-100">
                                <i class="fas fa-credit-card me-2"></i>
                                <span>Tiến hành thanh toán</span>
                                <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                        
                        <!-- Security Badge -->
                        <div class="security-badge mt-3">
                            <div class="d-flex align-items-center justify-content-center text-muted">
                                <i class="fas fa-shield-alt me-2 text-success"></i>
                                <small>Thanh toán an toàn & bảo mật</small>
                                <i class="fas fa-lock ms-2 text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Modern Empty Cart Section -->
        <div class="empty-cart-container text-center">
            <div class="empty-cart-icon mb-4">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h2 class="empty-cart-title">Giỏ hàng của bạn đang trống</h2>
            <p class="empty-cart-text">Khám phá hàng ngàn cuốn sách hay và thêm chúng vào giỏ hàng của bạn!</p>
            
            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center align-items-center">
                <a href="{{ route('books.index') }}" class="shop-now-btn">
                    <i class="fas fa-book-open me-2"></i>Khám phá sách ngay
                </a>
                <button class="btn btn-outline-primary" id="add-wishlist-btn" style="border-radius: 12px; padding: 14px 28px; font-weight: 600;">
                    <i class="fas fa-heart me-2"></i>Thêm từ yêu thích
                </button>
            </div>
            
            <!-- Additional suggestions -->
            <div class="mt-5 pt-4" style="border-top: 1px solid #e9ecef;">
                <h6 class="text-muted mb-3">Gợi ý cho bạn</h6>
                <div class="row g-2 justify-content-center">
                    <div class="col-auto">
                        <span class="badge bg-light text-dark border" style="padding: 8px 16px; border-radius: 20px;">
                            <i class="fas fa-fire me-1 text-danger"></i>Sách hot
                        </span>
                    </div>
                    <div class="col-auto">
                        <span class="badge bg-light text-dark border" style="padding: 8px 16px; border-radius: 20px;">
                            <i class="fas fa-star me-1 text-warning"></i>Bán chạy
                        </span>
                    </div>
                    <div class="col-auto">
                        <span class="badge bg-light text-dark border" style="padding: 8px 16px; border-radius: 20px;">
                            <i class="fas fa-percentage me-1 text-success"></i>Giảm giá
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('js/cart.js') }}"></script>
@endpush