<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    // Get all appointments (admin view)
    public function getAll(Request $request)
    {
        $appointments = Appointment::with(['user', 'doctor.user'])
            ->orderByDesc('appointment_date')
            ->get();

        return response()->json($appointments);
    }

    // Get user's appointments
    public function index(Request $request)
    {
        $user = $request->user();
        $appointments = Appointment::where('user_id', $user->id)
            ->with(['doctor.user'])
            ->orderByDesc('appointment_date')
            ->paginate(10);

        return response()->json($appointments);
    }

    public function show($id)
    {
        $appointment = Appointment::with(['user', 'doctor.user'])->findOrFail($id);

        return response()->json($appointment);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date_format:Y-m-d H:i:s|after:now',
            'reason' => 'nullable|string',
        ]);

        $appointment = Appointment::create([
            'user_id' => $request->user()->id,
            'doctor_id' => $validated['doctor_id'],
            'appointment_date' => $validated['appointment_date'],
            'reason' => $validated['reason'] ?? null,
            'status' => 'pending',
        ]);

        return response()->json($appointment, 201);
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $validated = $request->validate([
            'status' => 'sometimes|in:pending,confirmed,completed,cancelled',
            'catatan_dokter' => 'nullable|string',
            'appointment_date' => 'sometimes|date_format:Y-m-d H:i:s',
            'reason' => 'nullable|string',
        ]);

        $appointment->update($validated);

        return response()->json($appointment);
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return response()->json(['message' => 'Appointment deleted']);
    }
}
