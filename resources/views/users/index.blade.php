@extends('layouts.backend')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <h1>Danh sách người dùng</h1>
    
    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-4 d-flex" role="search">
        <input type="text" name="search" class="form-control me-2" placeholder="Tìm theo tên, email hoặc số điện thoại" value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tên</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-4">
    {{ $users->links('pagination::bootstrap-5') }} 
</div>
</div>
@endsection
