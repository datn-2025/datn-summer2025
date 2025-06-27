@php
    use Illuminate\Support\Str;
@endphp

@extends('layouts.backend')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quản lý đánh giá</h3>
                </div>
                <div class="card-body">
                    <!-- Tìm kiếm và lọc đánh giá -->
                    <form action="{{ route('admin.reviews.index') }}" method="GET" class="row g-3 mb-4">
                        <!-- Tìm kiếm trạng thái phản hồi của admin -->
                        <div class="col-md-2">
                            <label for="admin_response" class="form-label">Trạng thái phản hồi</label>
                            <select name="admin_response" class="form-select">
                                <option value="">Tất cả</option>
                                <option value="responded" {{ request('admin_response') == 'responded' ? 'selected' : '' }}>Đã phản hồi</option>
                                <option value="not_responded" {{ request('admin_response') == 'not_responded' ? 'selected' : '' }}>Chưa phản hồi</option>
                            </select>
                        </div>

                        <!-- Tìm kiếm trạng thái hiển thị đánh giá (Hiện/Ẩn) -->
                        <div class="col-md-2">
                            <label for="status" class="form-label">Trạng thái đánh giá</label>
                            <select name="status" class="form-select">
                                <option value="">Tất cả trạng thái</option>
                                <option value="visible" {{ request('status') == 'visible' ? 'selected' : '' }}>Hiện</option>
                                <option value="hidden" {{ request('status') == 'hidden' ? 'selected' : '' }}>Ẩn</option>
                            </select>
                        </div>

                        <!-- Tìm kiếm theo tên sản phẩm -->
                        <div class="col-md-2">
                            <label for="product_name" class="form-label">Tên sản phẩm</label>
                            <input type="text" class="form-control" name="product_name" value="{{ request('product_name') }}" placeholder="Tên sản phẩm">
                        </div>

                        <!-- Tìm kiếm theo tên khách hàng -->
                        <div class="col-md-2">
                            <label for="customer_name" class="form-label">Tên khách hàng</label>
                            <input type="text" class="form-control" name="customer_name" value="{{ request('customer_name') }}" placeholder="Tên khách hàng">
                        </div>

                        <!-- Tìm kiếm email khách hàng -->
                        <div class="col-md-2">
                            <label for="customer_email" class="form-label">Email khách hàng</label>
                            <input type="email" class="form-control" name="customer_email" value="{{ request('customer_email') }}" placeholder="Email khách hàng">
                        </div>

                        <!-- Tìm kiếm số sao đánh giá -->
                        <div class="col-md-2">
                            <label for="rating" class="form-label">Số sao</label>
                            <select name="rating" class="form-select">
                                <option value="">Tất cả</option>
                                @foreach(range(5, 1) as $i)
                                    <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                        {{ str_repeat('★', $i) }}{{ str_repeat('☆', 5 - $i) }} ({{ $i }} sao)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tìm kiếm bình luận khách hàng -->
                        <div class="col-md-2">
                            <label for="comment" class="form-label">Bình luận khách hàng</label>
                            <input type="text" class="form-control" name="comment" value="{{ request('comment') }}" placeholder="Bình luận khách hàng">
                        </div>

                        <!-- Tìm kiếm bình luận admin -->
                        <div class="col-md-2">
                            <label for="admin_comment" class="form-label">Bình luận admin</label>
                            <input type="text" class="form-control" name="admin_comment" value="{{ request('admin_comment') }}" placeholder="Bình luận admin">
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

                    <!-- Bảng danh sách đánh giá -->
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Sản phẩm</th>
                                    <th>Khách hàng</th>
                                    <th>Bình luận</th>
                                    <th>Phản hồi Admin</th>
                                    <th>Đánh giá</th>
                                    <th>Trạng thái phản hồi</th>
                                    <th>Ngày đăng</th>
                                    <th>Tùy chọn</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reviews as $index => $review)
                                    <tr>
                                        <td>{{ $reviews->firstItem() + $index }}</td>
                                        <td>
                                            @if($review->book)
                                                <a class="text-decoration-none text-reset" href="{{ route('admin.books.show', ['id' => $review->book->id, 'slug' => Str::slug($review->book->title)]) }}">
                                                    {{ $review->book->title }}
                                                </a>
                                            @else
                                                <span class="text-muted">Sản phẩm đã xóa</span>
                                            @endif
                                        </td>
                                        <td>{{ $review->user->name ?? 'Người dùng đã xóa' }}</td>
                                        <td>{{ Str::limit($review->comment, 50) }}</td>
                                        <td>
                                            <div class="form-control-sm">
                                                {{ $review->admin_response ?? 'Chưa có phản hồi' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="rating">
                                                @foreach(range(1, 5) as $i)
                                                    <i class="fas fa-star{{ $i <= $review->rating ? ' text-warning' : ' text-muted' }}"></i>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn btn-sm btn-{{ $review->admin_response ? 'success' : 'secondary' }} ">
                                                {{ $review->admin_response ? 'Đã phản hồi' : 'Chưa phản hồi' }}
                                            </div>
                                        </td>
                                        <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-center">
                                            <!-- Cập nhật trạng thái hiển thị hoặc ẩn -->
                                            <form action="{{ route('admin.reviews.update-status', $review->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-{{ $review->status === 'hidden' ? 'primary' : 'danger' }} ">
                                                    <i class="fas fa-eye-slash"></i> {{ $review->status === 'hidden' ? 'Hiển thị' : 'Ẩn' }}
                                                </button>
                                            </form>
                                            <a href="{{ route('admin.reviews.response', $review) }}" class="btn btn-sm btn-outline-primary mt-1" title="Xem và phản hồi">
                                                <i class="fas fa-reply"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Phân trang -->
                    <div class="d-flex justify-content-between align-items-center mt-3 px-3">
                        <div class="text-muted">
                            Hiển thị <strong>{{ $reviews->firstItem() }}</strong> đến <strong>{{ $reviews->lastItem() }}</strong> trong tổng số <strong>{{ $reviews->total() }}</strong> đánh giá
                        </div>
                        <div>
                            {{ $reviews->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
