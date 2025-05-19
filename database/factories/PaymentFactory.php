<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        // Lấy ngẫu nhiên order có sẵn hoặc tạo mới
        $order = Order::inRandomOrder()->first() ?? Order::factory()->create();

        // Lấy ngẫu nhiên phương thức thanh toán có sẵn hoặc tạo mới
        $paymentMethod = PaymentMethod::inRandomOrder()->first() ?? PaymentMethod::factory()->create();
        $paymentStatus = PaymentStatus::inRandomOrder()->first() ?? PaymentStatus::factory()->create();

        return [
            'order_id' => $order->id,
            'payment_method_id' => $paymentMethod->id,
            'payment_status_id' => $paymentStatus->id,
            'transaction_id' => $this->faker->boolean(80) ? Str::random(20) : null,
            'amount' => $this->faker->randomFloat(2, 100000, 10000000),
            'paid_at' => $this->faker->boolean(70) ? $this->faker->dateTimeBetween('-1 month', 'now') : null,
        ];
    }
}
