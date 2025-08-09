<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CrmLeads extends Model
{
    use HasFactory;

    protected $table = 'tbl_crm_leads';

    protected $guarded = [];

    const NEW = 1;
    const CONTACTED= 2;
    const CONVERTED = 3;
    const NOTINTERSTED= 4;
    const LOST= 4;

    // Status Constants
    const STATUS_NEW = 1;
    const STATUS_CONTACTED = 2;
    const STATUS_NEEDS_FOLLOWUP = 3;
    const STATUS_REGISTERED = 4;
    const STATUS_NOT_INTERESTED = 5;

    // Source Constants
    const SOURCE_FACEBOOK = 1;
    const SOURCE_INSTAGRAM = 2;
    const SOURCE_REFERRAL = 3;
    const SOURCE_VISIT = 4;
    const SOURCE_ADS = 5;
    const SOURCE_OTHER = 6;

    // Status Labels
    public function status(): BelongsTo
    {
        return $this->belongsTo(CrmLeadsStatus::class, 'status_id');
    }

    // Source Labels
    public function source(): BelongsTo
    {
        return $this->belongsTo(CrmLeadSource::class, 'source_id');
    }

    public static function getStatusLabels(): array
    {
        return CrmLeadsStatus::pluck('name', 'id')->toArray();
    }

    public static function getSourceLabels(): array
    {
        return CrmLeadSource::pluck('name', 'id')->toArray();
    }

    // Get Status Label
    public function getStatusLabelAttribute()
    {
        return self::getStatusLabels()[$this->status] ?? __('common.unknown');
    }

    // Get Source Label
    public function getSourceLabelAttribute()
    {
        return self::getSourceLabels()[$this->source] ?? __('common.unknown');
    }

    // Relationships
    public function course(): BelongsTo
    {
        return $this->belongsTo(Courses::class, 'course_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function followUps(): HasMany
    {
        return $this->hasMany(CrmFollowUps::class, 'lead_id');
    }

    protected static function boot()
    {
        parent::boot();
        
        static::deleting(function($lead) {
            $lead->followUps()->delete();
        });
    }
}
