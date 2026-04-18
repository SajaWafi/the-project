<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChildDoctor extends Model
{
    protected $table = 'child_doctor';

    protected $fillable = [
        'child_id',
        'doctor_id',
        'status',
        'assigned_at',
    ];
}