@extends('layouts.backend')

@section('title', 'Quản lý hóa đơn')

@section('content')
<div class="container-fluid">
    <!-- Tiêu đề trang -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Quản lý hóa đơn</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Hóa đơn</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Nội dung -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Danh sách hóa đơn</h4>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <!-- Bộ lọc tìm kiếm -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <input type="text" name="search_order_code" class="form-control"
                                placeholder="Tìm theo mã đơn hàng"
                                value="{{ request()->get('search_order_code') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="payment_method" class="form-select">
                                <option value="">-- Phương thức thanh toán --</option>
                                @foreach ($paymentMethods as $method)
                                    <option value="{{ $method->id }}"
                                        {{ request()->get('payment_method') == $method->id ? 'selected' : '' }}>
                                        {{ $method->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="payment_status" class="form-select">
                                <option value="">-- Trạng thái thanh toán --</option>
                                <option value="paid" {{ request()->get('payment_status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                                <option value="unpaid" {{ request()->get('payment_status') == 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ri-search-2-line me-1"></i> Tìm kiếm
                            </button>
                            <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline-secondary">
                                <i class="ri-refresh-line"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Bảng hóa đơn -->
                    <div class="table-responsive table-card mt-3">
                        @if ($invoices->isEmpty())
                            <div class="noresult text-center py-5">
                                <lord-icon src="https://cdn.lordicon.com/nocovwne.json" trigger="loop"
                                    colors="primary:#405189,secondary:#0ab39c" style="width:100px;height:100px">
                                </lord-icon>
                                <h5 class="mt-3 text-muted">Không tìm thấy hóa đơn nào</h5>
                                <p class="text-muted">Hãy thử thay đổi bộ lọc hoặc làm mới danh sách.</p>
                            </div>
                        @else
                            <table class="table align-middle table-nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Mã đơn hàng</th>
                                        <th>Khách hàng</th>
                                        <th>Phương thức</th>
                                        <th class="text-center">Trạng thái</th>
                                        <th class="text-end">Tổng tiền</th>
                                        <th>Ngày tạo</th>
                                        <th class="text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoices as $key => $invoice)
                                        <tr>
                                            <td>{{ $invoices->firstItem() + $key }}</td>
                                            <td class="fw-medium text-primary">#{{ $invoice->order->order_code }}</td>
                                            <td>
                                                <div class="fw-medium">{{ $invoice->order->user->name }}</div>
                                                <small class="text-muted"><i class="fas fa-envelope-open me-1"></i>{{ $invoice->order->user->email }}</small>
                                            </td>
                                            <td>{{ $invoice->order->paymentMethod->name ?? 'Không xác định' }}</td>
                                            <td class="text-center">
                                                @if ($invoice->order->payment_status == 'paid')
                                                    <span class="badge bg-success"><i class="ri-check-line me-1"></i> Đã thanh toán</span>
                                                @else
                                                    <span class="badge bg-warning"><i class="ri-time-line me-1"></i> Chưa thanh toán</span>
                                                @endif
                                            </td>
                                            <td class="text-end">{{ number_format($invoice->total_amount) }}đ</td>
                                            <td>{{ $invoice->created_at->format('d/m/Y') }}</td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.invoices.show', $invoice->id) }}"
                                                        class="btn btn-sm btn-info" title="Xem chi tiết">
                                                        <i class="ri-eye-line"></i>
                                                    </a>
                                                    <a href="{{ route('admin.invoices.generate-pdf', $invoice->id) }}"
                                                        target="_blank" class="btn btn-sm btn-primary" title="Tải PDF">
                                                        <i class="ri-file-pdf-line"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Phân trang -->
                             <div class="d-flex justify-content-between align-items-center mt-3 px-3">
                                <div class="text-muted">
                                    Hiển thị <strong>{{ $invoices->firstItem() }}</strong> đến
                                    <strong>{{ $invoices->lastItem() }}</strong> trên tổng số
                                    <strong>{{ $invoices->total() }}</strong> hóa đơn
                                </div>
                                <div>
                                    {{ $invoices->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        @endif
                    </div> <!-- table-card -->
                </div> <!-- card-body -->
            </div> <!-- card -->
        </div>
    </div>
</div>
@endsection
