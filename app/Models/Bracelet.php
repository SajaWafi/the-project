<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bracelet extends Model
{
    use HasFactory;

    // تحديد اسم الجدول زي ما طالع في الداتابيز عندك
    protected $table = 'bracelets'; 

    // السماح بإضافة وتعديل كل الحقول
    protected $guarded = [];

    // العلاقة: السوار يتبع لطفل واحد
    public function child()
    {
        return $this->belongsTo(Child::class, 'child_id');
    }
}