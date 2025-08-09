<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseComplaints extends Model
{
    protected $table = 'tbl_course_complaints';
    protected $guarded = [];

    //------------------------------------------------------
    public function student()
    {
        return $this->belongsTo(Students::class,'student_id','id');
    }
    //------------------------------------------------------
    public function course()
    {
        return $this->belongsTo(Courses::class,'course_id','id');
    }
}
