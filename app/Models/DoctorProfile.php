<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'birth_date',
        'specialization',
        'bio',
    ];

    protected $casts = [
        'birth_date' => 'date',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}