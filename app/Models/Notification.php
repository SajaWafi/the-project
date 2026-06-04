<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'related_id', 
        'title', 
        'message', 
        'type'
    ];

    // علاقة الإشعار بالمستخدم (ولي الأمر)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}