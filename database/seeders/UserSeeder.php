<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Address;
use App\Models\Role;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'Admin')->first();
        $userRole = Role::where('name', 'User')->first();

        // Tạo 2 admin users
        User::factory(2)->create([
            'role_id' => $adminRole->id
        ]);

        // Tạo 20 user thường
        User::factory(10)->create([
            'role_id' => $userRole->id
        ]);
    }
}
