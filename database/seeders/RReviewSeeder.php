<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\User;
use App\Models\Book;
use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RReviewSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $books = Book::all();
        $orders = Order::all();

        if ($users->isEmpty() || $books->isEmpty() || $orders->isEmpty()) {
            $this->command->error('Vui lòng seed đầy đủ users, books, orders trước khi seed reviews!');
            return;
        }

        $sampleComments = [
            'Sách rất hay, nội dung bổ ích.',
            'Chất lượng giấy in tốt, đóng gói cẩn thận.',
            'Hơi thất vọng, sách bị móp nhẹ.',
            'Nội dung dễ hiểu, phù hợp với người mới bắt đầu.',
            'Giao hàng nhanh, đóng gói chắc chắn.',
            'Tác phẩm kinh điển, nên đọc.',
        ];

        $reviewCount = 0;

        foreach ($users as $user) {
            foreach ($books->random(2) as $book) { // Mỗi user đánh giá 2 cuốn ngẫu nhiên
                if (Review::where('user_id', $user->id)->where('book_id', $book->id)->exists()) {
                    continue;
                }

                Review::create([
                    'id' => (string) Str::uuid(),
                    'user_id' => $user->id,
                    'book_id' => $book->id,
                    'order_id' => $orders->random()->id,
                    'rating' => rand(3, 5),
                    'comment' => $sampleComments[array_rand($sampleComments)],
                    'status' => 'approved',
                    'admin_response' => null,
                ]);

                $reviewCount++;
            }
        }

        $this->command->info("Đã tạo $reviewCount review mẫu thực tế.");
    }
}
