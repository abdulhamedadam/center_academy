<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmLeadSource extends Model
{
    use HasFactory;

    protected $guarded=[];
    protected $table='tbl_crm_lead_sources';
} 