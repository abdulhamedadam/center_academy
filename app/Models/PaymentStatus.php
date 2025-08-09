<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentStatus extends Model
{
    protected $table = 'tbl_payment_status';
    
    protected $fillable = [
        'name'
    ];
}
