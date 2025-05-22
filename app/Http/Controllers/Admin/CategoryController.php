<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Category::query();

            if (!empty($request['search_name_category'])) {
                $query->where('name', 'like', '%' . $request['search_name_category'] . '%');
            }

            $categories = $query
                ->withCount('books')
                ->paginate(10);

            return view('admin.categories.index', [
                'categories' => $categories,
                'searchName' => $request['search_name_category'] ?? ''
            ]);
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Lỗi khi truy vấn danh mục. Vui lòng thử lại sau.');
        }
    }
}
