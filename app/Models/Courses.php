<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Courses extends Model
{
    protected $table = 'tbl_courses';
    protected $guarded = [];

    const STATUS_ACTIVE = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_CANCELLED = 3;
    const STATUS_PENDING = 4;

    //----------------------------------------------------------------------
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_ACTIVE => __('common.Active'),
            self::STATUS_COMPLETED => __('common.Completed'),
            self::STATUS_CANCELLED => __('common.Cancelled'),
            self::STATUS_PENDING =>__('common.Pending'),
        ];
    }
    //----------------------------------------------------------------------
    public function getStatusNameAttribute(): string
    {
        $statuses = self::getStatusOptions();
        return $statuses[$this->status] ?? 'Unknown';
    }

    //----------------------------------------------------------------------
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    //----------------------------------------------------------------------
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

   //----------------------------------------------------------------------
    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }


    public static function booted()
    {
        static::creating(function ($model) {
            $lastCode = self::max('code');
            $model->code = $lastCode ? $lastCode + 1 : 1000;
        });
    }

    /************************************************/
    public function Level()
    {
        return $this->belongsTo(Levels::class,'level_id','id');
    }
    /************************************************/
    public function Instructor()
    {
        return $this->belongsTo(Instructor::class,'instructor_id','id');
    }

    /************************************************/
    public function Category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }
    /************************************************/
    public function CourseStudents()
    {
        return $this->hasMany(CourseStudents::class,'course_id','id');
    }
    /*************************************************/
    public function schedules()
    {
        return $this->hasMany(CourseSchedule::class, 'course_id');
    }
    /***************************************************/
    public function payments()
    {
        return $this->hasMany(CoursePayments::class, 'course_id');
    }

    public function instructors()
    {
        return $this->belongsToMany(Instructor::class, 'tbl_course_instructor', 'course_id', 'instructor_id');
    }

}
