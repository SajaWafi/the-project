<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminAppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with([
                'doctor.user',
                'parent.user',
                'child',
                'workplace',
            ])
            ->orderBy('date')
            ->orderByRaw("
                CASE 
                    WHEN from_period = 'AM' AND from_hour = 12 THEN 0
                    WHEN from_period = 'AM' THEN from_hour
                    WHEN from_period = 'PM' AND from_hour = 12 THEN 12
                    ELSE from_hour + 12
                END
            ")
            ->orderBy('from_minute')
            ->paginate(10);

        return view('admin.appointments_management', compact('appointments'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',

            'from_hour' => 'required|integer|min:1|max:12',
            'from_minute' => 'required|integer|in:0,15,30,45',
            'from_period' => 'required|in:AM,PM',

            'to_hour' => 'required|integer|min:1|max:12',
            'to_minute' => 'required|integer|in:0,15,30,45',
            'to_period' => 'required|in:AM,PM',

            'status' => 'required|in:pending,scheduled,completed,cancelled',
            'note' => 'nullable|string|max:1000',
        ]);

        $appointment = Appointment::findOrFail($id);

        DB::beginTransaction();

        try {
            $appointment->update([
                'date' => $request->date,

                'from_hour' => $request->from_hour,
                'from_minute' => $request->from_minute,
                'from_period' => $request->from_period,

                'to_hour' => $request->to_hour,
                'to_minute' => $request->to_minute,
                'to_period' => $request->to_period,

                'status' => $request->status,
                'note' => $request->note,
            ]);

            DB::commit();

            return back()->with('success', 'Appointment updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);

        $appointment->delete();

        return back()->with('success', 'Appointment deleted successfully.');
    }
}