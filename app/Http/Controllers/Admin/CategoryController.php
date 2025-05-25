<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Category::query()->withCount('books');

            if (!empty($request['search_name_category'])) {
                $query->where('name', 'like', '%' . $request['search_name_category'] . '%');
            }

            $categories = $query->orderBy('created_at', 'desc')->paginate(10);
            $trashCount = Category::onlyTrashed()->count();

            return view('admin.categories.index', [
                'categories' => $categories,
                'searchName' => $request['search_name_category'] ?? '',
                'trashCount' => $trashCount
            ]);
        } catch (\Throwable $e) {
            Log::error('Lỗi khi lấy danh sách danh mục: ' . $e->getMessage());
            return back()->with('error', 'Lỗi khi truy vấn danh mục. Vui lòng thử lại sau.');
        }
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Tên danh mục không được để trống.',
            'name.max' => 'Tên danh mục không được vượt quá 255 ký tự.',
            'name.unique' => 'Tên danh mục đã tồn tại.',
            'image.image' => 'Ảnh phải là file hình ảnh.',
            'image.mimes' => 'Ảnh phải có định dạng jpeg, png, jpg, gif.',
            'image.max' => 'Ảnh không được vượt quá 2MB.',
        ]);

        try {
            $categoryData = [
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name'])
            ];

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('images/admin/categories', 'public');
                $categoryData['image'] = $path;
            }

            Category::create($categoryData);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Thêm mới danh mục thành công!');
        } catch (\Throwable $e) {
            Log::error('Lỗi khi thêm danh mục: ' . $e->getMessage());
            return back()->with('error', 'Đã xảy ra lỗi khi thêm danh mục. Vui lòng thử lại sau.')
                ->withInput();
        }
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_image' => 'nullable|boolean'
        ], [
            'name.required' => 'Tên danh mục không được để trống.',
            'name.max' => 'Tên danh mục không được vượt quá 255 ký tự.',
            'name.unique' => 'Tên danh mục đã tồn tại.',
            'image.image' => 'File tải lên phải là hình ảnh.',
            'image.mimes' => 'Ảnh phải có định dạng: jpeg, png, jpg, gif.',
            'image.max' => 'Ảnh không được vượt quá 2MB.',
        ]);

        try {
            $categoryData = [
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name'])
            ];

            if (($request->hasFile('image') || $request->boolean('remove_image')) && $category->image) {
                Storage::disk('public')->delete($category->image);
                $categoryData['image'] = null;
            }
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('images/admin/categories', 'public');
                $categoryData['image'] = $path;
            }

            $category->update($categoryData);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Cập nhật danh mục thành công!');
        } catch (\Throwable $e) {
            Log::error('Lỗi khi cập nhật danh mục: ' . $e->getMessage());
            return back()->with('error', 'Đã xảy ra lỗi khi cập nhật danh mục.')
                ->withInput();
        }
    }

    // public function softDelete($id)
    // {
    //     try {
    //         $category = Category::findOrFail($id);
    //         $category->delete();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Đã chuyển danh mục vào thùng rác!'
    //         ]);
    //     } catch (\Exception $e) {
    //         report($e);
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Không thể xóa danh mục này.'
    //         ], 500);
    //     }
    // }

    // public function forceDelete($id)
    // {
    //     try {
    //         $category = Category::withTrashed()->findOrFail($id);

    //         if ($category->books()->exists()) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Không thể xóa danh mục đang có sách.'
    //             ], 422);
    //         }

    //         if ($category->image) {
    //             Storage::disk('public')->delete($category->image);
    //         }

    //         $category->forceDelete();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Đã xóa vĩnh viễn danh mục!'
    //         ]);
    //     } catch (\Exception $e) {
    //         report($e);
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Đã xảy ra lỗi khi xóa danh mục.'
    //         ], 500);
    //     }
    // }

    // public function trash(Request $request)
    // {
    //     try {
    //         $query = Category::onlyTrashed();
            
    //         if (!empty($request['search_name'])) {
    //             $query->where('name', 'like', '%' . $request['search_name'] . '%');
    //         }

    //         $deletedCategories = $query->withCount('books')->paginate(10);
    //         // Giữ lại tham số tìm kiếm khi phân trang
    //         $deletedCategories->appends(['search_name' => $request['search_name']]);

    //         return view('admin.categories.categories-trash', [
    //             'deletedCategories' => $deletedCategories,
    //             'searchName' => $request['search_name'] ?? ''
    //         ]);
    //     } catch (\Throwable $e) {
    //         report($e);
    //         toastr()->error('Lỗi khi truy vấn danh mục đã xóa. Vui lòng thử lại sau.');
    //         return back();
    //     }
    // }

    public function trash(Request $request)
    {
        try {
            \Log::info('Bắt đầu hàm trash');
            $query = Category::onlyTrashed();
            \Log::info('Sau onlyTrashed');
            
            if (!empty($request['search_name'])) {
                $query->where('name', 'like', '%' . $request['search_name'] . '%');
            }

            $deletedCategories = $query->withCount('books')->paginate(10);
            \Log::info('Sau withCount và paginate', ['count' => $deletedCategories->count()]);

            return view('admin.categories.categories-trash', [
                'deletedCategories' => $deletedCategories,
                'searchName' => $request['search_name'] ?? ''
            ]);
        } catch (\Throwable $e) {
            \Log::error('Lỗi trong hàm trash: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            report($e);
            toastr()->error('Lỗi khi truy vấn danh mục đã xóa. Vui lòng thử lại sau: ' . $e->getMessage());
            return back();
        }
    }

    public function destroy(Category $category)
    {
        try {
            // Kiểm tra xem tác giả có sách nào không
            // if ($category->books()->count() > 0) {
            //     toastr()->error('Không thể xóa danh mục đang có sách trong hệ thống.');
            //     return back();
            // }
            
            $category->delete();
            toastr()->success('Danh mục đã được xóa tạm thời thành công.');
            return back();
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Không thể xóa danh mục. Vui lòng thử lại sau.');
        }
    }

    public function restore($id)
    {
        try {
            Category::withTrashed()->findOrFail($id)->restore();
            toastr()->success('Danh mục đã được khôi phục thành công.');
            return back();
        } catch (\Throwable $e) {
            report($e);
            toastr()->error('Không thể khôi phục danh mục. Vui lòng thử lại sau.');
            return back();
        }
    }    
    public function forceDelete($id)
    {
        try {
            $category = Category::withTrashed()->findOrFail($id);
            
            // Kiểm tra xem danh mục có sách nào không
            if ($category->books()->count() > 0) {
                return back()->with('error', 'Không thể xóa vĩnh viễn danh mục đang có sách trong hệ thống. Vui lòng gán sách cho danh mục khác hoặc xóa mềm.');
            }

            $category->forceDelete();
            return back()->with('success', 'Danh mục đã được xóa vĩnh viễn.');
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Không thể xóa vĩnh viễn danh mục. Vui lòng thử lại sau.');
        }
    }
}
