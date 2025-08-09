<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentMethods extends Model
{
    protected $table = 'payment_methods';
    protected $guarded = [];


    public static function booted()
    {
        static::creating(function ($model) {
      //      $model->uuid = Str::uuid();
        });
    }
}
