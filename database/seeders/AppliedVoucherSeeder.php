<?php

namespace Database\Seeders;

use App\Models\AppliedVoucher;
use Illuminate\Database\Seeder;

class AppliedVoucherSeeder extends Seeder
{
    public function run()
    {
        AppliedVoucher::factory(20)->create();
    }
}
