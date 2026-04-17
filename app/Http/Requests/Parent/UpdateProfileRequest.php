<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Parent\UpdateProfileRequest;
use App\Models\ParentModule\Child;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

            if ($request->filled('birth_date')) {
                $childData['birth_date'] = $request->birth_date;
            }

            if ($request->filled('autism_level')) {
                $childData['autism_level'] = $request->autism_level;
            }

            if (!empty($childData)) {
                Child::updateOrCreate(
                    ['parent_id' => $user->id],
                    $childData
                );
            }

            DB::commit();

            return redirect()->back()->with('success', 'تم تحديث البيانات بنجاح');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
}