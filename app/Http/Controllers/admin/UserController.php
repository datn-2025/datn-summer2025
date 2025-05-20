<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role')->select('id', 'name', 'avatar', 'email', 'phone', 'role_id', 'status');

        // Tìm kiếm theo text
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%");
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->paginate(10)->appends($request->only(['search', 'status']));

        return view('users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::with('role')->findOrFail($id);
        // Lấy lịch sử mua hàng của user, join với trạng thái đơn hàng và thông tin người dùng
        $listDonHang = $user->orders()
            ->with(['orderStatus', 'address', 'paymentStatus'])
            ->get()
            ->map(function ($order) {
                return (object)[
                    'id' => $order->id,
                    'order_code' => $order->id, // Sử dụng id làm mã đơn hàng
                    'shipping_name' => $order->address->recipient_name ?? 'N/A',
                    'shipping_phone' => $order->address->phone ?? 'N/A',
                    'created_at' => $order->created_at->format('d/m/Y H:i'),
                    'total_amount' => $order->total_amount,
                    'orderStatus' => $order->orderStatus,
                    'paymentStatus' => $order->paymentStatus
                ];
            });
        return view('users.show', compact('user', 'listDonHang'));
    }


}
