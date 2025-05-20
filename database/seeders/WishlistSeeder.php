<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;

class WishlistSeeder extends Seeder
{    public function run()
    {
        $users = User::whereHas('role', function($query) {
            $query->where('name', 'User');
        })->get();

        foreach ($users as $user) {
            // Mỗi user có 0-5 sách trong wishlist
            $books = Book::inRandomOrder()->limit(rand(0, 2))->get();
            
            foreach ($books as $book) {
                Wishlist::factory()->create([
                    'user_id' => $user->id,
                    'book_id' => $book->id
                ]);
            }
        }
    }
}
