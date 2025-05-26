<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaymentMethodController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $query = PaymentMethod::query();
        
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }
        
        $paymentMethods = $query->latest()->paginate(10);
        $trashCount = PaymentMethod::onlyTrashed()->count();
        
        // Giữ lại tham số tìm kiếm khi phân trang
        if ($request->has('search')) {
            $paymentMethods->appends(['search' => $search]);
        }
        
        return view('admin.payment-methods.index', compact('paymentMethods', 'trashCount'));
    }
    public function create()
    {
        return view('admin.payment-methods.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:payment_methods',
            'description' => 'nullable|string',
            'is_active' => 'boolean'    
        ], [
            'name.required' => 'Tên phương thức thanh toán là bắt buộc',
            'name.string' => 'Tên phương thức thanh toán phải là chuỗi',
            'name.max' => 'Tên phương thức thanh toán không được vượt quá 100 ký tự',
            'name.unique' => 'Tên phương thức thanh toán đã tồn tại',
            'is_active.boolean' => 'Trạng thái không hợp lệ'
        ]);

        PaymentMethod::create(array_merge($validated, [
            'is_active' => $request->has('is_active')
        ]));

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Phương thức thanh toán đã được thêm thành công');
    }

}