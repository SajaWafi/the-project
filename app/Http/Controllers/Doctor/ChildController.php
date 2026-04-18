<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\ChildDoctor;
use App\Models\DoctorProfile;
use Illuminate\Http\Request;

class ChildController extends Controller
{
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

       $children = Child::with('parent.user')
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

    public function attach($id)
    {
        $doctor = DoctorProfile::where('user_id', auth()->id())->first();

        if (!$doctor) {
            return back()->withErrors(['doctor' => 'Doctor profile not found.']);
        }

        $child = Child::find($id);

        if (!$child) {
            return back()->withErrors(['child' => 'Child not found.']);
        }

        $exists = ChildDoctor::where('child_id', $child->id)
            ->where('doctor_id', $doctor->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'This child is already linked to you.');
        }

        ChildDoctor::create([
            'child_id' => $child->id,
            'doctor_id' => $doctor->id,
            'status' => 'active',
            'assigned_at' => now(),
        ]);

        return back()->with('success', 'Child added successfully.');
    }
}