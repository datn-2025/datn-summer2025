@extends('layouts.backend')

@section('title', 'Quản lý danh mục sách')

@section('content')

<div class="container-fluid">

    <!-- Page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Quản lý danh mục</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Quản lý</a></li>
                        <li class="breadcrumb-item active">Danh mục sách</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- End page title -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Quản lý danh mục</h4>
                </div>

                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="listjs-table" id="categoryList">
                        <!-- Search and Add -->
                        <div class="row g-4 mb-3">
                            <div class="col-sm-auto">
                                <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal" id="create-btn" data-bs-target="#showModal" style="height: 36px;">
                                    <i class="ri-add-line align-bottom me-1"></i> Thêm mới
                                </button>
                            </div>
                            <div class="col-sm">
                                <form method="GET" action="{{ route('admin.categories.index') }}" class="d-flex justify-content-sm-end">
                                    <input type="text" name="search_name_category" class="form-control me-2" placeholder="Tên danh mục" value="{{ $searchName }}" style="height: 36px; width: 200px;">
                                    <button type="submit" class="btn btn-primary" style="height: 36px;">Tìm kiếm</button>
                                </form>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="table-responsive table-card mt-3 mb-1">
                            @if ($categories->isEmpty())
                                <div class="noresult text-center">
                                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                                    <h5 class="mt-2">Không tìm thấy danh mục nào</h5>
                                </div>
                            @else
                                <table class="table align-middle table-nowrap" id="categoryTable">
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
                                    <tbody class="list form-check-all">
                                        @foreach ($categories as $key => $category)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $category->name }}</td>
                                                <td>
                                                    @if ($category->image)
                                                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" width="50">
                                                    @else
                                                        Không có ảnh
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $category->books_count }}</td>
                                                <td>{{ $category->created_at }}</td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <button class="btn btn-sm btn-warning edit-item-btn">
                                                            <i class="ri-edit-2-line"></i> Sửa
                                                        </button>
                                                        <button class="btn btn-sm btn-danger remove-item-btn">
                                                            <i class="ri-delete-bin-2-line"></i> Xóa
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-between align-items-center mt-3 px-3">
                                    <div class="text-muted">
                                        Hiển thị <strong>{{ $categories->firstItem() }}</strong> đến <strong>{{ $categories->lastItem() }}</strong> trong tổng số <strong>{{ $categories->total() }}</strong> danh mục
                                    </div>
                                    <div>
                                        {{ $categories->links('pagination::bootstrap-4') }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection