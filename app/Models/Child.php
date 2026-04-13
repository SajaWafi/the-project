<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
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
}