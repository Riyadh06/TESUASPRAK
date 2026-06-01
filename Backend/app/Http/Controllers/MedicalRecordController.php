<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $records = MedicalRecord::where('user_id', $user->id)
            ->with(['doctor.user'])
            ->orderByDesc('tanggal_periksa')
            ->paginate(10);

        return response()->json($records);
    }

    public function show($id)
    {
        $record = MedicalRecord::with(['user', 'doctor.user'])->findOrFail($id);

        return response()->json($record);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:doctors,id',
            'tanggal_periksa' => 'required|date_format:Y-m-d H:i:s',
            'diagnosis' => 'required|string',
            'resep' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $record = MedicalRecord::create($validated);

        return response()->json($record, 201);
    }

    public function update(Request $request, $id)
    {
        $record = MedicalRecord::findOrFail($id);

        $validated = $request->validate([
            'diagnosis' => 'sometimes|string',
            'resep' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $record->update($validated);

        return response()->json($record);
    }

    public function destroy($id)
    {
        $record = MedicalRecord::findOrFail($id);
        $record->delete();

        return response()->json(['message' => 'Medical record deleted']);
    }
}
