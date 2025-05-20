<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::select('id', 'name', 'avatar', 'email', 'phone', 'role_id', 'status');
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%")
                  ->orWhere('role_id', 'like', "%$search%")
                  ->orWhere('status', 'like', "%$search%");
            });
        }
        $users = $query->paginate(10)->appends($request->only('search'));
        return view('users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        // Lấy lịch sử mua hàng của user (ví dụ: các đơn hàng liên quan)
        $listDonHang = $user->orders()->with('status')->get();
        // Nếu có các bảng khác như bình luận, đánh giá thì lấy tương tự
        // $listBinhLuan = $user->comments()->with('product')->get();
        // $listDanhGia = $user->reviews()->with('product')->get();
        return view('users.show', compact('user', 'listDonHang'));
    }
}
