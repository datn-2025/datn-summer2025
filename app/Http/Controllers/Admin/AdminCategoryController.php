<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AdminCategoryController extends Controller
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
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:categories,name',
                'not_regex:/<.*?>/i'
            ],
            'description' => 'nullable|string|max:800',
            'image'       => 'nullable|image|mimetypes:image/jpeg,image/png,image/jpg,image/gif|max:2048',
        ], [
            'name.required'     => 'Tên danh mục không được để trống.',
            'name.max'          => 'Tên danh mục không được vượt quá 255 ký tự.',
            'name.unique'       => 'Tên danh mục đã tồn tại.',
            'name.not_regex'    => 'Tên danh mục không được chứa thẻ HTML.',
            'description.max'   => 'Mô tả không được vượt quá 800 ký tự.',
            'image.image'       => 'File tải lên phải là hình ảnh.',
            'image.mimetypes'   => 'Ảnh phải có định dạng jpeg, png, jpg, gif.',
            'image.max'         => 'Ảnh không được vượt quá 2MB.',
        ]);

        try {
            $slug = Str::slug($validated['name']);

            if (Category::where('slug', $slug)->exists()) {
                $slug .= '-' . Str::random(6);
            }
            $categoryData = [
                'name'          => $validated['name'],
                'slug'          => $slug,
                'description'   => $validated['description'] ?? null,
            ];

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = uniqid('cat_', true);
                $path = $file->storeAs('images/admin/categories', $filename, 'public');
                $categoryData['image'] = $path;
            }

            Category::create($categoryData);

            Toastr::success('Thêm mới danh mục thành công!');
            return redirect()->route('admin.categories.index');
        } catch (\Throwable $e) {
            Log::error('Lỗi khi thêm danh mục: ' . $e->getMessage());
            Toastr::error('Đã xảy ra lỗi khi thêm danh mục. Vui lòng thử lại sau.');
            return back()->withInput();
        }
    }

    public function edit($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)->first();

        if (!$category) {
            Toastr::info('Danh mục không còn tồn tại hoặc đã được cập nhật.');
            return redirect()->route('admin.categories.index');
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($category->id),
                'not_regex:/<.*?>/i'
            ],
            'description'   => 'nullable|string|max:800',
            'image'         => 'nullable|image|mimetypes:image/jpeg,image/png,image/jpg,image/gif|max:2048',
            'remove_image'  => 'nullable|boolean'
        ], [
            'name.required'     => 'Tên danh mục không được để trống.',
            'name.max'          => 'Tên danh mục không được vượt quá 255 ký tự.',
            'name.unique'       => 'Tên danh mục đã tồn tại.',
            'name.not_regex'    => 'Tên danh mục không được chứa thẻ HTML.',
            'description.max'   => 'Mô tả không được vượt quá 800 ký tự.',
            'image.image'       => 'File tải lên phải là hình ảnh.',
            'image.mimetypes'   => 'Ảnh phải có định dạng: jpeg, png, jpg, gif.',
            'image.max'         => 'Ảnh không được vượt quá 2MB.',
        ]);

        try {
            $categoryData = [
                'name'          => $validated['name'],
                'description'   => $validated['description'] ?? null,
            ];

            // Chỉ đổi slug nếu tên thay đổi
            if ($validated['name'] !== $category->name) {
                $baseSlug = Str::slug($validated['name']);
                $newSlug = $baseSlug;

                if (
                    Category::where('slug', $baseSlug)
                    ->whereKeyNot($category->id)
                    ->exists()
                ) {
                    $newSlug .= '-' . Str::random(6);
                }
                $categoryData['slug'] = $newSlug;
            }

            // Xử lý ảnh
            $hasImageChanged = false;

            if (($request->hasFile('image') || $request->boolean('remove_image')) && $category->image) {
                Storage::disk('public')->delete($category->image);
                $categoryData['image'] = null;
                $hasImageChanged = true;
            }

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = uniqid('cat_', true) . '.' . $file->extension();
                $path = $file->storeAs('images/admin/categories', $filename, 'public');
                $categoryData['image'] = $path;
                $hasImageChanged = true;
            }

            // Kiểm tra thay đổi
            $original = $category->only(['name', 'description']);
            $incoming = Arr::only($categoryData, ['name', 'description']);

            if ($original === $incoming && !$hasImageChanged) {
                Toastr::info('Không có thay đổi nào cho danh mục sách.');
                return redirect()->route('admin.categories.index');
            }

            $category->update($categoryData);

            Toastr::success('Cập nhật danh mục thành công!');
            return redirect()->route('admin.categories.index');
        } catch (\Throwable $e) {
            Log::error('Lỗi khi cập nhật danh mục: ' . $e->getMessage());
            Toastr::error('Đã xảy ra lỗi khi cập nhật danh mục. Vui lòng thử lại sau.');
            return back()->withInput();
        }
    }

    public function trash(Request $request)
    {
        try {
            Log::info('Bắt đầu hàm trash');
            $query = Category::onlyTrashed();
            Log::info('Sau onlyTrashed');

            if (!empty($request['search_name_category'])) {
                $query->where('name', 'like', '%' . $request['search_name_category'] . '%');
            }

            $deletedCategories = $query->withCount('books')->paginate(10);
            Log::info('Sau withCount và paginate', ['count' => $deletedCategories->count()]);

            return view('admin.categories.categories-trash', [
                'deletedCategories' => $deletedCategories,
                'searchName' => $request['search_name'] ?? ''
            ]);
        } catch (\Throwable $e) {
            Log::error('Lỗi trong hàm trash: ' . $e->getMessage(), [
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
