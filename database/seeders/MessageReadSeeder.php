<?php

namespace Database\Seeders;

use App\Models\MessageRead;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageReadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MessageRead::factory()->count(70)->create();
    }
}
