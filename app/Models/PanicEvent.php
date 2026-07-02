<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PanicEvent extends Model
{
    use HasFactory;

    // تم حذف guarded والاعتماد على fillable للحماية القصوى
    protected $fillable = [
        'child_id',
        'bracelet_id',
        'sensor_reading_id',
        'location_id',
        'event_type',
        'severity',
        'description',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function sensorReading()
    {
        return $this->belongsTo(SensorReading::class);
    }

    public function bracelet()
    {
        return $this->belongsTo(Bracelet::class);
    }

    public function alerts()
    {
        return $this->hasMany(Alert::class, 'panic_event_id');
    }
}