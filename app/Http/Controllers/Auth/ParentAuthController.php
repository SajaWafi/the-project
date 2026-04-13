<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Child;
use App\Models\ParentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ParentAuthController extends Controller
{
    public function create()
    {
        return view('auth.parent-signup');
    }

    public function store(Request $request)
    {
        $request->validate([
            // parent user
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:30',
            'gender' => 'nullable|in:Male,Female',
            'password' => 'required|string|min:6|confirmed',

            // parent profile
            'relation_to_child' => 'nullable|string|max:255',

            // child
            'child_name' => 'required|string|max:255',
            'child_gender' => 'nullable|in:Male,Female',
            'child_birth_date' => 'nullable|date',
            'autism_level' => 'nullable|in:Mild,Moderate,Severe',
        ]);

        DB::transaction(function () use ($request) {
            // 1) create user
            $user = User::create([
                'role' => 'parent',
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'password' => Hash::make($request->password),
            ]);

            // 2) create parent profile
            $parentProfile = ParentProfile::create([
                'user_id' => $user->id,
                'relation_to_child' => $request->relation_to_child,
            ]);

            // 3) create child
            Child::create([
                'parent_id' => $parentProfile->id,
                'name' => $request->child_name,
                'gender' => $request->child_gender,
                'birth_date' => $request->child_birth_date,
                'autism_level' => $request->autism_level,
            ]);
        });

        return redirect()->route('parent.signup')->with('success', 'Parent account created successfully.');
    }
}