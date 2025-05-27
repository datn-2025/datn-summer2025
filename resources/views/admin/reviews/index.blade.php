@php
    use Illuminate\Support\Str;
@endphp
@extends('layouts.backend')

@section('content')
<div class="container-fluid">
    <!-- Page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Quản lý đánh giá</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Quản lý</a></li>
                        <li class="breadcrumb-item active">Đánh giá</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- End page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quản lý đánh giá</h3>
                </div>
                <div class="card-body">
                    <form action="" method="GET" class="row g-3 mb-4">
                        <!-- <div class="col-md-3">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">Tất cả</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                            </select>
                        </div> -->
                        <div class="col-sm-3">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="">Tất cả trạng thái</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="customer_name" class="form-label">Tên khách hàng</label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" 
                                value="{{ request('customer_name') }}" placeholder="Nhập tên khách hàng">
                        </div>
                        <div class="col-md-3">
                            <label for="product_name" class="form-label">Tên sản phẩm</label>
                            <input type="text" class="form-control" id="product_name" name="product_name"
                                value="{{ request('product_name') }}" placeholder="Nhập tên sản phẩm">
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
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Sản phẩm</th>
                                    <th>Tác giả</th>
                                    <th>Khách hàng</th>
                                    <th>Bình luận</th>
                                    <th>Phản hồi Admin</th>
                                    <th>Đánh giá</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
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
                                    <td>
                                        @if($review->book && $review->book->author)
                                            {{ $review->book->author->name }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>{{ $review->user->name ?? 'Người dùng đã xóa' }}</td>
                                    <td>{{ Str::limit($review->comment, 50) }}</td>
                                    <td>
                                        <!-- <form action="{{ route('admin.reviews.response', $review) }}" method="POST">
                                            @csrf
                                            <div class="input-group">
                                                <input type="text" name="admin_response" 
                                                       class="form-control form-control-sm" 
                                                       value="{{ $review->admin_response ?? '' }}">
                                                <button type="submit" class="btn btn-sm btn-primary">Lưu</button>
                                            </div>
                                        </form> -->
                                        {{ $review->admin_response ?? 'Chưa có phản hồi' }}
                                    </td>
                                    <td>
                                        <div class="rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= $review->rating ? ' text-warning' : ' text-muted' }}"></i>
                                            @endfor
                                        </div>
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.reviews.update-status', [$review, 'status' => $review->status === 'approved' ? 'pending' : 'approved']) }}" 
                                              method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-{{ $review->status === 'approved' ? 'success' : 'secondary' }}">
                                                {{ $review->status === 'approved' ? 'Đã duyệt' : 'Chờ duyệt' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                                    <!-- <td>
                                        <form action="{{ route('admin.reviews.destroy', $review) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td> -->
                                    <td class="text-center">
                                        <a href="{{ route('admin.reviews.response', $review) }}" 
                                        class="btn btn-sm btn-outline-primary" 
                                        title="Xem và phản hồi">
                                            <i class="fas fa-reply"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3 px-3">
                        <div class="text-muted">
                            Hiển thị <strong>{{ $reviews->firstItem() }}</strong> đến <strong>{{ $reviews->lastItem() }}</strong> trong tổng số <strong>{{ $reviews->total() }}</strong> đánh giá
                        </div>
                        <div>
                            <!-- {{ $reviews->links('pagination::bootstrap-4') }} -->
                            {{ $reviews->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection