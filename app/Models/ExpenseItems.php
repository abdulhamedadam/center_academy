<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ExpenseItems extends Model
{
    protected $table = 'tbl_expense_items';
    protected $guarded = [];


    public static function booted()
    {
        static::creating(function ($model) {
           // $model->uuid = Str::uuid();
        });
    }
}
