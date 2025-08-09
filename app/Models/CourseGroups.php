<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseGroups extends Model
{
    protected $table='tbl_course_groups';
    protected $guarded=[];
    protected $casts = [
        'days' => 'array',
    ];
    //---------------------------------------------------------------------
    public function course()
    {
        return $this->belongsTo(Courses::class,'course_id','id');
    }
    //----------------------------------------------------------------------
    public function instructor()
    {
        return $this->belongsTo(Instructor::class,'course_id','id');
    }
    //---------------------------------------------------------------------
    public function groupDays()
    {
        return $this->hasMany(CourseGroupDays::class, 'group_id', 'id');
    }
    //---------------------------------------------------------------------
    public function hall()
    {
        return $this->belongsTo(Hall::class, 'hall_id', 'id');
    }
    //---------------------------------------------------------------------
}
