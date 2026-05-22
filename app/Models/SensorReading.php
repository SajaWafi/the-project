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

            // normal heart rate by age

            $age = \Carbon\Carbon::parse($child->birth_date)->age;

            if ($age >= 3 && $age <= 5) {
                $maxHeartRate = 120;
            } elseif ($age >= 6 && $age <= 12) {
                $maxHeartRate = 110;
            } else {
                $maxHeartRate = 100;
            }


            // CONDITIONS 

            $highHeartRate = $reading->heart_rate > $maxHeartRate;
            $highActivity = $reading->motion_level >= 71;
            $lowActivity = $reading->motion_level <= 20;

            // ALERTS LOGIC 

            if ($highHeartRate && $highActivity) {
                
                Alert::create([
                    'child_id'   => $child->id,
                    'parent_id'  => $child->parent_id,
                    'title'      => 'Panic Alert',
                    'message'    => $child->name . ' may be experiencing a panic episode. Heart Rate: ' . $reading->heart_rate . ' BPM, Activity: ' . $reading->motion_level . '%',
                    'alert_type' => 'panic',
                    'is_read'    => false,
                    'sent_at'    => now(),
                ]);

            } elseif ($highHeartRate) {
                
                Alert::create([
                    'child_id'   => $child->id,
                    'parent_id'  => $child->parent_id,
                    'title'      => 'High Heart Rate Alert',
                    'message'    => $child->name . ' has a high heart rate (' . $reading->heart_rate . ' BPM)',
                    'alert_type' => 'heart_rate',
                    'is_read'    => false,
                    'sent_at'    => now(),
                ]);

            } elseif ($highActivity) {
                
                Alert::create([
                    'child_id'   => $child->id,
                    'parent_id'  => $child->parent_id,
                    'title'      => 'High Activity Alert',
                    'message'    => $child->name . ' has unusual hyper activity (' . $reading->motion_level . '%)',
                    'alert_type' => 'activity',
                    'is_read'    => false,
                    'sent_at'    => now(),
                ]);

            } elseif ($lowActivity) {
                
                Alert::create([
                    'child_id'   => $child->id,
                    'parent_id'  => $child->parent_id,
                    'title'      => 'Low Activity Alert',
                    'message'    => $child->name . ' has very low activity (' . $reading->motion_level . '%)',
                    'alert_type' => 'activity',
                    'is_read'    => false,
                    'sent_at'    => now(),
                ]);

            }

        });
    }

    // child reatin
    public function child()
    {
        return $this->belongsTo(Child::class);
    }
}