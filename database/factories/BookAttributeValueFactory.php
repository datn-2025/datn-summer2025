<?php

namespace Database\Factories;

use App\Models\BookAttributeValue;
use App\Models\Book;
use App\Models\AttributeValue;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookAttributeValueFactory extends Factory
{
    protected $model = BookAttributeValue::class;

    public function definition(): array
    {
        return [
            'book_id' => Book::factory(),
            'attribute_value_id' => AttributeValue::factory(),
            'extra_price' => $this->faker->optional()->randomFloat(2, 0, 50000),
        ];
    }

    /**
     * Configure the factory to create a book attribute value for a specific book
     */
    public function forBook(Book $book): Factory
    {
        return $this->state(function (array $attributes) use ($book) {
            return [
                'book_id' => $book->id
            ];
        });
    }

    /**
     * Configure the factory to create a book attribute value for a specific attribute value
     */
    public function forAttributeValue(AttributeValue $attributeValue): Factory
    {
        return $this->state(function (array $attributes) use ($attributeValue) {
            return [
                'attribute_value_id' => $attributeValue->id
            ];
        });
    }
}
