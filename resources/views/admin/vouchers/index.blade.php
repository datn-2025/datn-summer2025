@extends('layouts.backend')

@section('title', 'Quản lý Voucher')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Danh Sách Voucher</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Vouchers</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1">Danh sách voucher</h5>
                    <div class="flex-shrink-0">
                        <a href="{{ route('admin.vouchers.trash') }}" class="btn btn-soft-danger btn-sm">
                            <i class="ri-delete-bin-line align-bottom"></i> Thùng rác
                        </a>
                        <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary btn-sm">
                            <i class="ri-add-line align-bottom"></i> Thêm voucher
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-3 mb-4">
                    <form action="{{ route('admin.vouchers.index') }}" method="GET" class="row g-3">
                        <div class="col-xl-3">
                            <div class="search-box">
                                <input type="text" name="search" class="form-control search" 
                                       placeholder="Tìm kiếm..." value="{{ request('search') }}">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <select class="form-select" name="status">
                                <option value="">Trạng thái</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                                    Đang hoạt động
                                </option>
                                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>
                                    Đã hết hạn
                                </option>
                                <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>
                                    Sắp diễn ra
                                </option>
                            </select>
                        </div>
                        <div class="col-xl-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ri-search-line align-bottom me-1"></i> Tìm kiếm
                            </button>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Mã voucher</th>
                                <th scope="col">Giảm giá</th>
                                <th scope="col">Điều kiện</th>
                                <th scope="col">Đã dùng/Tổng</th>
                                <th scope="col">Thời gian</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col" style="width: 150px;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vouchers as $voucher)
                            <tr>
                                <td>
                                    <strong>{{ $voucher->code }}</strong>
                                    @if($voucher->description)
                                        <small class="d-block text-muted">{{ $voucher->description }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $voucher->discount_percent }}%</span>
                                    <small class="d-block">
                                        Tối đa: {{ number_format($voucher->max_discount) }}đ
                                    </small>
                                </td>
                                <td>
                                    Đơn tối thiểu: {{ number_format($voucher->min_order_value) }}đ
                                </td>
                                <td>
                                    <div class="progress" style="height: 6px;">
                                        @php
                                            $usedPercentage = ($voucher->used_count / $voucher->quantity) * 100;
                                        @endphp
                                        <div class="progress-bar bg-success" role="progressbar" 
                                             style="width: {{ $usedPercentage }}%"></div>
                                    </div>
                                    <small class="mt-1 d-block text-center">
                                        {{ $voucher->used_count }}/{{ $voucher->quantity }}
                                    </small>
                                </td>
                                <td>
                                    {{ $voucher->valid_from->format('d/m/Y') }} -<br>
                                    {{ $voucher->valid_to->format('d/m/Y') }}
                                </td>
                                <td>
                                    @if($voucher->isValid())
                                        <span class="badge bg-success">Đang hoạt động</span>
                                    @elseif($voucher->valid_from->isFuture())
                                        <span class="badge bg-info">Sắp diễn ra</span>
                                    @else
                                        <span class="badge bg-danger">Hết hạn</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="hstack gap-2">
                                        <a href="{{ route('admin.vouchers.show', $voucher) }}" 
                                           class="btn btn-sm btn-soft-info">
                                            <i class="ri-eye-fill"></i>
                                        </a>
                                        <a href="{{ route('admin.vouchers.edit', $voucher) }}" 
                                           class="btn btn-sm btn-soft-warning">
                                            <i class="ri-pencil-fill"></i>
                                        </a>
                                        <form action="{{ route('admin.vouchers.destroy', $voucher) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-soft-danger"
                                                    onclick="return confirm('Bạn có chắc muốn xóa?')">
                                                <i class="ri-delete-bin-fill"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <img src="{{ asset('assets/images/no-data.png') }}" class="mb-3" 
                                         style="max-width: 120px" alt="No Data">
                                    <h5 class="text-muted">Không có dữ liệu</h5>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    {{ $vouchers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection