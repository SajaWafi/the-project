<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportEpisodeTrend extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'report_id', 'point_label', 'point_order', 'episode_count', 'created_at'
    ];

    public function report()
    {
        return $this->belongsTo(MedicalReport::class, 'report_id');
    }
}