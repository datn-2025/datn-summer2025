<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $query = Voucher::withCount(['appliedVouchers as used_count']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Filter by discount range
        if ($request->filled('min_discount') && $request->filled('max_discount')) {
            $query->whereBetween('discount_percent', [$request->min_discount, $request->max_discount]);
        }

        // Filter by validity period
        if ($request->filled('date_from')) {
            $query->where('valid_from', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('valid_to', '<=', $request->date_to);
        }

        // Filter by status (existing code)
        if ($request->has('status')) {
            $now = now()->toDateString();
            if ($request->status == 'active') {
                $query->where('status', 'active')
                      ->where('valid_from', '<=', $now)
                      ->where('valid_to', '>=', $now);
            } elseif ($request->status == 'expired') {
                $query->where('valid_to', '<', $now);
            } elseif ($request->status == 'upcoming') {
                $query->where('valid_from', '>', $now);
            }
        }

        $vouchers = $query->latest()->paginate(10);
        return view('admin.vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        return view('admin.vouchers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:100|unique:vouchers,code',
            'description' => 'nullable|string',
            'discount_percent' => 'required|numeric|min:0|max:100',
            'max_discount' => 'required|numeric|min:0',
            'min_order_value' => 'required|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after_or_equal:valid_from',
            'quantity' => 'required|integer|min:1',
            'status' => ['required', Rule::in(['active', 'inactive'])]
        ]);

        Voucher::create($validated);
        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Thêm voucher thành công');
    }
    public function show(Voucher $voucher)
{
    $voucher->loadCount('appliedVouchers');
    $recentUsage = $voucher->appliedVouchers()
        ->with('order.user')
        ->latest()
        ->take(10)
        ->get();
        
    return view('admin.vouchers.show', compact('voucher', 'recentUsage'));
}
    public function edit(Voucher $voucher)
    {
        return view('admin.vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:100', Rule::unique('vouchers')->ignore($voucher)],
            'description' => 'nullable|string',
            'discount_percent' => 'required|numeric|min:0|max:100',
            'max_discount' => 'required|numeric|min:0',
            'min_order_value' => 'required|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after_or_equal:valid_from',
            'quantity' => 'required|integer|min:1',
            'status' => ['required', Rule::in(['active', 'inactive'])]
        ]);

        $voucher->update($validated);
        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Cập nhật voucher thành công');
    }
 // Add restore method
    public function restore($id)
    {
        $voucher = Voucher::withTrashed()->findOrFail($id);
        $voucher->restore();
        
        return back()->with('success', 'Khôi phục voucher thành công');
    }

    // Add forceDelete method
    public function forceDelete($id)
    {
        $voucher = Voucher::withTrashed()->findOrFail($id);
        $voucher->forceDelete();
        return back()->with('success', 'Xóa vĩnh viễn voucher thành công');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return back()->with('success', 'Xóa voucher thành công');
    }

    public function trash()
{
    $trashedVouchers = Voucher::onlyTrashed()
        ->withCount(['appliedVouchers as used_count'])
        ->latest()
        ->paginate(10);
    
    return view('admin.vouchers.trash', compact('trashedVouchers'));
}


}