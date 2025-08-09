<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Hall extends Model
{
    use SoftDeletes;
    
    protected $table = 'tbl_halls';
    protected $guarded = [];

    public static function booted()
    {
        static::creating(function ($model) {
         //   $model->uuid = Str::uuid();
        });
    }
} 