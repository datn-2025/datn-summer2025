@extends('layouts.backend')

@section('title', 'Thùng Rác - Danh mục loại sách')

@section('content')
<div class="container-fluid">
    <!-- Tiêu đề trang -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Thùng rác - Danh mục loại sách</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Danh mục loại sách</a></li>
                        <li class="breadcrumb-item active">Thùng rác</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Nội dung -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <!-- Header -->
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Danh sách danh mục loại sách đã xóa</h4>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-primary btn-sm">
                        <i class="ri-arrow-go-back-line me-1"></i> Quay lại
                    </a>
                </div>

                <div class="card-body">
                    <!-- Alert -->
                    @foreach (['success' => 'success', 'error' => 'danger'] as $key => $type)
                        @if (session($key))
                            <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
                                {{ session($key) }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                            </div>
                        @endif
                    @endforeach

                    <!-- Tìm kiếm -->
                    <div class="row g-4 mb-3">
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('admin.categories.trash') }}" class="d-flex justify-content-md-end align-items-center gap-2">
                                <input type="text" name="search_name_category" class="form-control"
                                       placeholder="Tìm theo tên danh mục" value="{{ request('search_name_category') }}"
                                       style="width: 220px;">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="ri-search-2-line"></i> Tìm kiếm
                                </button>
                                <a href="{{ route('admin.categories.trash') }}" class="btn btn-outline-secondary px-4">
                                    <i class="ri-refresh-line"></i> Đặt lại
                                </a>
                            </form>
                        </div>
                    </div>

                    <!-- Bảng danh sách -->
                    <div class="table-responsive table-card mt-3 mb-1">
                        @if ($deletedCategories->isEmpty())
                            <div class="noresult text-center py-5">
                                @if (request()->filled('search_name_category'))
                                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                        colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px">
                                    </lord-icon>
                                    <h5 class="mt-3 text-danger">Không tìm thấy danh mục phù hợp</h5>
                                    <p class="text-muted">
                                        Không có danh mục nào bị xóa khớp với từ khóa
                                        <strong>"{{ request('search_name_category') }}"</strong>.<br>
                                        Vui lòng kiểm tra lại từ khóa hoặc thử tìm kiếm khác.
                                    </p>
                                @else
                                    <lord-icon src="https://cdn.lordicon.com/jmkrnisz.json" trigger="loop"
                                        colors="primary:#405189,secondary:#f06548" style="width:100px;height:100px">
                                    </lord-icon>
                                    <h5 class="mt-3 text-muted">Thùng rác hiện đang trống</h5>
                                    <p class="text-muted">Không có danh mục loại sách nào đã bị xóa tạm thời.</p>
                                @endif
                            </div>
                        @else
                            <table class="table align-middle table-nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên danh mục</th>
                                        <th>Ảnh</th>
                                        <th class="text-center">Số lượng sách</th>
                                        <th>Ngày tạo</th>
                                        <th>Ngày xóa</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($deletedCategories as $key => $category)
                                        @php
                                            $stt = $deletedCategories->firstItem() + $key;
                                            $confirm = $category->books_count > 0
                                                ? "alert('Danh mục này có sách, bạn không thể xóa vĩnh viễn.'); return false;"
                                                : "return confirm('Bạn có chắc muốn xóa vĩnh viễn danh mục này?')";
                                        @endphp
                                        <tr>
                                            <td>{{ $stt }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>
                                                <img src="{{ $category->image ? asset('storage/' . $category->image) : asset('images/default-category.png') }}"
                                                    alt="{{ $category->name }}" width="50" height="50" class="rounded object-fit-cover">
                                            </td>
                                            <td class="text-center">{{ $category->books_count }} quyển</td>
                                            <td>{{ $category->created_at->format('d/m/Y H:i') }}</td>
                                            <td>{{ $category->deleted_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <div class="btn-group gap-2">
                                                    <!-- Restore -->
                                                    <form action="{{ route('admin.categories.restore', $category->id) }}" method="POST" class="d-inline">
                                                        @csrf @method('PUT')
                                                        <button type="submit" class="btn btn-sm btn-success" title="Khôi phục">
                                                            <i class="ri-reply-line"></i>
                                                        </button>
                                                    </form>

                                                    <!-- Force delete -->
                                                    <form action="{{ route('admin.categories.force-delete', $category->id) }}" method="POST" class="d-inline">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                                title="Xóa vĩnh viễn"
                                                                onclick="{{ $confirm }}"
                                                                {{ $category->books_count > 0 ? 'disabled' : '' }}>
                                                            <i class="ri-delete-bin-fill"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Phân trang -->
                            <div class="d-flex justify-content-between align-items-center mt-3 px-3">
                                <div class="text-muted">
                                    Hiển thị <strong>{{ $deletedCategories->firstItem() }}</strong> đến
                                    <strong>{{ $deletedCategories->lastItem() }}</strong> trong tổng số
                                    <strong>{{ $deletedCategories->total() }}</strong> danh mục
                                </div>
                                <div>
                                    {{ $deletedCategories->links('pagination::bootstrap-4') }}
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
