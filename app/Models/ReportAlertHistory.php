<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportAlertHistory extends Model
{
    use HasFactory;

    protected $table = 'report_alert_history';
    public $timestamps = false; // لأنك مستخدمة created_at فقط بدون updated_at

    protected $fillable = [
        'report_id', 'alert_id', 'alert_date', 'alert_text', 'created_at'
    ];

    public function report()
    {
        return $this->belongsTo(MedicalReport::class, 'report_id');
    }
}