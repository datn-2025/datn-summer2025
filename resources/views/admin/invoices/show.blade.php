@extends('layouts.backend')
@section('title', 'Chi tiết hóa đơn')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="card shadow mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <div class="d-flex align-items-center mb-2">
                        <h1 class="h3 text-gray-800 mb-0">Chi tiết hóa đơn</h1>
                        <span class="badge badge-{{ $invoice->order->payment_status == 'paid' ? 'success' : 'warning' }} ml-3">
                            {{ $invoice->order->payment_status == 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                        </span>
                    </div>
                    <div class="text-muted">
                        <span class="mr-3"><i class="fas fa-hashtag mr-1"></i>{{ $invoice->order->order_code }}</span>
                        <span><i class="far fa-calendar-alt mr-1"></i>{{ $invoice->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
                <div class="mt-3 mt-sm-0">
                    <a href="{{ route('admin.invoices.generate-pdf', $invoice->id) }}" 
                        class="btn btn-primary btn-icon-split">
                        <span class="icon"><i class="fas fa-file-pdf"></i></span>
                        <span class="text">Xuất PDF</span>
                    </a>
                    <a href="{{ route('admin.invoices.index') }}" 
                        class="btn btn-secondary btn-icon-split ml-2">
                        <span class="icon"><i class="fas fa-arrow-left"></i></span>
                        <span class="text">Quay lại</span>
                    </a>
                </div>
            </div>
        </div>
    </div>    <div class="row">
        <!-- Thông tin khách hàng -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-circle mr-2"></i>Thông tin khách hàng
                    </h6>
                </div>
                <div class="card-body">
                    <div class="customer-info">
                        <div class="text-center mb-3">
                            <div class="img-profile rounded-circle mx-auto mb-2">
                                <i class="fas fa-user-circle fa-3x text-gray-300"></i>
                            </div>
                            <h5 class="mb-0">{{ $invoice->order->user->name }}</h5>
                            <div class="text-muted small">Khách hàng</div>
                        </div>
                        <hr>
                        <div class="info-item mb-3">
                            <label class="text-muted mb-1">Email:</label>
                            <div class="h6">{{ $invoice->order->user->email }}</div>
                        </div>
                        <div class="info-item mb-3">
                            <label class="text-muted mb-1">Số điện thoại:</label>
                            <div class="h6">{{ $invoice->order->user->phone }}</div>
                        </div>
                        <div class="info-item">
                            <label class="text-muted mb-1">Địa chỉ giao hàng:</label>
                            <div class="h6">{{ $invoice->order->address->full_address }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chi tiết đơn hàng -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-shopping-cart mr-2"></i>Chi tiết đơn hàng
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="order-info p-3 bg-light rounded">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-circle bg-primary text-white small mr-2">
                                        <i class="fas fa-info"></i>
                                    </div>
                                    <h6 class="mb-0 text-primary">Thông tin đơn hàng</h6>
                                </div>
                                <div class="info-item mb-2">
                                    <span class="text-muted">Trạng thái:</span>
                                    <span class="badge badge-{{ $invoice->order->status_color }} ml-2">
                                        {{ $invoice->order->status_text }}
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="text-muted">Phương thức thanh toán:</span>
                                    <div class="font-weight-bold mt-1">{{ $invoice->order->payment_method }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="order-summary p-3 bg-light rounded">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-circle bg-success text-white small mr-2">
                                        <i class="fas fa-dollar-sign"></i>
                                    </div>
                                    <h6 class="mb-0 text-success">Tổng quan thanh toán</h6>
                                </div>
                                <div class="summary-item d-flex justify-content-between mb-2">
                                    <span class="text-muted">Tổng tiền hàng:</span>
                                    <span class="font-weight-bold">{{ number_format($invoice->subtotal) }}đ</span>
                                </div>
                                @if($invoice->discount > 0)
                                <div class="summary-item d-flex justify-content-between mb-2">
                                    <span class="text-muted">Giảm giá:</span>
                                    <span class="text-danger">-{{ number_format($invoice->discount) }}đ</span>
                                </div>
                                @endif
                                <div class="summary-item d-flex justify-content-between mb-2">
                                    <span class="text-muted">Phí vận chuyển:</span>
                                    <span>{{ number_format($invoice->shipping_fee) }}đ</span>
                                </div>
                                <hr class="my-2">
                                <div class="summary-item d-flex justify-content-between">
                                    <span class="font-weight-bold">Tổng thanh toán:</span>
                                    <span class="text-primary h5 mb-0">{{ number_format($invoice->total_amount) }}đ</span>
                                </div>
                            </div>
                        </div>
                    </div>                    <!-- Danh sách sản phẩm -->
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <thead class="bg-light">
                                <tr>
                                    <th scope="col" class="pl-0">Sản phẩm</th>
                                    <th scope="col" width="120px">Đơn giá</th>
                                    <th scope="col" width="90px">Số lượng</th>
                                    <th scope="col" width="120px" class="text-right pr-0">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->items as $item)
                                <tr>
                                    <td class="pl-0">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $item->book->thumbnail }}" 
                                                alt="{{ $item->book->title }}" 
                                                class="rounded" 
                                                style="width: 60px; height: 80px; object-fit: cover;">
                                            <div class="ml-3">
                                                <h6 class="mb-1">{{ $item->book->title }}</h6>
                                                <div class="text-muted small">
                                                    Tác giả: {{ $item->book->author->name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($item->price) }}đ</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-right pr-0">{{ number_format($item->quantity * $item->price) }}đ</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- Lịch sử đơn hàng -->
    @if(isset($invoice->order->order_histories) && count($invoice->order->order_histories) > 0)
    <div class="card">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Lịch sử đơn hàng</h6>
        </div>
        <div class="card-body">
            <div class="timeline">
                @foreach($invoice->order->order_histories as $history)
                <div class="timeline-item">
                    <div class="timeline-item-marker">
                        <div class="timeline-item-marker-text">
                            {{ $history->created_at->format('H:i') }}<br>
                            {{ $history->created_at->format('d/m/Y') }}
                        </div>
                        <div class="timeline-item-marker-indicator bg-{{ $history->status_color }}"></div>
                    </div>
                    <div class="timeline-item-content">
                        {{ $history->message }}
                        @if($history->note)
                        <br><small class="text-muted">{{ $history->note }}</small>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

@push('styles')
<style>
    .icon-circle {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .timeline {
        position: relative;
        padding-left: 1rem;
        margin: 1rem 0;
    }

    .timeline-item {
        position: relative;
        padding-left: 3rem;
        padding-bottom: 2rem;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-item-marker {
        position: absolute;
        left: 0;
        top: 0;
    }

    .timeline-item-marker-text {
        position: absolute;
        left: -90px;
        top: 0;
        font-size: 0.85rem;
        color: #a2aab7;
    }

    .timeline-item-marker-indicator {
        display: inline-block;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        margin-top: 4px;
    }

    .timeline-item:not(:last-child):before {
        content: '';
        position: absolute;
        left: 6px;
        top: 24px;
        bottom: 0;
        border-left: 2px solid #e3e6ec;
    }

    .timeline-item-content {
        padding: 0 0 0 1rem;
    }

    .img-profile {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fc;
    }

    .info-item label {
        font-size: 0.85rem;
        display: block;
    }

    .table th {
        border-top: none;
        font-weight: 600;
        padding-top: 1.2rem;
        padding-bottom: 1.2rem;
    }

    .table td {
        padding-top: 1.2rem;
        padding-bottom: 1.2rem;
        vertical-align: middle;
    }

    .order-info, .order-summary {
        height: 100%;
    }

    .bg-light {
        background-color: #f8f9fc !important;
    }
</style>
@endpush
@endsection