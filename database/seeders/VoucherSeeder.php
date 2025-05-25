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

class VoucherSeeder extends Seeder
{
    protected function generateUniqueCode($prefix)
    {
        do {
            $code = $prefix . '_' . Str::random(5);
        } while (Voucher::where('code', $code)->exists());
        
        return $code;
    }

    protected function ensureTestData()
    {
        // Ensure we have a category
        if (!Category::exists()) {
            Category::create([
                'name' => 'Sách Văn Học',
                'slug' => 'sach-van-hoc',
                'description' => 'Sách văn học Việt Nam và thế giới'
            ]);
        }

        // Ensure we have an author
        if (!Author::exists()) {
            Author::create([
                'name' => 'Nguyễn Nhật Ánh',
                'slug' => 'nguyen-nhat-anh',
                'biography' => 'Tác giả nổi tiếng với nhiều tác phẩm văn học thiếu nhi'
            ]);
        }

        // Ensure we have a brand/publisher
        if (!Brand::exists()) {
            Brand::create([
                'name' => 'NXB Kim Đồng',
                'slug' => 'nxb-kim-dong',
                'description' => 'Nhà xuất bản sách thiếu nhi hàng đầu'
            ]);
        }

        // Ensure we have a book
        if (!Book::exists()) {
            $category = Category::first();
            $author = Author::first();
            $brand = Brand::first();

            Book::create([
                'title' => 'Cho Tôi Xin Một Vé Đi Tuổi Thơ',
                'slug' => 'cho-toi-xin-mot-ve-di-tuoi-tho',
                'description' => 'Tác phẩm nổi tiếng của Nguyễn Nhật Ánh',
                'price' => 85000,
                'quantity' => 100,
                'category_id' => $category->id,
                'author_id' => $author->id,
                'brand_id' => $brand->id
            ]);
        }
    }

    public function run()
    {
        // Ensure we have test data before creating vouchers
        $this->ensureTestData();

        // Create voucher for all books
        Voucher::create([
            'code' => $this->generateUniqueCode('ALLBOOKS20'),
            'description' => 'Giảm 20% cho tất cả sách',
            'discount_percent' => 20,
            'max_discount' => 100000,
            'min_order_value' => 200000,
            'quantity' => 100,
            'valid_from' => now(),
            'valid_to' => now()->addMonths(1),
            'status' => 'active'
        ])->conditions()->create(['type' => 'all']);

        // Create category-specific voucher
        $category = Category::first();
        $categoryVoucher = Voucher::create([
            'code' => $this->generateUniqueCode('CAT25OFF'),
            'description' => "Giảm 25% cho danh mục {$category->name}",
            'discount_percent' => 25,
            'max_discount' => 50000,
            'min_order_value' => 100000,
            'quantity' => 50,
            'valid_from' => now(),
            'valid_to' => now()->addMonths(1),
            'status' => 'active'
        ]);
        $categoryVoucher->conditions()->create([
            'type' => 'category',
            'condition_id' => $category->id
        ]);

        // Create author-specific voucher
        $author = Author::inRandomOrder()->first();
        $authorVoucher = Voucher::create([
            'code' => $this->generateUniqueCode('AUTHOR30'),
            'description' => "Giảm 30% cho sách của tác giả {$author->name}",
            'discount_percent' => 30,
            'max_discount' => 75000,
            'min_order_value' => 150000,
            'quantity' => 30,
            'valid_from' => now(),
            'valid_to' => now()->addMonths(1),
            'status' => 'active'
        ]);
        $authorVoucher->conditions()->create([
            'type' => 'author',
            'condition_id' => $author->id
        ]);

        // Create brand-specific voucher
        $brand = Brand::inRandomOrder()->first();
        $brandVoucher = Voucher::create([
            'code' => $this->generateUniqueCode('BRAND40'),
            'description' => "Giảm 40% cho sản phẩm của thương hiệu {$brand->name}",
            'discount_percent' => 40,
            'max_discount' => 100000,
            'min_order_value' => 250000,
            'quantity' => 20,
            'valid_from' => now(),
            'valid_to' => now()->addMonths(1),
            'status' => 'active'
        ]);
        $brandVoucher->conditions()->create([
            'type' => 'brand',
            'condition_id' => $brand->id
        ]);

        // Create specific book voucher
        $book = Book::inRandomOrder()->first();
        $bookVoucher = Voucher::create([
            'code' => $this->generateUniqueCode('BOOK50'),
            'description' => "Giảm 50% cho sách {$book->title}",
            'discount_percent' => 50,
            'max_discount' => 200000,
            'min_order_value' => 0,
            'quantity' => 10,
            'valid_from' => now(),
            'valid_to' => now()->addMonths(1),
            'status' => 'active'
        ]);
        $bookVoucher->conditions()->create([
            'type' => 'book',
            'condition_id' => $book->id
        ]);

        // Create expired voucher
        Voucher::create([
            'code' => $this->generateUniqueCode('EXPIRED15'),
            'description' => 'Voucher đã hết hạn',
            'discount_percent' => 15,
            'max_discount' => 30000,
            'min_order_value' => 100000,
            'quantity' => 100,
            'valid_from' => now()->subMonths(2),
            'valid_to' => now()->subMonths(1),
            'status' => 'active'
        ])->conditions()->create(['type' => 'all']);

        // Create future voucher
        Voucher::create([
            'code' => $this->generateUniqueCode('FUTURE35'),
            'description' => 'Voucher sắp có hiệu lực',
            'discount_percent' => 35,
            'max_discount' => 150000,
            'min_order_value' => 400000,
            'quantity' => 50,
            'valid_from' => now()->addMonths(1),
            'valid_to' => now()->addMonths(2),
            'status' => 'active'
        ])->conditions()->create(['type' => 'all']);
    }
}
