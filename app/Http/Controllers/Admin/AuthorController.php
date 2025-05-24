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

            $authors = $query->withCount('books')->paginate(10);
            $trashCount = Author::onlyTrashed()->count();

            return view('admin.categories.authors', [
                'authors' => $authors,
                'searchName' => $request['search_name'] ?? '',
                'trashCount' => $trashCount
            ]);
        } catch (\Throwable $e) {
            report($e);
            toastr()->error('Lỗi khi truy vấn tác giả. Vui lòng thử lại sau.');
            return back();
        }
    }

    public function trash(Request $request)
    {
        try {
            $query = Author::onlyTrashed();
            
            if (!empty($request['search_name'])) {
                $query->where('name', 'like', '%' . $request['search_name'] . '%');
            }

            $deletedAuthors = $query->withCount('books')->paginate(10);

            return view('admin.categories.authors-trash', [
                'deletedAuthors' => $deletedAuthors,
                'searchName' => $request['search_name'] ?? ''
            ]);
        } catch (\Throwable $e) {
            report($e);
            toastr()->error('Lỗi khi truy vấn tác giả đã xóa. Vui lòng thử lại sau.');
            return back();
        }
    }

    public function destroy(Author $author)
    {
        try {
            // Kiểm tra xem tác giả có sách nào không
            if ($author->books()->count() > 0) {
                toastr()->error('Không thể xóa tác giả đang có sách trong hệ thống.');
                return back();
            }
            
            $author->delete();
            toastr()->success('Tác giả đã được xóa tạm thời thành công.');
            return back();
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Không thể xóa tác giả. Vui lòng thử lại sau.');
        }
    }

    public function restore($id)
    {
        try {
            Author::withTrashed()->findOrFail($id)->restore();
            toastr()->success('Tác giả đã được khôi phục thành công.');
            return back();
        } catch (\Throwable $e) {
            report($e);
            toastr()->error('Không thể khôi phục tác giả. Vui lòng thử lại sau.');
            return back();
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
