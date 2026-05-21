<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Alert;
use Carbon\Carbon;

class SensorReading extends Model
{
    protected $fillable = [
        'bracelet_id',
        'child_id',
        'heart_rate',
        'motion_level',
        'pressure_level',
        'place_value',
        'recorded_at',
    ];

    protected static function booted()
    {
        static::created(function ($reading) {

            $child = $reading->child;

            if (!$child) {
                return;
            }

            // حساب العمر
            $age = Carbon::parse($child->birth_date)->age;

            // الحد الطبيعي للنبض
            $maxHeartRate = 100;

            if ($age >= 3 && $age <= 5) {

                $maxHeartRate = 120;

            } elseif ($age >= 6 && $age <= 12) {

                $maxHeartRate = 110;

            } elseif ($age >= 13) {

                $maxHeartRate = 100;
            }

            // إذا النبض مرتفع
            if ($reading->heart_rate > $maxHeartRate) {

                Alert::create([

                    'child_id' => $child->id,

                    'parent_id' => $child->parent_id,

                    'title' => 'High Heart Rate Alert',

                    'message' =>
                        $child->name .
                        ' has a high heart rate (' .
                        $reading->heart_rate .
                        ' BPM)',

                    'alert_type' => 'heart_rate',

                    'is_read' => false,

                    'sent_at' => now(),
                ]);
            }

        });
    }

    // العلاقة مع الطفل
    public function child()
    {
        return $this->belongsTo(Child::class);
    }
}