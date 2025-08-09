<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Instructor extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $table = 'tbl_instructors';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'city_id',
        'region_id',
        'address1',
        'gender',
        'date_of_birth',
        'specialization',
        'qualifications',
        'experience',
        'bio',
        'status',
        'hire_date',
        'administrative_notes',
    ];

    /**********************************************/
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('instructor')
            ->useDisk('public')
            ->singleFile();

        $this->addMediaCollection('instructor_cv')
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
    public function Country()
    {
        return $this->belongsTo(Country::class,'city_id','id');
    }
    /**********************************************/
    public function City()
    {
        return $this->belongsTo(Country::class,'region_id','id');
    }
    /**********************************************/
    public function courses()
    {
        return $this->belongsToMany(Courses::class, 'tbl_course_instructor', 'instructor_id', 'course_id');
    }

}
