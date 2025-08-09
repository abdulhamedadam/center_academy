<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmLeadsStatus extends Model
{



   
    protected $guarded=[];
    protected $table='tbl_crm_leads_statuses';

   

    public function leads()
    {
        return $this->hasMany(CrmLeads::class, 'status');
    }
}
