<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\OrderStatus;
use App\Models\PaymentStatus;
use App\Models\PaymentMethod;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'order_status_id' => OrderStatus::inRandomOrder()->first()->id ?? OrderStatus::factory(),
            'payment_status_id' => PaymentStatus::inRandomOrder()->first()->id ?? PaymentStatus::factory()->create()->id,
            'payment_method_id' => PaymentMethod::inRandomOrder()->first()->id ?? PaymentMethod::factory(),

            'coupon_code' => $this->faker->optional()->bothify('COUPON###'),
            'discount_amount' => $this->faker->randomFloat(2, 0, 50),
            'total_price' => $this->faker->randomFloat(2, 100, 1000),
            'shipping_address' => $this->faker->address,

            'order_date' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'shipped_date' => $this->faker->optional()->dateTimeBetween('now', '+10 days'),
        ];
    }
}
