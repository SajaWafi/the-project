<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'role',
        'first_name',
        'last_name',
        'email',
        'phone',
        'gender',
        'profile_image',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function parentProfile()
    {
        return $this->hasOne(ParentProfile::class);
    }
}