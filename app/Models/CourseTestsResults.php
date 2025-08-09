<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseTestsResults extends Model
{
    protected $guarded=[];
    protected $table='tbl_course_tests_results';

    public function test()
    {
        return $this->belongsTo(CourseTests::class, 'test_id');
    }

    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id');
    }
}
