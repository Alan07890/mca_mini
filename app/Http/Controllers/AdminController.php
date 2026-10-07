<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use App\Models\diseases_categories;
use App\Models\diseases_symptoms;
use App\Models\SymptomReg;
use App\Models\User;
use App\Models\fertilizer_reg;
use App\Models\FarmerField;
use App\Models\DiseaseFertilizer;
use App\Models\DiseaseAnalysisResult;

class AdminController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Admin Dashboard
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('admin.adminhome');
    }


    /*
    |--------------------------------------------------------------------------
    | Disease Category Management
    |--------------------------------------------------------------------------
    */

    public function category(Request $request)
    {
        $application = new diseases_categories();

        $application->diseases_name =
            $request->diseases_name;

        $application->save();

        return back()->with(
            'success',
            'Category added successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Symptom Management
    |--------------------------------------------------------------------------
    */

    public function symptoms()
    {
        return view('admin.symptoms');
    }


    public function symptom_insert(Request $request)
    {
        $request->validate([
            'symptom_name' => [
                'required',
                'string',
                'max:255'
            ],

            'symptom_image' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ],
        ]);


        $symptom = new diseases_symptoms();

        $symptom->symptom_name =
            $request->symptom_name;


        if ($request->hasFile('symptom_image')) {

            $symptom->symptom_image =
                $request
                    ->file('symptom_image')
                    ->store(
                        'symptoms',
                        'public'
                    );
        }


        $symptom->save();


        return back()->with(
            'success',
            'Symptom added successfully.'
        );
    }


    public function symptomsview()
    {
        $symptoms =
            diseases_symptoms::orderBy(
                'symptom_id',
                'asc'
            )->get();


        return view(
            'admin.symptomsview',
            compact('symptoms')
        );
    }


    public function symptom_edit($id)
    {
        $symptom =
            diseases_symptoms::findOrFail($id);


        return view(
            'admin.symptomedit',
            compact('symptom')
        );
    }


    public function symptom_update(
        Request $request,
        $id
    ) {
        $request->validate([
            'symptom_name' => [
                'required',
                'string',
                'max:255'
            ],

            'symptom_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ],
        ]);


        $symptom =
            diseases_symptoms::findOrFail($id);


        $symptom->symptom_name =
            $request->symptom_name;


        if ($request->hasFile('symptom_image')) {

            /*
            |--------------------------------------------------------------------------
            | Delete old image
            |--------------------------------------------------------------------------
            */

            if ($symptom->symptom_image) {

                Storage::disk('public')
                    ->delete(
                        $symptom->symptom_image
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | Store new image
            |--------------------------------------------------------------------------
            */

            $symptom->symptom_image =
                $request
                    ->file('symptom_image')
                    ->store(
                        'symptoms',
                        'public'
                    );
        }


        $symptom->save();


        return redirect()
            ->route('symptomsview')
            ->with(
                'success',
                'Symptom updated successfully.'
            );
    }


    public function symptom_delete($id)
    {
        $symptom =
            diseases_symptoms::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Delete symptom image
        |--------------------------------------------------------------------------
        */

        if ($symptom->symptom_image) {

            Storage::disk('public')
                ->delete(
                    $symptom->symptom_image
                );
        }


        $symptom->delete();


        return redirect()
            ->route('symptomsview')
            ->with(
                'success',
                'Symptom deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Disease - Symptom Registration
    |--------------------------------------------------------------------------
    */

    public function indexSymptomReg()
    {
        $symptomRegs =
            SymptomReg::with([
                'category',
                'symptom'
            ])->get();


        return view(
            'admin.symptomregview',
            compact('symptomRegs')
        );
    }


    public function createSymptomReg()
    {
        $categories =
            diseases_categories::all();

        $symptoms =
            diseases_symptoms::all();


        return view(
            'admin.symptomreg',
            compact(
                'categories',
                'symptoms'
            )
        );
    }


    public function storeSymptomReg(Request $request)
    {
        $request->validate([
            'category_id' => [
                'required',
                'exists:diseases_categories,category_id'
            ],

            'symptom_id' => [
                'required',
                'array'
            ],

            'symptom_id.*' => [
                'exists:diseases_symptoms,symptom_id'
            ],
        ]);


        foreach ($request->symptom_id as $symptomId) {

            SymptomReg::firstOrCreate([
                'category_id' =>
                    $request->category_id,

                'symptom_id' =>
                    $symptomId,
            ]);
        }


        return redirect()
            ->route('symptom_regs')
            ->with(
                'success',
                'Symptoms registered successfully.'
            );
    }


    public function editSymptomReg($id)
    {
        $category =
            diseases_categories::findOrFail($id);

        $categories =
            diseases_categories::all();

        $symptoms =
            diseases_symptoms::all();

        $selectedSymptomIds =
            SymptomReg::where(
                'category_id',
                $id
            )
            ->pluck('symptom_id')
            ->toArray();


        return view(
            'admin.symptomregedit',
            compact(
                'category',
                'categories',
                'symptoms',
                'selectedSymptomIds'
            )
        );
    }


    public function updateSymptomReg(
        Request $request,
        $id
    ) {
        $request->validate([
            'category_id' => [
                'required',
                'exists:diseases_categories,category_id'
            ],

            'symptom_id' => [
                'required',
                'array'
            ],

            'symptom_id.*' => [
                'exists:diseases_symptoms,symptom_id'
            ],
        ]);


        SymptomReg::where(
            'category_id',
            $id
        )->delete();


        foreach ($request->symptom_id as $symptomId) {

            SymptomReg::firstOrCreate([
                'category_id' =>
                    $request->category_id,

                'symptom_id' =>
                    $symptomId,
            ]);
        }


        return redirect()
            ->route('symptom_regs')
            ->with(
                'success',
                'Registration updated successfully.'
            );
    }


    public function deleteSymptomReg($id)
    {
        SymptomReg::where(
            'category_id',
            $id
        )->delete();


        return redirect()
            ->route('symptom_regs')
            ->with(
                'success',
                'Symptom registration deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | User Management
    |--------------------------------------------------------------------------
    */

    public function users()
{
    $users = User::orderBy(
        'created_at',
        'desc'
    )->get();

    /*
    |--------------------------------------------------------------------------
    | Load analysis activity for the users shown on this page.
    | The activity is grouped by user_id so the existing User Management
    | Blade can show it inside each user's View modal.
    |--------------------------------------------------------------------------
    */
    $userActivities = DiseaseAnalysisResult::with('symptoms')
        ->whereIn('user_id', $users->pluck('id'))
        ->orderByDesc('analyzed_at')
        ->get()
        ->groupBy('user_id');

    return view(
        'admin.users',
        compact(
            'users',
            'userActivities'
        )
    );
}


    /*
    |--------------------------------------------------------------------------
    | Fertilizer Management
    |--------------------------------------------------------------------------
    */

    public function fertilizer()
    {
        /*
        |--------------------------------------------------------------------------
        | Existing fertilizers
        |--------------------------------------------------------------------------
        */

        $fertilizers =
            fertilizer_reg::orderBy(
                'id',
                'desc'
            )->get();


        /*
        |--------------------------------------------------------------------------
        | Diseases for mapping
        |--------------------------------------------------------------------------
        */

        $categories =
            diseases_categories::orderBy(
                'diseases_name',
                'asc'
            )->get();


        /*
        |--------------------------------------------------------------------------
        | Existing disease-fertilizer mappings
        |--------------------------------------------------------------------------
        */

        $diseaseFertilizers =
            DiseaseFertilizer::with([
                'disease',
                'fertilizer'
            ])
            ->orderBy(
                'id',
                'desc'
            )
            ->get();


        return view(
            'admin.fertilizer',
            compact(
                'fertilizers',
                'categories',
                'diseaseFertilizers'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Add Fertilizer
    |--------------------------------------------------------------------------
    */

    public function fertilizer_insert(
        Request $request
    ) {
        $request->validate([
            'fertilizer_name' => [
                'required',
                'string',
                'max:255'
            ],

            'description' => [
                'required',
                'string'
            ],

            'category' => [
                'required',
                'in:Organic,Inorganic'
            ],

            'dosage_amount' => [
                'required',
                'numeric',
                'min:0.01'
            ],

            'dosage_unit' => [
                'required',
                'in:g,kg,ml,L'
            ],

            'reference_quantity' => [
                'nullable',
                'numeric',
                'min:0.01'
            ],

            'reference_unit' => [
                'nullable',
                'in:L,ml'
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Category-specific validation
        |--------------------------------------------------------------------------
        */

        if ($request->category === 'Organic') {

            $request->validate([
                'dosage_unit' => [
                    'required',
                    'in:g,kg'
                ],
            ]);

        } elseif (
            $request->category === 'Inorganic'
        ) {

            $request->validate([
                'dosage_unit' => [
                    'required',
                    'in:ml,L'
                ],

                'reference_quantity' => [
                    'required',
                    'numeric',
                    'min:0.01'
                ],

                'reference_unit' => [
                    'required',
                    'in:L,ml'
                ],
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Create fertilizer
        |--------------------------------------------------------------------------
        */

        $fertilizer =
            new fertilizer_reg();


        $fertilizer->fertilizer_name =
            $request->fertilizer_name;

        $fertilizer->description =
            $request->description;

        $fertilizer->category =
            $request->category;

        $fertilizer->dosage_amount =
            $request->dosage_amount;

        $fertilizer->dosage_unit =
            $request->dosage_unit;


        /*
        |--------------------------------------------------------------------------
        | Inorganic reference data
        |--------------------------------------------------------------------------
        */

        if (
            $request->category === 'Inorganic'
        ) {

            $fertilizer->reference_quantity =
                $request->reference_quantity;

            $fertilizer->reference_unit =
                $request->reference_unit;

        } else {

            $fertilizer->reference_quantity =
                null;

            $fertilizer->reference_unit =
                null;
        }


        $fertilizer->save();


        return back()->with(
            'success',
            'Fertilizer added successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit Fertilizer
    |--------------------------------------------------------------------------
    */

    public function fertilizer_edit($id)
    {
        $fertilizer =
            fertilizer_reg::findOrFail($id);


        return view(
            'admin.fertilizeredit',
            compact('fertilizer')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Fertilizer
    |--------------------------------------------------------------------------
    */

    public function fertilizer_update(
        Request $request,
        $id
    ) {
        $request->validate([
            'fertilizer_name' => [
                'required',
                'string',
                'max:255'
            ],

            'description' => [
                'required',
                'string'
            ],

            'category' => [
                'required',
                'in:Organic,Inorganic'
            ],

            'dosage_amount' => [
                'required',
                'numeric',
                'min:0.01'
            ],

            'dosage_unit' => [
                'required',
                'in:g,kg,ml,L'
            ],

            'reference_quantity' => [
                'nullable',
                'numeric',
                'min:0.01'
            ],

            'reference_unit' => [
                'nullable',
                'in:L,ml'
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Category-specific validation
        |--------------------------------------------------------------------------
        */

        if ($request->category === 'Organic') {

            $request->validate([
                'dosage_unit' => [
                    'required',
                    'in:g,kg'
                ],
            ]);

        } elseif (
            $request->category === 'Inorganic'
        ) {

            $request->validate([
                'dosage_unit' => [
                    'required',
                    'in:ml,L'
                ],

                'reference_quantity' => [
                    'required',
                    'numeric',
                    'min:0.01'
                ],

                'reference_unit' => [
                    'required',
                    'in:L,ml'
                ],
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Find fertilizer
        |--------------------------------------------------------------------------
        */

        $fertilizer =
            fertilizer_reg::findOrFail($id);


        $fertilizer->fertilizer_name =
            $request->fertilizer_name;

        $fertilizer->description =
            $request->description;

        $fertilizer->category =
            $request->category;

        $fertilizer->dosage_amount =
            $request->dosage_amount;

        $fertilizer->dosage_unit =
            $request->dosage_unit;


        /*
        |--------------------------------------------------------------------------
        | Inorganic reference data
        |--------------------------------------------------------------------------
        */

        if (
            $request->category === 'Inorganic'
        ) {

            $fertilizer->reference_quantity =
                $request->reference_quantity;

            $fertilizer->reference_unit =
                $request->reference_unit;

        } else {

            $fertilizer->reference_quantity =
                null;

            $fertilizer->reference_unit =
                null;
        }


        $fertilizer->save();


        return redirect()
            ->route('fertilizer')
            ->with(
                'success',
                'Fertilizer updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Disease → Fertilizer Mapping
    |--------------------------------------------------------------------------
    */

    public function fertilizer_mapping_store(
        Request $request
    ) {
        /*
        |--------------------------------------------------------------------------
        | Validate disease
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'category_id' => [
                'required',
                'exists:diseases_categories,category_id'
            ],

            'fertilizer_id' => [
                'required',
                'exists:fertilizer_regs,id'
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Check for duplicate mapping
        |--------------------------------------------------------------------------
        */

        $mappingExists =
            DiseaseFertilizer::where(
                'category_id',
                $request->category_id
            )
            ->where(
                'fertilizer_id',
                $request->fertilizer_id
            )
            ->exists();


        if ($mappingExists) {

            return redirect()
                ->route('fertilizer')
                ->with(
                    'error',
                    'This fertilizer is already mapped to this disease.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Create mapping
        |--------------------------------------------------------------------------
        */

        DiseaseFertilizer::create([

            'category_id' =>
                $request->category_id,

            'fertilizer_id' =>
                $request->fertilizer_id,

        ]);


        return redirect()
            ->route('fertilizer')
            ->with(
                'success',
                'Disease and fertilizer mapping added successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Disease → Fertilizer Mapping
    |--------------------------------------------------------------------------
    */

    public function fertilizer_mapping_delete($id)
    {
        $mapping =
            DiseaseFertilizer::findOrFail($id);


        $mapping->delete();


        return redirect()
            ->route('fertilizer')
            ->with(
                'success',
                'Disease-fertilizer mapping deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Fertilizer
    |--------------------------------------------------------------------------
    */

    public function fertilizer_delete($id)
    {
        $fertilizer =
            fertilizer_reg::findOrFail($id);


        $fertilizer->delete();


        return redirect()
            ->route('fertilizer')
            ->with(
                'success',
                'Fertilizer deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Field Management
    |--------------------------------------------------------------------------
    */

    public function fieldManagement(
        Request $request
    ) {
        $query =
            FarmerField::with('user');


        /*
        |--------------------------------------------------------------------------
        | Search by farmer name/email
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search =
                $request->search;


            $query->whereHas(
                'user',
                function ($userQuery) use ($search) {

                    $userQuery
                        ->where(
                            'name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'email',
                            'like',
                            "%{$search}%"
                        );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Filter by area unit
        |--------------------------------------------------------------------------
        */

        if ($request->filled('unit')) {

            $query->where(
                'area_unit',
                $request->unit
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        switch (
            $request->get(
                'sort',
                'newest'
            )
        ) {

            case 'oldest':

                $query->oldest();

                break;


            case 'largest':

                $query->orderByDesc(
                    'area'
                );

                break;


            case 'plants':

                $query->orderByDesc(
                    'plant_count'
                );

                break;


            default:

                $query->latest();

                break;
        }


        /*
        |--------------------------------------------------------------------------
        | Paginate fields
        |--------------------------------------------------------------------------
        */

        $fields =
            $query->paginate(10);


        /*
        |--------------------------------------------------------------------------
        | Dashboard statistics
        |--------------------------------------------------------------------------
        */

        $totalFields =
            FarmerField::count();


        $totalFarmers =
            FarmerField::distinct(
                'user_id'
            )->count(
                'user_id'
            );


        $totalPlants =
            FarmerField::sum(
                'plant_count'
            );


        $totalAreaInAcres =
            (
                (float)
                FarmerField::where(
                    'area_unit',
                    'acre'
                )->sum('area')
            )
            +
            (
                (
                    (float)
                    FarmerField::where(
                        'area_unit',
                        'cent'
                    )->sum('area')
                )
                / 100
            );


        return view(
            'admin.fieldmanagement',
            compact(
                'fields',
                'totalFields',
                'totalFarmers',
                'totalAreaInAcres',
                'totalPlants'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Field
    |--------------------------------------------------------------------------
    */

    public function fieldDelete($id)
    {
        $field =
            FarmerField::findOrFail($id);


        $field->delete();


        return redirect()
            ->route('admin.field-management')
            ->with(
                'success',
                'Field record deleted successfully.'
            );
    }
    /*
|--------------------------------------------------------------------------
| 1. ADD THIS MODEL IMPORT
|--------------------------------------------------------------------------
|
| Add this line with the other App\Models imports at the top of
| app/Http/Controllers/AdminController.php
|
*/



/*
|--------------------------------------------------------------------------
| 2. ADD THIS METHOD INSIDE AdminController
|--------------------------------------------------------------------------
|
| Put this method before the final closing brace of the controller.
|
*/

public function analysisHistory(Request $request)
{
    $query = DiseaseAnalysisResult::with([
        'user',
        'category'
    ]);


    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    |
    | Search by:
    | - farmer name
    | | - farmer email
    | - predicted disease
    |
    */

    if ($request->filled('search')) {

        $search = trim($request->search);

        $query->where(function ($analysisQuery) use ($search) {

            $analysisQuery
                ->where(
                    'farmer_name',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'farmer_email',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'disease_name',
                    'like',
                    "%{$search}%"
                );
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Analysis type filter
    |--------------------------------------------------------------------------
    */

    if ($request->filled('analysis_type')) {

        $query->where(
            'analysis_type',
            $request->analysis_type
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Status filter
    |--------------------------------------------------------------------------
    */

    if ($request->filled('status')) {

        $query->where(
            'status',
            $request->status
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Sorting
    |--------------------------------------------------------------------------
    |
    | Newest analysis first.
    |
    */

    $query->orderByDesc('analyzed_at');


    /*
    |--------------------------------------------------------------------------
    | Paginate
    |--------------------------------------------------------------------------
    */

    $analyses = $query
        ->paginate(15)
        ->withQueryString();


    /*
    |--------------------------------------------------------------------------
    | Dashboard statistics
    |--------------------------------------------------------------------------
    */

    $totalAnalyses =
        DiseaseAnalysisResult::count();


    $completedAnalyses =
        DiseaseAnalysisResult::where(
            'status',
            'completed'
        )->count();


    $imageAnalyses =
        DiseaseAnalysisResult::where(
            'analysis_type',
            'image'
        )->orWhere(
            'analysis_type',
            'image_and_symptoms'
        )->count();


    $symptomAnalyses =
        DiseaseAnalysisResult::where(
            'analysis_type',
            'symptoms'
        )->count();


    return view(
        'admin.analysis-history',
        compact(
            'analyses',
            'totalAnalyses',
            'completedAnalyses',
            'imageAnalyses',
            'symptomAnalyses'
        )
    );
}

}