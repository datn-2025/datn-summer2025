@extends('layouts.backend')

@section('title', 'Quản lý hóa đơn')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-primary">
                                <i class="fas fa-file-invoice me-2"></i>Quản lý hóa đơn
                            </h5>
                            <div>
                                <a href="{{ route('admin.dashboard') }}" 
                                   class="btn btn-outline-secondary btn-sm"
                                   data-bs-toggle="tooltip" 
                                   title="Quay lại Dashboard">
                                    <i class="fas fa-arrow-left me-1"></i>Quay lại
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif                        <!-- Thanh công cụ -->                        <div class="mb-4">
                            <form method="GET" action="{{ route('admin.invoices.index') }}" class="row g-3">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-search text-primary"></i>
                                        </span>
                                        <input type="text" 
                                               name="search_order_code" 
                                               class="form-control"
                                               placeholder="Nhập mã đơn hàng..." 
                                               value="{{ request()->get('search_order_code') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-wallet text-primary"></i>
                                        </span>                        <select name="payment_method" class="form-select">
                                            <option value="">-- Phương thức thanh toán --</option>
                                            @foreach($paymentMethods as $method)
                                                <option value="{{ $method->id }}" 
                                                        {{ request()->get('payment_method') == $method->id ? 'selected' : '' }}>
                                                    {{ $method->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-filter text-primary"></i>
                                        </span>
                                        <select name="payment_status" class="form-select">
                                            <option value="">-- Trạng thái thanh toán --</option>
                                            <option value="paid" {{ request()->get('payment_status') == 'paid' ? 'selected' : '' }}>
                                                Đã thanh toán
                                            </option>
                                            <option value="unpaid" {{ request()->get('payment_status') == 'unpaid' ? 'selected' : '' }}>
                                                Chưa thanh toán
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-auto">
                                    <div class="d-flex gap-2">
                                        <button type="submit" 
                                                class="btn btn-primary"
                                                data-bs-toggle="tooltip" 
                                                title="Tìm kiếm hóa đơn">
                                            <i class="fas fa-search me-1"></i>Tìm kiếm
                                        </button>
                                        <a href="{{ route('admin.invoices.index') }}" 
                                           class="btn btn-outline-secondary"
                                           data-bs-toggle="tooltip"
                                           title="Xóa bộ lọc">
                                            <i class="fas fa-redo me-1"></i>Làm mới
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div><!-- Bảng hóa đơn -->
                        <div class="table-responsive">
                            @if ($invoices->isEmpty())
                                <div class="text-center py-5">
                                    <div class="mb-3">
                                        <i class="fas fa-file-invoice fa-4x text-muted"></i>
                                    </div>
                                    <h5 class="text-muted">Không tìm thấy hóa đơn nào</h5>
                                    @if (request()->has('search_order_code') || request()->has('payment_status') || request()->has('payment_method'))
                                        <p class="text-muted">
                                            Thử tìm kiếm với từ khóa khác hoặc xóa bộ lọc
                                        </p>
                                    @endif
                                </div>
                            @else                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%">#</th>
                                            <th>Mã đơn hàng</th>
                                            <th>Khách hàng</th>
                                            <th>Phương thức</th>
                                            <th class="text-center">Trạng thái</th>
                                            <th class="text-end">Tổng tiền</th>
                                            <th width="10%">Ngày tạo</th>
                                            <th width="10%" class="text-center">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($invoices as $key => $invoice)
                                            <tr>
                                                <td>{{ $invoices->firstItem() + $key }}</td>
                                                <td>
                                                    <span class="fw-medium text-primary">
                                                        #{{ $invoice->order->order_code }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-medium">{{ $invoice->order->user->name }}</span>
                                                        <small class="text-muted">
                                                            <i class="fas fa-envelope-open me-1"></i>
                                                            {{ $invoice->order->user->email }}
                                                        </small>
                                                    </div>
                                                </td>                                <td>
                                    <span class="badge bg-light text-dark">
                                        <i class="fas fa-money-bill-wave me-1"></i>
                                        {{ $invoice->order->paymentMethod->name }}
                                    </span>
                                </td>
                                                <td class="text-center">
                                                    @if($invoice->order->payment_status == 'paid')
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check-circle me-1"></i>Đã thanh toán
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning">
                                                            <i class="fas fa-clock me-1"></i>Chưa thanh toán
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <span class="fw-medium">{{ number_format($invoice->total_amount) }}đ</span>
                                                </td>
                                                <td>
                                                    <span class="text-muted">
                                                        <i class="fas fa-calendar-alt me-1"></i>
                                                        {{ $invoice->created_at->format('d/m/Y') }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center gap-1">
                                                        <a href="{{ route('admin.invoices.show', $invoice->id) }}"
                                                           class="btn btn-sm btn-info"
                                                           data-bs-toggle="tooltip"
                                                           title="Xem chi tiết hóa đơn">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.invoices.generate-pdf', $invoice->id) }}"
                                                           class="btn btn-sm btn-primary"
                                                           data-bs-toggle="tooltip"
                                                           title="Tải PDF hóa đơn"
                                                           target="_blank">
                                                            <i class="fas fa-file-pdf"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>                                <!-- Phân trang -->
                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <div class="text-muted">
                                        <span class="badge bg-light text-dark">
                                            <i class="fas fa-list me-1"></i>
                                            Hiển thị <strong>{{ $invoices->firstItem() }}</strong> đến
                                            <strong>{{ $invoices->lastItem() }}</strong> trong tổng số
                                            <strong>{{ $invoices->total() }}</strong> hóa đơn
                                        </span>
                                    </div>
                                    <div>
                                        {{ $invoices->links('pagination::bootstrap-5') }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Khởi tạo tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
    @endpush
@endsection
