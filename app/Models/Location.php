<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    // هذا السطر هو اللي يسمح للارافل بتخزين الإحداثيات في الجدول
    protected $fillable = [
        'child_id', 
        'bracelet_id', 
        'latitude', 
        'longitude', 
        'location_name', 
        'recorded_at'
    ];
}