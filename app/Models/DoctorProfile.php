<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'gender',
        'specialization',
        'birth_date',
        'bio',
    ];

    public function children()
    {
        return $this->belongsToMany(
            Child::class,
            'child_doctor',
            'doctor_id',
            'child_id'
        )->withPivot('status', 'assigned_at')->withTimestamps();
    }
}