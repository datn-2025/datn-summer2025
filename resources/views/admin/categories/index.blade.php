@extends('layouts.backend')

@section('title', 'Quản lý danh mục sách')

@section('content')
<div class="container-fluid">
    <!-- Tiêu đề trang -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Quản lý danh mục</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="#">Quản lý</a></li>
                        <li class="breadcrumb-item active">Danh mục loại sách</li>
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
                    <h4 class="card-title mb-0">Quản lý danh mục loại sách</h4>
                </div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <!-- Thanh công cụ -->
                    <div class="row g-4 mb-3">
                        <!-- Bên trái: nút thêm + thùng rác -->
                        <div class="col-md-6 d-flex align-items-center gap-2">
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-success btn-sm">
                                <i class="ri-add-line me-1"></i> Thêm danh mục
                            </a>
                            <a href="{{ route('admin.categories.trash') }}" class="btn btn-danger btn-sm px-4">
                                <i class="ri-delete-bin-line me-1"></i> Thùng rác
                                @if ($trashCount > 0)
                                    <span class="badge bg-light text-danger ms-1">{{ $trashCount }}</span>
                                @endif
                            </a>
                        </div>

                        <!-- Bên phải: form tìm kiếm -->
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('admin.categories.index') }}"
                                class="d-flex justify-content-md-end align-items-center gap-2">
                                <input type="text" name="search_name_category" class="form-control"
                                    placeholder="Tên danh mục" value="{{ $searchName ?? '' }}" style="width: 200px;">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="ri-search-2-line"></i> Tìm kiếm
                                </button>
                                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary px-4">
                                    <i class="ri-refresh-line"></i> Đặt lại
                                </a>
                            </form>
                        </div>
                    </div>

                    <!-- Bảng danh mục -->
                    <div class="table-responsive table-card mt-3 mb-1">
                        @if ($categories->isEmpty())
                            <div class="noresult text-center">
                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                           colors="primary:#121331,secondary:#08a88a"
                                           style="width:75px;height:75px">
                                </lord-icon>
                                <h5 class="mt-2">Không tìm thấy danh mục nào</h5>
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
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $key => $category)
                                        <tr>
                                            <td>{{ $categories->firstItem() + $key }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>
                                                <img src="{{ $category->image ? asset('storage/' . $category->image) : asset('images/default-category.png') }}"
                                                     alt="{{ $category->name }}" width="50">
                                            </td>
                                            <td class="text-center">{{ $category->books_count }}</td>
                                            <td>{{ $category->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                <div class="btn-group gap-2">
                                                        <a href="{{ route('admin.categories.edit', $category->id) }}"
                                                           class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                                            <i class="ri-edit-2-line"></i>
                                                        </a>
                                                        <form action="{{ route('admin.categories.destroy', $category->id) }}"
                                                              method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa tạm thời danh mục này?')"
                                                              class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" title="Xóa tạm thời">
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
                                    Hiển thị <strong>{{ $categories->firstItem() }}</strong> đến
                                    <strong>{{ $categories->lastItem() }}</strong> trong tổng số
                                    <strong>{{ $categories->total() }}</strong> danh mục
                                </div>
                                <div>
                                    {{ $categories->links('pagination::bootstrap-4') }}
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
