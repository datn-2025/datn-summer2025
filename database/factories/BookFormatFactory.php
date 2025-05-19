<?php

namespace Database\Factories;

use App\Models\BookFormat;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFormatFactory extends Factory
{
    protected $model = BookFormat::class;

    public function definition(): array
    {
        $format = $this->faker->randomElement(['Ebook', 'Bìa cứng', 'Bìa mềm']);
        $isPhysical = in_array($format, ['Bìa cứng', 'Bìa mềm']);
        
        return [
            'book_id' => Book::factory(),
            'format_name' => $format,
            'price' => $this->faker->randomFloat(2, 10, 100),
            'discount' => $this->faker->optional(0.3)->randomFloat(2, 5, 30), // 30% chance of having a discount
            'stock' => $isPhysical ? $this->faker->numberBetween(0, 100) : null,
            'file_url' => !$isPhysical ? $this->faker->url : null,
            'sample_file_url' => !$isPhysical ? $this->faker->optional(0.8)->url : null,
            'allow_sample_read' => !$isPhysical ? $this->faker->boolean(70) : false
        ];
    }
}
