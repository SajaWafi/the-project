<?php

namespace App\Models\ParentModule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Child extends Model
{
    use HasFactory;

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
}