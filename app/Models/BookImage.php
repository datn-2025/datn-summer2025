<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
class BookImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'image_url'
    ];

    public $incrementing = false; 
    protected $keyType = 'string';

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }
}
