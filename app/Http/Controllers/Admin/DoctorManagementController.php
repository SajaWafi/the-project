<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DoctorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DoctorManagementController extends Controller
{
    public function index()
    {
        $doctors = DoctorProfile::with('user')
            ->latest()
            ->paginate(10);

        return view('admin.doctors_management', compact('doctors'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
        ]);

        $doctor = DoctorProfile::with('user')->findOrFail($id);

        DB::beginTransaction();

        try {
            if ($doctor->user) {
                $doctor->user->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                ]);
            }

            $doctor->update([
                'specialization' => $request->specialization,
            ]);

            DB::commit();

            return back()->with('success', 'Doctor updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $doctor = DoctorProfile::with('user')->findOrFail($id);

        DB::beginTransaction();

        try {
            if ($doctor->user) {
                $doctor->user->delete();
            } else {
                $doctor->delete();
            }

            DB::commit();

            return back()->with('success', 'Doctor deleted successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }
}
