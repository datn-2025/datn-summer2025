<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
class BookFormat extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'format_name',
        'price',
        'discount',
        'stock',
        'file_url',
        'sample_file_url',
        'allow_sample_read'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'stock' => 'integer',
        'allow_sample_read' => 'boolean'
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
}
