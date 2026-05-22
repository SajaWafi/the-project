<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecommendationRule extends Model
{
    use HasFactory;

    // اسم الجدول في قاعدة البيانات
    protected $table = 'recommendation_rules';

    // حماية الحقول (استخدمنا guarded باش نسمحوا بإدخال كل الحقول بدون استثناء)
    protected $guarded = [];

    // تحويل أنواع البيانات باش اللارافل يفهمها صح
    protected $casts = [
        'is_active' => 'boolean',
    ];
}