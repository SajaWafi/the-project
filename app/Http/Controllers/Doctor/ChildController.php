<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\ChildDoctor;
use App\Models\DoctorProfile;
use Illuminate\Http\Request;

class ChildController extends Controller
{
    //search page for child
    public function searchPage()
    {
        $doctor = DoctorProfile::where('user_id', auth()->id())->first();

        if (!$doctor) {
            return back()->withErrors(['doctor' => 'Doctor profile not found.']);
        }

        $linkedChildIds = ChildDoctor::where('doctor_id', $doctor->id)
            ->pluck('child_id')
            ->toArray();

        return view('doctor.search-child', compact('linkedChildIds'));
    }

    //find child
    public function find(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
        ]);

        $doctor = DoctorProfile::where('user_id', auth()->id())->first();

        if (!$doctor) {
            return back()->withErrors(['doctor' => 'Doctor profile not found.']);
        }

        $search = $request->search;

    $children = Child::with('parentProfile')
    ->when($search, function ($query) use ($search) {
        $query->where('name', 'like', '%' . $search . '%');
    })
    ->orderBy('name')
    ->get();

        $linkedChildIds = ChildDoctor::where('doctor_id', $doctor->id)
            ->pluck('child_id')
            ->toArray();

        return view('doctor.search-child', compact('children', 'search', 'linkedChildIds'));
    }

    //attach child
    public function attach($id)
    {
        $doctor = auth()->user()->doctorProfile;
        if (!$doctor) return back()->withErrors(['doctor' => 'Doctor profile not found.']);

        $child = \App\Models\Child::findOrFail($id);
        $parent = \App\Models\ParentProfile::findOrFail($child->parent_id);

        $alreadyLinked = \Illuminate\Support\Facades\DB::table('child_doctor')
            ->where('child_id', $child->id)
            ->where('doctor_id', $doctor->id)
            ->exists();

        if ($alreadyLinked) return back()->withErrors(['link' => 'This parent is already linked to this doctor.']);

        $pendingRequest = \App\Models\DoctorRequest::where('doctor_id', $doctor->id)
            ->where('parent_id', $parent->id)
            ->where('status', 'pending')
            ->exists();

        if ($pendingRequest) return back()->withErrors(['request' => 'A pending request has already been sent.']);

        \App\Models\DoctorRequest::create([
            'doctor_id' => $doctor->id,
            'parent_id' => $parent->id,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Request sent successfully.');
    }

}