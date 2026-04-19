<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'doctor_id',
        'parent_id',
        'child_id',
        'date',
        'from_hour',
        'from_minute',
        'from_period',
        'to_hour',
        'to_minute',
        'to_period',
        'clinic_name',
        'status',
        'note',
    ];

    public function doctor()
    {
        return $this->belongsTo(DoctorProfile::class, 'doctor_id');
    }

    public function parent()
    {
        return $this->belongsTo(ParentProfile::class, 'parent_id');
    }

    public function child()
    {
        return $this->belongsTo(Child::class, 'child_id');
    }

    public function workplace()
    {
        return $this->belongsTo(Workplace::class, 'workplace_id');
    }
}
