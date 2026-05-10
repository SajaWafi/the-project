<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $fillable = [
        'child_id',
        'parent_id',
        'panic_event_id',
        'title',
        'message',
        'is_read',
        'sent_at',
        'alert_type',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'sent_at' => 'datetime',
    ];

    public function child()
    {
        return $this->belongsTo(Child::class, 'child_id');
    }

    public function parent()
    {
        return $this->belongsTo(ParentProfile::class, 'parent_id');
    }
}