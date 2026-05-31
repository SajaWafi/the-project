<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ParentProfile;
use App\Models\Workplace;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $doctorProfile = auth()->user()->doctorProfile;
        if (!$doctorProfile) abort(404, 'Doctor profile not found.');

        $appointments = Appointment::with(['parent.user', 'child'])
            ->where('doctor_id', $doctorProfile->id)
            ->whereDate('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderByRaw("
                CASE 
                    WHEN from_period = 'AM' AND from_hour = 12 THEN 0
                    WHEN from_period = 'AM' THEN from_hour
                    WHEN from_period = 'PM' AND from_hour = 12 THEN 12
                    ELSE from_hour + 12
                END
            ")
            ->orderBy('from_minute')
            ->get();

        return view('doctor.appointments', compact('appointments'));
    }

    public function create()
    {
        $doctorProfile = auth()->user()->doctorProfile;
        if (!$doctorProfile) abort(404, 'Doctor profile not found.');

        $parents = ParentProfile::with(['user', 'children'])
            ->whereHas('children.doctors', function ($query) use ($doctorProfile) {
                $query->where('doctor_profiles.id', $doctorProfile->id);
            })->get();

        $workplaces = Workplace::where('doctor_id', $doctorProfile->id)->latest()->get();

        return view('doctor.add-appointment', compact('parents', 'workplaces'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'parent_id' => 'required|exists:parent_profiles,id',
            'workplace_id' => 'required|exists:workplaces,id',
            'date' => 'required|date',
            'from_hour' => 'required|integer|min:1|max:12',
            'from_minute' => 'required|integer|in:0,15,30,45',
            'from_period' => 'required|in:AM,PM',
            'to_hour' => 'required|integer|min:1|max:12',
            'to_minute' => 'required|integer|in:0,15,30,45',
            'to_period' => 'required|in:AM,PM',
            'note' => 'nullable|string|max:1000',
        ]);

        $doctorProfile = auth()->user()->doctorProfile;
        if (!$doctorProfile) return back()->withErrors(['doctor' => 'Doctor profile not found.'])->withInput();

        $parent = ParentProfile::with(['children.doctors'])->find($request->parent_id);
        if (!$parent) return back()->withErrors(['parent_id' => 'Selected parent not found.'])->withInput();

        $child = $parent->children->first(function ($child) use ($doctorProfile) {
            return $child->doctors->contains('id', $doctorProfile->id);
        });

        if (!$child) return back()->withErrors(['parent_id' => 'This parent is not linked to this doctor.'])->withInput();

        $workplace = Workplace::where('id', $request->workplace_id)->where('doctor_id', $doctorProfile->id)->first();
        if (!$workplace) return back()->withErrors(['workplace_id' => 'Selected workplace does not belong to this doctor.'])->withInput();

        Appointment::create([
            'doctor_id' => $doctorProfile->id,
            'parent_id' => $request->parent_id,
            'child_id' => $child->id,
            'workplace_id' => $workplace->id,
            'date' => $request->date,
            'from_hour' => $request->from_hour,
            'from_minute' => $request->from_minute,
            'from_period' => $request->from_period,
            'to_hour' => $request->to_hour,
            'to_minute' => $request->to_minute,
            'to_period' => $request->to_period,
            'status' => 'pending',
            'note' => $request->note,
        ]);

        return redirect()->route('doctor.appointments')->with('success', 'Appointment added successfully.');
    }

    public function edit($id)
    {
        $doctorProfile = auth()->user()->doctorProfile;
        if (!$doctorProfile) abort(404, 'Doctor profile not found.');

        $appointment = Appointment::where('doctor_id', $doctorProfile->id)->findOrFail($id);
        $parents = ParentProfile::with('user')->get();
        $workplaces = Workplace::where('doctor_id', $doctorProfile->id)->get();

        return view('doctor.edit-appointment', compact('appointment', 'parents', 'workplaces'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'parent_id' => 'required|exists:parent_profiles,id',
            'date' => 'required|date',
            'from_hour' => 'required|integer|min:1|max:12',
            'from_minute' => 'required|integer|in:0,15,30,45',
            'from_period' => 'required|in:AM,PM',
            'to_hour' => 'required|integer|min:1|max:12',
            'to_minute' => 'required|integer|in:0,15,30,45',
            'to_period' => 'required|in:AM,PM',
            'note' => 'nullable|string|max:1000',
        ]);

        $doctorProfile = auth()->user()->doctorProfile;
        $appointment = Appointment::where('doctor_id', $doctorProfile->id)->findOrFail($id);
        $parent = ParentProfile::with('children')->find($request->parent_id);
        $child = $parent->children->first();

        $appointment->update([
            'parent_id' => $request->parent_id,
            'child_id' => $child->id,
            'date' => $request->date,
            'from_hour' => $request->from_hour,
            'from_minute' => $request->from_minute,
            'from_period' => $request->from_period,
            'to_hour' => $request->to_hour,
            'to_minute' => $request->to_minute,
            'to_period' => $request->to_period,
            'note' => $request->note,
        ]);

        return redirect()->route('doctor.appointments')->with('success', 'Appointment updated successfully.');
    }

    public function destroy($id)
    {
        $doctorProfile = auth()->user()->doctorProfile;
        $appointment = Appointment::where('doctor_id', $doctorProfile->id)->findOrFail($id);
        $appointment->delete();

        return back()->with('success', 'Appointment deleted successfully.');
    }
}