<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorRequest extends Model
{
    use HasFactory;

    // اسم الجدول في قاعدة البيانات
    protected $table = 'doctor_requests';

    // الحقول المسموح بتعبئتها
    protected $fillable = [
        'doctor_id',
        'parent_id',
        'status', // pending, accepted, rejected
    ];

    // علاقة الطلب بالدكتور (من جدول users)
   public function doctor()
{
    // هنا نقولوله إن الـ doctor_id مربوط بجدول doctor_profiles
    return $this->belongsTo(DoctorProfile::class, 'doctor_id');
}

    // علاقة الطلب بالأب (من جدول users)
    public function parentUser()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    // داخل كلاس DoctorRequest ضيف هادي الدالة:
    public function parentProfile()
    {
        return $this->belongsTo(ParentProfile::class, 'parent_id');
    }
    /**
     * علاقة الطلب بالولي (Parent)
     */
    public function parent()
    {
        // ملاحظة: لو كان اسم المودل بتاعك يختلف (مثلا ParentModel أو User)، غيره هنا
        return $this->belongsTo(ParentProfile::class, 'parent_id');
    }
}