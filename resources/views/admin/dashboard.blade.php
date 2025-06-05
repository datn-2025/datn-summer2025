@extends('layouts.backend')
@section('title','dashboard')

@section('content')

    <div class="container-fluid">

        <!-- Tiêu đề -->
        <div class="row mb-3 pb-1">
            <div class="col-12">
                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-16 mb-1">Chào mừng trở lại!</h4>
                        <p class="text-muted mb-0">Bảng điều khiển quản trị viên</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Breadcrumb -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Tổng quan</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4 Thẻ thống kê chính -->
        <livewire:dashboard-stats />

        <!-- Các báo cáo bổ sung -->
        <div class="row mt-4">
            
            <div class="col-xl-4 col-md-6">
                <livewire:top-selling-books-report />
            </div>
            <div class="col-xl-4 col-md-6">
                <livewire:inventory-status-report />
            </div>
            <div class="col-xl-4 col-md-6">
                <livewire:revenue-by-author-publisher-report />
            </div>
        </div>

        <div class="row mt-4">
             <div class="col-xl-4 col-md-6">
                <livewire:book-category-chart/>
            </div>
            <div class="col-xl-4 col-md-6">
                <livewire:book-author-chart/>
            </div>
            <div class="col-xl-4 col-md-6">
                <livewire:book-brand-chart/>
            </div>
        </div>
    </div>

@endsection
