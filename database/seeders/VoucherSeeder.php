<?php

namespace Database\Seeders;

use App\Models\Voucher;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{    public function run()
    {
        // Xóa các voucher hiện có để tránh trùng lặp khi chạy lại seeder
        // Voucher::truncate(); // Chỉ dùng nếu bạn chắc chắn muốn xóa tất cả voucher cũ

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

        // Bạn có thể giữ lại phần tạo voucher ngẫu nhiên nếu muốn có thêm dữ liệu test
        // Voucher::factory(10)->create();
    }
}
