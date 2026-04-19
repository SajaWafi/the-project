<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workplace extends Model
{
    protected $fillable = [
        'doctor_id',
        'place_name',
        'from_hour',
        'from_minute',
        'from_period',
        'to_hour',
        'to_minute',
        'to_period',
        'days',
    ];

    protected $casts = [
        'days' => 'array',
    ];

    public function doctorProfile()
    {
        return $this->belongsTo(DoctorProfile::class, 'doctor_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'workplace_id');
    }
}