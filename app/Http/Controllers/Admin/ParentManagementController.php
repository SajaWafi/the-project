<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Child;
use Illuminate\Support\Facades\Hash;

class ParentManagementController extends Controller
{
    public function index()
    {
        $parents = ParentProfile::with(['user', 'children'])
            ->latest()
            ->paginate(10);

        return view('admin.parents_management', compact('parents'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'relation_to_child' => 'nullable|string|max:255',

            'child_name' => 'required|string|max:255',
            'child_gender' => 'nullable|in:Male,Female',
            'autism_level' => 'nullable|in:Mild,Moderate,Severe',
        ]);

        $parent = ParentProfile::with(['user', 'children'])->findOrFail($id);

        DB::beginTransaction();

        try {
            if ($parent->user) {
                $parent->user->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'phone' => $request->phone,
                ]);
            }

            $parent->update([
                'relation_to_child' => $request->relation_to_child,
            ]);

            $child = $parent->children->first();

            if ($child) {
                $child->update([
                    'name' => $request->child_name,
                    'gender' => $request->child_gender,
                    'autism_level' => $request->autism_level,
                ]);
            }

            DB::commit();

            return back()->with('success', 'Parent updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $parent = ParentProfile::with(['user', 'children'])->findOrFail($id);

        DB::beginTransaction();

        try {
            if ($parent->user) {
                $parent->user->delete();
            } else {
                $parent->delete();
            }

            DB::commit();

            return back()->with('success', 'Parent deleted successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }
    public function store(Request $request)
{
    $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone' => 'nullable|string|max:30',
        'gender' => 'nullable|in:Male,Female',
        'password' => 'required|string|min:6',

        'relation_to_child' => 'nullable|string|max:255',

        'child_name' => 'required|string|max:255',
        'child_gender' => 'nullable|in:Male,Female',
        'child_birth_date' => 'nullable|date',
        'autism_level' => 'nullable|in:Mild,Moderate,Severe',
    ]);

    DB::beginTransaction();

    try {
        $user = User::create([
            'role' => 'parent',
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'password' => Hash::make($request->password),
        ]);

        $parent = ParentProfile::create([
            'user_id' => $user->id,
            'relation_to_child' => $request->relation_to_child,
        ]);

        Child::create([
            'parent_id' => $parent->id,
            'name' => $request->child_name,
            'gender' => $request->child_gender,
            'birth_date' => $request->child_birth_date,
            'autism_level' => $request->autism_level,
        ]);

        DB::commit();

        return back()->with('success', 'Parent and child added successfully.');
    } catch (\Throwable $e) {
        DB::rollBack();

        return back()->with('error', $e->getMessage());
    }
}
}
