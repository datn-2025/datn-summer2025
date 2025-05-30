@extends('layouts.backend')

@section('title', 'Chi Tiết Tin Tức')

@section('content')
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Chi Tiết Tin Tức</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.news.index') }}">Tin tức</a></li>
                        <li class="breadcrumb-item active">Chi tiết</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="{{ asset('storage/' . $article->thumbnail) }}" 
                             alt="{{ $article->title }}"
                             class="img-fluid rounded"
                             style="max-height: 400px;">
                    </div>

                    <h1 class="mb-3">{{ $article->title }}</h1>

                    <div class="d-flex gap-3 mb-4 text-muted">
                        <div>
                            <i class="ri-calendar-line align-bottom me-1"></i>
                            {{ $article->created_at->format('d/m/Y H:i') }}
                        </div>
                        <div>
                            <i class="ri-price-tag-3-line align-bottom me-1"></i>
                            {{ $article->category }}
                        </div>
                        <div>
                            @if($article->is_featured)
                                <i class="ri-star-fill align-bottom me-1 text-warning"></i>
                                Bài viết nổi bật
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="text-muted">Tóm tắt:</h5>
                        <p class="lead">{{ $article->summary }}</p>
                    </div>

                    <div class="article-content">
                        {!! $article->content !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thao tác</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <a href="{{ route('admin.news.edit', $article) }}" class="btn btn-warning w-100 mb-2">
                            <i class="ri-pencil-line align-bottom me-1"></i> Chỉnh sửa
                        </a>
                        <form action="{{ route('admin.news.destroy', $article) }}" 
                              method="POST"
                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa tin tức này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="ri-delete-bin-line align-bottom me-1"></i> Xóa
                            </button>
                        </form>
                    </div>

                    <a href="{{ route('admin.news.index') }}" class="btn btn-light w-100">
                        <i class="ri-arrow-go-back-line align-bottom me-1"></i> Quay lại danh sách
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thông tin</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>ID:</h6>
                        <p class="text-muted mb-0">{{ $article->id }}</p>
                    </div>
                    <div class="mb-3">
                        <h6>Ngày tạo:</h6>
                        <p class="text-muted mb-0">{{ $article->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="mb-3">
                        <h6>Cập nhật lần cuối:</h6>
                        <p class="text-muted mb-0">{{ $article->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <h6>Trạng thái:</h6>
                        @if($article->is_featured)
                            <span class="badge bg-success">Bài viết nổi bật</span>
                        @else
                            <span class="badge bg-secondary">Bài viết thường</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
