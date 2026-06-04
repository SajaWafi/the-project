<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    use HasFactory;

    protected $table = 'children';

    protected $fillable = [
        'parent_id',
        'name',
        'bracelet_id',
        'is_bracelet_connected',
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

    public function parentUser()
    {
        return $this->parentProfile->user();
    }

    public function doctors()
    {
        return $this->belongsToMany(
            DoctorProfile::class,
            'child_doctor',
            'child_id',
            'doctor_id'
        )->withPivot('status', 'assigned_at')
         ->withTimestamps();
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'child_id');
    }

    public function sensorReadings()
    {
        return $this->hasMany(SensorReading::class, 'child_id');
    }
}