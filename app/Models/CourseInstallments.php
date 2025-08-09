<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseInstallments extends Model
{
    protected $guarded = [];
    protected $table ='tbl_course_installments';

    public function coursePayment()
    {
        return $this->belongsTo(CoursePayments::class,'course_payment_id','id');
    }

    /*****************************************************/
    public function payment_transaction()
    {
        return $this->hasOne(PaymentTransactions::class,'installment_id','id');
    }
    //------------------------------------------------------------------------------------------------------------------
    public function student()
    {
        return $this->belongsTo(Students::class);
    }

    public function course()
    {
        return $this->belongsTo(Courses::class);
    }

    public function group()
    {
        return $this->belongsTo(CourseGroups::class, 'group_id');
    }

    public function installment()
    {
        return $this->belongsTo(CourseInstallments::class, 'id');
    }



}
