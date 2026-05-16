<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DoctorRequest;
use Illuminate\Support\Facades\DB;

class DoctorRequestManagementController extends Controller
{
    public function index()
    {
        $requests = DoctorRequest::with([
                'doctor.user',
                'parent.user',
                'parent.children',
            ])
            ->latest()
            ->paginate(10);

        return view('admin.doctor_requests_management', compact('requests'));
    }

    public function accept($id)
    {
        $requestItem = DoctorRequest::with(['parent.children'])
            ->findOrFail($id);

        DB::beginTransaction();

        try {
            $child = $requestItem->parent?->children?->first();

            if (!$child) {
                return back()->withErrors([
                    'child' => 'No child found for this parent.',
                ]);
            }

            $alreadyLinked = DB::table('child_doctor')
                ->where('child_id', $child->id)
                ->where('doctor_id', $requestItem->doctor_id)
                ->exists();

            if (!$alreadyLinked) {
                DB::table('child_doctor')->insert([
                    'child_id' => $child->id,
                    'doctor_id' => $requestItem->doctor_id,
                ]);
            }

            $requestItem->update([
                'status' => 'accepted',
            ]);

            DB::commit();

            return back()->with('success', 'Request accepted and relationship fixed successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    public function reject($id)
    {
        $requestItem = DoctorRequest::findOrFail($id);

        $requestItem->update([
            'status' => 'rejected',
        ]);

        return back()->with('success', 'Request rejected successfully.');
    }

    public function destroy($id)
    {
        $requestItem = DoctorRequest::findOrFail($id);
        $requestItem->delete();

        return back()->with('success', 'Request deleted successfully.');
    }
}