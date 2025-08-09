<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransactions extends Model
{
    protected $guarded = [];
    protected $table ='tbl_course_payment_transactions';

    /**************************************************/
    
    public static function booted()
    {
        static::creating(function ($model) {
            $lastCode = self::max('code');
            $model->code = $lastCode ? $lastCode + 1 : 100;
        });
    }
     /**************************************************/
    public function coursePayment()
    {
        return $this->belongsTo(CoursePayments::class,'course_payment_id','id');
    }
    /**************************************************/
    public function course_installment()
    {
        return $this->belongsTo(CourseInstallments::class,'installment_id','id');
    }
    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id', 'id');
    }
    public function course()
    {
        return $this->belongsTo(Courses::class, 'course_id', 'id');
    }
    public function group()
    {
        return $this->belongsTo(CourseGroups::class, 'group_id', 'id');
    }
}
