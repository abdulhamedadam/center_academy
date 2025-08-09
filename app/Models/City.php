<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class City extends Model
{
    protected $table = 'tbl_country_city';
    protected $guarded = [];




    public static function booted()
    {
        static::creating(function ($model) {
         //   $model->uuid = Str::uuid();
        });
    }

    /***************************************/
    public function parent()
    {
        return $this->belongsTo(City::class, 'parent_id', 'id');
    }
    /***************************************/
    public function Country()
    {
        return $this->belongsTo(Country::class,'parent_id','id');
    }
}
