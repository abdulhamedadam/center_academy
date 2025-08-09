<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Levels extends Model
{
    protected $table = 'tbl_levels';
    protected $guarded = [];


    public static function booted()
    {
        static::creating(function ($model) {
       //     $model->uuid = Str::uuid();
          //  $model->created_by = Auth::id();
        });
    }
}
