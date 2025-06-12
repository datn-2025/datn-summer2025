@extends('layouts.backend')

@section('title', 'Quản lý ví người dùng')

@section('content')

    <!-- Page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Quản lý ví</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Ví người dùng</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- End page title -->

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Danh sách ví người dùng</h5>
                    </div>

                    <!-- Bộ lọc -->
                    <div class="card-body border-bottom py-4">
                        <form method="GET" action="{{ route('admin.wallets.index') }}">
                            <div class="row g-3 align-items-center">
                                <!-- Tìm kiếm -->
                                <div class="col-lg-4">
                                    <input type="text" name="search" class="form-control ps-4"
                                        placeholder="🔍 Tìm theo tên hoặc email" value="{{ request('search') }}">
                                </div>

                                <div class="col-lg-auto d-flex gap-2">
                                    <button type="submit" class="btn btn-primary px-4" style="min-width: 130px;">
                                        <i class="ri-filter-3-line me-1"></i> Lọc
                                    </button>
                                    <a href="{{ route('admin.wallets.index') }}" class="btn btn-outline-secondary px-4"
                                        style="min-width: 130px;">
                                        <i class="ri-refresh-line me-1"></i> Đặt lại
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Danh sách ví -->
                    <div class="card-body">
                        <div class="table-responsive table-card mb-4">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr class="text-muted">
                                        <th>#</th>
                                        <th>Tên người dùng</th>
                                        <th>Email</th>
                                        <th>Số dư</th>
                                        <th>Ngày tạo</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($wallets as $index => $wallet)
                                        <tr>
                                            <td>{{ $wallets->firstItem() + $index }}</td>
                                            <td>{{ $wallet->user->name ?? '-' }}</td>
                                            <td>{{ $wallet->user->email ?? '-' }}</td>
                                            <td>₫{{ number_format($wallet->balance) }}</td>
                                            <td>{{ $wallet->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                <a href="{{ route('admin.wallets.show', $wallet->id) }}"
                                                    class="btn btn-sm btn-info">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Không có dữ liệu ví.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        {{-- Phân trang --}}
                        <div class="d-flex justify-content-end mt-4">
                            <nav>
                                @if ($wallets->hasPages())
                                    <ul class="pagination mb-0">
                                        {{-- Previous Page Link --}}
                                        @if ($wallets->onFirstPage())
                                            <li class="page-item disabled">
                                                <span class="page-link">Prev</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $wallets->previousPageUrl() }}" rel="prev">Prev</a>
                                            </li>
                                        @endif

                                        {{-- Pagination Elements --}}
                                        @foreach ($wallets->getUrlRange(1, $wallets->lastPage()) as $page => $url)
                                            @if ($page == $wallets->currentPage())
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
                                        @if ($wallets->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $wallets->nextPageUrl() }}" rel="next">Next</a>
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
        </div>
    </div>

@endsection