<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo 1 admin
        // User::factory()->create([
        //     'name' => 'Admin User',
        //     'email' => 'admin@example.com',
        //     'password' => bcrypt('12345678'), // Mật khẩu đã được mã hóa
        //     'role' => 'admin',
        //     'status' => 'Hoạt động',
        // ]);

        // Tạo 10 user ngẫu nhiên
        User::factory()->count(10)->create();
    }
}
