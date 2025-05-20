<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        // Get all users
        $users = User::all();

        foreach ($users as $user) {
            // Create 1-3 addresses for each user
            Address::factory(rand(1, 3))->create([
                'user_id' => $user->id,
                'is_default' => false
            ]);
            
            // Set one address as default
            $user->addresses()->inRandomOrder()->first()->update(['is_default' => true]);
        }
    }
}
