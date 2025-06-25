<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Contact;
use Faker\Factory as Faker;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN'); // Sử dụng ngôn ngữ Tiếng Việt
        $statuses = ['new', 'processing', 'replied', 'closed'];

        for ($i = 0; $i < 20; $i++) {
            Contact::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'note' => $faker->paragraph(3, true), // Sử dụng note cho nội dung tin nhắn
                'status' => $faker->randomElement($statuses),
            ]);
        }
    }
}
