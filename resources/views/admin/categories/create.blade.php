@extends('layouts.backend')

@section('title', 'Quản lý danh mục sách')

@section('content')
<div class="container-fluid">

    <!-- Page Title -->
    <div class="row mb-3">
        <div class="col-12 d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-0">Thêm mới danh mục</h4>
            <nav>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.categories.index') }}">Danh mục sách</a>
                    </li>
                    <li class="breadcrumb-item active">Thêm mới</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- End Page Title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin danh mục</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return disableSubmitOnce(this)">
                        @csrf

                        <!-- Tên danh mục -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" 
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Mô tả danh mục -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả danh mục</label>
                            <textarea name="description"
                                      class="form-control @error('description') is-invalid @enderror"
                                      rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Ảnh danh mục -->
                        <div class="mb-3">
                            <label for="image" class="form-label">Ảnh danh mục</label>
                            <input type="file" id="image" name="image" accept="image/*"
                                   class="form-control @error('image') is-invalid @enderror">
                            @error('image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                            <img id="preview" src="#" alt="Xem trước ảnh"
                                 class="mt-2 border rounded"
                                 style="display: none; max-height: 120px;" />
                        </div>

                        <!-- Button -->
                        <div class="text-end">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary me-2">
                                <i class="ri-arrow-left-line align-bottom"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line"></i> Thêm
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
{!! Toastr::message() !!}
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const imageInput = document.getElementById("image");
        const previewImg = document.getElementById("preview");

        if (!imageInput || !previewImg) return;

        imageInput.addEventListener("change", (e) => {
            const file = e.target.files?.[0];

            // Nếu có file và là ảnh
            if (file && file.type.startsWith("image/")) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    previewImg.src = event.target.result;
                    previewImg.style.display = "block";
                };
                reader.readAsDataURL(file);
            } else {
                // Không chọn hoặc không phải ảnh ⇒ ẩn preview
                previewImg.src = "#";
                previewImg.style.display = "none";
            }
        });
    });
</script>
<script>
    let submitted = false;
    function disableSubmitOnce(form) {
        if (submitted) return false; // Ngăn gửi lại

        submitted = true;
        const btn = form.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1"></span> Đang xử lý...`;

        return true; // Cho phép gửi 1 lần đầu
    }
</script>

@endpush
