<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmCommunicationType extends Model
{
    use HasFactory;

    protected $table = 'tbl_crm_communication_types';
    protected $guarded = [];

    // Default icons for common communication types
    public static function getDefaultIcons(): array
    {
        return [
            'phone' => 'heroicon-o-phone',
            'home' => 'heroicon-o-home',
            'chat' => 'heroicon-o-chat-bubble-left-right',
            'envelope' => 'heroicon-o-envelope',
            'video' => 'heroicon-o-video-camera',
            'message' => 'heroicon-o-message',
            'mail' => 'heroicon-o-envelope',
            'link' => 'heroicon-o-link',
        ];
    }
} 