@extends('layouts.backend')

@section('title', 'Thùng Rác - Danh mục')

@section('content')
<div class="container-fluid">
    <!-- Flash messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
        </div>
    @endif

    <!-- Page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Thùng Rác - Danh mục loại sách</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Danh mục loại sách</a></li>
                        <li class="breadcrumb-item active">Thùng rác</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="row">
        <div class="col">
            <div class="card">
                <!-- Header -->
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Danh sách danh mục loại sách đã xóa</h4>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-primary btn-sm">
                        <i class="las la-arrow-left"></i> Quay lại
                    </a>
                </div>

                <!-- Tìm kiếm -->
                <div class="row g-4 px-3 pt-3">
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                        <form action="{{ route('admin.categories.trash') }}" method="GET"
                              class="d-flex justify-content-md-end align-items-center gap-2">
                            <input type="text" name="search_name" class="form-control"
                                   placeholder="Tìm theo tên danh mục"
                                   value="{{ $searchName ?? '' }}" style="width: 220px;">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="ri-search-line"></i> Tìm kiếm
                            </button>
                            <a href="{{ route('admin.categories.trash') }}" class="btn btn-outline-secondary px-4">
                                <i class="ri-refresh-line"></i> Đặt lại
                            </a>
                        </form>
                    </div>
                </div>

                <!-- Danh sách -->
                <div class="card-body">
                    <div class="table-responsive table-card mt-3">
                        <table class="table table-striped table-nowrap align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>STT</th>
                                    <th>Tên danh mục</th>
                                    <th>Ảnh</th>
                                    <th class="text-center">Số lượng sách</th>
                                    <th>Ngày tạo</th>
                                    <th>Ngày xóa</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($deletedCategories as $key => $category)
                                    <tr>
                                        <td>{{ $deletedCategories->firstItem() + $key }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td>
                                            <img src="{{ $category->image ? asset('storage/' . $category->image) : asset('images/default-category.png') }}"
                                                 alt="{{ $category->name }}" width="50" height="50"
                                                 class="rounded" style="object-fit: cover;">
                                        </td>
                                        <td class="text-center">{{ $category->books_count }}</td>
                                        <td>{{ $category->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $category->deleted_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group gap-2">
                                                <!-- Khôi phục -->
                                                <form action="{{ route('admin.categories.restore', $category->id) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-sm btn-success" title="Khôi phục">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                </form>

                                                <!-- Xoá vĩnh viễn -->
                                                <form action="{{ route('admin.categories.force-delete', $category->id) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="
                                                                @if ($category->books_count > 0)
                                                                    alert('Danh mục này có sách, bạn không thể xóa vĩnh viễn.');
                                                                    return false;
                                                                @else
                                                                    return confirm('Bạn có chắc muốn xóa vĩnh viễn danh mục này?');
                                                                @endif
                                                            " title="Xóa vĩnh viễn">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Không có danh mục nào trong thùng rác</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $deletedCategories->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
