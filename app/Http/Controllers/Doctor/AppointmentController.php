<?php
/*
namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['doctor', 'parent', 'child'])
            ->orderBy('date', 'asc')
            ->get();

        return response()->json($appointments);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctor_profiles,id',
            'parent_id' => 'required|exists:parent_profiles,id',
            'child_id' => 'required|exists:children,id',
            'date' => 'required|date',
            'from_hour' => 'required|integer|min:1|max:12',
            'from_minute' => 'required|integer|min:0|max:59',
            'from_period' => 'required|in:AM,PM',
            'to_hour' => 'required|integer|min:1|max:12',
            'to_minute' => 'required|integer|min:0|max:59',
            'to_period' => 'required|in:AM,PM',
            'clinic_name' => 'required|string|max:255',
            'note' => 'nullable|string',
        ]);

        $validated['status'] = 'scheduled';

        $appointment = Appointment::create($validated);

        return response()->json([
            'message' => 'Appointment created successfully',
            'data' => $appointment
        ], 201);
    }

    public function show($id)
    {
        $appointment = Appointment::with(['doctor', 'parent', 'child'])->findOrFail($id);

        return response()->json($appointment);
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctor_profiles,id',
            'parent_id' => 'required|exists:parent_profiles,id',
            'child_id' => 'required|exists:children,id',
            'date' => 'required|date',
            'from_hour' => 'required|integer|min:1|max:12',
            'from_minute' => 'required|integer|min:0|max:59',
            'from_period' => 'required|in:AM,PM',
            'to_hour' => 'required|integer|min:1|max:12',
            'to_minute' => 'required|integer|min:0|max:59',
            'to_period' => 'required|in:AM,PM',
            'clinic_name' => 'required|string|max:255',
            'note' => 'nullable|string',
            'status' => 'nullable|in:scheduled,completed,cancelled',
        ]);

        $appointment->update($validated);

        return response()->json([
            'message' => 'Appointment updated successfully',
            'data' => $appointment
        ]);
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return response()->json([
            'message' => 'Appointment deleted successfully'
        ]);
    }

    public function cancel($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update(['status' => 'cancelled']);

        return response()->json([
            'message' => 'Appointment cancelled successfully',
            'data' => $appointment
        ]);
    }

    public function complete($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update(['status' => 'completed']);

        return response()->json([
            'message' => 'Appointment marked as completed',
            'data' => $appointment
        ]);
    }

    public function doctorAppointments($doctorId)
    {
        $appointments = Appointment::with(['child', 'parent'])
            ->where('doctor_id', $doctorId)
            ->orderBy('date', 'asc')
            ->get();

        return response()->json($appointments);
    }

    public function childAppointments($childId)
    {
        $appointments = Appointment::with(['doctor', 'parent'])
            ->where('child_id', $childId)
            ->orderBy('date', 'asc')
            ->get();

        return response()->json($appointments);
    }
} */