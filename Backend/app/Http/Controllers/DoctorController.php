<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::with('user');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('spesialis', 'like', "%{$search}%")
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
        }

        $doctors = $query->paginate(10);

        return response()->json($doctors);
    }

    public function show($id)
    {
        $doctor = Doctor::with(['user', 'appointments', 'medicalRecords'])->findOrFail($id);

        return response()->json($doctor);
    }

    public function schedule($id)
    {
        $doctor = Doctor::findOrFail($id);
        $appointments = $doctor->appointments()
            ->where('status', '!=', 'cancelled')
            ->orderBy('tanggal_jam')
            ->get();

        return response()->json([
            'doctor' => $doctor,
            'appointments' => $appointments,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'spesialis' => 'required|string',
            'nomor_sertifikat' => 'required|string|unique:doctors',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|string',
        ]);

        $doctor = Doctor::create($validated);

        return response()->json($doctor, 201);
    }

    public function update(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);

        $validated = $request->validate([
            'spesialis' => 'sometimes|string',
            'nomor_sertifikat' => 'sometimes|string|unique:doctors,nomor_sertifikat,' . $id,
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|string',
            'rating' => 'sometimes|numeric|min:0|max:5',
            'status' => 'sometimes|in:available,unavailable',
        ]);

        $doctor->update($validated);

        return response()->json($doctor);
    }

    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->delete();

        return response()->json(['message' => 'Doctor deleted successfully']);
    }
}
