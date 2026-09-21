<?php

namespace App\Http\Controllers;

use App\Models\FarmerField;
use App\Models\diseases_symptoms;
use Illuminate\Http\Request;

class FarmerController extends Controller
{
    public function farmerHome()
    {
        return view('farmer.farmerhome');
    }

    public function field()
    {
        $field = FarmerField::where('user_id', auth()->id())->first();

        return view('farmer.field', compact('field'));
    }

    public function saveField(Request $request)
    {
        $validated = $request->validate([
            'area' => 'required|numeric|min:0.01',
            'area_unit' => 'required|in:acre,cent',
            'plant_count' => 'required|integer|min:1',
        ]);

        FarmerField::updateOrCreate(
            ['user_id' => auth()->id()],
            $validated
        );

        return redirect()
            ->route('farmer.field')
            ->with(
                'ff_success',
                'Your field details have been saved successfully.'
            );
    }

    public function diseaseIdentification()
    {
        $symptoms = diseases_symptoms::orderBy(
            'symptom_id',
            'asc'
        )->get();

        return view(
            'farmer.disease-identification',
            compact('symptoms')
        );
    }

    public function analyzeDisease(Request $request)
    {
        $validated = $request->validate([
            'symptoms' => ['required', 'array', 'min:1'],
            'symptoms.*' => [
                'integer',
                'exists:diseases_symptoms,symptom_id'
            ],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Symptoms received successfully.',
            'selected_symptoms' => $validated['symptoms'],
        ]);
    }
}