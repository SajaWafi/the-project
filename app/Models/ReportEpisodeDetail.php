<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportEpisodeDetail extends Model
{
    // هنا كان الغلط، لازم يكون نفس اسم الحقل في الداتابيز
    protected $fillable = [
        'report_id',         // <--- عدلي هادي بس
        'panic_event_id', 
        'episode_title', 
        'episode_date',      
        'location_name', 
        'duration_min',      
        'heart_rate', 
        'severity'
    ];

    public function report()
    {
        return $this->belongsTo(MedicalReport::class, 'report_id');
    }
}