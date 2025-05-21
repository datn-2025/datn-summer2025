<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Brand;
use App\Models\Category;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query()
            ->with([
                'category:id,name',
                'author:id,name',
                'brand:id,name', 
                'formats:id,book_id,format_name,price,discount,stock',
                'images:id,book_id,image_url'
            ])
            ->withSum(['formats as total_stock' => function($query) {
                $query->whereIn('format_name', ['Bìa mềm', 'Bìa cứng']);
            }], 'stock');

        // Tìm kiếm theo tiêu đề sách hoặc mã ISBN
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('isbn', 'like', '%' . $request->search . '%');
            });
        }

        // Lọc theo danh mục
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Lọc theo thương hiệu (brand)
        if ($request->filled('brand')) {
            $query->where('brand_id', $request->brand);
        }

        // Lọc theo tác giả
        if ($request->filled('author')) {
            $query->where('author_id', $request->author);
        }

        // Lọc theo khoảng trang
        if ($request->filled('min_pages')) {
            $query->where('page_count', '>=', $request->min_pages);
        }
        if ($request->filled('max_pages')) {
            $query->where('page_count', '<=', $request->max_pages);
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo khoảng giá của format
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $query->whereHas('formats', function($q) use ($request) {
                if ($request->filled('min_price')) {
                    $q->where('price', '>=', $request->min_price);
                }
                if ($request->filled('max_price')) {
                    $q->where('price', '<=', $request->max_price);
                }
            });
        }

        // Sắp xếp theo số trang hoặc giá
        if ($request->filled('sort')) {
            match ($request->sort) {
                'pages_asc' => $query->orderBy('page_count', 'asc'),
                'pages_desc' => $query->orderBy('page_count', 'desc'),
                'price_asc' => $query->withMin('formats as min_price', 'price')
                                   ->orderBy('min_price', 'asc'),
                'price_desc' => $query->withMax('formats as max_price', 'price')
                                    ->orderBy('max_price', 'desc'),
                default => $query->orderBy('id', 'desc')
            };
        } else {
            $query->orderBy('id', 'desc');
        }

        $books = $query->paginate(10)->withQueryString();
        $categories = Category::all();
        $brands = Brand::all();
        $authors = Author::all();
        return view('admin.books.index', compact('books', 'categories', 'brands', 'authors'));
    }
}
