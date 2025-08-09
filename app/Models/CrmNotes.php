<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmNotes extends Model
{
    protected $table='tbl_crm_notes';
    protected $guarded=[];
    const INTERNAL = 1;
    const EXTERNAL= 2;
}
