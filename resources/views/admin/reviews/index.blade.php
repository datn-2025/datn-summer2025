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
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Sản phẩm</th>
                                    <th>Chủ sở hữu</th>
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
                    <div class="d-flex justify-content-end mt-3">
                        {{ $reviews->links(('layouts.pagination')) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection