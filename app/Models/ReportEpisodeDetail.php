<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportEpisodeDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id', 'panic_event_id', 'episode_title', 'episode_date', 
        'location_name', 'duration_min', 'heart_rate', 'severity'
    ];

    public function report()
    {
        return $this->belongsTo(MedicalReport::class, 'report_id');
    }
}