<?php

namespace Database\Seeders;

use App\Models\Voucher;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{    public function run()
    {
        Voucher::factory(15)->create();
    }
}
