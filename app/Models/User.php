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

    /*
    |----------------------------------------------------------------------
    | Relationships
    |----------------------------------------------------------------------
    */

    public function child()
    {
        return $this->hasOne(\App\Models\ParentModule\Child::class, 'parent_id');
    }

    public function doctorProfile()
    {
        return $this->hasOne(\App\Models\DoctorProfile::class, 'user_id');
    }

    public function parentProfile()
    {
        return $this->hasOne(\App\Models\ParentProfile::class, 'user_id');
    }

    /*
    |----------------------------------------------------------------------
    | Model Events
    |----------------------------------------------------------------------
    */

    protected static function booted()
<<<<<<< HEAD
    {
        static::deleting(function ($user) {
            $user->doctorProfile()?->delete();
            $user->child()?->delete();
        });
    }
=======
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
>>>>>>> 88c2a8cecd71617fb87e2e367d1b90a2772dcee7
}

   
    