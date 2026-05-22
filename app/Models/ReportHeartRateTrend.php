<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportHeartRateTrend extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'report_id', 'point_label', 'point_order', 'heart_rate_value', 'created_at'
    ];

    public function report()
    {
        return $this->belongsTo(MedicalReport::class, 'report_id');
    }
}