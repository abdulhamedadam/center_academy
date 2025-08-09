<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CourseSchedule extends Model
{
    protected $guarded = [];
    protected $table ='tbl_course_schedules';
    public static function booted()
    {
        static::creating(function ($model) {
         //   $model->uuid = Str::uuid();
        });
    }
    /************************************************/
    public function course()
    {
        return $this->belongsTo(Courses::class,'course_id','id');
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'created_by');
    }
    /************************************************/

}
