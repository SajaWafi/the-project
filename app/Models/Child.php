<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    protected $table = 'children';

    protected $fillable = [
        'parent_id',
        'name',
        'gender',
        'birth_date',
        'autism_level',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function parentProfile()
    {
        return $this->belongsTo(ParentProfile::class, 'parent_id');
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
}