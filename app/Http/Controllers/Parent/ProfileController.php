<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Parent\UpdateProfileRequest;
use App\Models\ParentModule\Child;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('edit-profile');
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        DB::beginTransaction();

        try {
            $userData = [];

            if ($request->filled('first_name')) {
                $userData['first_name'] = $request->first_name;
            }

            if ($request->filled('last_name')) {
                $userData['last_name'] = $request->last_name;
            }

            if ($request->filled('phone')) {
                $userData['phone'] = $request->phone;
            }

            if ($request->filled('email')) {
                $userData['email'] = $request->email;
            }

            if ($request->hasFile('profile_image')) {
                if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                    Storage::disk('public')->delete($user->profile_image);
                }

                $userData['profile_image'] = $request->file('profile_image')->store('profiles', 'public');
            }

            if (!empty($userData)) {
                $user->update($userData);
            }

            $childData = [];

            if ($request->filled('child_name')) {
                $childData['name'] = $request->child_name;
            }

            if ($request->filled('gender')) {
                $childData['gender'] = $request->gender;
            }

            if ($request->filled('autism_level')) {
                $childData['autism_level'] = $request->autism_level;
            }

            if ($request->filled('birth_date')) {
                $childData['birth_date'] = $request->birth_date;
            }

            if (!empty($childData)) {
                Child::updateOrCreate(
                    ['parent_id' => $user->id],
                    $childData
                );
            }

            DB::commit();

            return redirect()->back()->with('success', 'The data has been updated successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    // --- الإضافات الجديدة: تغيير كلمة المرور ---
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!$user || !Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password updated successfully');
    }

    // --- الإضافات الجديدة: حذف الحساب ---
    public function destroyAccount()
    {
        $user = Auth::user();
        DB::beginTransaction();
        
        try {
            if ($user?->parentProfile) {
                $user->parentProfile->children()->delete();
                $user->parentProfile()->delete();
            }
            
            $tempUser = $user;
            Auth::logout();
            
            if ($tempUser) {
                $tempUser->delete();
            }

            DB::commit();
            return redirect()->route('login.page')->with('success', 'Account deleted successfully');
            
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong while deleting the account.');
        }
    }
}