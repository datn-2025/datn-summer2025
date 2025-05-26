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
        
        // Giữ lại tham số tìm kiếm khi phân trang
        if ($request->has('search')) {
            $paymentMethods->appends(['search' => $search]);
        }
        
        return view('admin.payment-methods.index', compact('paymentMethods'));
    }

}