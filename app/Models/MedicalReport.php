<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id', 'report_type', 'report_month', 'report_year', 
        'title', 'child_status', 'total_episodes', 'previous_period_episodes', 
        'avg_episode_duration_min', 'longest_episode_duration_min', 
        'average_heart_rate', 'peak_heart_rate', 'min_heart_rate', 
        'average_steps', 'safe_zone_exit_count', 'comparison_percentage', 'summary_text'
    ];

    // علاقة التقرير بالطفل
    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    // علاقات التقرير بتفاصيله (التوصيات، النوبات، التنبيهات، الرسوم البيانية)
    public function recommendations()
    {
        return $this->hasMany(ReportRecommendation::class, 'report_id');
    }

    public function episodeDetails()
    {
        return $this->hasMany(ReportEpisodeDetail::class, 'report_id');
    }

    public function alertHistory()
    {
        return $this->hasMany(ReportAlertHistory::class, 'report_id');
    }

    public function episodeTrends()
    {
        return $this->hasMany(ReportEpisodeTrend::class, 'report_id');
    }

    public function heartRateTrends()
    {
        return $this->hasMany(ReportHeartRateTrend::class, 'report_id');
    }
}