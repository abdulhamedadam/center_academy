<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Country extends Model
{
    protected $table = 'tbl_country_city';
    protected $guarded = [];



    /*******************************************/
    public static function booted()
    {
        static::creating(function ($model) {
          //  $model->uuid = Str::uuid();
        });
    }
    /********************************************/
    public function City()
    {
        return $this->hasMany(City::class,'parent_id','id');
    }
}
