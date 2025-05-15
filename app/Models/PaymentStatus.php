<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PaymentStatus extends Model
{
    use HasFactory;

    protected $table = 'payment_statuses';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'name',
        'description',
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

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
