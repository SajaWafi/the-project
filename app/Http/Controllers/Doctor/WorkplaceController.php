<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Workplace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkplaceController extends Controller
{
    // 1. عرض صفحة أماكن العمل
    public function index()
    {
        $doctorProfile = Auth::user()->doctorProfile;

        if (!$doctorProfile) {
            abort(404, 'Doctor profile not found.');
        }

        $workplaces = Workplace::where('doctor_id', $doctorProfile->id)
            ->latest()
            ->get();

        return view('doctor.workplace-timing', compact('workplaces'));
    }

    // 2. عرض صفحة إضافة مكان عمل جديد
    public function create()
    {
        return view('doctor.add-workplace');
    }

    // 3. حفظ مكان العمل الجديد في قاعدة البيانات
    public function store(Request $request)
    {
        $request->validate([
            'days' => 'required|array|min:1',
            'days.*' => 'in:SAT,SUN,MON,TUE,WED,THU,FRI',
            'from_hour' => 'required',
            'from_minute' => 'required',
            'from_period' => 'required|in:AM,PM',
            'to_hour' => 'required',
            'to_minute' => 'required',
            'to_period' => 'required|in:AM,PM',
            'place_name' => 'required|string|max:255',
        ]);

        $doctorProfile = Auth::user()->doctorProfile;

        if (!$doctorProfile) {
            return back()->withErrors([
                'doctor' => 'Doctor profile not found.',
            ]);
        }

        Workplace::create([
            'doctor_id' => $doctorProfile->id,
            'place_name' => $request->place_name,
            'from_hour' => $request->from_hour,
            'from_minute' => $request->from_minute,
            'from_period' => $request->from_period,
            'to_hour' => $request->to_hour,
            'to_minute' => $request->to_minute,
            'to_period' => $request->to_period,
            'days' => $request->days,
        ]);

        return back()->with('success', 'Workplace added successfully');
    }

    // 4. عرض صفحة تعديل مكان العمل
    public function edit($id)
    {
        $doctorProfile = Auth::user()->doctorProfile;

        $workplace = Workplace::where('doctor_id', $doctorProfile->id)
            ->findOrFail($id);

        return view('doctor.edit-workplace', [
            'workplace' => $workplace->toArray()
        ]);
    }

    // 5. تحديث بيانات مكان العمل
    public function update(Request $request, $id)
    {
        $request->validate([
            'days' => 'required|array|min:1',
            'days.*' => 'in:SAT,SUN,MON,TUE,WED,THU,FRI',
            'from_hour' => 'required',
            'from_minute' => 'required',
            'from_period' => 'required|in:AM,PM',
            'to_hour' => 'required',
            'to_minute' => 'required',
            'to_period' => 'required|in:AM,PM',
            'place_name' => 'required|string|max:255',
        ]);

        $doctorProfile = Auth::user()->doctorProfile;

        $workplace = Workplace::where('doctor_id', $doctorProfile->id)->findOrFail($id);

        $workplace->update([
            'place_name' => $request->place_name,
            'from_hour' => $request->from_hour,
            'from_minute' => $request->from_minute,
            'from_period' => $request->from_period,
            'to_hour' => $request->to_hour,
            'to_minute' => $request->to_minute,
            'to_period' => $request->to_period,
            'days' => $request->days,
        ]);

        return back()->with('success', 'Workplace updated successfully');
    }

    // 6. حذف مكان العمل
    public function destroy($id)
    {
        $doctorProfile = Auth::user()->doctorProfile;

        $workplace = Workplace::where('doctor_id', $doctorProfile->id)->findOrFail($id);
        $workplace->delete();

        return back()->with('success', 'Workplace deleted');
    }
}