<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    protected $table='tbl_expenses';
    protected $guarded =[];

    public function band()
    {
        return $this->belongsTo(ExpenseItems::class,'band_id','id');
    }
}
