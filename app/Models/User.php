<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    // ⚠️ إذا عندك أكثر من طفل خليه hasMany مش hasOne
    public function children()
    {
        return $this->hasMany(\App\Models\Child::class, 'parent_id');
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
    {
        static::deleting(function ($user) {

            // حذف بيانات الطبيب
            if ($user->doctorProfile) {
                $user->doctorProfile()->delete();
            }

            // حذف الأطفال المرتبطين
            if ($user->children) {
                $user->children()->delete();
            }

            // حذف parent profile
            if ($user->parentProfile) {
                $user->parentProfile()->delete();
            }
        });
    }
}


