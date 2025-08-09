<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CourseAttendanceDetails extends Model
{
    protected $guarded = [];
    protected $table ='tbl_course_attendances_details';
    const STATUS_PRESENT = 1;
    const STATUS_ABSENT = 2;
    const STATUS_LATE = 3;
    public static $statuses = [
        self::STATUS_PRESENT => 'Present',
        self::STATUS_ABSENT => 'Absent',
        self::STATUS_LATE => 'Late',
    ];

    /********************************************/
    public function getStatusTextAttribute()
    {
        return self::$statuses[$this->status] ?? 'Unknown';
    }

    /********************************************/
    public function setStatusAttribute($value)
    {
        if (!array_key_exists($value, self::$statuses)) {
            throw new \InvalidArgumentException("Invalid status value");
        }
        $this->attributes['status'] = $value;
    }

    /*******************************************/
    public function attendance()
    {
        return $this->belongsTo(CourseAttendance::class, 'attendance_id');
    }
    /*******************************************/
    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id');
    }
}
