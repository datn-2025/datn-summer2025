<?php

namespace Database\Factories;

use App\Models\AppliedVoucher;
use App\Models\User;
use App\Models\Voucher;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppliedVoucherFactory extends Factory
{
    protected $model = AppliedVoucher::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'voucher_id' => Voucher::factory(),
            'order_id' => $this->faker->boolean(70) ? Order::factory() : null, // 70% chance of being used in an order
            'used_at' => $this->faker->boolean(70) ? $this->faker->dateTimeBetween('-1 month', 'now') : null,
            'usage_count' => $this->faker->numberBetween(1, 3)
        ];
    }

    public function used(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'order_id' => Order::factory(),
                'used_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
                'usage_count' => 1
            ];
        });
    }
}
