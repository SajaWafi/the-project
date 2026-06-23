<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'child_id',
        'note_text',
        'is_shared',
    ];

    // علاقة الملاحظة بالدكتور
public function doctor()
    {
        return $this->belongsTo(DoctorProfile::class, 'doctor_id');
    }

    // علاقة الملاحظة بالطفل
    public function child()
    {
        return $this->belongsTo(Child::class, 'child_id');
    }
}