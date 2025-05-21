<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
        public function run()
        {
            PaymentMethod::factory(3)->create();
        }
}
