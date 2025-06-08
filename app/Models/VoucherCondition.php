<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VoucherCondition extends Model
{
    protected $fillable = [
        'voucher_id',
        'type',
        'condition_id'
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function categoryCondition()
    {
        return $this->belongsTo(Category::class, 'condition_id');
    }

    public function authorCondition()
    {
        return $this->belongsTo(Author::class, 'condition_id');
    }

    public function brandCondition()
    {
        return $this->belongsTo(Brand::class, 'condition_id');
    }

    public function bookCondition()
    {
        return $this->belongsTo(Book::class, 'condition_id');
    }

    public function condition()
    {
        return match($this->type) {
            'category' => $this->categoryCondition(),
            'author' => $this->authorCondition(),
            'brand' => $this->brandCondition(),
            'book' => $this->bookCondition(),
            default => null,
        };
    }
}