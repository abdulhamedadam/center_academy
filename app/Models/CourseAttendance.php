<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CourseAttendance extends Model
{
    protected $guarded = [];
    protected $table ='tbl_course_attendances';

    /********************************************/
    public static function booted()
    {
        static::creating(function ($model) {
         //   $model->uuid = Str::uuid();
        });
    }
    /********************************************/
    public function details()
    {
        return $this->hasMany(CourseAttendanceDetails::class, 'attendance_id');
    }

    /*******************************************/
    public function course()
    {
        return $this->belongsTo(Courses::class, 'course_id');
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_id');
    }

    public function group()
    {
        return $this->belongsTo(CourseGroups::class, 'group_id');
    }

}
