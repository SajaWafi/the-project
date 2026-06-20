<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ParentProfile;
use App\Models\Workplace;
use App\Models\Notification; 
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a filtered list of appointments for the doctor.
     */
    public function index(Request $request)
    {
        $doctorProfile = auth()->user()->doctorProfile;

        if (!$doctorProfile) {
            abort(404, 'Doctor profile not found.');
        }

        $query = Appointment::with(['parent.user', 'child'])
            ->where('doctor_id', $doctorProfile->id)
            ->where('status', '!=', 'cancelled');

        //search filter
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->whereHas('parent.user', function($sub) use ($searchTerm) {
                    $sub->where('first_name', 'like', "%{$searchTerm}%")
                        ->orWhere('last_name', 'like', "%{$searchTerm}%");
                })
                ->orWhereHas('child', function($sub) use ($searchTerm) {
                    $sub->where('name', 'like', "%{$searchTerm}%");
                });
            });
        }

        //date filter
        $today = \Carbon\Carbon::today()->toDateString();
        $dateFilter = $request->input('date_filter', 'all');

        if ($dateFilter === 'today') {
            $query->whereDate('date', $today);
        } elseif ($dateFilter === 'tomorrow') {
            $query->whereDate('date', \Carbon\Carbon::tomorrow()->toDateString());
        } elseif ($dateFilter === 'week') {
            $query->whereBetween('date', [
                $today, 
                \Carbon\Carbon::today()->addDays(7)->toDateString()
            ]);
        } else {
            $query->whereDate('date', '>=', $today);
        }

        //order by date and time
        $appointments = $query->orderBy('date')
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

    /**
     * Show the form to create a new appointment.
     */
    public function create()
    {
        $doctorProfile = auth()->user()->doctorProfile;

        if (!$doctorProfile) {
            abort(404, 'Doctor profile not found.');
        }

        $parents = ParentProfile::with(['user', 'children'])
            ->whereHas('children.doctors', function ($query) use ($doctorProfile) {
                $query->where('doctor_profiles.id', $doctorProfile->id);
            })
            ->get();

        $workplaces = Workplace::where('doctor_id', $doctorProfile->id)
            ->latest()
            ->get();

        return view('doctor.add-appointment', compact('parents', 'workplaces'));
    }

    /**
     * Store a newly created appointment.
     */
    public function store(Request $request)
    {
        $request->validate([
            'parent_id' => 'required|exists:parent_profiles,id',
            'workplace_id' => 'required|exists:workplaces,id',
            'date' => 'required|date|after_or_equal:today',
            'from_hour' => 'required|integer|min:1|max:12',
            'from_minute' => 'required|integer|in:0,15,30,45',
            'from_period' => 'required|in:AM,PM',
            'to_hour' => 'required|integer|min:1|max:12',
            'to_minute' => 'required|integer|in:0,15,30,45',
            'to_period' => 'required|in:AM,PM',
            'note' => 'nullable|string|max:1000',
        ]);

        //get doctor profile
        $doctorProfile = auth()->user()->doctorProfile;

        if (!$doctorProfile) {
            return back()->withErrors(['doctor' => 'Doctor profile not found.'])->withInput();
        }

        //get parent profile
        $parent = ParentProfile::with(['children.doctors'])->find($request->parent_id);

        if (!$parent) {
            return back()->withErrors(['parent_id' => 'Selected parent not found.'])->withInput();
        }

        //get child profile
        $child = $parent->children->first(function ($child) use ($doctorProfile) {
            return $child->doctors->contains('id', $doctorProfile->id);
        });

        if (!$child) {
            return back()->withErrors(['parent_id' => 'This parent is not linked to this doctor.'])->withInput();
        }

        //get workplace profile
        $workplace = Workplace::where('id', $request->workplace_id)->where('doctor_id', $doctorProfile->id)->first();

        if (!$workplace) {
            return back()->withErrors(['workplace_id' => 'Selected workplace does not belong to this doctor.'])->withInput();
        }

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

    /**
     * Show the form to edit an existing appointment.
     */
    public function edit($id)
    {
        $doctorProfile = auth()->user()->doctorProfile;

        if (!$doctorProfile) {
            abort(404, 'Doctor profile not found.');
        }

        $appointment = Appointment::where('doctor_id', $doctorProfile->id)->findOrFail($id);
        $parents = ParentProfile::with('user')->get();
        $workplaces = Workplace::where('doctor_id', $doctorProfile->id)->get();

        return view('doctor.edit-appointment', compact('appointment', 'parents', 'workplaces'));
    }

    /**
     * Update an existing appointment and notify the parent.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'parent_id' => 'required|exists:parent_profiles,id',
            'date' => 'required|date|after_or_equal:today',
            'from_hour' => 'required|integer|min:1|max:12',
            'from_minute' => 'required|integer|in:0,15,30,45',
            'from_period' => 'required|in:AM,PM',
            'to_hour' => 'required|integer|min:1|max:12',
            'to_minute' => 'required|integer|in:0,15,30,45',
            'to_period' => 'required|in:AM,PM',
            'workplace_id' => 'required|exists:workplaces,id',
            'note' => 'nullable|string|max:1000',
        ]);

        //get doctor profile
        $doctorProfile = auth()->user()->doctorProfile;

        if (!$doctorProfile) {
            return back()->withErrors(['doctor' => 'Doctor profile not found.']);
        }

        //get appointment
        $appointment = Appointment::where('doctor_id', $doctorProfile->id)->findOrFail($id);
        
        //get parent profile
        $parent = ParentProfile::with('children')->find($request->parent_id);

        if (!$parent) {
            return back()->withErrors(['parent_id' => 'Selected parent not found.'])->withInput();
        }

        //get child profile
        $child = $parent->children->first();

        if (!$child) {
            return back()->withErrors(['parent_id' => 'This parent has no child linked.'])->withInput();
        }

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
            'status' => 'scheduled',
            'workplace_id' => $request->workplace_id,
            'note' => $request->note,
        ]);

        Notification::create([
            'user_id'    => $parent->user_id,
            'related_id' => $doctorProfile->id,
            'title'      => 'Appointment Updated',
            'message'    => 'The doctor has changed the appointment time. Please review the request and confirm your response.',
            'type'       => 'appointment_update',
        ]);

        return redirect()->route('doctor.appointments')->with('success', 'Appointment updated successfully.');
    }

    /**
     * Cancel an appointment.
     */
    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);

        $appointment->update([
            'status' => 'cancelled'
        ]);

        return redirect()->back()->with('success', 'Appointment has been cancelled successfully.');
    }
}