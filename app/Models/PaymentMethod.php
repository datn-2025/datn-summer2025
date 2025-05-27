<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class PaymentMethod extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'is_active',
        'description'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public $incrementing = false; 
    protected $keyType = 'string';

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
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
