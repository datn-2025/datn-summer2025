<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Book;
use App\Models\NewsArticle;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    //dau noi di
    {
        $books = Book::with('category', 'formats', 'images')->latest()->take(8)->get();

        // $categories = Category::whereHas('books')->with('books')->take(3)->get();
        $categories = Category::withCount('books')
            ->orderBy('books_count', 'desc')
            ->with(['books' => function ($query) {
                $query->with(['formats', 'images'])->latest()->take(8); // Lấy tối đa 8 sản phẩm/danh mục
            }])
            ->take(3)
            ->get();


        $featuredBooks = Book::select('books.*',)
            ->join('book_formats', 'book_formats.book_id', '=', 'books.id')
            ->with(['formats' => function ($q) {
                $q->orderByDesc('price');
            }, 'author', 'images'])
            ->orderBy(DB::raw('(SELECT MAX(price) FROM book_formats WHERE book_formats.book_id = books.id)'), 'desc')
            ->groupBy('books.id')
            ->take(4)
            ->get();

        $latestBooks = Book::with(['author', 'images'])->latest()->take(4)->get();
        $bestReviewedBooks = Book::with(['author', 'images'])->orderBy('page_count', 'desc')->take(4)->get();
        $saleBooks = Book::select('books.*')
            ->join('book_formats', 'book_formats.book_id', '=', 'books.id')
            ->with(['formats' => function ($q) {
                $q->orderByDesc('discount');
            }, 'author', 'images'])
            ->orderBy(DB::raw('(SELECT MAX(discount) FROM book_formats WHERE book_formats.book_id = books.id)'), 'desc')
            ->groupBy('books.id')
            ->take(4)
            ->get();

        // Lấy 10 đánh giá mới nhất
        $reviews = Review::with('user')->latest()->take(10)->get();
        $articles = NewsArticle::latest()->take(4)->get();
        return view('clients.home', compact('books', 'categories', 'featuredBooks', 'latestBooks', 'bestReviewedBooks', 'saleBooks', 'reviews', 'articles'));
    }
}
