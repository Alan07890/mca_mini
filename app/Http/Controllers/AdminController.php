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
use App\Models\FertilizerApplication;
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
        | Load each user's disease-analysis activity for the User Activity modal.
        | Grouping by user_id allows the Blade view to retrieve a user's records
        | with: $userActivities->get($user->id, collect()).
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


    public function user_update(
        Request $request,
        $id
    ) {
        $user =
            User::findOrFail($id);


        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . $id
            ],

            'role' => [
                'required',
                'in:user,admin'
            ],
        ]);


        $user->name =
            $request->name;

        $user->email =
            $request->email;

        $user->role =
            $request->role;

        $user->save();


        return redirect()
            ->route('users')
            ->with(
                'success',
                'User updated successfully.'
            );
    }


    public function user_delete($id)
    {
        $user =
            User::findOrFail($id);


        if (auth()->id() == $user->id) {

            return redirect()
                ->route('users')
                ->with(
                    'error',
                    'You cannot delete your own account.'
                );
        }


        $user->delete();


        return redirect()
            ->route('users')
            ->with(
                'success',
                'User deleted successfully.'
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
                'required',
                'numeric',
                'min:0.01'
            ],

            'reference_unit' => [
                'required',
                'in:plant,ha,acre,L,ml'
            ],
        ]);


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
        | Reference basis
        |--------------------------------------------------------------------------
        |
        | Examples:
        |   1 kg / 1 plant
        |   25 kg / 1 ha
        |   250 g / 100 L
        |
        */

        $fertilizer->reference_quantity =
            $request->reference_quantity;

        $fertilizer->reference_unit =
            $request->reference_unit;


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
                'required',
                'numeric',
                'min:0.01'
            ],

            'reference_unit' => [
                'required',
                'in:plant,ha,acre,L,ml'
            ],
        ]);


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

        $fertilizer->reference_quantity =
            $request->reference_quantity;

        $fertilizer->reference_unit =
            $request->reference_unit;


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
    | Admin Activity & Farm Report
    |--------------------------------------------------------------------------
    */

    public function analysisHistory(Request $request)
    {
        $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'analysis_type' => ['nullable', 'in:symptoms,image,image_and_symptoms'],
            'status' => ['nullable', 'string', 'max:60'],
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
        ]);
    
        /* -------------------- Filterable disease-analysis records -------------------- */
        $query = DiseaseAnalysisResult::with(['user', 'category', 'symptoms']);
    
        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($analysisQuery) use ($search) {
                $analysisQuery
                    ->where('farmer_name', 'like', "%{$search}%")
                    ->orWhere('farmer_email', 'like', "%{$search}%")
                    ->orWhere('disease_name', 'like', "%{$search}%");
            });
        }
    
        if ($request->filled('analysis_type')) {
            $query->where('analysis_type', $request->input('analysis_type'));
        }
    
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
    
        if ($request->filled('from_date')) {
            $query->whereDate('analyzed_at', '>=', $request->input('from_date'));
        }
    
        if ($request->filled('to_date')) {
            $query->whereDate('analyzed_at', '<=', $request->input('to_date'));
        }
    
        $analyses = $query->orderByDesc('analyzed_at')->paginate(15)->withQueryString();
    
        /* -------------------- Summary cards -------------------- */
        $totalFarmers = User::where('role', 'user')->count();
        $totalAnalyses = DiseaseAnalysisResult::count();
        $completedAnalyses = DiseaseAnalysisResult::where('status', 'completed')->count();
        $totalFertilizerApplications = FertilizerApplication::count();
    
        $recentAnalysisUserIds = DiseaseAnalysisResult::where('analyzed_at', '>=', now()->copy()->subDays(30))
            ->whereNotNull('user_id')->pluck('user_id');
        $recentFertilizerUserIds = FertilizerApplication::where('created_at', '>=', now()->copy()->subDays(30))
            ->whereNotNull('user_id')->pluck('user_id');
        $activeFarmers = $recentAnalysisUserIds->merge($recentFertilizerUserIds)->unique()->count();
    
        /* -------------------- Daily activity trend (last 14 days) -------------------- */
        $startDate = now()->startOfDay()->subDays(13);
        $analysisTrend = DiseaseAnalysisResult::where('analyzed_at', '>=', $startDate)->get()
            ->groupBy(function ($analysis) {
                return $analysis->analyzed_at
                    ? \Illuminate\Support\Carbon::parse($analysis->analyzed_at)->format('Y-m-d')
                    : 'unknown';
            });
        $fertilizerTrend = FertilizerApplication::where('created_at', '>=', $startDate)->get()
            ->groupBy(function ($application) {
                return $application->created_at
                    ? \Illuminate\Support\Carbon::parse($application->created_at)->format('Y-m-d')
                    : 'unknown';
            });
    
        $trendLabels = [];
        $analysisTrendData = [];
        $fertilizerTrendData = [];
        for ($i = 0; $i < 14; $i++) {
            $date = $startDate->copy()->addDays($i);
            $key = $date->format('Y-m-d');
            $trendLabels[] = $date->format('d M');
            $analysisTrendData[] = $analysisTrend->get($key, collect())->count();
            $fertilizerTrendData[] = $fertilizerTrend->get($key, collect())->count();
        }
    
        /* -------------------- Activity distribution -------------------- */
        $symptomAnalysisCount = DiseaseAnalysisResult::where('analysis_type', 'symptoms')->count();
        $imageAnalysisCount = DiseaseAnalysisResult::where('analysis_type', 'image')->count();
        $imageAndSymptomsCount = DiseaseAnalysisResult::where('analysis_type', 'image_and_symptoms')->count();
    
        /* -------------------- Most frequently predicted diseases -------------------- */
        $topDiseases = DiseaseAnalysisResult::select('disease_name')
            ->whereNotNull('disease_name')->where('disease_name', '!=', '')->get()
            ->groupBy(fn ($analysis) => trim((string) $analysis->disease_name))
            ->map(fn ($group) => $group->count())->sortDesc()->take(8);
        $diseaseLabels = $topDiseases->keys()->values();
        $diseaseData = $topDiseases->values();
    
        /* -------------------- Most frequently recorded fertilizers -------------------- */
        $topFertilizers = FertilizerApplication::with('fertilizer')->get()
            ->groupBy(fn ($application) => optional($application->fertilizer)->fertilizer_name ?: 'Unknown Fertilizer')
            ->map(fn ($group) => $group->count())->sortDesc()->take(8);
        $fertilizerLabels = $topFertilizers->keys()->values();
        $fertilizerData = $topFertilizers->values();
    
        /* -------------------- Dedicated tables: fertilizer and field records -------------------- */
        $fertilizerApplications = FertilizerApplication::with(['user', 'fertilizer'])
            ->orderByDesc('applied_date')->limit(15)->get();
        $fields = FarmerField::with('user')->orderByDesc('updated_at')->limit(15)->get();
    
        /* -------------------- Combined latest activity feed -------------------- */
        $recentActivities = collect();
    
        $recentAnalyses = DiseaseAnalysisResult::with('user')->orderByDesc('analyzed_at')->limit(20)->get();
        foreach ($recentAnalyses as $analysis) {
            $typeLabel = match ($analysis->analysis_type) {
                'image_and_symptoms' => 'Image + Symptoms',
                'image' => 'Image Analysis',
                'symptoms' => 'Symptom Analysis',
                default => ucwords(str_replace('_', ' ', (string) ($analysis->analysis_type ?: 'Analysis'))),
            };
            $date = $analysis->analyzed_at ? \Illuminate\Support\Carbon::parse($analysis->analyzed_at) : $analysis->created_at;
            $confidence = $analysis->ai_confidence_score !== null
                ? round((float) $analysis->ai_confidence_score) . '%'
                : ($analysis->ai_confidence ?: 'Not available');
    
            $recentActivities->push([
                'user_name' => $analysis->farmer_name ?: optional($analysis->user)->name ?: 'Unknown Farmer',
                'user_email' => $analysis->farmer_email ?: optional($analysis->user)->email ?: '',
                'activity_type' => $typeLabel,
                'details' => $analysis->disease_name ?: 'Disease not identified',
                'meta' => 'Status: ' . ucwords(str_replace('_', ' ', (string) ($analysis->status ?: 'unknown'))) . ' · Confidence: ' . $confidence,
                'date' => $date ? $date->format('d M Y, h:i A') : '—',
                'sort_date' => $date ? $date->timestamp : 0,
            ]);
        }
    
        $recentFertilizerApplications = FertilizerApplication::with(['user', 'fertilizer'])
            ->orderByDesc('created_at')->limit(20)->get();
        foreach ($recentFertilizerApplications as $application) {
            $date = $application->created_at;
            $recentActivities->push([
                'user_name' => optional($application->user)->name ?: 'Unknown Farmer',
                'user_email' => optional($application->user)->email ?: '',
                'activity_type' => 'Fertilizer Application',
                'details' => optional($application->fertilizer)->fertilizer_name ?: 'Unknown Fertilizer',
                'meta' => 'Quantity: ' . $application->quantity . ' ' . $application->quantity_unit,
                'date' => $date ? $date->format('d M Y, h:i A') : '—',
                'sort_date' => $date ? $date->timestamp : 0,
            ]);
        }
    
        $recentActivities = $recentActivities->sortByDesc('sort_date')->take(20)->values();
    
        return view('admin.activity-report', compact(
            'analyses',
            'totalFarmers',
            'totalAnalyses',
            'completedAnalyses',
            'totalFertilizerApplications',
            'activeFarmers',
            'trendLabels',
            'analysisTrendData',
            'fertilizerTrendData',
            'symptomAnalysisCount',
            'imageAnalysisCount',
            'imageAndSymptomsCount',
            'diseaseLabels',
            'diseaseData',
            'fertilizerLabels',
            'fertilizerData',
            'recentActivities',
            'fertilizerApplications',
            'fields'
        ));
    }

    /**
     * Export all report sections to an Excel-compatible multi-sheet workbook.
     * The file uses Excel 2003 Spreadsheet XML (.xls), avoiding extra Composer packages.
     */
    public function exportActivityReportExcel(Request $request)
    {
        $this->validateActivityReportFilters($request);

        $analyses = $this->filteredActivityAnalyses($request)
            ->orderByDesc('analyzed_at')
            ->get();

        $fertilizerApplications = FertilizerApplication::with(['user', 'fertilizer'])
            ->orderByDesc('applied_date')
            ->get();

        $fields = FarmerField::with('user')
            ->orderByDesc('updated_at')
            ->get();

        $totalFarmers = User::where('role', 'user')->count();
        $totalAnalyses = DiseaseAnalysisResult::count();
        $completedAnalyses = DiseaseAnalysisResult::where('status', 'completed')->count();
        $totalFertilizerApplications = FertilizerApplication::count();
        $recentAnalysisUserIds = DiseaseAnalysisResult::where('analyzed_at', '>=', now()->copy()->subDays(30))
            ->whereNotNull('user_id')->pluck('user_id');
        $recentFertilizerUserIds = FertilizerApplication::where('created_at', '>=', now()->copy()->subDays(30))
            ->whereNotNull('user_id')->pluck('user_id');
        $activeFarmers = $recentAnalysisUserIds->merge($recentFertilizerUserIds)->unique()->count();

        $summaryRows = [
            ['Metric', 'Value'],
            ['Generated At', now()->format('d M Y, h:i A')],
            ['Total Farmers', $totalFarmers],
            ['Total Disease Analyses', $totalAnalyses],
            ['Completed Analyses', $completedAnalyses],
            ['Total Fertilizer Applications', $totalFertilizerApplications],
            ['Active Farmers (last 30 days)', $activeFarmers],
            ['Exported Analysis Records', $analyses->count()],
            ['Applied Analysis Filters', $this->activityFilterDescription($request)],
        ];

        $analysisRows = [[
            'Analysis ID', 'Farmer Name', 'Farmer Email', 'Analysis Type',
            'Predicted Disease', 'Status', 'AI Confidence', 'Symptoms', 'Analyzed At'
        ]];

        foreach ($analyses as $analysis) {
            $symptoms = $analysis->symptoms
                ->pluck('symptom_name')
                ->filter()
                ->implode(', ');

            $analysisType = match ($analysis->analysis_type) {
                'image_and_symptoms' => 'Image + Symptoms',
                'image' => 'Image Analysis',
                'symptoms' => 'Symptom Analysis',
                default => ucwords(str_replace('_', ' ', (string) ($analysis->analysis_type ?: 'Analysis'))),
            };

            $analysisRows[] = [
                $analysis->id,
                $analysis->farmer_name ?: optional($analysis->user)->name ?: 'Unknown Farmer',
                $analysis->farmer_email ?: optional($analysis->user)->email ?: '',
                $analysisType,
                $analysis->disease_name ?: 'Disease not identified',
                ucwords(str_replace('_', ' ', (string) ($analysis->status ?: 'Unknown'))),
                $analysis->ai_confidence_score !== null
                    ? round((float) $analysis->ai_confidence_score) . '%'
                    : ($analysis->ai_confidence ?: 'Not available'),
                $symptoms ?: '—',
                $analysis->analyzed_at
                    ? \Illuminate\Support\Carbon::parse($analysis->analyzed_at)->format('d M Y, h:i A')
                    : '',
            ];
        }

        $fertilizerRows = [[
            'Application ID', 'Farmer Name', 'Farmer Email', 'Fertilizer',
            'Quantity', 'Unit', 'Applied Date', 'Next Application Date', 'Notes'
        ]];

        foreach ($fertilizerApplications as $application) {
            $fertilizerRows[] = [
                $application->id,
                optional($application->user)->name ?: 'Unknown Farmer',
                optional($application->user)->email ?: '',
                optional($application->fertilizer)->fertilizer_name ?: 'Unknown Fertilizer',
                $application->quantity,
                $application->quantity_unit,
                $application->applied_date
                    ? \Illuminate\Support\Carbon::parse($application->applied_date)->format('d M Y')
                    : '',
                $application->next_application_date
                    ? \Illuminate\Support\Carbon::parse($application->next_application_date)->format('d M Y')
                    : 'Not scheduled',
                $application->notes ?: '',
            ];
        }

        $fieldRows = [['Field ID', 'Farmer Name', 'Farmer Email', 'Area', 'Area Unit', 'Plant Count', 'Last Updated']];
        foreach ($fields as $field) {
            $fieldRows[] = [
                $field->id,
                optional($field->user)->name ?: 'Unknown Farmer',
                optional($field->user)->email ?: '',
                $field->area,
                $field->area_unit,
                $field->plant_count,
                $field->updated_at ? $field->updated_at->format('d M Y, h:i A') : '',
            ];
        }

        $xml = $this->buildActivityReportExcelXml([
            'Summary' => $summaryRows,
            'Disease Analyses' => $analysisRows,
            'Fertilizer Applications' => $fertilizerRows,
            'Farmer Fields' => $fieldRows,
        ]);

        $filename = 'cardo-fer-activity-report-' . now()->format('Y-m-d_H-i') . '.xls';

        return response($xml, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
        ]);
    }

    /**
     * Return a print-friendly, complete report. In the browser, administrators
     * can choose "Save as PDF" from the print dialog.
     */
    public function exportActivityReportPdf(Request $request)
    {
        $this->validateActivityReportFilters($request);

        $analyses = $this->filteredActivityAnalyses($request)
            ->orderByDesc('analyzed_at')
            ->get();

        $fertilizerApplications = FertilizerApplication::with(['user', 'fertilizer'])
            ->orderByDesc('applied_date')
            ->get();

        $fields = FarmerField::with('user')
            ->orderByDesc('updated_at')
            ->get();

        $totalFarmers = User::where('role', 'user')->count();
        $totalAnalyses = DiseaseAnalysisResult::count();
        $completedAnalyses = DiseaseAnalysisResult::where('status', 'completed')->count();
        $totalFertilizerApplications = FertilizerApplication::count();
        $recentAnalysisUserIds = DiseaseAnalysisResult::where('analyzed_at', '>=', now()->copy()->subDays(30))
            ->whereNotNull('user_id')->pluck('user_id');
        $recentFertilizerUserIds = FertilizerApplication::where('created_at', '>=', now()->copy()->subDays(30))
            ->whereNotNull('user_id')->pluck('user_id');
        $activeFarmers = $recentAnalysisUserIds->merge($recentFertilizerUserIds)->unique()->count();

        return view('admin.activity-report', array_merge(compact(
            'analyses',
            'fertilizerApplications',
            'fields',
            'totalFarmers',
            'totalAnalyses',
            'completedAnalyses',
            'totalFertilizerApplications',
            'activeFarmers'
        ), ['pdfMode' => true]));
    }

    /** Validate the filters supported by the report and its export actions. */
    private function validateActivityReportFilters(Request $request): void
    {
        $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'analysis_type' => ['nullable', 'in:symptoms,image,image_and_symptoms'],
            'status' => ['nullable', 'string', 'max:60'],
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
        ]);
    }

    /** Build the same filtered analysis query used by the report table. */
    private function filteredActivityAnalyses(Request $request)
    {
        $query = DiseaseAnalysisResult::with(['user', 'category', 'symptoms']);

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($analysisQuery) use ($search) {
                $analysisQuery
                    ->where('farmer_name', 'like', "%{$search}%")
                    ->orWhere('farmer_email', 'like', "%{$search}%")
                    ->orWhere('disease_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('analysis_type')) {
            $query->where('analysis_type', $request->input('analysis_type'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('from_date')) {
            $query->whereDate('analyzed_at', '>=', $request->input('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->whereDate('analyzed_at', '<=', $request->input('to_date'));
        }

        return $query;
    }

    private function activityFilterDescription(Request $request): string
    {
        $parts = [];
        if ($request->filled('search')) $parts[] = 'Search: ' . trim((string) $request->input('search'));
        if ($request->filled('analysis_type')) $parts[] = 'Type: ' . $request->input('analysis_type');
        if ($request->filled('status')) $parts[] = 'Status: ' . $request->input('status');
        if ($request->filled('from_date')) $parts[] = 'From: ' . $request->input('from_date');
        if ($request->filled('to_date')) $parts[] = 'To: ' . $request->input('to_date');
        return $parts ? implode(' | ', $parts) : 'No filters applied';
    }

    /** Build a dependency-free, multi-worksheet Excel XML workbook. */
    private function buildActivityReportExcelXml(array $sheets): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<?mso-application progid="Excel.Sheet"?>' . "\n";
        $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" '
            . 'xmlns:o="urn:schemas-microsoft-com:office:office" '
            . 'xmlns:x="urn:schemas-microsoft-com:office:excel" '
            . 'xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" '
            . 'xmlns:html="http://www.w3.org/TR/REC-html40">';
        $xml .= '<Styles><Style ss:ID="Default" ss:Name="Normal"><Alignment ss:Vertical="Center"/> '
            . '<Font ss:FontName="Calibri" ss:Size="10"/></Style>'
            . '<Style ss:ID="Header"><Font ss:Bold="1" ss:Color="#FFFFFF" ss:Size="10"/>'
            . '<Interior ss:Color="#1B5E20" ss:Pattern="Solid"/><Alignment ss:Vertical="Center"/></Style>'
            . '<Style ss:ID="Title"><Font ss:Bold="1" ss:Color="#FFFFFF" ss:Size="12"/>'
            . '<Interior ss:Color="#123C17" ss:Pattern="Solid"/></Style></Styles>';

        foreach ($sheets as $sheetName => $rows) {
            $safeName = substr(preg_replace('/[\\\\\/\?\*\[\]:]/', '', (string) $sheetName), 0, 31);
            $xml .= '<Worksheet ss:Name="' . $this->escapeExcelXml($safeName) . '"><Table>';
            foreach (array_values($rows) as $rowIndex => $row) {
                $xml .= '<Row>';
                foreach (array_values($row) as $value) {
                    $textValue = $value === null ? '' : (string) $value;
                    $numeric = is_numeric($value) && $textValue !== '';
                    $type = $numeric ? 'Number' : 'String';
                    $style = $rowIndex === 0 ? ' ss:StyleID="Header"' : '';
                    $xml .= '<Cell' . $style . '><Data ss:Type="' . $type . '">'
                        . $this->escapeExcelXml($textValue) . '</Data></Cell>';
                }
                $xml .= '</Row>';
            }
            $xml .= '</Table><WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">'
                . '<ProtectObjects>False</ProtectObjects><ProtectScenarios>False</ProtectScenarios>'
                . '</WorksheetOptions></Worksheet>';
        }

        $xml .= '</Workbook>';
        return $xml;
    }

    private function escapeExcelXml(string $value): string
    {
        $value = preg_replace('/[^\x09\x0A\x0D\x20-\x{D7FF}\x{E000}-\x{FFFD}]/u', '', $value) ?? '';
        return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }
}