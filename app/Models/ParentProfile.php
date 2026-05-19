<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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


    /*
    public function child()

    public function children()


{
    return $this->hasMany(\App\Models\Child::class, 'parent_id');
}

  */

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'parent_id');
    }

    /*
    public function child()
    {
        return $this->hasOne(\App\Models\ParentModule\Child::class, 'parent_id');

 } */

    // إذا نظامكم طفل واحد فقط لكل ولي أمر
    public function child()
    {
        return $this->hasOne(Child::class, 'parent_id');
   
    }


    public function children()
    {
        return $this->hasMany(Child::class, 'parent_id');
    }
     
    
}