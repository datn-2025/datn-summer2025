@extends('layouts.backend')

@section('title', 'Lịch Sử Thanh Toán')
@section('content')
    <!-- Page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Quản lý thanh toán</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">admin</a></li>
                        <li class="breadcrumb-item active">Lịch sử thanh toán</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- End page title -->

    <div class="container-fluid">

        <!-- Bộ lọc tìm kiếm và trạng thái -->
        <div class="card mb-4">
            <div class="card-body border-bottom py-4">
                <h5 class="mb-4">Lịch Sử Thanh Toán</h5>

                <form method="GET" action="{{ route('admin.payment-methods.history') }}">
                    <div class="row g-3 align-items-center">
                        <!-- Ô tìm kiếm -->
                        <div class="col-lg-4">
                            <input type="text" name="search" class="form-control ps-4" placeholder="🔍 Tìm kiếm đơn hàng..."
                                value="{{ request('search') }}">
                        </div>

                        <!-- Trạng thái thanh toán -->
                        <div class="col-lg-auto" style="min-width: 190px;">
                            <select class="form-select" name="payment_status">
                                <option value="">✨ Tất cả trạng thái</option>
                                <option value="Chờ xử lý" {{ request('payment_status') == 'Chờ xử lý' ? 'selected' : '' }}>
                                    Chờ xử lý
                                </option>
                                <option value="Chưa thanh toán" {{ request('payment_status') == 'Chưa thanh toán' ? 'selected' : '' }}>
                                    Chưa thanh toán
                                </option>
                                <option value="Đã thanh toán" {{ request('payment_status') == 'Đã thanh toán' ? 'selected' : '' }}>
                                    Đã thanh toán
                                </option>
                                <option value="Thất bại" {{ request('payment_status') == 'Thất bại' ? 'selected' : '' }}>
                                    Thất bại
                                </option>
                            </select>
                        </div>

                        <!-- Nút lọc + đặt lại -->
                        <div class="col-lg-auto d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4" style="min-width: 130px;">
                                <i class="ri-filter-3-line me-1"></i> Lọc
                            </button>
                            <a href="{{ route('admin.payment-methods.history') }}" class="btn btn-outline-secondary px-4"
                                style="min-width: 130px;">
                                <i class="ri-refresh-line me-1"></i> Đặt lại
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bảng lịch sử thanh toán -->
        <div class="card">
            <div class="card-body">
                <table class="table table-hover table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-muted">
                            <th>STT</th>
                            <th>Order Code</th>
                            <th>Phương thức thanh toán</th>
                            <th>Số tiền</th>
                            <th>Ngày thanh toán</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $key => $payment)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $payment->order->order_code }}</td>
                                </td>
                                <td>{{ $payment->paymentMethod->name ?? 'Không xác định' }}</td>
                                <td>{{ number_format($payment->amount, 2) }}</td>
                                <td>{{ $payment->paid_at }}</td>
                                <td>
                                    @if($payment->paymentStatus->name == 'Đã Thanh Toán')
                                        <span class="badge bg-success">{{ $payment->paymentStatus->name }}</span>
                                    @elseif($payment->paymentStatus->name == 'Chưa thanh toán')
                                        <span class="badge bg-warning text-dark">{{ $payment->paymentStatus->name }}</span>
                                    @elseif($payment->paymentStatus->name == 'Chờ Xử Lý')
                                        <span class="badge bg-primary">{{ $payment->paymentStatus->name }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ $payment->paymentStatus->name }}</span>
                                    @endif
                                </td>
                                <td>
    @php
        $status = $payment->paymentStatus->name;
    @endphp

    @if ($status === 'Chờ Xử Lý' || $status === 'Chưa thanh toán')
    {{-- Hiện nút duyệt và từ chối --}}
    <form action="{{ route('admin.payment-methods.updateStatus', $payment->id) }}" method="POST" style="display:inline-block;">
        @csrf
        @method('PUT')
        <input type="hidden" name="payment_status" value="Đã Thanh Toán">
        <button type="submit" class="btn btn-success btn-sm">Duyệt</button>
    </form>

    <form action="{{ route('admin.payment-methods.updateStatus', $payment->id) }}" method="POST" style="display:inline-block;">
        @csrf
        @method('PUT')
        <input type="hidden" name="payment_status" value="Thất Bại">
        <button type="submit" class="btn btn-danger btn-sm">Từ chối</button>
    </form>
@elseif ($status === 'Đã Thanh Toán')
    <button class="btn btn-outline-success btn-sm" disabled>Đã duyệt</button>
@elseif ($status === 'Thất Bại')
    <button class="btn btn-outline-danger btn-sm" disabled>Đã từ chối</button>
@else
    <button class="btn btn-secondary btn-sm" disabled>{{ $status }}</button>
@endif


</td>



                            </tr>
                        @endforeach
                        @foreach($payments as $payment)
                            <!-- Modal chỉnh sửa trạng thái -->
                            <div class="modal fade" id="editStatusModal{{ $payment->id }}" tabindex="-1"
                                aria-labelledby="editStatusModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Chỉnh sửa trạng thái thanh toán</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Đóng"></button>
                                        </div>
                                        <form action="{{ route('admin.payment-methods.updateStatus', $payment->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="payment_status" class="form-label">Trạng thái</label>
                                                    @php
                                                        $status = $payment->paymentStatus->name;
                                                        $lockedStatuses = ['Đã Thanh Toán', 'Thất Bại'];
                                                    @endphp

                                                    <select class="form-select" name="payment_status" required>
                                                        <option value="Chờ xử lý"
                                                            {{ $status == 'Chờ xử lý' ? 'selected' : '' }}
                                                            @if(in_array($status, array_merge(['Chưa thanh toán'], $lockedStatuses))) disabled @endif>
                                                            Chờ xử lý
                                                        </option>

                                                        <option value="Chưa thanh toán"
                                                            {{ $status == 'Chưa thanh toán' ? 'selected' : '' }}
                                                            @if(in_array($status, $lockedStatuses)) disabled @endif>
                                                            Chưa thanh toán
                                                        </option>

                                                        <option value="Đã thanh toán"
                                                            {{ $status == 'Đã Thanh Toán' ? 'selected' : '' }}
                                                            @if(in_array($status, $lockedStatuses) && $status != 'Đã Thanh Toán') disabled @endif>
                                                            Đã thanh toán
                                                        </option>

                                                        <option value="Thất bại"
                                                            {{ $status == 'Thất Bại' ? 'selected' : '' }}
                                                            @if(in_array($status, $lockedStatuses) && $status != 'Thất Bại') disabled @endif>
                                                            Thất bại
                                                        </option>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Hủy</button>
                                                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach


                    </tbody>
                </table>

                <!-- Phân trang -->
                <div class="d-flex justify-content-end mt-4">
                    <nav>
                        @if ($payments->hasPages())
                            <ul class="pagination mb-0">
                                {{-- Previous Page Link --}}
                                @if ($payments->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">Prev</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $payments->previousPageUrl() }}" rel="prev">Prev</a>
                                    </li>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($payments->getUrlRange(1, $payments->lastPage()) as $page => $url)
                                    @if ($page == $payments->currentPage())
                                        <li class="page-item active">
                                            <span class="page-link">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($payments->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $payments->nextPageUrl() }}" rel="next">Next</a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">Next</span>
                                    </li>
                                @endif
                            </ul>
                        @endif
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection