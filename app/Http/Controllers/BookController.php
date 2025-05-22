<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    public function index(Request $request, $categoryId = null)
    {
        // Lấy tất cả danh mục dùng cho sidebar
        $categories = DB::table('categories')->get();

        // Lấy danh mục hiện tại nếu có
        $category = null;
        if ($categoryId) {
            $category = DB::table('categories')->where('id', $categoryId)->first();
            if (!$category) {
                abort(404, "Category not found");
            }
        }

        // Lấy filter từ query string
        $authorIds = $request->input('authors', []);
        $brandIds = $request->input('brands', []);
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $minRating = $request->input('min_rating');
        $sort = $request->input('sort', 'newest');

        // Chuyển filter chuỗi sang mảng nếu cần
        if (!is_array($authorIds) && $authorIds !== null) {
            $authorIds = explode(',', $authorIds);
        }
        if (!is_array($brandIds) && $brandIds !== null) {
            $brandIds = explode(',', $brandIds);
        }

        // Tạo query lấy sách
        $booksQuery = DB::table('books')
            ->join('authors', 'books.author_id', '=', 'authors.id')
            ->join('brands', 'books.brand_id', '=', 'brands.id')
            ->join('book_formats', 'books.id', '=', 'book_formats.book_id')
            ->leftJoin('reviews', 'books.id', '=', 'reviews.book_id')
            ->select(
                'books.id',
                'books.title',
                'books.slug',
                'books.cover_image',
                'authors.name as author_name',
                'brands.name as brand_name',
                DB::raw('MIN(book_formats.price) as min_price'),
                DB::raw('MAX(book_formats.price) as max_price'),
                DB::raw('AVG(reviews.rating) as avg_rating')
            )
            ->when($categoryId, fn($query) => $query->where('books.category_id', $categoryId))
            ->groupBy('books.id', 'books.title', 'books.slug', 'books.cover_image', 'authors.name', 'brands.name');

        // Áp dụng filter tác giả
        if (!empty($authorIds)) {
            $booksQuery->whereIn('books.author_id', $authorIds);
        }

        // Áp dụng filter thương hiệu
        if (!empty($brandIds)) {
            $booksQuery->whereIn('books.brand_id', $brandIds);
        }

        // Áp dụng filter giá min/max
        if ($minPrice !== null) {
            $booksQuery->havingRaw('MIN(book_formats.price) >= ?', [$minPrice]);
        }
        if ($maxPrice !== null) {
            $booksQuery->havingRaw('MAX(book_formats.price) <= ?', [$maxPrice]);
        }

        // Thêm logic để xử lý các khoảng giá cố định
        $priceRanges = [
            '1-10' => [1, 10],
            '10-50' => [10, 50],
            '50-100' => [50, 100],
            '100+' => [100, null],
        ];

        $selectedPriceRange = $request->input('price_range');
        if ($selectedPriceRange && isset($priceRanges[$selectedPriceRange])) {
            [$minPrice, $maxPrice] = $priceRanges[$selectedPriceRange];
            if ($minPrice !== null) {
                $booksQuery->havingRaw('MIN(book_formats.price) >= ?', [$minPrice]);
            }
            if ($maxPrice !== null) {
                $booksQuery->havingRaw('MAX(book_formats.price) <= ?', [$maxPrice]);
            }
        }

        // Áp dụng filter rating
        if ($minRating !== null) {
            $booksQuery->havingRaw('AVG(reviews.rating) >= ?', [$minRating]);
        }

        // Add search functionality
        if ($search = request('search')) {
            $booksQuery->where('books.title', 'like', "%$search%")
                       ->orWhere('authors.name', 'like', "%$search%");
        }

        // Xử lý sắp xếp
        switch ($sort) {
            case 'price_asc':
                $booksQuery->orderBy('min_price', 'asc');
                break;
            case 'price_desc':
                $booksQuery->orderBy('min_price', 'desc');
                break;
            case 'name_asc':
                $booksQuery->orderBy('books.title', 'asc');
                break;
            case 'name_desc':
                $booksQuery->orderBy('books.title', 'desc');
                break;
            case 'newest':
            default:
                $booksQuery->orderBy('books.created_at', 'desc');
                break;
        }

        // Phân trang, giữ query string
        $books = $booksQuery->paginate(12)->withQueryString();

        // Lấy bộ lọc tác giả
        $authors = DB::table('authors')
            ->join('books', 'authors.id', '=', 'books.author_id')
            ->when($categoryId, fn($query) => $query->where('books.category_id', $categoryId))
            ->select('authors.id', 'authors.name')
            ->distinct()
            ->get();

        // Lấy bộ lọc thương hiệu
        $brands = DB::table('brands')
            ->join('books', 'brands.id', '=', 'books.brand_id')
            ->when($categoryId, fn($query) => $query->where('books.category_id', $categoryId))
            ->select('brands.id', 'brands.name')
            ->distinct()
            ->get();

        // Trả về view với dữ liệu đầy đủ
        return view('books.index', [
            'categories' => $categories,
            'category' => $category,
            'books' => $books,
            'authors' => $authors,
            'brands' => $brands,
            'filters' => [
                'authors' => $authorIds,
                'brands' => $brandIds,
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
                'min_rating' => $minRating,
                'sort' => $sort,
            ],
        ]);
    }
}
