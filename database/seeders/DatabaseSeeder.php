<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PaymentStatus;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CartSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            AuthorSeeder::class,
            AttributeSeeder::class,
            AttributeValueSeeder::class,
            NewsArticleSeeder::class,
            BookSeeder::class,            // Book sẽ tự tạo Format, Image và Attribute Values
            // Bỏ 3 seeder này vì đã được xử lý trong BookSeeder
            // BookFormatSeeder::class,
            // BookAttributeValueSeeder::class,
            // BookImageSeeder::class,
            AddressSeeder::class,
            PaymentMethodSeeder::class,
            PaymentStatusSeeder::class,
            OrderStatusSeeder::class,
            OrderSeeder::class,
            InvoiceSeeder::class,
            InvoiceItemSeeder::class,
            AppliedVoucherSeeder::class,
            VoucherSeeder::class,
            ReviewSeeder::class,
            PaymentSeeder::class,
            WishlistSeeder::class,
        ]);
    }
}
