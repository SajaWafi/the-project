<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ParentModule\Child;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'gender',
        'profile_image',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function child()
    {
       return $this->hasOne(\App\Models\ParentModule\Child::class, 'parent_id');
    }

    public function doctorProfile()
    {
<<<<<<< HEAD
        return $this->hasOne(\App\Models\DoctorProfile::class);
    }
   
=======
        return $this->hasOne(\App\Models\DoctorProfile::class, 'user_id');
    }
    protected static function booted()
        {
            static::deleting(function ($user) {
                $user->doctorProfile()->delete();
                $user->child()->delete();
            });
        }
        public function parentProfile()
{
    return $this->hasOne(\App\Models\ParentProfile::class, 'user_id');
}
>>>>>>> 32430d76775c2256dea2acdf9252796e2db0ae09
}

   
    