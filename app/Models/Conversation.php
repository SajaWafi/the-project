<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $table = 'conversations';

    protected $fillable = [
        'doctor_id',
        'parent_id',
        'child_id',
    ];

    public function messages()
    {
        return $this->hasMany(Message::class, 'conversation_id');
    }

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
}