<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentProfile extends Model
{
    protected $fillable = [
        'user_id',
        'relation_to_child',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function children()
    {
        return $this->hasMany(Child::class, 'parent_id');
    }
<<<<<<< HEAD

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'parent_id');
=======
    public function child()
    {
        return $this->hasOne(\App\Models\ParentModule\Child::class, 'parent_id');
>>>>>>> 32430d76775c2256dea2acdf9252796e2db0ae09
    }
}