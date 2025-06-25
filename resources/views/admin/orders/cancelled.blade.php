@extends('layouts.backend')

@section('title', 'Đơn hàng đã hủy')

@section('content')
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Đơn hàng đã hủy</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Đơn hàng</a></li>
                            <li class="breadcrumb-item active">Đơn hàng đã hủy</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <!-- Order summary cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted mb-0">Tổng đơn hàng hủy</p>
                                <h4 class="fs-22 fw-semibold mb-0">{{ $orderCounts['Đã Hủy'] }}</h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-danger rounded fs-3">
                                    <i class="ri-close-circle-line text-danger"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted mb-0">Khách hàng hủy đơn</p>
                                <h4 class="fs-22 fw-semibold mb-0">{{ $orderCounts['cancelled_customers'] }}</h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-info rounded fs-3">
                                    <i class="ri-user-unfollow-line text-info"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted mb-0">Chờ Xác Nhận</p>
                                <h4 class="fs-22 fw-semibold mb-0">{{ $orderCounts['Chờ Xác Nhận'] }}</h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-warning rounded fs-3">
                                    <i class="ri-time-line text-warning"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-uppercase fw-medium text-muted mb-0">Đã giao hàng</p>
                                <h4 class="fs-22 fw-semibold mb-0">{{ $orderCounts['Đã Giao Thành Công'] }}</h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-success rounded fs-3">
                                    <i class="ri-checkbox-circle-line text-success"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header border-0">
                        <div class="d-flex align-items-center">
                            <h5 class="card-title mb-0 flex-grow-1">Danh sách đơn hàng đã hủy</h5>
                        </div>
                    </div>
                    <div class="card-body border border-dashed border-end-0 border-start-0">
                        <form action="{{ route('admin.orders.cancelled') }}" method="GET">
                            <div class="row g-3">
                                <div class="col-xxl-4 col-sm-6">
                                    <div class="search-box">
                                        <input type="text" class="form-control search"
                                            placeholder="Tìm kiếm theo mã đơn hàng, tên khách hàng..."
                                            name="search" value="{{ request('search') }}">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-sm-6">
                                    <div>
                                        <select class="form-control" data-choices data-choices-search-false
                                            name="payment">
                                            <option value="">Trạng thái thanh toán</option>
                                            @foreach ($paymentStatuses as $status)
                                                <option value="{{ $status->name }}" {{ request('payment') == $status->name ? 'selected' : '' }}>{{ $status->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xxl-2 col-sm-6">
                                    <div>
                                        <input type="date" class="form-control" name="date" value="{{ request('date') }}">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 d-flex">
                                    <button type="submit" class="btn btn-primary me-2" style="background-color:#405189; border-color: #405189">
                                        <i class="ri-search-2-line"></i> Tìm kiếm
                                    </button>
                                    <a href="{{ route('admin.orders.cancelled') }}" class="btn btn-light me-2">
                                        <i class="ri-refresh-line"></i> Đặt lại
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle table-nowrap mb-0">
                                <thead class="text-muted table-light">
                                    <tr>
                                        <th scope="col">Mã đơn hàng</th>
                                        <th scope="col">Khách hàng</th>
                                        <th scope="col">Số điện thoại</th>
                                        <th scope="col">Tổng tiền</th>
                                        <th scope="col">Trạng thái</th>
                                        <th scope="col">Lý do hủy</th>
                                        <th scope="col">Ngày Hủy</th>
                                        <th scope="col">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($orders as $order)
                                    <tr>
                                        <td>{{$order->order_code }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    @if ($order->user->avatar)
                                                    <img src="{{ asset('storage/' . $order->user->avatar) }} " alt="" class="avatar-xs rounded-circle">
                                                    @else
                                                    <img src="{{ asset('assets/images/users/avatar-1.jpg') }} " alt="" class="avatar-xs rounded-circle">
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1">
                                                    {{ $order->user->name ?? 'N/A' }}
                                                    <span class="text-muted d-block">{{ $order->user->email ?? '' }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $order->address->phone ?? 'N/A' }}</td>
                                        <td class="fw-medium">{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
                                        <td class="text-center">
                                            <span class="badge rounded-pill bg-danger">{{ $order->orderStatus->name ?? 'N/A' }}</span>
                                            <br>
                                            <span class="badge rounded-pill 
                                                @if($order->paymentStatus->name == 'Đã Thanh Toán') bg-success 
                                                @elseif($order->paymentStatus->name == 'Chưa Thanh Toán') bg-warning text-dark
                                                @elseif($order->paymentStatus->name == 'Thất Bại') bg-danger 
                                                @else bg-secondary  @endif">
                                                {{ $order->paymentStatus->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>{{ $order->cancellation_reason ?? 'Không có lý do' }}</td>
                                        <td>{{ $order->cancelled_at ? $order->cancelled_at->format('d/m/Y') : 'N/A' }}</td>
                                        <td>
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ri-more-fill align-middle"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="dropdown-item">
                                                            <i class="ri-eye-fill align-bottom me-2 text-muted"></i> Chi tiết
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <div class="py-4">
                                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:72px;height:72px"></lord-icon>
                                                <h5 class="mt-4">Không có đơn hàng nào đã hủy</h5>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            {{ $orders->links(('layouts.pagination')) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('scripts')
<script src="https://cdn.lordicon.com/bhenfmcm.js"></script>
@endsection
