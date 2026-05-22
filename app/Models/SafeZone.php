<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SafeZone extends Model
{
    use HasFactory;

    // تأكيد اسم الجدول
    protected $table = 'safe_zones';

    // إعطاء الإذن للارافل بتخزين هذي البيانات
    protected $fillable = [
        'child_id',
        'zone_name',
        'center_latitude',
        'center_longitude',
        'radius_meters',
        'is_active',
    ];
}