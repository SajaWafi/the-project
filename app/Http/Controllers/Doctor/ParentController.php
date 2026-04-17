<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\DoctorProfile;
use App\Models\ParentProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ParentController extends Controller
{
     public function index(Request $request)
    {
        $doctor = DoctorProfile::where('user_id', auth()->id())->first();

        if (!$doctor) {
            return back()->withErrors(['doctor' => 'Doctor profile not found.']);
        }

        $search = trim($request->search ?? '');

        $parents = ParentProfile::with(['user', 'children.doctors'])
            ->get()
            ->filter(function ($parent) use ($doctor, $search) {
                $linkedChildren = $parent->children->filter(function ($child) use ($doctor) {
                    return $child->doctors->contains('id', $doctor->id);
                });

                if ($linkedChildren->isEmpty()) {
                    return false;
                }

                if ($search === '') {
                    return true;
                }

                $parentName = strtolower(trim(
                    ($parent->user->first_name ?? '') . ' ' . ($parent->user->last_name ?? '')
                ));

                $matchesParent = str_contains($parentName, strtolower($search));

                $matchesChild = $linkedChildren->contains(function ($child) use ($search) {
                    return str_contains(strtolower($child->name ?? ''), strtolower($search));
                });

                return $matchesParent || $matchesChild;
            })
            ->map(function ($parent) use ($doctor) {
                $linkedChild = $parent->children->first(function ($child) use ($doctor) {
                    return $child->doctors->contains('id', $doctor->id);
                });

                $parentName = trim(
                    ($parent->user->first_name ?? '') . ' ' . ($parent->user->last_name ?? '')
                );

                if ($parentName === '') {
                    $parentName = 'No Name';
                }

                return [
                    'id' => $parent->id,
                    'name' => $parentName,
                    'subtitle' => $linkedChild
                        ? $linkedChild->name . "'s parent"
                        : 'No child linked',
                    'image' => 'child.png',
                ];
            })
            ->values();

        return view('doctor.parents', compact('parents', 'search'));
    }
    public function show($id)
    {
        $doctor = DoctorProfile::where('user_id', auth()->id())->first();

        if (!$doctor) {
            return back()->withErrors(['doctor' => 'Doctor profile not found.']);
        }

        $parent = ParentProfile::with(['user', 'children.doctors'])->findOrFail($id);

        $linkedChild = $parent->children->first(function ($child) use ($doctor) {
            return $child->doctors->contains('id', $doctor->id);
        });

        if (!$linkedChild) {
            abort(404);
        }

        $parentName = trim(
            ($parent->user->first_name ?? '') . ' ' . ($parent->user->last_name ?? '')
        );

        if ($parentName === '') {
            $parentName = 'No Name';
        }

        $childAge = $linkedChild->birth_date
            ? Carbon::parse($linkedChild->birth_date)->age . ' years'
            : 'Not available';

        $data = [
            'id' => $parent->id,
            'name' => $parentName,
            'subtitle' => $linkedChild->name . "'s parent",
            'image' => 'p1.png',
            'phone' => $parent->user->phone ?? 'No Phone',
            'autism_level' => $linkedChild->autism_level ?? 'Not set',
            'age' => $childAge,
        ];

        return view('doctor.parent-profile', ['parent' => $data]);
    }

    public function chat($id)
    {
        $doctor = DoctorProfile::where('user_id', auth()->id())->first();

        if (!$doctor) {
            return back()->withErrors(['doctor' => 'Doctor profile not found.']);
        }

        $parent = ParentProfile::with(['user', 'children.doctors'])->findOrFail($id);

        $linkedChild = $parent->children->first(function ($child) use ($doctor) {
            return $child->doctors->contains('id', $doctor->id);
        });

        if (!$linkedChild) {
            abort(404);
        }

        $parentName = trim(
            ($parent->user->first_name ?? '') . ' ' . ($parent->user->last_name ?? '')
        );

        if ($parentName === '') {
            $parentName = 'Parent';
        }

        $data = [
            'id' => $parent->id,
            'name' => $parentName,
            'image' => 'child.png',
        ];

        return view('doctor.chat', ['parent' => $data]);
    }
    public function searchAjax(Request $request)
{
    $doctor = DoctorProfile::where('user_id', auth()->id())->first();

    if (!$doctor) {
        return response()->json([
            'parents' => [],
            'message' => 'Doctor profile not found.'
        ], 404);
    }

    $search = trim($request->search ?? '');

    $parents = ParentProfile::with(['user', 'children.doctors'])
        ->get()
        ->filter(function ($parent) use ($doctor, $search) {
            $linkedChildren = $parent->children->filter(function ($child) use ($doctor) {
                return $child->doctors->contains('id', $doctor->id);
            });

            if ($linkedChildren->isEmpty()) {
                return false;
            }

            if ($search === '') {
                return true;
            }

            $parentName = strtolower(trim(
                ($parent->user->first_name ?? '') . ' ' . ($parent->user->last_name ?? '')
            ));

            $matchesParent = str_contains($parentName, strtolower($search));

            $matchesChild = $linkedChildren->contains(function ($child) use ($search) {
                return str_contains(strtolower($child->name ?? ''), strtolower($search));
            });

            return $matchesParent || $matchesChild;
        })
        ->map(function ($parent) use ($doctor) {
            $linkedChild = $parent->children->first(function ($child) use ($doctor) {
                return $child->doctors->contains('id', $doctor->id);
            });

            $parentName = trim(
                ($parent->user->first_name ?? '') . ' ' . ($parent->user->last_name ?? '')
            );

            if ($parentName === '') {
                $parentName = 'No Name';
            }

            return [
                'id' => $parent->id,
                'name' => $parentName,
                'subtitle' => $linkedChild
                    ? $linkedChild->name . "'s parent"
                    : 'No child linked',
                'image' => asset('images/child.png'),
                'profile_url' => route('doctor.parent.profile', ['id' => $parent->id]),
                'chat_url' => route('doctor.chat', ['parentId' => $parent->id]),
            ];
        })
        ->values();

    return response()->json([
        'parents' => $parents,
    ]);
}
}