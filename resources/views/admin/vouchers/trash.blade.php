@extends('layouts.backend')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Thùng rác - Voucher</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.vouchers.index') }}">Vouchers</a></li>
                    <li class="breadcrumb-item active">Thùng rác</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-arrow-left"></i>
            </span>
            <span class="text">Quay lại</span>
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            @if($trashedVouchers->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Mã voucher</th>
                                <th>Giảm giá</th>
                                <th>Đã sử dụng</th>
                                <th>Ngày xóa</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($trashedVouchers as $voucher)
                            <tr>
                                <td>
                                    <strong>{{ $voucher->code }}</strong>
                                    @if($voucher->description)
                                        <small class="d-block text-muted">{{ $voucher->description }}</small>
                                    @endif
                                </td>
                                <td>
                                    {{ $voucher->discount_percent }}%
                                    <small class="d-block text-muted">
                                        Tối đa: {{ number_format($voucher->max_discount) }}đ
                                    </small>
                                </td>
                                <td>{{ $voucher->applied_vouchers_count }}/{{ $voucher->quantity }}</td>
                                <td>{{ $voucher->deleted_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <form action="{{ route('admin.vouchers.restore', $voucher->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fas fa-trash-restore"></i> Khôi phục
                                        </button>
                                    </form>
                                    
                                    @if($voucher->applied_vouchers_count == 0)
                                    <form action="{{ route('admin.vouchers.force-delete', $voucher->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                onclick="return confirm('Xóa vĩnh viễn? Không thể khôi phục!')">
                                            <i class="fas fa-trash-alt"></i> Xóa vĩnh viễn
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $trashedVouchers->links() }}
            @else
                <div class="text-center py-5">
                    <img src="{{ asset('images/empty-trash.svg') }}" alt="Empty Trash" 
                         style="max-width: 150px" class="mb-3">
                    <h6 class="text-muted">Thùng rác trống</h6>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection