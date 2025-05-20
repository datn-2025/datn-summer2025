@extends('layouts.backend')

@section('title', 'Chi tiết người dùng')

@section('content')      
                <div class="container-fluid">
                     <!-- start page title -->
                     
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                                <h4 class="mb-sm-0">Quản Lý Người Dùng</h4>
                                

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                                        <li class="breadcrumb-item active">Người Dùng</li>
                                    </ol>
                                </div>

                            </div>

                    <!-- end page title -->
                    

                    <div class="row">
                        <div class="col">

                            <div class="h-100">
                               <!-- Striped Rows -->
                               <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Tài khoản người dùng</h4>

                                </div><!-- end card header -->

                                            <section class="content">
                                                <div class="container-fluid">
                                                    <div class="row">
                                                        <div class="col-4 d-flex justify-content-center align-items-center">
                                                            <img src="{{ $user->avatar ?? 'https://upload.wikimedia.org/wikipedia/commons/9/99/Sample_User_Icon.png' }}"
                                                                style="width:80%;border-radius:50%;" alt="Avatar người dùng"
                                                                onerror="this.onerror=null; this.src='https://upload.wikimedia.org/wikipedia/commons/9/99/Sample_User_Icon.png'">
                                                        </div>
                                                        <div class="col-8">
                                                            <table class="table table-borderless">
                                                                <tbody style="font-size: large;">
                                                                    <tr>
                                                                        <th>Họ tên:</th>
                                                                        <td>{{ $user->name }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Email:</th>
                                                                        <td>{{ $user->email }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Số điện thoại:</th>
                                                                        <td>{{ $user->phone }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Vai trò:</th>
                                                                        <td>{{ $user->role_id == 1 ? 'Admin' : 'Client' }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Ngày tạo:</th>
                                                                        <td>{{ $user->created_at }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Ngày cập nhật:</th>
                                                                        <td>{{ $user->updated_at }}</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="col-12">
                                                            <hr>
                                                            <h2>Lịch sử mua hàng</h2>
                                                            <div>
                                                                <table id="example1" class="table table-bordered table-striped">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>STT</th>
                                                                            <th>Mã đơn hàng</th>
                                                                            <th>Tên người nhân</th>
                                                                            <th>Số điện thoại</th>
                                                                            <th>Ngày đặt</th>
                                                                            <th>Tổng Tiền</th>
                                                                            <th>Trạng thái</th>
                                                                            <th>Thao tác</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php foreach ($listDonHang as $key => $donHang) : ?>
                                                                            <tr>
                                                                                <td><?= $key + 1 ?></td>
                                                                                <td><?= $donHang['ma_don_hang'] ?></td>
                                                                                <td><?= $donHang['ten_nguoi_nhan'] ?></td>
                                                                                <td><?= $donHang['sdt_nguoi_nhan'] ?></td>
                                                                                <td><?= $donHang['ngay_dat'] ?></td>
                                                                                <td><?= $donHang['tong_don_hang'] ?></td>
                                                                                <td>
                                                                                    <?php
                                                            if ($donHang["trang_thai_id"] >= 1 &&  $donHang["trang_thai_id"] <= 5){
                                                            ?>
                                                            <span class="badge bg-primary"><?= $donHang["ten_trang_thai"]?></span>
                                                            <?php
                                                            }elseif($donHang["trang_thai_id"] == 6 ){
                                                            ?>
                                                            <span class="badge bg-danger"><?= $donHang["ten_trang_thai"]?></span>
                                                            <?php } elseif($donHang["trang_thai_id"] == 7 ){
                                                            ?>
                                                            <span class="badge bg-warning"><?= $donHang["ten_trang_thai"]?></span>
                                                            <?php }else{
                                                            ?>
                                                            <span class="badge bg-success"><?= $donHang["ten_trang_thai"]?></span>
                                                            <?php } 
                                                            ?>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="btn-group">
                                                                                       <a style="margin-right:15px;" href="<?= '?act=chi-tiet-don-hang&id_don_hang=' . $donHang['id'] ?>" class="link-success fs-15"><i class="las la-eye"></i></a>
                                                <a href="<?=  '?act=form-sua-don-hang&id_don_hang=' . $donHang['id'] ?>"  class="link-success fs-15"><i class="ri-edit-2-line"></i></a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <?php endforeach ?>
                                                                        </tbody>
                                                                    </table>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <hr>
                                                            <h2>Lịch sử bình luận</h2>
                                                            <div>
                                                                <table id="example1" class="table table-striped table-nowrap align-middle mb-0">
                                                                <thead>
                                                                    <tr>
                                                                    <th>STT</th>
                                                                    <th>Sản Phẩm</th>
                                                                    <th>Ảnh Sản phẩm</th>
                                                                    <th>Nội dung</th>
                                                                    <th>Ngày bình luận</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php if (!empty($listBinhLuan)) : ?>
                                                                    <?php foreach ($listBinhLuan as $key => $binhluan) :?>
                                                                    <tr>
                                                                        <td><?= $key + 1?></td>
                                                                        
                                                                        <td><?= $binhluan['ten_san_pham']?></td>
                                                                        <td>
                                                                            <img style="border-radius:5px;" src="<?= BASE_URL . $binhluan['hinh_anh']?>" alt="chưa có ảnh" width="100px" height="100px">
                                                                        </td>
                                                                        <td><?= $binhluan['noi_dung']?></td>
                                                                        <td><?= $binhluan['ngay_binh_luan']?></td>
                                                                        
                                                                        
                                                                    </tr>
                                                                    <?php endforeach;?>
                                                                    <?php else : ?>
                                                                        <tr>
                                                                            <td colspan="6" class="text-center">Người dùng chưa có bình luận nào.</td>
                                                                        </tr>
                                                                    <?php endif; ?>
                                                                </tbody>
                                                                </table>
                                                            </div>
                                                            <hr>
                                                            <h2>Lịch sử đánh giá</h2>
                                                            <div>
                                                                <table id="example1" class="table table-striped table-nowrap align-middle mb-0">
                                                                <thead>
                                                                    <tr>
                                                                    <th>STT</th>
                                                                    <th>Sản Phẩm</th>
                                                                    <th>Ảnh Sản phẩm</th>
                                                                    <th>Đánh giá</th>
                                                                    <th>Ngày đánh giá</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php if (!empty($listDanhGia)) : ?>
                                                                    <?php foreach ($listDanhGia as $key => $danhgia) :?>
                                                                    <tr>
                                                                        <td><?= $key + 1?></td>
                                                                        
                                                                        <td><?= $danhgia['ten_san_pham']?></td>
                                                                        <td>
                                                                            <img style="border-radius:5px;" src="<?= BASE_URL . $danhgia['hinh_anh']?>" alt="chưa có ảnh" width="100px" height="100px">
                                                                        </td>
                                                                        <td><?= $danhgia['danh_gia']?></td>
                                                                        <td><?= $danhgia['ngay_danh_gia']?></td>
                                                                        
                                                                        
                                                                    </tr>
                                                                    <?php endforeach;?>
                                                                    <?php else : ?>
                                                                        <tr>
                                                                            <td colspan="6" class="text-center">Người dùng chưa có bình luận nào.</td>
                                                                        </tr>
                                                                    <?php endif; ?>
                                                                </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <!-- /.col -->
                                                    </div>
                                                    <!-- /.row -->
                                                </div>
                                                <!-- /.container-fluid -->
                                            </section>
                            </div><!-- end card -->

                            </div> <!-- end .h-100-->

                        </div> <!-- end col -->
                    </div>

                </div>
                <!-- container-fluid -->         
@endsection
