<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\SafeZone;
use App\Models\Child;
use App\Models\ParentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SafeZoneController extends Controller
{
    // عرض الواجهة مع الأماكن المحفوظة
    public function index()
    {
        $parentProfile = ParentProfile::where('user_id', Auth::id())->first();
        
        $safeZones = collect();
        $child = null;

        if ($parentProfile) {
            $child = Child::where('parent_id', $parentProfile->id)->first();
            
            if ($child) {
                //نجيبوا السيف زون متاع هذا الطفل
                $safeZones = SafeZone::where('child_id', $child->id)->get();
            }
        }

        return view('safe-zone-settings', compact('safeZones', 'child'));
    }

    // حفظ منطقة جديدة
    public function store(Request $request)
    {
        $request->validate([
            'zone_name' => 'required|string|max:255',
            'center_latitude' => 'required|numeric',
            'center_longitude' => 'required|numeric',
            'radius_meters' => 'required|numeric|min:10'
        ]);

        // نفس التسلسل المنطقي للتخزين
        $parentProfile = ParentProfile::where('user_id', Auth::id())->first();

        if (!$parentProfile) {
            return back()->with('error', 'Parent account settings are incomplete.');
        }

        $child = Child::where('parent_id', $parentProfile->id)->first();

        if (!$child) {
            return back()->with('error', 'No child is linked to this account.');
        }

        // التخزين النهائي
        SafeZone::create([
            'child_id' => $child->id,
            'zone_name' => $request->zone_name,
            'center_latitude' => $request->center_latitude,
            'center_longitude' => $request->center_longitude,
            'radius_meters' => $request->radius_meters,
            'is_active' => true,
        ]);

        return back()->with('success', 'Safe zone saved successfully!');
    }

    // حذف منطقة
    public function destroy($id)
    {
        $zone = SafeZone::findOrFail($id);
        
        $parentProfile = ParentProfile::where('user_id', Auth::id())->first();
        //يمنع ثغرة خطيرة (IDOR)، حيث لا يمكن لأي مستخدم آخر تغيير الـ ID
        if ($parentProfile) {
            $child = Child::where('parent_id', $parentProfile->id)->where('id', $zone->child_id)->first();
            
            if ($child) {
                $zone->delete();
            }
        }

        return back()->with('success', 'Safe zone removed!');
    }
}