<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class TopSellingBooksReport extends Component
{
    public $books = [];

    public function mount()
    {
        $this->books = DB::table('order_items')
            ->join('books', 'order_items.book_id', '=', 'books.id')
            ->select('books.title', 'books.cover_image', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('books.id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.top-selling-books-report');
    }
}
