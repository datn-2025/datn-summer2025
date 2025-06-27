@extends('layouts.backend')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow">

                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">Chi tiết đánh giá & Phản hồi</h3>
                        <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                    </div>

                    <div class="card-body">

                        <!-- Thông tin sản phẩm -->
                        @if ($review->book)
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Thông tin sản phẩm</h5>
                                </div>
                                <div class="card-body row">
                                    <div class="col-md-3 text-center">
                                        @if ($review->book->cover_image)
                                            <img src="{{ asset('storage/' . $review->book->cover_image) }}"
                                                class="img-fluid rounded shadow-sm" alt="{{ $book->title }}" style="max-height: 300px;">
                                        @else
                                            <img src="{{ asset('images/placeholder.jpg') }}"
                                                class="img-fluid rounded shadow-sm" alt="Không có hình">
                                        @endif
                                    </div>

                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-6">
                                                @if ($review->book->isbn)
                                                    <p><strong>ISBN:</strong> {{ $review->book->isbn }}</p>
                                                @endif
                                                <p><strong>Tên:</strong> {{ $review->book->title }}</p>
                                                @if ($review->book->author)
                                                    <p><strong>Tác giả:</strong> {{ $review->book->author->name }}</p>
                                                @endif
                                                @if ($review->book->brand)
                                                    <p><strong>Thương hiệu:</strong> {{ $review->book->brand->name }}</p>
                                                @endif
                                                @if ($review->book->category)
                                                    <p><strong>Thể loại:</strong> {{ $review->book->category->name }}</p>
                                                @endif
                                                @if ($review->book->publication_date)
                                                    <p><strong>Ngày xuất bản:</strong>
                                                        {{ $review->book->publication_date->format('d/m/Y') }}</p>
                                                @endif
                                                @if ($review->book->page_count)
                                                    <p><strong>Số trang:</strong> {{ $review->book->page_count }}</p>
                                                @endif
                                            </div>
                                            <div class="col-md-6">


                                                <p><strong>Trạng thái:</strong>
                                                    @if ($review->book->is_active)
                                                        <span class="badge bg-success">Còn hàng</span>
                                                    @else
                                                        <span class="badge bg-danger">Hết hàng</span>
                                                    @endif
                                                </p>
                                                @if ($review->orderItem)
                                                    <p><strong>Định dạng:</strong>
                                                        @if (strtolower($review->orderItem->format) === 'ebook')
                                                            <span class="badge bg-info">Ebook</span>
                                                        @else
                                                            <span class="badge bg-primary">Sách vật lý</span>
                                                        @endif
                                                    </p>

                                                    @if ($review->orderItem->language)
                                                        <p><strong>Ngôn ngữ:</strong> {{ $review->orderItem->language }}
                                                        </p>
                                                    @endif

                                                    @if ($review->orderItem->dimensions)
                                                        <p><strong>Kích thước:</strong>
                                                            {{ $review->orderItem->dimensions }}</p>
                                                    @endif
                                                @else
                                                    <p class="text-muted">Không có thông tin đơn hàng liên quan.</p>
                                                @endif

                                            </div>
                                        </div>
                                        <hr>
                                        <p><strong>Đánh giá trung bình:</strong>
                                            @if ($review->book->reviews_avg_rating)
                                                @foreach (range(1, 5) as $i)
                                                    <i
                                                        class="fas fa-star{{ $i <= round($review->book->reviews_avg_rating) ? ' text-warning' : ' text-muted' }}"></i>
                                                @endforeach
                                                ({{ number_format($review->book->reviews_avg_rating, 1) }}/5)
                                            @else
                                                <span class="text-muted">Chưa có đánh giá</span>
                                            @endif
                                        </p>
                                        <p><strong>Số lượng đánh giá:</strong> {{ $review->book->reviews_count ?? 0 }}</p>
                                        <p><strong>Số lượng đã bán:</strong> {{ $review->book->sold_count ?? 0 }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Thông tin đánh giá khách hàng -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Đánh giá khách hàng</h5>
                            </div>
                            <div class="card-body row">
                                <div class="col-md-6">
                                    <p><strong>Khách hàng:</strong> {{ $review->user->name ?? 'Khách' }}</p>
                                    <p><strong>Ngày đánh giá:</strong> {{ $review->created_at->format('d/m/Y H:i') }}</p>
                                    <p><strong>Số sao:</strong>
                                        @foreach (range(1, 5) as $i)
                                            <i
                                                class="fas fa-star{{ $i <= $review->rating ? ' text-warning' : ' text-muted' }}"></i>
                                        @endforeach
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Bình luận:</strong></p>
                                    <div class="border p-3 rounded bg-light">
                                        {{ $review->comment }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Phản hồi của admin -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Phản hồi của quản trị viên</h5>
                            </div>
                            <div class="card-body">
                                @if ($review->admin_response)
                                    <div class="d-flex justify-content-between mb-2">
                                        <strong>Ngày phản hồi:</strong>
                                        <span>{{ $review->updated_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div class="bg-light border p-3 rounded">
                                        {{ $review->admin_response }}
                                    </div>
                                @else
                                    <p>Chưa có phản hồi nào từ quản trị viên.</p>
                                    <form action="{{ route('admin.reviews.response.store', $review) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="admin_response" class="form-label">Nội dung phản hồi</label>
                                            <textarea name="admin_response" id="admin_response" class="form-control" rows="4" required
                                                placeholder="Nhập phản hồi..."></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-paper-plane me-1"></i> Gửi phản hồi
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <!-- Đánh giá khác của sản phẩm -->
                        @if ($otherReviews->count())
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Các đánh giá khác của sản phẩm</h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Người đánh giá</th>
                                                    <th>Nội dung</th>
                                                    <th>Đánh giá</th>
                                                    <th>Ngày đăng</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($otherReviews as $otherReview)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $otherReview->user->name ?? 'Khách' }}</td>
                                                        <td class="text-truncate" style="max-width: 250px;">
                                                            {{ $otherReview->comment }}</td>
                                                        <td>
                                                            @foreach (range(1, 5) as $i)
                                                                <i
                                                                    class="fas fa-star{{ $i <= $otherReview->rating ? ' text-warning' : ' text-muted' }}"></i>
                                                            @endforeach
                                                        </td>
                                                        <td>{{ $otherReview->created_at->format('d/m/Y') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @if ($otherReviews->hasPages())
                                        <div class="card-footer">
                                            {{ $otherReviews->links() }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
