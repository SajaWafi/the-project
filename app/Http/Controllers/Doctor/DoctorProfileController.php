<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\UpdateDoctorProfileRequest;
use App\Models\DoctorProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DoctorProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user()->load('doctorProfile');

        return view('doctor.edit-profile', compact('user'));
    }

    public function update(UpdateDoctorProfileRequest $request)
    {
        $user = Auth::user();

        DB::beginTransaction();

        try {
            $userData = [];

            if ($request->filled('full_name')) {
                $parts = preg_split('/\s+/', trim($request->full_name), 2);
                $userData['first_name'] = $parts[0] ?? null;
                $userData['last_name'] = $parts[1] ?? '';
            }

            if ($request->filled('phone')) {
                $userData['phone'] = $request->phone;
            }

            if ($request->filled('email')) {
                $userData['email'] = $request->email;
            }

            if ($request->filled('gender')) {
                $userData['gender'] = ucfirst(strtolower($request->gender));
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

            $doctorProfileData = [];

            if ($request->filled('specialize')) {
                $doctorProfileData['specialization'] = $request->specialize;
            }

            if ($request->filled('bio')) {
                $doctorProfileData['bio'] = $request->bio;
            }

            if ($request->filled('birth_day') && $request->filled('birth_month') && $request->filled('birth_year')) {
                $doctorProfileData['birth_date'] = $request->birth_year . '-' . $request->birth_month . '-' . $request->birth_day;
            }

            if (!empty($doctorProfileData)) {
                DoctorProfile::updateOrCreate(
                    ['user_id' => $user->id],
                    $doctorProfileData
                );
            }

            DB::commit();

            return redirect()->back()->with('success', 'تم تحديث بيانات الدكتور بنجاح');
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
}