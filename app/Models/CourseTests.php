<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CourseTests extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = [];
    protected $table = 'tbl_course_tests';
    const DRAFT = 1;
    const PUBLISHED = 2;
    const CLOSED = 3;
    const CANCELLED = 4;

    protected $casts = [
        'status' => 'integer',
    ];

    public static function getStatusName(int $status): string
    {
        return match($status) {
            self::DRAFT => __('common.Draft'),
            self::PUBLISHED => __('common.Published'),
            self::CLOSED => __('common.closed'),
            self::CANCELLED => __('common.cancelled'),
            default => 'Unknown',
        };
    }

    public static function getStatusColor(int $status): string
    {
        return match($status) {
            self::DRAFT => 'gray',
            self::PUBLISHED => 'success',
            self::CLOSED => 'warning',
            self::CANCELLED => 'danger',
            default => 'gray',
        };
    }
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('tests');
    }

    /**********************************************/
    public function course()
    {
        return $this->belongsTo(Courses::class, 'course_id', 'id');
    }
    /**********************************************/
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    /**********************************************/
    public function results()
    {
        return $this->hasMany(CourseTestsResults::class, 'test_id');
    }
    /**********************************************/
    public function students()
    {
        return $this->belongsToMany(Students::class, 'tbl_course_tests_results', 'test_id', 'student_id')
            ->withPivot(['grade', 'feedback', 'answers_json']);
    }
}
