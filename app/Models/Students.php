<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Students extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'tbl_students';
    protected $guarded = [];

    /**********************************************/
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('students')
            ->useDisk('public')
            ->singleFile();
    }
    /**********************************************/
    public static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->code)) {
                $lastCode = self::max('code');
                $model->code = $lastCode ? $lastCode + 1 : 1000;
            }
        });
    }
    /**********************************************/
    protected function getImageUrl(?Media $media): ?string
    {
        if (!$media) {
            return null;
        }

        return str_replace('/storage/', '/build/storage/', $media->getUrl());
    }
    /************************************************/
    public function courses()
    {
        return $this->belongsToMany(Courses::class, 'tbl_course_students','student_id','id');
    }

    public function complaints()
    {
        return $this->hasMany(CourseComplaints::class,'student_id','id');
    }

    public function assignmentSubmissions()
    {
        return $this->hasMany(CourseAssignmentsResults::class,'student_id','id');
    }

    public function testResults()
    {
        return $this->hasMany(CourseTestsResults::class,'student_id','id');
    }

    public function coursePayments()
    {
        return $this->hasMany(CoursePayments::class,'student_id','id');
    }

    public function attendances()
    {
        return $this->hasMany(CourseAttendanceDetails::class,'student_id','id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethods::class, 'payment_method_id');
    }

    public function Country()
    {
        return $this->belongsTo(Country::class, 'city_id', 'id');
    }

    public function City()
    {
        return $this->belongsTo(Country::class, 'region_id', 'id');
    }

    // Attribute methods
    protected function coursesCount()
    {
        return Attribute::make(
            get: fn () => $this->courses()->count(),
        );
    }

    protected function complaintsCount()
    {
        return Attribute::make(
            get: fn () => $this->complaints()->count(),
        );
    }

    protected function assignmentsCount()
    {
        return Attribute::make(
            get: fn () => $this->assignmentSubmissions()->count(),
        );
    }

    protected function testsCount()
    {
        return Attribute::make(
            get: fn () => $this->testResults()->count(),
        );
    }

    protected function paymentsCount()
    {
        return Attribute::make(
            get: fn () => $this->coursePayments()->count(),
        );
    }

    public function attendance_percentage()
    {
        return Attribute::make(
            get: function () {
                $totalSessions = $this->courses()->attendances->count();
                dd($totalSessions );
                if ($totalSessions === 0) return 0;
                $attended = $this->attendances()->where('status', 1)->count();
                return round(($attended / $totalSessions) * 100);
            },
        );
    }

}
