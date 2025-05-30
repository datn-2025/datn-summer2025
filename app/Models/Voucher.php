<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Voucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'description',
        'discount_percent',
        'max_discount',
        'min_order_value',
        'valid_from',
        'valid_to',
        'quantity',
        'status'
    ];

    protected $casts = [
        'discount_percent' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'min_order_value' => 'decimal:2',
        'valid_from' => 'datetime',
        'valid_to' => 'datetime',
        'quantity' => 'integer',
        'status' => 'boolean'
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function appliedVouchers(): HasMany
    {
        return $this->hasMany(AppliedVoucher::class);
    }

    public function isValid()
    {
        $now = now();
        return $this->status 
            && $now->between($this->valid_from, $this->valid_to)
            && $this->quantity > 0;
    }

    public function getUsedCountAttribute()
    {
        return $this->appliedVouchers()->count();
    }

    public function getRemainingQuantityAttribute()
    {
        return $this->quantity - $this->used_count;
    }
}
