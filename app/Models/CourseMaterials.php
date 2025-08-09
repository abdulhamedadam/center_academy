<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CourseMaterials extends Model implements HasMedia
{

    use InteractsWithMedia;
    protected $guarded = [];
    protected $table ='tbl_course_materials';
    public static function booted()
    {
        static::creating(function ($model) {
         //   $model->uuid = Str::uuid();
        });
    }

    /**********************************************/
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('materials');
    }

}
