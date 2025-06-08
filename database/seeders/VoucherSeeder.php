<?php

namespace Database\Seeders;

use App\Models\Voucher;
use App\Models\VoucherCondition;
use App\Models\Book;
use App\Models\Author;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VoucherSeeder extends Seeder
{
    public function run()
    {
        // Tạo một số voucher hoạt động cho mục đích test
        Voucher::factory()->active()->create([
            'code' => 'TESTACTIVE10',
            'discount_percent' => 10,
            'min_order_value' => 100000,
            'valid_to' => now()->addMonths(6),
        ]);

        Voucher::factory()->active()->create([
            'code' => 'TESTACTIVE20',
            'discount_percent' => 20,
            'min_order_value' => 200000,
            'valid_to' => now()->addMonths(6),
        ]);

        // Tạo thêm một số voucher ngẫu nhiên (status active hoặc inactive)
        for ($i = 0; $i < 10; $i++) { // Tăng số lượng voucher mẫu
            $code = 'VOUCHER' . \Illuminate\Support\Str::random(5);
            $discount = rand(5, 30);
            $minOrder = rand(100000, 500000);
            $status = rand(0, 1) == 1 ? 'active' : 'inactive'; // Chọn ngẫu nhiên active/inactive
            $validFrom = now()->subDays(rand(0, 30)); // Ngày bắt đầu có thể ở quá khứ gần
            $validTo = now()->addMonths(rand(1, 12)); // Ngày kết thúc ở tương lai

            // Để có cả voucher inactive (và có thể hết hạn theo ngày):
            if ($status === 'inactive') {
                 $validFrom = now()->subMonths(rand(1, 6)); // Ngày bắt đầu ở quá khứ xa hơn
                 $validTo = now()->subDays(rand(1, 30)); // Ngày kết thúc ở quá khứ
            }

            \App\Models\Voucher::factory()->create([
                'code' => $code,
                'description' => fake()->sentence,
                'discount_percent' => $discount,
                'max_discount' => rand(10000, 100000),
                'min_order_value' => $minOrder,
                'quantity' => rand(10, 500),
                'valid_from' => $validFrom,
                'valid_to' => $validTo,
                'status' => $status
            ]);

            // Thêm điều kiện ngẫu nhiên cho một số voucher
            if (rand(0, 1) == 1) {
                 $conditionType = fake()->randomElement(['book', 'category', 'author', 'brand']);
                 $conditionId = null;
                 switch($conditionType) {
                     case 'book': $conditionId = \App\Models\Book::inRandomOrder()->first()->id; break;
                     case 'category': $conditionId = \App\Models\Category::inRandomOrder()->first()->id; break;
                     case 'author': $conditionId = \App\Models\Author::inRandomOrder()->first()->id; break;
                     case 'brand': $conditionId = \App\Models\Brand::inRandomOrder()->first()->id; break;
                 }
                 if ($conditionId) {
                      \App\Models\VoucherCondition::create([
                          'voucher_id' => \App\Models\Voucher::latest()->first()->id,
                          'type' => $conditionType,
                          'condition_id' => $conditionId
                      ]);
                 }
            } else { // Tạo điều kiện 'all'
                 \App\Models\VoucherCondition::create([
                     'voucher_id' => \App\Models\Voucher::latest()->first()->id,
                     'type' => 'all'
                 ]);
            }
        }
    }
}
