@extends('layouts.backend')

@section('title', 'Chi Tiết Voucher')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Chi Tiết Voucher</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.vouchers.index') }}">Vouchers</a></li>
                    <li class="breadcrumb-item active">Chi tiết</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-4">
        <!-- Thông tin cơ bản -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h5 class="card-title flex-grow-1 mb-0">Thông tin voucher</h5>
                    <div class="flex-shrink-0">
                        <a href="{{ route('admin.vouchers.edit', $voucher) }}" 
                           class="btn btn-soft-warning btn-sm">
                            <i class="ri-pencil-fill align-middle"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th class="ps-0" scope="row">Mã voucher :</th>
                                <td class="text-muted">{{ $voucher->code }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0" scope="row">Trạng thái :</th>
                                <td>
                                    @if($voucher->isValid())
                                        <span class="badge bg-success">Đang hoạt động</span>
                                    @elseif($voucher->valid_from->isFuture())
                                        <span class="badge bg-info">Sắp diễn ra</span>
                                    @else
                                        <span class="badge bg-danger">Hết hạn</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="ps-0" scope="row">Giảm giá :</th>
                                <td class="text-muted">
                                    {{ $voucher->discount_percent }}%
                                    <small class="d-block">Tối đa: {{ number_format($voucher->max_discount) }}đ</small>
                                </td>
                            </tr>
                            <tr>
                                <th class="ps-0" scope="row">Đơn tối thiểu :</th>
                                <td class="text-muted">{{ number_format($voucher->min_order_value) }}đ</td>
                            </tr>
                            <tr>
                                <th class="ps-0" scope="row">Thời gian :</th>
                                <td class="text-muted">
                                    {{ $voucher->valid_from->format('d/m/Y') }} - 
                                    {{ $voucher->valid_to->format('d/m/Y') }}
                                </td>
                            </tr>
                            @if($voucher->description)
                            <tr>
                                <th class="ps-0" scope="row">Mô tả :</th>
                                <td class="text-muted">{{ $voucher->description }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Thống kê sử dụng -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Thống kê sử dụng</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="card card-body bg-light mb-0">
                            <h4 class="mb-3">{{ $voucher->applied_vouchers_count }} <small class="text-muted">/{{ $voucher->quantity }}</small></h4>
                            <p class="text-muted mb-0">Đã sử dụng</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card card-body bg-light mb-0">
                            <h4 class="mb-3">{{ $voucher->quantity - $voucher->applied_vouchers_count }}</h4>
                            <p class="text-muted mb-0">Còn lại</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <!-- Lịch sử sử dụng -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Lịch sử sử dụng</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Thời gian</th>
                                <th>Người dùng</th>
                                <th>Đơn hàng</th>
                                <th>Giá trị đơn</th>
                                <th>Giảm giá</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentUsage as $usage)
                            <tr>
                                <td>{{ $usage->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="flex-shrink-0">
                                            <img src="{{ $usage->order->user->avatar_url }}" 
                                                 alt="" class="avatar-xs rounded-circle">
                                        </div>
                                        <div class="flex-grow-1">
                                            {{ $usage->order->user->name }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $usage->order) }}" 
                                       class="fw-medium">#{{ $usage->order->code }}</a>
                                </td>
                                <td>{{ number_format($usage->order->total_amount) }}đ</td>
                                <td>{{ number_format($usage->discount_amount) }}đ</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <img src="{{ asset('assets/images/no-data.png') }}" class="mb-3" 
                                         style="max-width: 120px" alt="No Data">
                                    <h5 class="text-muted">Chưa có lượt sử dụng nào</h5>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    {{ $recentUsage->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection