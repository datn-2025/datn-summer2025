<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q');
        $searchType = $request->get('type', 'all'); // all, title, author, publisher
        $perPage = 24; // Fixed per page
        
        if (empty($query)) {
            return view('search.results', [
                'books' => collect(),
                'query' => '',
                'searchType' => $searchType,
                'totalResults' => 0
            ]);
        }

        $books = $this->searchBooks($query, $searchType, $perPage);
        
        return view('search.results', [
            'books' => $books,
            'query' => $query,
            'searchType' => $searchType,
            'totalResults' => $books->total()
        ]);    }

    private function searchBooks($query, $searchType, $perPage)
    {
        $booksQuery = Book::with(['author', 'brand', 'category', 'formats'])
            ->where('status', 'available');

        // Apply text search based on type
        if (!empty($query)) {
            switch ($searchType) {
                case 'title':
                    $booksQuery->where('title', 'LIKE', "%{$query}%");
                    break;
                    
                case 'author':
                    $booksQuery->whereHas('author', function (Builder $q) use ($query) {
                        $q->where('name', 'LIKE', "%{$query}%");
                    });
                    break;
                    
                case 'publisher':
                    $booksQuery->whereHas('brand', function (Builder $q) use ($query) {
                        $q->where('name', 'LIKE', "%{$query}%");
                    });
                    break;
                    
                default: // all
                    $booksQuery->where(function (Builder $q) use ($query) {
                        $q->where('title', 'LIKE', "%{$query}%")
                          ->orWhere('description', 'LIKE', "%{$query}%")
                          ->orWhere('isbn', 'LIKE', "%{$query}%")
                          ->orWhereHas('author', function (Builder $subQ) use ($query) {
                              $subQ->where('name', 'LIKE', "%{$query}%");
                          })
                          ->orWhereHas('brand', function (Builder $subQ) use ($query) {
                              $subQ->where('name', 'LIKE', "%{$query}%");
                          });
                    });                    break;
            }
        }

        return $booksQuery->orderBy('title', 'asc')
                         ->paginate($perPage)
                         ->appends(request()->query());
    }

    public function suggestions(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $suggestions = [];

        // Tìm kiếm sách theo tên
        $books = Book::where('title', 'LIKE', "%{$query}%")
                    ->where('status', 'available')
                    ->limit(5)
                    ->get(['id', 'title']);
        
        foreach ($books as $book) {
            $suggestions[] = [
                'type' => 'book',
                'value' => $book->title,
                'label' => $book->title,
                'id' => $book->id            ];
        }

        // Tìm kiếm tác giả
        $authors = Author::where('name', 'LIKE', "%{$query}%")
                        ->limit(3)
                        ->get(['id', 'name']);
        
        foreach ($authors as $author) {
            $suggestions[] = [
                'type' => 'author',
                'value' => $author->name,
                'label' => "Tác giả: " . $author->name,
                'id' => $author->id            ];
        }

        // Tìm kiếm nhà xuất bản
        $publishers = Brand::where('name', 'LIKE', "%{$query}%")
                          ->limit(3)
                          ->get(['id', 'name']);
        
        foreach ($publishers as $publisher) {
            $suggestions[] = [
                'type' => 'publisher',
                'value' => $publisher->name,
                'label' => "Nhà xuất bản: " . $publisher->name,
                'id' => $publisher->id
            ];
        }

        return response()->json($suggestions);
    }
}
