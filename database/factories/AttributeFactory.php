<?php

namespace Database\Factories;

use App\Models\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttributeFactory extends Factory
{
    protected $model = Attribute::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Kích Thước', 'Ngôn Ngữ', 'Loại Bìa'
            ])
        ];
    }
}
