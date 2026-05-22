<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id', 'message', 'priority_level', 'sort_order'
    ];

    public function report()
    {
        return $this->belongsTo(MedicalReport::class, 'report_id');
    }
}