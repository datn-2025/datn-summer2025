<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Order;
use App\Models\Address;
use App\Models\Voucher;
use App\Models\OrderStatus;
use Illuminate\Support\Str;
use App\Models\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        return [
            'user_id' => $user->id,
            'order_code' => 'BB-' . strtoupper(Str::random(6)),
            'address_id' => Address::where('user_id', $user->id)->inRandomOrder()->first()->id ?? Address::factory()->create(['user_id' => $user->id])->id,
            'voucher_id' => $this->faker->boolean(30) ? Voucher::inRandomOrder()->first()->id ?? Voucher::factory()->create()->id : null,
            'total_amount' => $this->faker->randomFloat(2, 100000, 10000000),
            'order_status_id' => OrderStatus::inRandomOrder()->first()->id ?? OrderStatus::factory()->create()->id,
            'payment_status_id' => PaymentStatus::inRandomOrder()->first()->id ?? PaymentStatus::factory()->create()->id,
        ];
    }
}
