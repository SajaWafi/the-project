<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = [
    'user_id',
    'message',
    'category',
    'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}