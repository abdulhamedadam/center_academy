<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmFollowUps extends Model
{
    protected $table='tbl_crm_follow_ups';
    protected $guarded=[];
    const INTERSTED = 1;
    const BUSY= 2;
    const NO_ANSWER= 3;
    const WRONG_NUMBER= 4;
    const NOT_INTERSTED= 5;


    public function lead()
    {
        return $this->belongsTo(CrmLeads::class, 'lead_id');
    }

    public function communicationType()
    {
        return $this->belongsTo(CrmCommunicationType::class, 'communication_type');
    }

    /*************************************************/
    public function user()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
