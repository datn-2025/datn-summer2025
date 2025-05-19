<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
class BookAttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'attribute_value_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public $incrementing = false; 
    protected $keyType = 'string';

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function attributeValue(): BelongsTo
    {
        return $this->belongsTo(AttributeValue::class);
    }
}
