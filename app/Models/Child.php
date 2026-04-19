<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ParentProfile;
use App\Models\DoctorProfile;

class Child extends Model
{
   protected $fillable = [
    'parent_id',
    'name',
    'gender',
    'birth_date',
    'autism_level',
];

    public function parent()
    {
         return $this->belongsTo(User::class, 'parent_id');
    }

    public function doctors()
    {
        return $this->belongsToMany(
            DoctorProfile::class,
            'child_doctor',
            'child_id',
            'doctor_id'
        )->withPivot('status', 'assigned_at')->withTimestamps();
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'child_id');
    }
}