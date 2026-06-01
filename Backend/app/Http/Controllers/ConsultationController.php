<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $consultations = Consultation::where('user_id', $user->id)
            ->with(['doctor.user'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return response()->json($consultations);
    }

    public function show($id)
    {
        $consultation = Consultation::with(['user', 'doctor.user'])->findOrFail($id);

        return response()->json($consultation);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'topik' => 'required|string',
            'pertanyaan' => 'required|string',
        ]);

        $consultation = Consultation::create([
            'user_id' => $request->user()->id,
            'doctor_id' => $validated['doctor_id'],
            'topik' => $validated['topik'],
            'pertanyaan' => $validated['pertanyaan'],
            'status' => 'pending',
        ]);

        return response()->json($consultation, 201);
    }

    public function update(Request $request, $id)
    {
        $consultation = Consultation::findOrFail($id);

        $validated = $request->validate([
            'jawaban' => 'nullable|string',
            'status' => 'sometimes|in:pending,answered,closed',
        ]);

        $consultation->update($validated);

        return response()->json($consultation);
    }

    public function destroy($id)
    {
        $consultation = Consultation::findOrFail($id);
        $consultation->delete();

        return response()->json(['message' => 'Consultation deleted']);
    }
}
