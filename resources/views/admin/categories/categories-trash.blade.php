@extends('layouts.backend')

@section('title', 'Thùng Rác - Danh mục')

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0">Thùng Rác - Danh mục</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Quản lý tác giả</a>
                            </li>
                            <li class="breadcrumb-item active">Thùng rác</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header align-items-center d-flex justify-content-between">
                        <h4 class="card-title mb-0">Danh Sách Danh mục Đã Xóa</h4>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-primary btn-sm">
                                <i class="las la-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>

                    <!-- Thanh tìm kiếm tác giả đã xóa -->
                    <div class="row mb-4 mt-2">
                        <div class="d-flex justify-content-sm-end">
                            <form action="{{ route('admin.categories.trash') }}" method="GET" class="d-flex gap-2">
                                <div class="col-auto">
                                    <input type="text" name="search_name" class="form-control"
                                        placeholder="Tìm kiếm theo tên danh mục đã xóa" value="{{ $searchName }}">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                                    <a href="{{ route('admin.categories.trash') }}" class="btn btn-secondary">Đặt lại
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-nowrap align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">STT</th>
                                        <th scope="col">Tên Danh Muc</th>
                                        <th scope="col">Ảnh</th>
                                        <th scope="col">Số Lượng Sách</th>
                                        <th scope="col">Ngày Tạo</th>
                                        <th scope="col">Ngày Xóa</th>
                                        <th scope="col">Thao Tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($deletedCategories as $key => $category)
                                        <tr>
                                            <td>{{ $deletedCategories->firstItem() + $key }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>
                                                <img src="{{ $category->image }}" alt="{{ $category->name }}" class="rounded"
                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                            </td>
                                            <td>{{ $category->books_count }} cuốn</td>
                                            <td>{{ $category->created_at->format('d/m/Y H:i') }}</td>
                                            <td>{{ $category->deleted_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <form action="{{ route('admin.categories.restore', $category->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-sm btn-success" title="Khôi phục">
                                                            <!-- <i class="las la-undo"></i> -->
                                                            <i class="fas fa-undo"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.categories.force-delete', $category->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Bạn có chắc muốn xóa vĩnh viễn danh mục này? Hành động này không thể hoàn tác.')"
                                                            title="Xóa vĩnh viễn">
                                                            <!-- <i class="las la-times"></i> -->
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

                        <div class="d-flex justify-content-center mt-4">
                            {{ $deletedCategories->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
