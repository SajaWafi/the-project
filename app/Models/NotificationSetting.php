<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    protected $fillable = [
        'user_id',
        'notification_type',
        'is_enabled',
        'has_sound',
        'has_vibrate'
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'has_sound' => 'boolean',
        'has_vibrate' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}