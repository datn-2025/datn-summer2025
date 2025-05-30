<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'book_id',
        'book_format_id',
        'quantity',
        'attribute_value_ids',
        'price'
    ];

    protected $casts = [
        'attribute_value_ids' => 'array'
    ];
} 