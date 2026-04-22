<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorRequest extends Model
{
    protected $fillable = [
        'doctor_id',
        'parent_id',
        'status',
    ];

    public function doctor()
    {
        return $this->belongsTo(DoctorProfile::class, 'doctor_id');
    }

    public function parent()
    {
        return $this->belongsTo(ParentProfile::class, 'parent_id');
    }
}