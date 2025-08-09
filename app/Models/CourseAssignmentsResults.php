<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseAssignmentsResults extends Model
{
    protected $guarded=[];
    protected $table='tbl_course_assignments_results';

    public function assignment()
    {
        return $this->belongsTo(CourseAssignments::class, 'assignment_id');
    }

    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id');
    }
}
