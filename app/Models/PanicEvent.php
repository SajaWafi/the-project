<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PanicEvent extends Model
{
    use HasFactory;

    // استخدمنا guarded بدل fillable باش يقبل أي أعمدة عندك في الجدول بدون مشاكل
    protected $guarded = [];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }
}