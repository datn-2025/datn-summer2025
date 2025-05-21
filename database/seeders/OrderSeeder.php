<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $users = User::whereHas('role', function ($query) {
            $query->where('name', 'User');
        })->get();

        foreach ($users as $user) {
            // Mỗi user có 1-3 đơn hàng
            Order::factory(rand(1, 3))->create([
                'user_id' => $user->id,
                'address_id' => $user->addresses()->inRandomOrder()->first()->id,
                'order_status_id' => OrderStatus::inRandomOrder()->first()->id
            ]);
        }
    }
}
