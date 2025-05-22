<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Author::query();

            if (!empty($request['search_name'])) {
                $query->where('name', 'like', '%' . $request['search_name'] . '%');
            }

            // Add option to show trashed authors
            if ($request->has('show_deleted')) {
                $query->withTrashed();
            }

            $authors = $query
                ->withCount('books')
                ->paginate(10);

            return view('admin.categories.authors', [
                'authors' => $authors,
                'searchName' => $request['search_name'] ?? '',
                'showDeleted' => $request->has('show_deleted')
            ]);
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Lỗi khi truy vấn tác giả. Vui lòng thử lại sau.');
        }
    }

    public function destroy(Author $author)
    {
        try {
            $author->delete();
            return back()->with('success', 'Tác giả đã được xóa tạm thời.');
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Không thể xóa tác giả. Vui lòng thử lại sau.');
        }
    }

    public function restore($id)
    {
        try {
            Author::withTrashed()->findOrFail($id)->restore();
            return back()->with('success', 'Tác giả đã được khôi phục.');
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Không thể khôi phục tác giả. Vui lòng thử lại sau.');
        }
    }    public function forceDelete($id)
    {
        try {
            $author = Author::withTrashed()->findOrFail($id);
            
            // Kiểm tra xem tác giả có sách nào không
            if ($author->books()->count() > 0) {
                return back()->with('error', 'Không thể xóa vĩnh viễn tác giả đang có sách trong hệ thống. Vui lòng gán sách cho tác giả khác hoặc xóa mềm.');
            }

            $author->forceDelete();
            return back()->with('success', 'Tác giả đã được xóa vĩnh viễn.');
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Không thể xóa vĩnh viễn tác giả. Vui lòng thử lại sau.');
        }
    }
}
