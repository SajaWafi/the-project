<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'child_id',
        'title',
        'description',
        'is_completed',
    ];

    public function doctor()
    {
        return $this->belongsTo(DoctorProfile::class, 'doctor_id');
    }

    public function child()
    {
        return $this->belongsTo(Child::class, 'child_id');
    }
}