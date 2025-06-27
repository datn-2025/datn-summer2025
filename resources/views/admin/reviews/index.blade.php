@php
    use Illuminate\Support\Str;
@endphp

@extends('layouts.backend')

@section('title', 'Quản lý đánh giá')

@section('content')
    <div class="container-fluid">
        <!-- Tiêu đề trang -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Quản lý đánh giá</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Đánh giá</li>
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
                        <h4 class="card-title mb-0">Danh sách đánh giá</h4>
                    </div>

                    <div class="card-body">
                        <!-- Thông báo -->
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <!-- Bộ lọc tìm kiếm giữ nguyên -->
                        <form action="{{ route('admin.reviews.index') }}" method="GET" class="row g-3 mb-4">
                            <div class="col-md-2">
                                <label for="admin_response" class="form-label">Trạng thái phản hồi</label>
                                <select name="admin_response" class="form-select">
                                    <option value="">Tất cả</option>
                                    <option value="responded"
                                        {{ request('admin_response') == 'responded' ? 'selected' : '' }}>Đã phản hồi
                                    </option>
                                    <option value="not_responded"
                                        {{ request('admin_response') == 'not_responded' ? 'selected' : '' }}>Chưa phản hồi
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="status" class="form-label">Trạng thái đánh giá</label>
                                <select name="status" class="form-select">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="visible" {{ request('status') == 'visible' ? 'selected' : '' }}>Hiện
                                    </option>
                                    <option value="hidden" {{ request('status') == 'hidden' ? 'selected' : '' }}>Ẩn</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="product_name" class="form-label">Tên sản phẩm</label>
                                <input type="text" class="form-control" name="product_name"
                                    value="{{ request('product_name') }}" placeholder="Tên sản phẩm">
                            </div>

                            <div class="col-md-2">
                                <label for="customer_name" class="form-label">Tên khách hàng</label>
                                <input type="text" class="form-control" name="customer_name"
                                    value="{{ request('customer_name') }}" placeholder="Tên khách hàng">
                            </div>

                            <div class="col-md-2">
                                <label for="customer_email" class="form-label">Email khách hàng</label>
                                <input type="email" class="form-control" name="customer_email"
                                    value="{{ request('customer_email') }}" placeholder="Email khách hàng">
                            </div>

                            <div class="col-md-2">
                                <label for="rating" class="form-label">Số sao</label>
                                <select name="rating" class="form-select">
                                    <option value="">Tất cả</option>
                                    @foreach (range(5, 1) as $i)
                                        <option value="{{ $i }}"
                                            {{ request('rating') == $i ? 'selected' : '' }}>
                                            {{ str_repeat('★', $i) }}{{ str_repeat('☆', 5 - $i) }} ({{ $i }}
                                            sao)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="comment" class="form-label">Bình luận khách hàng</label>
                                <input type="text" class="form-control" name="comment" value="{{ request('comment') }}"
                                    placeholder="Bình luận khách hàng">
                            </div>

                            <div class="col-md-2">
                                <label for="admin_comment" class="form-label">Bình luận admin</label>
                                <input type="text" class="form-control" name="admin_comment"
                                    value="{{ request('admin_comment') }}" placeholder="Bình luận admin">
                            </div>

                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-search me-1"></i> Tìm kiếm
                                </button>
                                <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-undo me-1"></i> Làm mới
                                </a>
                            </div>
                        </form>

                        <!-- Bảng danh sách đánh giá giao diện đẹp -->
                        <div class="table-responsive table-card mt-3">
                            @if ($reviews->isEmpty())
                                <div class="noresult text-center py-5">
                                    @if (filled(request()->get('admin_response')) ||
                                            filled(request()->get('status')) ||
                                            filled(request()->get('product_name')) ||
                                            filled(request()->get('customer_name')) ||
                                            filled(request()->get('customer_email')) ||
                                            filled(request()->get('rating')) ||
                                            filled(request()->get('comment')) ||
                                            filled(request()->get('admin_comment')))
                                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                            colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px">
                                        </lord-icon>
                                        <h5 class="mt-3 text-danger">Không tìm thấy đánh giá phù hợp</h5>
                                        <p class="text-muted">
                                            Không có đánh giá nào khớp với từ khóa tìm kiếm hiện tại.<br>
                                            Vui lòng kiểm tra lại bộ lọc hoặc thử tìm kiếm khác.
                                        </p>
                                    @else
                                        <lord-icon src="https://cdn.lordicon.com/nocovwne.json" trigger="loop"
                                            colors="primary:#405189,secondary:#0ab39c" style="width:100px;height:100px">
                                        </lord-icon>
                                        <h5 class="mt-3 text-muted">Danh sách đánh giá hiện đang trống</h5>
                                        <p class="text-muted">Hãy đợi khách hàng để lại đánh giá để bắt đầu quản lý.</p>
                                    @endif
                                </div>
                            @else
                                <table class="table align-middle table-nowrap">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50px;">#</th>
                                            <th style="min-width: 150px;">Sản phẩm</th>
                                            <th style="min-width: 150px;">Khách hàng</th>
                                            <th style="max-width: 200px;">Bình luận</th>
                                            <th style="max-width: 200px;">Phản hồi Admin</th>
                                            <th style="width: 100px;">Đánh giá</th>
                                            <th style="width: 130px;">Phản hồi</th>
                                            <th style="width: 130px;">Ngày đăng</th>
                                            <th class="text-center" style="width: 130px;">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($reviews as $index => $review)
                                            <tr>
                                                <td>{{ $reviews->firstItem() + $index }}</td>
                                                <td>
                                                    @if ($review->book)
                                                        <a href="{{ route('admin.books.show', ['id' => $review->book->id, 'slug' => Str::slug($review->book->title)]) }}"
                                                            class="fw-medium text-primary">
                                                            {{ $review->book->title }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted">Sản phẩm đã xóa</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="fw-medium">
                                                        {{ $review->user->name ?? 'Người dùng đã xóa' }}</div>
                                                    <small class="text-muted">
                                                        <i
                                                            class="ri-mail-line me-1"></i>{{ $review->user->email ?? 'Không có email' }}
                                                    </small>
                                                </td>
                                                <td class="text-truncate" style="max-width: 200px;">
                                                    {{ $review->comment }}
                                                </td>
                                                <td class="text-truncate" style="max-width: 200px;">
                                                    {{ $review->admin_response ?? 'Chưa có phản hồi' }}
                                                </td>
                                                <td>
                                                    @foreach (range(1, 5) as $i)
                                                        <i
                                                            class="fas fa-star{{ $i <= $review->rating ? ' text-warning' : ' text-muted' }}"></i>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $review->admin_response ? 'success' : 'secondary' }}">
                                                        {{ $review->admin_response ? 'Đã phản hồi' : 'Chưa phản hồi' }}
                                                    </span>
                                                </td>
                                                <td>{{ $review->created_at->format('H:i d/m/Y') }}</td>
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center gap-1">
                                                        <form
                                                            action="{{ route('admin.reviews.update-status', $review->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            @if ($review->status === 'visible')
                                                                <button type="submit" class="btn btn-sm btn-danger"
                                                                    title="Ẩn">
                                                                    <i class="fas fa-eye-slash"></i>
                                                                </button>
                                                            @else
                                                                <button type="submit" class="btn btn-sm btn-primary"
                                                                    title="Hiển thị">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                            @endif
                                                        </form>
                                                        <a href="{{ route('admin.reviews.response', $review) }}"
                                                            class="btn btn-sm btn-outline-primary" title="Xem & phản hồi">
                                                            <i class="ri-chat-3-line"></i>
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
                                        Hiển thị <strong>{{ $reviews->firstItem() }}</strong> đến
                                        <strong>{{ $reviews->lastItem() }}</strong> trong tổng số
                                        <strong>{{ $reviews->total() }}</strong> đánh giá
                                    </div>
                                    <div>
                                        {{ $reviews->appends(request()->query())->links('pagination::bootstrap-4') }}
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
