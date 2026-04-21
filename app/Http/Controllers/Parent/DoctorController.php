<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\DoctorProfile;
use App\Models\ParentProfile;

class DoctorController extends Controller
{
    public function index()
    {
        $parent = ParentProfile::with(['children.doctors.user'])
            ->where('user_id', auth()->id())
            ->first();

        if (!$parent) {
            return back()->withErrors(['parent' => 'Parent profile not found.']);
        }

        $doctors = collect();

        foreach ($parent->children as $child) {
            foreach ($child->doctors as $doctor) {
                $doctorName = trim(
                    ($doctor->user->first_name ?? '') . ' ' . ($doctor->user->last_name ?? '')
                );

                if ($doctorName === '') {
                    $doctorName = 'No Name';
                }

                $doctors->push([
                    'id' => $doctor->id,
                    'name' => $doctorName,
                    'specialty' => $doctor->specialization ?? 'No Specialty',
                    'image' => 'doctor1.png',
                ]);
            }
        }

        $doctors = $doctors->unique('id')->values();

        return view('parents.doctors', compact('doctors'));
    }

   public function show($id)
{
    $parent = ParentProfile::with(['children.doctors.user'])
        ->where('user_id', auth()->id())
        ->first();

    if (!$parent) {
        return back()->withErrors(['parent' => 'Parent profile not found.']);
    }

    $doctor = DoctorProfile::with('user')->findOrFail($id);

    $isLinked = $parent->children->contains(function ($child) use ($doctor) {
        return $child->doctors->contains('id', $doctor->id);
    });

    if (!$isLinked) {
        abort(404);
    }

    $doctorName = trim(
        ($doctor->user->first_name ?? '') . ' ' . ($doctor->user->last_name ?? '')
    );

    if ($doctorName === '') {
        $doctorName = 'No Name';
    }

    $data = [
        'id' => $doctor->id,
        'name' => $doctorName,
        'specialty' => $doctor->specialization ?? 'No Specialty',
        'image' => $doctor->user->profile_image ?? null,
        'phone' => $doctor->user->phone ?? 'No Phone',
        'bio' => $doctor->bio ?? 'No bio available',
    ];

    return view('parents.doctor-profile', ['doctor' => $data]);
}

    public function chat($id)
    {
        $parent = ParentProfile::with(['children.doctors.user'])
            ->where('user_id', auth()->id())
            ->first();

        if (!$parent) {
            return back()->withErrors(['parent' => 'Parent profile not found.']);
        }

        $doctor = DoctorProfile::with('user')->findOrFail($id);

        $isLinked = $parent->children->contains(function ($child) use ($doctor) {
            return $child->doctors->contains('id', $doctor->id);
        });

        if (!$isLinked) {
            abort(404);
        }

        $doctorName = trim(
            ($doctor->user->first_name ?? '') . ' ' . ($doctor->user->last_name ?? '')
        );

        if ($doctorName === '') {
            $doctorName = 'Doctor';
        }

        $data = [
            'id' => $doctor->id,
            'name' => $doctorName,
            'image' => 'doctor1.png',
        ];

        return view('parents.chat', ['doctor' => $data]);
    }
}