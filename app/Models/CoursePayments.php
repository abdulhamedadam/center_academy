<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CoursePayments extends Model
{
    protected $guarded = [];
    protected $table = 'tbl_course_payments';


    /****************************************************/
    public function student()
    {
        return $this->belongsTo(Students::class);
    }

    /****************************************************/
    public function course()
    {
        return $this->belongsTo(Courses::class);
    }
    /****************************************************/
    public function payment_transactions()
    {
        return $this->hasMany(PaymentTransactions::class,'course_payment_id','id');
    }
    public function installments()
    {
        return $this->hasMany(CourseInstallments::class, 'course_payment_id');
    }


}
