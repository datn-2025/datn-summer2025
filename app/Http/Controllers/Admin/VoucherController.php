<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VoucherRequest;
use App\Models\Author;
use App\Models\Book;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $query = Voucher::with([
            'conditions',
            'conditions.categoryCondition',
            'conditions.authorCondition',
            'conditions.brandCondition',
            'conditions.bookCondition'
        ])->withCount('appliedVouchers');

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

        // Filter by status
        if ($request->has('status')) {
            $now = now()->toDateString();
            switch ($request->status) {
                case 'active':
                    $query->where('status', 'active')
                          ->where('valid_from', '<=', $now)
                          ->where('valid_to', '>=', $now);
                    break;
                case 'expired':
                    $query->where(function($q) use ($now) {
                        $q->where('valid_to', '<', $now)
                          ->orWhere('status', 'inactive');
                    });
                    break;
                case 'upcoming':
                    $query->where('valid_from', '>', $now);
                    break;
            }
        }

        $vouchers = $query->latest()->paginate(10);
        $totalVouchers = Voucher::count();
        $activeVouchers = Voucher::where('status', 'active')
            ->where('valid_from', '<=', now())
            ->where('valid_to', '>=', now())
            ->count();
        $expiredVouchers = Voucher::where('valid_to', '<', now())->count();
        $usedVouchers = Voucher::whereHas('appliedVouchers')->count();
        return view('admin.vouchers.index', compact('vouchers', 'totalVouchers', 'activeVouchers', 'expiredVouchers', 'usedVouchers'));
    }

    public function create()
    {
        $books = Book::select('id', 'title')->get();
        $authors = Author::select('id', 'name')->get();
        $brands = Brand::select('id', 'name')->get();
        $categories = Category::select('id', 'name')->get();

        return view('admin.vouchers.create', compact('books', 'authors', 'brands', 'categories'));
    }

    public function store(VoucherRequest $request)
    {
        try {
            DB::beginTransaction();

            // Clean up numeric inputs
            $validated = $request->validated();
            $validated['max_discount'] = (float) str_replace(',', '', $validated['max_discount']);
            $validated['min_order_value'] = (float) str_replace(',', '', $validated['min_order_value']);

            // Create voucher
            $voucher = Voucher::create([
                'code' => strtoupper($validated['code']),
                'description' => $validated['description'] ?? null,
                'discount_percent' => $validated['discount_percent'],
                'max_discount' => $validated['max_discount'],
                'min_order_value' => $validated['min_order_value'],
                'quantity' => $validated['quantity'],
                'valid_from' => $validated['valid_from'],
                'valid_to' => $validated['valid_to'],
                'status' => $validated['status']
            ]);

            // Create voucher conditions
            if ($validated['condition_type'] === 'all') {
                $voucher->conditions()->create(['type' => 'all']);
            } else {
                $conditionIds = $request->input('condition_ids', []);
                if (!empty($conditionIds)) {
                    foreach ($conditionIds as $conditionId) {
                        $voucher->conditions()->create([
                            'type' => $validated['condition_type'],
                            'condition_id' => $conditionId
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()
                ->route('admin.vouchers.index')
                ->with('success', 'Thêm voucher thành công');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Voucher creation error: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi tạo voucher')
                         ->withInput();
        }
    }

    public function show(Voucher $voucher)
    {
        $voucher->load([
            'conditions.categoryCondition',
            'conditions.authorCondition',
            'conditions.brandCondition',
            'conditions.bookCondition'
        ]);

        $recentUsage = $voucher->appliedVouchers()
            ->with(['order.user'])
            ->latest()
            ->paginate(10);

        return view('admin.vouchers.show', compact('voucher', 'recentUsage'));
    }

    public function edit(Voucher $voucher)
    {
        $voucher->load([
            'conditions.categoryCondition',
            'conditions.authorCondition',
            'conditions.brandCondition',
            'conditions.bookCondition'
        ]);

        $categories = Category::select('id', 'name')->get();
        $authors = Author::select('id', 'name')->get();
        $brands = Brand::select('id', 'name')->get();
        $books = Book::select('id', 'title')->get();

        return view('admin.vouchers.edit', compact('voucher', 'categories', 'authors', 'brands', 'books'));
    }

    public function update(VoucherRequest $request, Voucher $voucher)
    {
        try {
            DB::beginTransaction();

            // Clean up numeric inputs
            $validated = $request->validated();
            $validated['max_discount'] = (float) str_replace(',', '', $validated['max_discount']);
            $validated['min_order_value'] = (float) str_replace(',', '', $validated['min_order_value']);

            // Update voucher
            $voucher->update([
                'description' => $validated['description'] ?? null,
                'discount_percent' => $validated['discount_percent'],
                'max_discount' => $validated['max_discount'],
                'min_order_value' => $validated['min_order_value'],
                'quantity' => $validated['quantity'],
                'valid_from' => $validated['valid_from'],
                'valid_to' => $validated['valid_to'],
                'status' => $validated['status']
            ]);

            // Delete existing conditions
            $voucher->conditions()->delete();

            // Create new conditions
            if ($validated['condition_type'] === 'all') {
                $voucher->conditions()->create(['type' => 'all']);
            } else {
                $conditionIds = $request->input('condition_ids', []);
                if (!empty($conditionIds)) {
                    foreach ($conditionIds as $conditionId) {
                        $voucher->conditions()->create([
                            'type' => $validated['condition_type'],
                            'condition_id' => $conditionId
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()
                ->route('admin.vouchers.index')
                ->with('success', 'Cập nhật voucher thành công');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Voucher update error: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi cập nhật voucher');
        }
    }

    public function destroy(Voucher $voucher)
    {
        try {
            if ($voucher->appliedVouchers()->exists()) {
                return back()->with('error', 'Không thể xóa voucher đã được sử dụng');
            }

            $voucher->delete();
            return redirect()
                ->route('admin.vouchers.index')
                ->with('success', 'Xóa voucher thành công');
        } catch (\Exception $e) {
            Log::error('Voucher deletion error: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi xóa voucher');
        }
    }

    public function trash()
    {
        $vouchers = Voucher::onlyTrashed()
            ->withCount('appliedVouchers')
            ->latest()
            ->paginate(10);
        return view('admin.vouchers.trash', compact('vouchers'));
    }

    public function restore($id)
    {
        try {
            $voucher = Voucher::onlyTrashed()->findOrFail($id);
            $voucher->restore();
            return redirect()
                ->route('admin.vouchers.trash')
                ->with('success', 'Khôi phục voucher thành công');
        } catch (\Exception $e) {
            Log::error('Voucher restoration error: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi khôi phục voucher');
        }
    }

    public function forceDelete($id)
    {
        try {
            $voucher = Voucher::onlyTrashed()->findOrFail($id);
            if ($voucher->appliedVouchers()->exists()) {
                return back()->with('error', 'Không thể xóa vĩnh viễn voucher đã được sử dụng');
            }
            $voucher->forceDelete();
            return redirect()
                ->route('admin.vouchers.trash')
                ->with('success', 'Xóa vĩnh viễn voucher thành công');
        } catch (\Exception $e) {
            Log::error('Voucher force deletion error: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi xóa vĩnh viễn voucher');
        }
    }

    public function getConditionOptions(Request $request)
    {
        $conditionType = $request->input('condition_type');
        $options = [];

        switch ($conditionType) {
            case 'book':
                $options = Book::select('id', 'title as name')->get();
                break;
            case 'category':
                $options = Category::select('id', 'name')->get();
                break;
            case 'author':
                $options = Author::select('id', 'name')->get();
                break;
            case 'brand':
                $options = Brand::select('id', 'name')->get();
                break;
        }

        return response()->json(['options' => $options]);
    }

    public function search(Request $request)
    {
        $term = $request->get('term');

        // Tìm kiếm trong sách
        $books = Book::where('title', 'like', "%{$term}%")
            ->select('id', 'title as name')
            ->get()
            ->map(function($item) {
                $item->type = 'Sách';
                return $item;
            });

        // Tìm kiếm trong tác giả
        $authors = Author::where('name', 'like', "%{$term}%")
            ->select('id', 'name')
            ->get()
            ->map(function($item) {
                $item->type = 'Tác giả';
                return $item;
            });

        // Tìm kiếm trong NXB
        $brands = Brand::where('name', 'like', "%{$term}%")
            ->select('id', 'name')
            ->get()
            ->map(function($item) {
                $item->type = 'NXB';
                return $item;
            });

        // Tìm kiếm trong danh mục
        $categories = Category::where('name', 'like', "%{$term}%")
            ->select('id', 'name')
            ->get()
            ->map(function($item) {
                $item->type = 'Danh mục';
                return $item;
            });

        // Gộp kết quả
        $results = $books->concat($authors)->concat($brands)->concat($categories);

        return response()->json($results);
    }
}
