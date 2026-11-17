<?php

namespace App\Http\Controllers;

use App\Services\GeminiService;
use App\Models\FarmerField;
use App\Models\diseases_symptoms;
use App\Models\SymptomReg;
use App\Models\DiseaseAnalysisResult;
use App\Models\DiseaseAnalysisSymptom;
use App\Models\diseases_categories;
use App\Models\FertilizerApplication;
use App\Models\fertilizer_reg;
use App\Mail\FertilizerScheduleConfirmationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FarmerController extends Controller
{
    public function farmerHome()
    {
        return view('farmer.farmerhome');
    }


    public function field()
    {
        $field = FarmerField::where(
            'user_id',
            Auth::id()
        )->first();

        return view(
            'farmer.field',
            compact('field')
        );
    }


   public function saveField(Request $request)
{
    $validated = $request->validate([
        'area' => 'required|numeric|min:0.01',
        'area_unit' => 'required|in:acre,cent',
        'plant_count' => 'required|integer|min:1',
        'place' => 'required|string|max:255',
        'latitude' => 'required|numeric|between:-90,90',
        'longitude' => 'required|numeric|between:-180,180',
    ]);

    FarmerField::updateOrCreate(
        ['user_id' => Auth::id()],
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


    /**
     * Preliminary AI vision analysis.
     *
     * This endpoint is kept separate from the existing symptom-analysis
     * endpoint because image analysis is a multi-stage process:
     *
     *   Farmer image
     *        -> Gemini visual analysis
     *        -> visible observations / possible symptoms
     *        -> confirmation questions
     *
     * The final disease prediction will be performed only after the
     * farmer responds to the confirmation questions.
     */
    public function analyzePlantImage(
        Request $request,
        GeminiService $geminiService
    ) {

        /*
        |--------------------------------------------------------------------------
        | 1. Validate uploaded image
        |--------------------------------------------------------------------------
        |
        | The image is intentionally handled independently of the database
        | symptom list. Gemini should be able to inspect a new/unseen plant
        | photo rather than requiring an identical image to exist in the DB.
        |
        */

        $validated = $request->validate([
            'image' => [
                'required',
                'file',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:10240',
            ],

            'symptoms' => [
                'nullable',
                'array',
            ],

            'symptoms.*' => [
                'integer',
                'exists:diseases_symptoms,symptom_id',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | 2. Normalize any symptoms already selected by the farmer
        |--------------------------------------------------------------------------
        |
        | Zero symptoms is valid here. The whole purpose of the image step is
        | to help when the farmer cannot recognize the symptoms from the list.
        |
        */

        $selectedSymptomIds = collect(
            $validated['symptoms'] ?? []
        )
            ->map(function ($id) {
                return (int) $id;
            })
            ->unique()
            ->values();


        /*
        |--------------------------------------------------------------------------
        | 3. Convert selected symptom IDs into names
        |--------------------------------------------------------------------------
        */

        $selectedSymptomNames = diseases_symptoms::whereIn(
            'symptom_id',
            $selectedSymptomIds
        )
            ->orderBy('symptom_id')
            ->pluck('symptom_name')
            ->values()
            ->all();


        /*
        |--------------------------------------------------------------------------
        | 4. Get farmer field details
        |--------------------------------------------------------------------------
        |
        | Field details are optional for preliminary visual analysis, but they
        | are passed when available so the later AI recommendation can use the
        | correct plantation context.
        |
        */

        $field = FarmerField::where(
            'user_id',
            Auth::id()
        )->first();


        $fieldData = $field
            ? [

                'area' =>
                    $field->area,

                'area_unit' =>
                    $field->area_unit,

                'plant_count' =>
                    $field->plant_count,

                'place' =>
                    $field->place,

            ]
            : null;


        /*
        |--------------------------------------------------------------------------
        | 5. Send image to Gemini Vision
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | - No Google Search grounding is used.
        | - No disease from the database is supplied as a hint.
        | - Gemini is asked to inspect the actual image independently.
        | - The AI must be allowed to return insufficient evidence when the
        |   photograph is unclear or does not contain enough visible signs.
        |
        */

        try {

            $aiResult =
                $geminiService->analyzePlantImage(
                    $request->file('image'),
                    $selectedSymptomNames,
                    $fieldData
                );

        } catch (\Throwable $e) {

            report($e);

            return response()->json([

                'success' => false,

                'message' => $e->getMessage(),

            ], 500);
        }


        /*
        |--------------------------------------------------------------------------
        | 6. Return preliminary visual analysis
        |--------------------------------------------------------------------------
        |
        | At this stage we do NOT finalize the disease.
        | The response is intended to help the Blade UI ask the farmer
        | confirmation questions about what Gemini observed.
        |
        */

        return response()->json([

            'success' => true,

            'result' => [

                'analysis_stage' =>
                    'preliminary_image_analysis',

                'identification_source' =>
                    'Gemini Vision',

                'disease_finalized' =>
                    false,

                'selected_symptoms' =>
                    $selectedSymptomNames,

                'visual_summary' =>
                    $aiResult['visual_summary']
                    ?? '',

                'visible_symptoms' =>
                    $aiResult['visible_symptoms']
                    ?? [],

                'possible_diseases' =>
                    $aiResult['possible_diseases']
                    ?? [],

                'confirmation_questions' =>
                    $aiResult['confirmation_questions']
                    ?? [],

                'confidence' =>
                    $aiResult['confidence']
                    ?? '',

                'image_quality' =>
                    $aiResult['image_quality']
                    ?? '',

                'needs_better_image' =>
                    (bool) (
                        $aiResult['needs_better_image']
                        ?? false
                    ),
            ],

            'message' =>
                'The image was analyzed visually. Please review the AI observations and answer the confirmation questions before final disease prediction.',
        ]);
    }


    /**
     * Final AI disease prediction using the uploaded plant image,
     * farmer-confirmed evidence, and the preliminary visual analysis.
     *
     * The preliminary candidate list is treated as reference only.
     * Gemini receives the image again and must independently inspect it
     * before producing the final AI-assisted disease suggestion.
     */
    public function predictDiseaseFromImageAndSymptoms(
        Request $request,
        GeminiService $geminiService
    ) {

        /*
        |--------------------------------------------------------------------------
        | 1. Validate final image-prediction request
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'image' => [
                'required',
                'file',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:10240',
            ],

            'confirmed_symptoms' => [
                'nullable',
                'array',
                'max:30',
            ],

            'confirmed_symptoms.*' => [
                'string',
                'max:500',
            ],

            'preliminary_analysis' => [
                'nullable',
                'string',
                'max:20000',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | 2. Normalize farmer-confirmed evidence
        |--------------------------------------------------------------------------
        */

        $confirmedSymptoms = collect(
            $validated['confirmed_symptoms'] ?? []
        )
            ->map(function ($item) {
                return trim((string) $item);
            })
            ->filter(function ($item) {
                return $item !== '';
            })
            ->unique()
            ->values()
            ->all();


        /*
        |--------------------------------------------------------------------------
        | 3. Decode preliminary analysis safely
        |--------------------------------------------------------------------------
        |
        | This is only reference context generated during Stage 1.
        | It must never be treated as a trusted disease diagnosis.
        |
        */

        $preliminaryAnalysis = [];

        if (!empty($validated['preliminary_analysis'])) {

            $decoded = json_decode(
                $validated['preliminary_analysis'],
                true
            );

            if (json_last_error() !== JSON_ERROR_NONE) {

                return response()->json([

                    'success' => false,

                    'message' =>
                        'The preliminary image analysis data is invalid. Please analyze the image again.',

                ], 422);
            }

            if (is_array($decoded)) {
                $preliminaryAnalysis = $decoded;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | 4. Get farmer field details
        |--------------------------------------------------------------------------
        */

        $field = FarmerField::where(
            'user_id',
            Auth::id()
        )->first();


        $fieldData = $field
            ? [

                'area' =>
                    $field->area,

                'area_unit' =>
                    $field->area_unit,

                'plant_count' =>
                    $field->plant_count,

                'place' =>
                    $field->place,

            ]
            : null;


        /*
        |--------------------------------------------------------------------------
        | 5. Send image + confirmed evidence to Gemini Vision
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | The preliminary disease candidate is NOT treated as the final answer.
        | Gemini must inspect the image again and reason from the combined
        | evidence.
        |
        | No Google Search grounding is used.
        |
        */

        try {

            $aiResult =
                $geminiService->predictDiseaseFromImageAndSymptoms(

                    $request->file('image'),

                    $confirmedSymptoms,

                    $fieldData,

                    $preliminaryAnalysis

                );

        } catch (\Throwable $e) {

            report($e);

            return response()->json([

                'success' => false,

                'message' => $e->getMessage(),

            ], 500);
        }


        /*
        |--------------------------------------------------------------------------
        | 6. Extract farmer-selected symptoms for display
        |--------------------------------------------------------------------------
        |
        | The frontend sends farmer-selected symptoms in the form:
        | "Farmer selected symptom: ..."
        | along with the YES/NO/NOT SURE answers.
        |
        */

        $farmerSelectedSymptoms = collect(
            $confirmedSymptoms
        )
            ->filter(function ($item) {
                return str_starts_with(
                    $item,
                    'Farmer selected symptom:'
                );
            })
            ->map(function ($item) {

                return trim(
                    preg_replace(
                        '/^Farmer selected symptom:\s*/i',
                        '',
                        $item
                    )
                );

            })
            ->filter()
            ->values()
            ->all();


        /*
        |--------------------------------------------------------------------------
        | 7. Prepare final AI response
        |--------------------------------------------------------------------------
        |
        | category_id intentionally remains null here.
        | The final disease is Gemini's independent visual + symptom result.
        | Database disease/fertilizer mapping can be resolved separately later.
        |
        */

        $finalDiseaseName =
            trim((string) (
                $aiResult['disease_name']
                ?? 'Unknown Disease'
            ));


        if ($finalDiseaseName === '') {
            $finalDiseaseName = 'Unknown Disease';
        }


        /*
        |--------------------------------------------------------------------------
        | 7. Save completed image analysis to history
        |--------------------------------------------------------------------------
        */

        $imagePath = null;

        try {

            $imagePath = $request->file('image')->store(
                'analysis-images',
                'public'
            );

            $categoryId = $this->resolveCategoryId(
                $finalDiseaseName
            );

            $adminFertilizers = $this->getAdminFertilizersForCategory($categoryId);
            $fertilizerRecommendation = $this->formatAdminFertilizerRecommendation(
                $adminFertilizers
            );

            $confirmationAnswers = $this->buildConfirmationAnswers(
                $confirmedSymptoms
            );

            $imageSymptoms = [];

            foreach ($farmerSelectedSymptoms as $symptomName) {

                $symptomId = diseases_symptoms::where(
                    'symptom_name',
                    $symptomName
                )->value('symptom_id');

                $imageSymptoms[] = [
                    'symptom_id' => $symptomId,
                    'symptom_name' => $symptomName,
                    'source' => 'farmer',
                ];
            }

            $visibleSymptoms = is_array(
                $preliminaryAnalysis['visible_symptoms'] ?? null
            )
                ? $preliminaryAnalysis['visible_symptoms']
                : [];

            foreach ($visibleSymptoms as $symptomName) {

                $symptomName = trim((string) $symptomName);

                if ($symptomName === '') {
                    continue;
                }

                $symptomId = diseases_symptoms::where(
                    'symptom_name',
                    $symptomName
                )->value('symptom_id');

                $imageSymptoms[] = [
                    'symptom_id' => $symptomId,
                    'symptom_name' => $symptomName,
                    'source' => 'ai',
                ];
            }

            $analysisRecord = $this->storeAnalysisHistory([

                'analysis_type' =>
                    empty($farmerSelectedSymptoms) && empty($confirmationAnswers)
                        ? 'image'
                        : 'image_and_symptoms',

                'status' =>
                    ($aiResult['needs_more_evidence'] ?? false)
                        ? 'needs_confirmation'
                        : 'completed',

                'field' => $fieldData,

                'image_path' => $imagePath,
                'image_original_name' =>
                    $request->file('image')->getClientOriginalName(),
                'image_mime_type' =>
                    $request->file('image')->getMimeType(),

                'disease_name' => $finalDiseaseName,
                'category_id' => $categoryId,

                'ai_confidence' =>
                    $aiResult['confidence'] ?? null,
                'ai_confidence_score' =>
                    $aiResult['confidence_score'] ?? null,
                'ai_reason' =>
                    $aiResult['reason'] ?? null,

                'causes' =>
                    $aiResult['causes'] ?? null,
                'prevention' =>
                    $aiResult['prevention'] ?? null,
                'fertilizer' =>
                    $fertilizerRecommendation,

                'database_match_found' => false,
                'database_disease_name' => null,
                'database_match_percentage' => null,
                'database_matched_count' => null,
                'database_total_symptoms' => null,
                'database_matched_symptoms' => [],
                'database_other_known_symptoms' => [],

                'visual_summary' =>
                    $preliminaryAnalysis['visual_summary'] ?? null,
                'visible_symptoms' =>
                    $visibleSymptoms,
                'possible_diseases' =>
                    is_array($preliminaryAnalysis['possible_diseases'] ?? null)
                        ? $preliminaryAnalysis['possible_diseases']
                        : [],
                'image_analysis_confidence' =>
                    $preliminaryAnalysis['confidence'] ?? null,
                'image_quality' =>
                    $preliminaryAnalysis['image_quality'] ?? null,
                'needs_better_image' =>
                    (bool) ($preliminaryAnalysis['needs_better_image'] ?? false),

                'confirmation_answers' =>
                    $confirmationAnswers,

                'ai_model' => 'gemini-3.6-flash',
                'analyzed_at' => now(),

                'symptoms' => $imageSymptoms,
            ]);

        } catch (\Throwable $e) {

            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            report($e);

            return response()->json([
                'success' => false,
                'message' =>
                    'The AI result was generated, but saving the analysis history failed: ' .
                    $e->getMessage(),
            ], 500);
        }

        return response()->json([

            'success' => true,

            'analysis_id' => $analysisRecord->id,

            'result' => [

                'analysis_id' => $analysisRecord->id,

                'analysis_stage' =>
                    'final_image_prediction',

                'identification_source' =>
                    'Gemini Vision + Farmer Confirmation',

                'database_match' =>
                    false,

                'ai_identified' =>
                    true,

                'disease_finalized' =>
                    true,

                'category_id' =>
                    $categoryId,

                'disease_name' =>
                    $finalDiseaseName,

                'match_percentage' =>
                    0,

                'ai_confidence' =>
                    $aiResult['confidence']
                    ?? '',

                'ai_confidence_score' =>
                    (int) (
                        $aiResult['confidence_score']
                        ?? 0
                    ),

                'ai_reason' =>
                    $aiResult['reason']
                    ?? '',

                'matched_count' =>
                    count($farmerSelectedSymptoms),

                'selected_count' =>
                    count($farmerSelectedSymptoms),

                'total_disease_symptoms' =>
                    0,

                'matched_symptoms' =>
                    $farmerSelectedSymptoms,

                'unmatched_symptoms' =>
                    $confirmedSymptoms,

                'causes' =>
                    $aiResult['causes']
                    ?? '',

                'prevention' =>
                    $aiResult['prevention']
                    ?? '',

                'fertilizer' =>
                    $fertilizerRecommendation ?? '',

                'fertilizer_source' =>
                    $fertilizerRecommendation
                        ? 'Administrator-defined mapping'
                        : null,

                'fertilizer_mapping_found' =>
                    $adminFertilizers->isNotEmpty(),

                'sources' =>
                    $aiResult['sources']
                    ?? [],

                'needs_more_evidence' =>
                    (bool) (
                        $aiResult['needs_more_evidence']
                        ?? false
                    ),

                'follow_up' =>
                    $aiResult['follow_up']
                    ?? '',

                'database_comparison' => [

                    'match_found' =>
                        false,

                    'disease_name' =>
                        null,

                    'category_id' =>
                        null,

                    'match_percentage' =>
                        0,

                    'matched_count' =>
                        0,

                    'total_disease_symptoms' =>
                        0,

                    'matched_symptoms' =>
                        [],

                    'other_known_symptoms' =>
                        [],
                ],
            ],

            'alternatives' => [],

            'message' =>
                'Gemini independently reviewed the uploaded plant image together with the farmer-confirmed symptoms. The result is an AI-assisted suggestion, not a confirmed scientific diagnosis.',
        ]);
    }


    public function analyzeDisease(
        Request $request,
        GeminiService $geminiService
    ) {

        /*
        |--------------------------------------------------------------------------
        | 1. Validate selected symptoms
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'symptoms' => [
                'required',
                'array',
                'min:1'
            ],

            'symptoms.*' => [
                'integer',
                'exists:diseases_symptoms,symptom_id'
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | 2. Normalize selected symptom IDs
        |--------------------------------------------------------------------------
        */

        $selectedSymptomIds = collect(
            $validated['symptoms']
        )
            ->map(function ($id) {
                return (int) $id;
            })
            ->unique()
            ->values();


        $selectedCount = $selectedSymptomIds->count();


        /*
        |--------------------------------------------------------------------------
        | 3. Get selected symptom names
        |--------------------------------------------------------------------------
        */

        $selectedSymptomNames = diseases_symptoms::whereIn(
            'symptom_id',
            $selectedSymptomIds
        )
            ->orderBy('symptom_id')
            ->pluck('symptom_name')
            ->values()
            ->all();


        /*
        |--------------------------------------------------------------------------
        | 4. Get disease-symptom mappings
        |--------------------------------------------------------------------------
        |
        | Database comparison is performed independently for reference.
        | The database's best disease is NOT passed to Gemini and therefore
        | does not anchor the AI prediction.
        |
        */

        $allMappings = SymptomReg::with([
            'category',
            'symptom'
        ])->get();


        /*
        |--------------------------------------------------------------------------
        | 5. Group mappings disease-wise
        |--------------------------------------------------------------------------
        */

        $diseaseGroups = $allMappings
            ->groupBy('category_id');


        $matches = collect();


        /*
        |--------------------------------------------------------------------------
        | 6. Compare selected symptoms with every database disease
        |--------------------------------------------------------------------------
        */

        foreach ($diseaseGroups as $categoryId => $mappings) {

            /*
            | Remove duplicate symptom mappings
            */

            $uniqueMappings = $mappings
                ->unique('symptom_id');


            /*
            | Get all symptoms belonging to this disease
            */

            $diseaseSymptomIds = $uniqueMappings
                ->pluck('symptom_id')
                ->map(function ($id) {
                    return (int) $id;
                })
                ->unique()
                ->values();


            /*
            | Find matching selected symptoms
            */

            $matchedSymptomIds = $diseaseSymptomIds
                ->intersect($selectedSymptomIds)
                ->values();


            $matchedCount = $matchedSymptomIds->count();


            /*
            | Ignore diseases with zero matches
            */

            if ($matchedCount === 0) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | 7. Calculate database symptom match percentage
            |--------------------------------------------------------------------------
            */

            $matchPercentage = round(
                ($matchedCount / $selectedCount) * 100,
                2
            );


            /*
            |--------------------------------------------------------------------------
            | 8. Get database matched symptom names
            |--------------------------------------------------------------------------
            */

            $matchedSymptoms = $uniqueMappings
                ->filter(function ($mapping) use (
                    $selectedSymptomIds
                ) {

                    return $selectedSymptomIds->contains(
                        (int) $mapping->symptom_id
                    );

                })
                ->map(function ($mapping) {

                    return $mapping->symptom
                        ? $mapping->symptom->symptom_name
                        : null;

                })
                ->filter()
                ->values()
                ->all();


            /*
            |--------------------------------------------------------------------------
            | 9. Get remaining known symptoms of database disease
            |--------------------------------------------------------------------------
            */

            $unmatchedSymptoms = $uniqueMappings
                ->filter(function ($mapping) use (
                    $selectedSymptomIds
                ) {

                    return !$selectedSymptomIds->contains(
                        (int) $mapping->symptom_id
                    );

                })
                ->map(function ($mapping) {

                    return $mapping->symptom
                        ? $mapping->symptom->symptom_name
                        : null;

                })
                ->filter()
                ->values()
                ->all();


            /*
            |--------------------------------------------------------------------------
            | 10. Store database disease match
            |--------------------------------------------------------------------------
            */

            $matches->push([

                'category_id' => (int) $categoryId,

                'disease_name' => $mappings->first()->category
                    ? $mappings->first()->category->diseases_name
                    : 'Unknown Disease',

                'match_percentage' =>
                    $matchPercentage,

                'matched_count' =>
                    $matchedCount,

                'selected_count' =>
                    $selectedCount,

                'total_disease_symptoms' =>
                    $diseaseSymptomIds->count(),

                'matched_symptoms' =>
                    $matchedSymptoms,

                'unmatched_symptoms' =>
                    $unmatchedSymptoms,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | 11. Sort database matches
        |--------------------------------------------------------------------------
        */

        $matches = $matches
            ->sort(function ($a, $b) {

                /*
                | Higher percentage first
                */

                if (
                    $a['match_percentage']
                    !==
                    $b['match_percentage']
                ) {

                    return
                        $b['match_percentage']
                        <=>
                        $a['match_percentage'];
                }


                /*
                | Same percentage:
                | higher matched count first
                */

                return
                    $b['matched_count']
                    <=>
                    $a['matched_count'];

            })
            ->values();


        /*
        |--------------------------------------------------------------------------
        | 12. Database comparison information
        |--------------------------------------------------------------------------
        */

        $databaseMatchAvailable =
            $matches->isNotEmpty();


        $databaseBestMatch =
            $databaseMatchAvailable
                ? $matches->first()
                : null;


        /*
        |--------------------------------------------------------------------------
        | 13. Database alternative matches
        |--------------------------------------------------------------------------
        */

        $databaseAlternatives = $matches
            ->slice(1)
            ->map(function ($match) {

                return [

                    'disease_name' =>
                        $match['disease_name'],

                    'match_percentage' =>
                        $match['match_percentage'],

                    'matched_count' =>
                        $match['matched_count'],
                ];

            })
            ->values()
            ->all();


        /*
        |--------------------------------------------------------------------------
        | 14. Get farmer field details
        |--------------------------------------------------------------------------
        */

        $field = FarmerField::where(
            'user_id',
            Auth::id()
        )->first();


        $fieldData = $field
            ? [

                'area' =>
                    $field->area,

                'area_unit' =>
                    $field->area_unit,

                'plant_count' =>
                    $field->plant_count,

                'place' =>
                    $field->place,

            ]
            : null;


        /*
        |--------------------------------------------------------------------------
        | 15. IMPORTANT AI STEP
        |--------------------------------------------------------------------------
        |
        | Gemini receives ONLY:
        |
        | - Farmer selected symptoms
        | - Farmer field details
        |
        | It does NOT receive the database best-match disease.
        |
        | The existing service method name is kept for controller compatibility,
        | but the current Gemini implementation does NOT use Google Search.
        |
        */

        try {

            $aiResult =
                $geminiService->identifyDiseaseWithWebSearch(

                    $selectedSymptomNames,

                    $fieldData

                );

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Temporary debugging response
            |--------------------------------------------------------------------------
            */

            report($e);

            return response()->json([

                'success' => false,

                'message' => $e->getMessage(),

            ], 500);
        }


        /*
        |--------------------------------------------------------------------------
        | 16. Prepare database comparison information
        |--------------------------------------------------------------------------
        |
        | This information is kept separately.
        | It does NOT control the AI disease identification.
        |
        */

        $databaseComparison = $databaseBestMatch
            ? [

                'match_found' =>
                    true,

                'disease_name' =>
                    $databaseBestMatch['disease_name'],

                'category_id' =>
                    $databaseBestMatch['category_id'],

                'match_percentage' =>
                    $databaseBestMatch['match_percentage'],

                'matched_count' =>
                    $databaseBestMatch['matched_count'],

                'total_disease_symptoms' =>
                    $databaseBestMatch['total_disease_symptoms'],

                'matched_symptoms' =>
                    $databaseBestMatch['matched_symptoms'],

                'other_known_symptoms' =>
                    $databaseBestMatch['unmatched_symptoms'],

            ]
            : [

                'match_found' =>
                    false,

                'disease_name' =>
                    null,

                'category_id' =>
                    null,

                'match_percentage' =>
                    0,

                'matched_count' =>
                    0,

                'total_disease_symptoms' =>
                    0,

                'matched_symptoms' =>
                    [],

                'other_known_symptoms' =>
                    [],

            ];


        /*
        |--------------------------------------------------------------------------
        | 17. Return Gemini independent disease identification
        |--------------------------------------------------------------------------
        */

        /*
        |--------------------------------------------------------------------------
        | 18. Save completed symptom analysis to history
        |--------------------------------------------------------------------------
        */

        try {

            $categoryId = $this->resolveCategoryId(
                $aiResult['disease_name'] ?? null
            );

            $adminFertilizers = $this->getAdminFertilizersForCategory($categoryId);
            $fertilizerRecommendation = $this->formatAdminFertilizerRecommendation(
                $adminFertilizers
            );

            $symptomHistoryRows = [];

            foreach ($selectedSymptomIds as $symptomId) {

                $symptomModel = diseases_symptoms::find($symptomId);

                if (!$symptomModel) {
                    continue;
                }

                $symptomHistoryRows[] = [
                    'symptom_id' => $symptomModel->symptom_id,
                    'symptom_name' => $symptomModel->symptom_name,
                    'source' => 'farmer',
                ];
            }

            $analysisRecord = $this->storeAnalysisHistory([

                'analysis_type' => 'symptoms',
                'status' => 'completed',

                'field' => $fieldData,

                'image_path' => null,
                'image_original_name' => null,
                'image_mime_type' => null,

                'disease_name' =>
                    $aiResult['disease_name'] ?? 'Unknown Disease',
                'category_id' => $categoryId,

                'ai_confidence' =>
                    $aiResult['confidence'] ?? null,
                'ai_confidence_score' =>
                    $aiResult['confidence_score'] ?? null,
                'ai_reason' =>
                    $aiResult['reason'] ?? null,

                'causes' =>
                    $aiResult['causes'] ?? null,
                'prevention' =>
                    $aiResult['prevention'] ?? null,
                'fertilizer' =>
                    $fertilizerRecommendation,

                'database_match_found' =>
                    $databaseComparison['match_found'],
                'database_disease_name' =>
                    $databaseComparison['disease_name'],
                'database_match_percentage' =>
                    $databaseComparison['match_percentage'],
                'database_matched_count' =>
                    $databaseComparison['matched_count'],
                'database_total_symptoms' =>
                    $databaseComparison['total_disease_symptoms'],
                'database_matched_symptoms' =>
                    $databaseComparison['matched_symptoms'],
                'database_other_known_symptoms' =>
                    $databaseComparison['other_known_symptoms'],

                'visual_summary' => null,
                'visible_symptoms' => [],
                'possible_diseases' => [],
                'image_analysis_confidence' => null,
                'image_quality' => null,
                'needs_better_image' => false,

                'confirmation_answers' => null,

                'ai_model' => 'gemini-3.6-flash',
                'analyzed_at' => now(),

                'symptoms' => $symptomHistoryRows,
            ]);

        } catch (\Throwable $e) {

            report($e);

            return response()->json([
                'success' => false,
                'message' =>
                    'The AI result was generated, but saving the analysis history failed: ' .
                    $e->getMessage(),
            ], 500);
        }

        return response()->json([

            'success' => true,

            'analysis_id' => $analysisRecord->id,

            'result' => [

                'analysis_id' => $analysisRecord->id,

                /*
                |--------------------------------------------------------------------------
                | AI identification
                |--------------------------------------------------------------------------
                */

                'identification_source' =>
                    'Gemini AI',

                'database_match' =>
                    $databaseMatchAvailable,

                'ai_identified' =>
                    true,

                /*
                |--------------------------------------------------------------------------
                | AI disease
                |--------------------------------------------------------------------------
                */

                'category_id' =>
                    $categoryId,

                'disease_name' =>
                    $aiResult['disease_name']
                    ?? 'Unknown Disease',

                /*
                | IMPORTANT:
                | This is NOT the database match percentage.
                | The Blade can use ai_confidence for the AI ring.
                */

                'match_percentage' =>
                    0,

                'ai_confidence' =>
                    $aiResult['confidence']
                    ?? '',

                'ai_confidence_score' =>
                    (int) (
                        $aiResult['confidence_score']
                        ?? 0
                    ),

                'ai_reason' =>
                    $aiResult['reason']
                    ?? '',

                /*
                |--------------------------------------------------------------------------
                | Selected symptoms considered by Gemini
                |--------------------------------------------------------------------------
                */

                'matched_count' =>
                    0,

                'selected_count' =>
                    $selectedCount,

                'total_disease_symptoms' =>
                    0,

                'matched_symptoms' =>
                    [],

                'unmatched_symptoms' =>
                    $selectedSymptomNames,

                /*
                |--------------------------------------------------------------------------
                | Gemini analysis
                |--------------------------------------------------------------------------
                */

                'causes' =>
                    $aiResult['causes']
                    ?? '',

                'prevention' =>
                    $aiResult['prevention']
                    ?? '',

                'fertilizer' =>
                    $fertilizerRecommendation ?? '',

                'fertilizer_source' =>
                    $fertilizerRecommendation
                        ? 'Administrator-defined mapping'
                        : null,

                'fertilizer_mapping_found' =>
                    $adminFertilizers->isNotEmpty(),

                /*
                |--------------------------------------------------------------------------
                | No web-search sources are used in the current free-tier flow
                |--------------------------------------------------------------------------
                */

                'sources' =>
                    $aiResult['sources']
                    ?? [],

                /*
                |--------------------------------------------------------------------------
                | Database comparison
                |--------------------------------------------------------------------------
                |
                | This is only informational.
                | It does NOT determine the disease shown above.
                |
                */

                'database_comparison' =>
                    $databaseComparison,
            ],

            /*
            |--------------------------------------------------------------------------
            | Keep database alternatives separately
            |--------------------------------------------------------------------------
            */

            'alternatives' =>
                $databaseAlternatives,

            /*
            |--------------------------------------------------------------------------
            | General message
            |--------------------------------------------------------------------------
            */

            'message' =>
                $databaseMatchAvailable
                    ? 'Gemini independently analyzed the selected symptoms. The database match is shown only as a comparison.'
                    : 'No registered disease matched the selected symptoms. Gemini independently analyzed the selected symptoms.',
        ]);
    }

    /**
     * Resolve an existing CARDO-FER disease category by the AI-generated
     * disease name. This is only done after Gemini has produced its result;
     * the category is never supplied to Gemini as an input hint.
     */
    private function resolveCategoryId(?string $diseaseName): ?int
    {
        $diseaseName = trim((string) $diseaseName);

        if ($diseaseName === '') {
            return null;
        }

        return diseases_categories::whereRaw(
            'LOWER(diseases_name) = ?',
            [mb_strtolower($diseaseName)]
        )->value('category_id');
    }


    /**
     * Convert image-analysis confirmation evidence into a structured JSON
     * array suitable for the analysis history table.
     */
    private function buildConfirmationAnswers(array $confirmedEvidence): array
    {
        return collect($confirmedEvidence)
            ->map(function ($item) {

                $item = trim((string) $item);

                if ($item === '') {
                    return null;
                }

                if (preg_match(
                    '/^(YES|NO|NOT SURE):\\s*(.+)$/i',
                    $item,
                    $matches
                )) {

                    return [
                        'question' => trim($matches[2]),
                        'answer' => strtoupper(trim($matches[1])),
                    ];
                }

                return null;
            })
            ->filter()
            ->values()
            ->all();
    }


    /**
     * Store one completed analysis and all its symptom snapshot rows.
     */
    private function storeAnalysisHistory(array $payload): DiseaseAnalysisResult
    {
        $user = Auth::user();

        return DB::transaction(function () use ($payload, $user) {

            $field = $payload['field'] ?? null;

            $analysis = DiseaseAnalysisResult::create([

                'user_id' => $user?->id,
                'farmer_name' => $user?->name,
                'farmer_email' => $user?->email,

                'analysis_type' =>
                    $payload['analysis_type'] ?? 'symptoms',
                'status' =>
                    $payload['status'] ?? 'completed',

                'field_area' => $field['area'] ?? null,
                'field_area_unit' => $field['area_unit'] ?? null,
                'field_plant_count' => $field['plant_count'] ?? null,

                'image_path' =>
                    $payload['image_path'] ?? null,
                'image_original_name' =>
                    $payload['image_original_name'] ?? null,
                'image_mime_type' =>
                    $payload['image_mime_type'] ?? null,

                'disease_name' =>
                    $payload['disease_name'] ?? null,
                'category_id' =>
                    $payload['category_id'] ?? null,

                'ai_confidence' =>
                    $payload['ai_confidence'] ?? null,
                'ai_confidence_score' =>
                    $payload['ai_confidence_score'] ?? null,
                'ai_reason' =>
                    $payload['ai_reason'] ?? null,

                'causes' =>
                    $payload['causes'] ?? null,
                'prevention' =>
                    $payload['prevention'] ?? null,
                'fertilizer' =>
                    $payload['fertilizer'] ?? null,

                'database_match_found' =>
                    (bool) ($payload['database_match_found'] ?? false),
                'database_disease_name' =>
                    $payload['database_disease_name'] ?? null,
                'database_match_percentage' =>
                    $payload['database_match_percentage'] ?? null,
                'database_matched_count' =>
                    $payload['database_matched_count'] ?? null,
                'database_total_symptoms' =>
                    $payload['database_total_symptoms'] ?? null,
                'database_matched_symptoms' =>
                    $payload['database_matched_symptoms'] ?? [],
                'database_other_known_symptoms' =>
                    $payload['database_other_known_symptoms'] ?? [],

                'visual_summary' =>
                    $payload['visual_summary'] ?? null,
                'visible_symptoms' =>
                    $payload['visible_symptoms'] ?? [],
                'possible_diseases' =>
                    $payload['possible_diseases'] ?? [],
                'image_analysis_confidence' =>
                    $payload['image_analysis_confidence'] ?? null,
                'image_quality' =>
                    $payload['image_quality'] ?? null,
                'needs_better_image' =>
                    (bool) ($payload['needs_better_image'] ?? false),

                'confirmation_answers' =>
                    $payload['confirmation_answers'] ?? null,

                'ai_model' =>
                    $payload['ai_model'] ?? null,
                'analyzed_at' =>
                    $payload['analyzed_at'] ?? now(),
            ]);


            foreach (($payload['symptoms'] ?? []) as $symptom) {

                $symptomName = trim(
                    (string) ($symptom['symptom_name'] ?? '')
                );

                if ($symptomName === '') {
                    continue;
                }

                DiseaseAnalysisSymptom::create([
                    'analysis_id' => $analysis->id,
                    'symptom_id' =>
                        $symptom['symptom_id'] ?? null,
                    'symptom_name' => $symptomName,
                    'source' =>
                        $symptom['source'] ?? 'farmer',
                ]);
            }

            return $analysis;
        });
    }
    /**
     * Farmer Fertilizer Management / Documentation.
     *
     * This page is driven only by administrator-defined
     * disease -> fertilizer mappings. No AI recommendation is generated here.
     */
    public function fertilizer()
    {
        $diseaseFertilizers = DB::table('diseases_categories as dc')
            ->leftJoin(
                'disease_fertilizers as df',
                'dc.category_id',
                '=',
                'df.category_id'
            )
            ->leftJoin(
                'fertilizer_regs as fr',
                'df.fertilizer_id',
                '=',
                'fr.id'
            )
            ->select([
                'dc.category_id',
                'dc.diseases_name',
                'fr.id as fertilizer_id',
                'fr.fertilizer_name',
                'fr.category as fertilizer_category',
                'fr.description',
                'fr.dosage_amount',
                'fr.dosage_unit',
                'fr.reference_quantity',
                'fr.reference_unit',
            ])
            ->orderBy('dc.diseases_name', 'asc')
            ->orderBy('fr.fertilizer_name', 'asc')
            ->get()
            ->groupBy('category_id');

        // Field details let the guide page estimate quantities for the farmer's field.
        $field = FarmerField::where(
            'user_id',
            Auth::id()
        )->first();

        return view(
            'farmer.fertilizer',
            compact('diseaseFertilizers', 'field')
        );
    }

    /**
     * Farmer Fertilizer Reminder / application history.
     */
    public function fertilizerReminder()
    {
        $fertilizers = fertilizer_reg::orderBy(
            'fertilizer_name',
            'asc'
        )->get();

        $applications = FertilizerApplication::with('fertilizer')
            ->where('user_id', Auth::id())
            ->orderByDesc('applied_date')
            ->get();

        // Field information is used by the reminder page to calculate
        // suggested fertilizer quantities from the farmer's saved details.
        $field = FarmerField::where(
            'user_id',
            Auth::id()
        )->first();

        return view(
            'farmer.fertilizer-reminder',
            compact('fertilizers', 'applications', 'field')
        );
    }

    /**
     * Store one farmer fertilizer application for the reminder/history page.
     */
    public function fertilizerStore(Request $request)
    {
        $validated = $request->validate([
            'fertilizer_id' => [
                'required',
                'exists:fertilizer_regs,id',
            ],
            'quantity' => [
                'required',
                'numeric',
                'min:0.01',
            ],
            'quantity_unit' => [
                'required',
                'in:g,kg,ml,L',
            ],
            'applied_date' => [
                'required',
                'date',
            ],
            'next_application_date' => [
                'nullable',
                'date',
                'after_or_equal:applied_date',
            ],
            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        // Save the fertilizer application first so email errors never lose the record.
        $application = FertilizerApplication::create([
            'user_id' => Auth::id(),
            'fertilizer_id' => $validated['fertilizer_id'],
            'quantity' => $validated['quantity'],
            'quantity_unit' => $validated['quantity_unit'],
            'applied_date' => $validated['applied_date'],
            'next_application_date' => $validated['next_application_date'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Preserve existing behavior: clear older upcoming reminders for the same fertilizer.
        FertilizerApplication::where('user_id', Auth::id())
            ->where('fertilizer_id', $application->fertilizer_id)
            ->where('id', '!=', $application->id)
            ->whereNotNull('next_application_date')
            ->whereDate('applied_date', '<=', $validated['applied_date'])
            ->update(['next_application_date' => null]);

        // Send confirmation to the logged-in farmer using the existing mail configuration.
        $user = Auth::user();
        $emailSent = false;

        if ($user && $user->email) {
            try {
                $application->load('fertilizer');

                Mail::to($user->email)->send(
                    new FertilizerScheduleConfirmationMail(
                        $application,
                        $user->name ?? 'Farmer'
                    )
                );

                $emailSent = true;
            } catch (\Throwable $e) {
                // Keep the application saved even if email delivery fails.
                report($e);
            }
        }

        $message = 'Fertilizer application recorded successfully.';

        if ($emailSent) {
            $message .= ' A confirmation email has been sent to your registered email address.';
        } else {
            $message .= ' However, the confirmation email could not be sent. Please check the mail configuration and logs.';
        }

        return redirect()
            ->route('farmer.fertilizer.reminder')
            ->with('success', $message);
    }

    /**
     * Fetch only administrator-approved fertilizers mapped to one disease.
     */
    private function getAdminFertilizersForCategory(?int $categoryId)
    {
        if (!$categoryId) {
            return collect();
        }

        return DB::table('disease_fertilizers as df')
            ->join(
                'fertilizer_regs as fr',
                'df.fertilizer_id',
                '=',
                'fr.id'
            )
            ->where('df.category_id', $categoryId)
            ->select([
                'fr.id',
                'fr.fertilizer_name',
                'fr.category',
                'fr.description',
                'fr.dosage_amount',
                'fr.dosage_unit',
                'fr.reference_quantity',
                'fr.reference_unit',
            ])
            ->orderBy('fr.fertilizer_name', 'asc')
            ->get();
    }

    /**
     * Convert administrator-defined fertilizer mappings into the string
     * expected by the existing disease-identification Blade.
     */
    private function formatAdminFertilizerRecommendation($fertilizers): ?string
    {
        if (!$fertilizers || $fertilizers->isEmpty()) {
            return null;
        }

        return $fertilizers->map(function ($fertilizer) {
            $line = '• ' . $fertilizer->fertilizer_name;

            if (
                $fertilizer->dosage_amount !== null &&
                $fertilizer->dosage_unit
            ) {
                $line .= ' — Dosage: ' .
                    $fertilizer->dosage_amount . ' ' .
                    $fertilizer->dosage_unit;
            }

            if (
                $fertilizer->reference_quantity !== null &&
                $fertilizer->reference_unit
            ) {
                $line .= ' (Reference: ' .
                    $fertilizer->reference_quantity . ' ' .
                    $fertilizer->reference_unit . ')';
            }

            return $line;
        })->implode("\n");
    }


    /**
     * Display only the logged-in farmer's saved disease-analysis history.
     */
    public function analysisHistory()
    {
        $analyses = DiseaseAnalysisResult::with('symptoms')
            ->where('user_id', Auth::id())
            ->orderByDesc('analyzed_at')
            ->orderByDesc('id')
            ->paginate(10);

        return view('farmer.analysis-history', compact('analyses'));
    }

}