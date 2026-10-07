<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class GeminiService
{
    /*
    |--------------------------------------------------------------------------
    | Analyze a disease already identified by CARDO-FER database
    |--------------------------------------------------------------------------
    |
    | This method is preserved for compatibility with existing code.
    |
    */
    public function analyzeDisease(
        string $diseaseName,
        array $selectedSymptoms,
        array $matchedSymptoms,
        ?array $field
    ): array {
        $apiKey = config('services.gemini.api_key');

        if (!$apiKey) {
            throw new RuntimeException(
                'Gemini API key is not configured.'
            );
        }

        $fieldText = $field
            ? json_encode(
                $field,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            )
            : 'No field details available.';

        $prompt = <<<PROMPT
You are the agricultural AI assistant for CARDO-FER,
a cardamom disease identification and fertilizer recommendation
system.

IMPORTANT SYSTEM RULE:

The disease below has already been identified by the CARDO-FER
database using disease-symptom matching.

You MUST NOT replace the identified disease with another disease.

You are responsible for providing useful agricultural information
about the identified disease.

IDENTIFIED DISEASE:
{$diseaseName}

SELECTED SYMPTOMS BY FARMER:
{$this->formatList($selectedSymptoms)}

SYMPTOMS THAT MATCHED THE IDENTIFIED DISEASE:
{$this->formatList($matchedSymptoms)}

FARMER FIELD DETAILS:
{$fieldText}

TASK:

1. Explain the likely causes of the identified disease.

2. Provide practical prevention and management measures suitable
   for cardamom cultivation.

3. Recommend fertilizer or nutrient management that is specifically
   relevant to the identified disease and cardamom crop.

4. The fertilizer recommendation must be disease-specific.
   Do not provide one generic fertilizer recommendation for every
   disease.

5. Consider the farmer's field area and plant count when providing
   fertilizer guidance.

6. Do NOT invent a fertilizer quantity or dosage when a reliable
   application rate is not provided in the available information.

7. Do NOT claim that the disease identification is a confirmed
   scientific diagnosis. It is a symptom-pattern decision-support
   result.

8. Do NOT invent diseases that are not provided by CARDO-FER.

9. Keep the response practical and understandable for a cardamom
   farmer.

RETURN ONLY VALID JSON.

The JSON must contain exactly these fields:

{
    "causes": "Plain text explanation of the likely causes.",
    "prevention": "Plain text prevention and management guidance.",
    "fertilizer": "Plain text disease-specific fertilizer and nutrient recommendation."
}
PROMPT;

        $response = $this->sendTextRequest($apiKey, $prompt, [
            'causes' => ['type' => 'string'],
            'prevention' => ['type' => 'string'],
            'fertilizer' => ['type' => 'string'],
        ], [
            'causes',
            'prevention',
            'fertilizer',
        ]);

        $result = $this->decodeJsonResult(
            $response,
            [
                'causes',
                'prevention',
                'fertilizer',
            ],
            'Gemini returned an invalid JSON structure.'
        );

        return [
            'causes' => (string) $result['causes'],
            'prevention' => (string) $result['prevention'],
            'fertilizer' => (string) $result['fertilizer'],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Independent disease identification using normal Gemini
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    |
    | This method DOES NOT use Google Search.
    |
    | It uses Gemini's existing knowledge and reasoning to
    | independently analyze the selected symptoms.
    |
    | The database disease match is NOT passed to this method.
    |
    | The method name is retained for controller compatibility.
    |
    */
    public function identifyDiseaseWithWebSearch(
        array $selectedSymptoms,
        ?array $field
    ): array {
        $apiKey = config('services.gemini.api_key');

        if (!$apiKey) {
            throw new RuntimeException(
                'Gemini API key is not configured.'
            );
        }

        $fieldText = $field
            ? json_encode(
                $field,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            )
            : 'No field details available.';

        $prompt = <<<PROMPT
You are the agricultural AI assistant for CARDO-FER,
a cardamom disease identification and fertilizer recommendation
system.

Your task is to independently identify the most likely cardamom
disease based on the symptoms selected by the farmer.

IMPORTANT:

The CARDO-FER application has its own disease database, but you
must NOT assume that any disease from that database is correct.

Do NOT use any pre-identified disease name.

Do NOT simply repeat a disease that may exist in the application.

Analyze the selected symptoms independently using your existing
agricultural knowledge about cardamom diseases.

A disease that is not currently stored in the CARDO-FER database
may be suggested.

IMPORTANT RULES:

1. Identify the MOST LIKELY recognized disease affecting cardamom.

2. Do not claim that you have discovered a scientifically new
   disease.

3. Do not claim certainty.

4. The result is an AI-assisted disease suggestion.

5. Compare the selected symptoms carefully before choosing the
   disease.

6. Use your learned knowledge of cardamom diseases, plant
   pathology, symptoms, causes, and disease progression.

7. Explain why the selected disease is the most likely based on
   the selected symptoms.

8. Provide a confidence level as Low, Moderate, or High.

9. Also provide a confidence_score from 0 to 100. This is an
   estimated model confidence score for display only; it is not a
   scientific probability and must not be presented as certainty.

10. Provide practical prevention and management measures suitable
   for cardamom cultivation.

11. Provide disease-relevant fertilizer or nutrient guidance.

12. Do NOT invent an exact fertilizer quantity or dosage unless
    reliable information is explicitly available.

13. Farmer field area and plant count may be considered for
    general guidance, but do not invent unsupported exact dosage.

14. Clearly state that this is an AI-assisted suggestion and not
    a confirmed scientific diagnosis.

15. Do not claim that the disease was identified from an image.
    This method identifies the disease from farmer-selected
    symptoms.

FARMER SELECTED SYMPTOMS:
{$this->formatList($selectedSymptoms)}

FARMER FIELD DETAILS:
{$fieldText}

RETURN ONLY VALID JSON.

The JSON must contain exactly these fields:

{
    "disease_name": "Most likely recognized cardamom disease",
    "confidence": "Low, Moderate, or High",
    "confidence_score": 0,
    "reason": "Explanation of why this disease best matches the selected symptoms",
    "causes": "Likely causes of the suggested disease",
    "prevention": "Practical prevention and management measures",
    "fertilizer": "Disease-specific fertilizer or nutrient guidance"
}
PROMPT;

        $response = $this->sendTextRequest($apiKey, $prompt, [
            'disease_name' => ['type' => 'string'],
            'confidence' => ['type' => 'string'],
            'confidence_score' => [
                'type' => 'integer',
                'minimum' => 0,
                'maximum' => 100,
            ],
            'reason' => ['type' => 'string'],
            'causes' => ['type' => 'string'],
            'prevention' => ['type' => 'string'],
            'fertilizer' => ['type' => 'string'],
        ], [
            'disease_name',
            'confidence',
            'confidence_score',
            'reason',
            'causes',
            'prevention',
            'fertilizer',
        ]);

        $result = $this->decodeJsonResult(
            $response,
            [
                'disease_name',
                'confidence',
                'confidence_score',
                'reason',
                'causes',
                'prevention',
                'fertilizer',
            ],
            'Gemini returned an invalid disease identification JSON structure.'
        );

        return [
            'disease_name' => (string) $result['disease_name'],
            'confidence' => (string) $result['confidence'],
            'confidence_score' => max(
                0,
                min(
                    100,
                    (int) $result['confidence_score']
                )
            ),
            'reason' => (string) $result['reason'],
            'causes' => (string) $result['causes'],
            'prevention' => (string) $result['prevention'],
            'fertilizer' => (string) $result['fertilizer'],
            'sources' => [],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Preliminary plant image analysis using Gemini Vision
    |--------------------------------------------------------------------------
    |
    | Stage 1 of the image-based workflow:
    |
    | Farmer image
    |      -> Gemini Vision
    |      -> visible symptoms / observations
    |      -> possible disease candidates
    |      -> confirmation questions
    |
    | This method does NOT require the exact same image to exist in
    | the CARDO-FER database. Gemini analyzes the supplied image from
    | its visual understanding capabilities.
    |
    | No Google Search grounding is used.
    |
    */
    public function analyzePlantImage(
        UploadedFile $image,
        array $selectedSymptoms = [],
        ?array $field = null
    ): array {
        $apiKey = config('services.gemini.api_key');

        if (!$apiKey) {
            throw new RuntimeException(
                'Gemini API key is not configured.'
            );
        }

        if (!$image->isValid()) {
            throw new RuntimeException(
                'The uploaded plant image is not valid.'
            );
        }

        $mimeType = $image->getMimeType();

        $allowedMimeTypes = [
            'image/jpeg',
            'image/png',
            'image/webp',
        ];

        if (!in_array($mimeType, $allowedMimeTypes, true)) {
            throw new RuntimeException(
                'Unsupported image type. Please upload JPG, JPEG, PNG or WEBP.'
            );
        }

        $imageBytes = $image->get();

        if ($imageBytes === false || $imageBytes === '') {
            throw new RuntimeException(
                'Unable to read the uploaded plant image.'
            );
        }

        $imageBase64 = base64_encode($imageBytes);

        $symptomsText = $this->formatList($selectedSymptoms);

        $fieldText = $field
            ? json_encode(
                $field,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            )
            : 'No field details available.';

        $prompt = <<<PROMPT
You are the plant-vision assistant for CARDO-FER, a cardamom
plant disease identification and fertilizer recommendation system.

This is the PRELIMINARY VISUAL ANALYSIS stage.

Your job is to inspect the supplied image of a cardamom plant and
identify visible visual evidence that could help determine a plant
disease or disorder.

IMPORTANT IMAGE ANALYSIS RULES:

1. Analyze the image itself. Do not assume that an exact copy of
   this image exists in the CARDO-FER database.

2. You may recognize visual patterns from your learned knowledge.

3. Do NOT claim that the image alone proves a scientific diagnosis.

4. Do NOT claim 100% certainty.

5. Focus on visible features such as leaf spots, lesions,
   discoloration, yellowing, drying, wilting, necrosis, tissue
   deformation, stem symptoms, capsule symptoms, or other visible
   abnormalities.

6. Distinguish between what is clearly visible and what cannot be
   confirmed from the image.

7. If the image is too dark, blurry, distant, obstructed, or does
   not show enough plant tissue, explicitly say so and set
   "needs_better_image" to true.

8. Do not invent a symptom that is not reasonably supported by the
   visible image.

9. Provide a short list of possible recognized cardamom diseases or
   disorders only when the visual evidence supports them.

10. Generate practical confirmation questions that the farmer can
    answer with YES, NO, or NOT SURE. Questions should concern
    symptoms that cannot be confirmed reliably from this image and
    should help distinguish between the possible conditions.

11. The farmer may already have selected symptoms from the CARDO-FER
    symptom list. Treat those symptoms as farmer-reported context,
    not as proof of a disease.

FARMER-SELECTED SYMPTOMS:
{$symptomsText}

FARMER FIELD DETAILS:
{$fieldText}

RETURN ONLY VALID JSON.

The JSON must contain exactly these fields:

{
    "image_quality": "Good, Fair, or Poor",
    "needs_better_image": true,
    "visual_observations": "What is visibly observed in the image.",
    "visible_symptoms": [
        "Visible symptom 1",
        "Visible symptom 2"
    ],
    "possible_diseases": [
        {
            "disease_name": "Possible recognized cardamom disease or disorder",
            "reason": "Why the visible evidence could fit this condition",
            "confidence": "Low, Moderate, or High"
        }
    ],
    "confirmation_questions": [
        "YES/NO/NOT SURE question about a related symptom"
    ],
    "overall_confidence": "Low, Moderate, or High"
}
PROMPT;

        $response = $this->sendImageRequest(
            $apiKey,
            $imageBase64,
            $mimeType,
            $prompt,
            [
                'image_quality' => ['type' => 'string'],
                'needs_better_image' => ['type' => 'boolean'],
                'visual_observations' => ['type' => 'string'],
                'visible_symptoms' => [
                    'type' => 'array',
                    'items' => ['type' => 'string'],
                ],
                'possible_diseases' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'disease_name' => ['type' => 'string'],
                            'reason' => ['type' => 'string'],
                            'confidence' => ['type' => 'string'],
                        ],
                        'required' => [
                            'disease_name',
                            'reason',
                            'confidence',
                        ],
                    ],
                ],
                'confirmation_questions' => [
                    'type' => 'array',
                    'items' => ['type' => 'string'],
                ],
                'overall_confidence' => ['type' => 'string'],
            ],
            [
                'image_quality',
                'needs_better_image',
                'visual_observations',
                'visible_symptoms',
                'possible_diseases',
                'confirmation_questions',
                'overall_confidence',
            ]
        );

        $result = $this->decodeJsonResult(
            $response,
            [
                'image_quality',
                'needs_better_image',
                'visual_observations',
                'visible_symptoms',
                'possible_diseases',
                'confirmation_questions',
                'overall_confidence',
            ],
            'Gemini returned an invalid plant image analysis JSON structure.'
        );

        return [
            'image_quality' => (string) $result['image_quality'],
            'needs_better_image' => (bool) $result['needs_better_image'],
            'visual_observations' => (string) $result['visual_observations'],
            'visible_symptoms' => is_array($result['visible_symptoms'])
                ? array_values($result['visible_symptoms'])
                : [],
            'possible_diseases' => is_array($result['possible_diseases'])
                ? array_values($result['possible_diseases'])
                : [],
            'confirmation_questions' => is_array($result['confirmation_questions'])
                ? array_values($result['confirmation_questions'])
                : [],
            'overall_confidence' => (string) $result['overall_confidence'],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Final image + confirmed symptom disease prediction
    |--------------------------------------------------------------------------
    |
    | Stage 2 of the image workflow:
    |
    | Image + farmer-confirmed symptoms
    |       -> Gemini Vision reasoning
    |       -> final AI disease suggestion
    |
    | This is added now so the service is ready for the next
    | controller/frontend stage. It does NOT use Google Search.
    |
    */
    public function predictDiseaseFromImageAndSymptoms(
        UploadedFile $image,
        array $confirmedSymptoms,
        ?array $field = null,
        array $preliminaryAnalysis = []
    ): array {
        $apiKey = config('services.gemini.api_key');

        if (!$apiKey) {
            throw new RuntimeException(
                'Gemini API key is not configured.'
            );
        }

        if (!$image->isValid()) {
            throw new RuntimeException(
                'The uploaded plant image is not valid.'
            );
        }

        $mimeType = $image->getMimeType();

        $allowedMimeTypes = [
            'image/jpeg',
            'image/png',
            'image/webp',
        ];

        if (!in_array($mimeType, $allowedMimeTypes, true)) {
            throw new RuntimeException(
                'Unsupported image type. Please upload JPG, JPEG, PNG or WEBP.'
            );
        }

        $imageBytes = $image->get();

        if ($imageBytes === false || $imageBytes === '') {
            throw new RuntimeException(
                'Unable to read the uploaded plant image.'
            );
        }

        $imageBase64 = base64_encode($imageBytes);

        $confirmedSymptomsText = $this->formatList($confirmedSymptoms);

        $fieldText = $field
            ? json_encode(
                $field,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            )
            : 'No field details available.';

        $preliminaryText = !empty($preliminaryAnalysis)
            ? json_encode(
                $preliminaryAnalysis,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            )
            : 'No preliminary image analysis available.';

        $prompt = <<<PROMPT
You are the final agricultural AI decision-support assistant for
CARDO-FER, a cardamom disease identification and fertilizer
recommendation system.

Use BOTH the plant image and the farmer-confirmed symptoms to make
an independent final disease suggestion.

IMPORTANT:

1. Inspect the supplied image yourself. Do not assume the
   preliminary analysis is correct.

2. Treat farmer-confirmed symptoms as important evidence, but they
   are still self-reported observations.

3. The disease must NOT be selected merely because it appeared as
   the first preliminary candidate.

4. A disease that is not currently stored in the CARDO-FER database
   may be suggested.

5. Do not claim 100% certainty or a confirmed scientific diagnosis.

6. If the image and confirmed symptoms are insufficient or
   contradictory, return an uncertain result and explain what is
   missing.

7. Explain why the selected disease best fits the combined visual
   evidence and confirmed symptoms.

8. Provide a confidence level as Low, Moderate, or High.

9. Also provide a confidence_score from 0 to 100. This is an
   estimated model confidence score for display only; it is not a
   scientific probability and must not be presented as certainty.

10. Provide practical causes and prevention/management guidance for
   cardamom cultivation.

9. Provide disease-relevant fertilizer or nutrient guidance.

10. Do NOT invent an exact fertilizer quantity or dosage. Exact
    quantity calculations will be handled separately using
    CARDO-FER's stored fertilizer data and farmer field details.

CONFIRMED FARMER SYMPTOMS:
{$confirmedSymptomsText}

FARMER FIELD DETAILS:
{$fieldText}

PRELIMINARY IMAGE ANALYSIS (REFERENCE ONLY):
{$preliminaryText}

RETURN ONLY VALID JSON.

The JSON must contain exactly these fields:

{
    "disease_name": "Most likely recognized cardamom disease or condition",
    "confidence": "Low, Moderate, or High",
    "confidence_score": 0,
    "reason": "Why the combined image and confirmed symptoms support this result",
    "causes": "Likely causes",
    "prevention": "Practical prevention and management",
    "fertilizer": "Disease-specific fertilizer or nutrient guidance",
    "needs_more_evidence": false,
    "follow_up": "What additional information would improve confidence, or an empty string"
}
PROMPT;

        $response = $this->sendImageRequest(
            $apiKey,
            $imageBase64,
            $mimeType,
            $prompt,
            [
                'disease_name' => ['type' => 'string'],
                'confidence' => ['type' => 'string'],
                'confidence_score' => [
                    'type' => 'integer',
                    'minimum' => 0,
                    'maximum' => 100,
                ],
                'reason' => ['type' => 'string'],
                'causes' => ['type' => 'string'],
                'prevention' => ['type' => 'string'],
                'fertilizer' => ['type' => 'string'],
                'needs_more_evidence' => ['type' => 'boolean'],
                'follow_up' => ['type' => 'string'],
            ],
            [
                'disease_name',
                'confidence',
                'confidence_score',
                'reason',
                'causes',
                'prevention',
                'fertilizer',
                'needs_more_evidence',
                'follow_up',
            ]
        );

        $result = $this->decodeJsonResult(
            $response,
            [
                'disease_name',
                'confidence',
                'confidence_score',
                'reason',
                'causes',
                'prevention',
                'fertilizer',
                'needs_more_evidence',
                'follow_up',
            ],
            'Gemini returned an invalid final image disease prediction JSON structure.'
        );

        return [
            'disease_name' => (string) $result['disease_name'],
            'confidence' => (string) $result['confidence'],
            'confidence_score' => max(
                0,
                min(
                    100,
                    (int) $result['confidence_score']
                )
            ),
            'reason' => (string) $result['reason'],
            'causes' => (string) $result['causes'],
            'prevention' => (string) $result['prevention'],
            'fertilizer' => (string) $result['fertilizer'],
            'needs_more_evidence' => (bool) $result['needs_more_evidence'],
            'follow_up' => (string) $result['follow_up'],
            'sources' => [],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Send a normal text-only Interactions API request
    |--------------------------------------------------------------------------
    */
    private function sendTextRequest(
        string $apiKey,
        string $prompt,
        array $properties,
        array $required
    ): array {
        $payload = [
            'input' => $prompt,
            'response_format' => [
                'type' => 'text',
                'mime_type' => 'application/json',
                'schema' => [
                    'type' => 'object',
                    'properties' => $properties,
                    'required' => $required,
                ],
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Text-analysis resilience
        |--------------------------------------------------------------------------
        |
        | Symptom analysis must work even when the selected symptoms do not
        | match a disease stored in CARDO-FER. The controller intentionally
        | calls Gemini for both matched and unmatched symptom sets.
        |
        | Use the current Interactions API beta endpoint and retry transient
        | capacity/network failures before trying a documented fallback model.
        |--------------------------------------------------------------------------
        */

        $models = [
            'gemini-3.6-flash',
            'gemini-3.5-flash',
        ];

        $lastResponse = null;

        foreach ($models as $model) {

            $attempts = 3;

            for ($attempt = 0; $attempt < $attempts; $attempt++) {

                try {

                    $response = Http::timeout(60)
                        ->withHeaders([
                            'Content-Type' => 'application/json',
                            'x-goog-api-key' => $apiKey,
                        ])
                        ->post(
                            'https://generativelanguage.googleapis.com/v1beta/interactions',
                            array_merge(
                                $payload,
                                [
                                    'model' => $model,
                                ]
                            )
                        );

                    $lastResponse = $response;

                } catch (\Throwable $e) {

                    if ($attempt >= $attempts - 1) {
                        continue;
                    }

                    $baseDelay = 1000 * (2 ** $attempt);
                    $jitter = random_int(0, 400);

                    usleep(
                        ($baseDelay + $jitter) * 1000
                    );

                    continue;
                }

                if ($response->successful()) {

                    $data = $response->json();

                    $content = $this->extractModelText($data);

                    if (!$content) {

                        throw new RuntimeException(
                            'Gemini returned an empty response.'
                        );
                    }

                    return [
                        'content' => $content,
                        'data' => $data,
                    ];
                }

                $status = $response->status();

                $isTransient = in_array(
                    $status,
                    [408, 429, 500, 502, 503, 504],
                    true
                );

                if (!$isTransient) {

                    throw new RuntimeException(
                        'Gemini API request failed: ' .
                        $response->body()
                    );
                }

                if ($attempt < $attempts - 1) {

                    $baseDelay = 1000 * (2 ** $attempt);
                    $jitter = random_int(0, 400);

                    usleep(
                        ($baseDelay + $jitter) * 1000
                    );
                }
            }
        }

        $body = $lastResponse
            ? $lastResponse->body()
            : 'No response received from Gemini.';

        throw new RuntimeException(
            'Gemini symptom-analysis request failed after retries and fallback: ' .
            $body
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Send multimodal image + text Interactions API request
    |--------------------------------------------------------------------------
    |
    | Gemini Interactions API accepts image input as base64 data with
    | a mime_type, alongside a text input block.
    |
    */
    private function sendImageRequest(
        string $apiKey,
        string $imageBase64,
        string $mimeType,
        string $prompt,
        array $properties,
        array $required
    ): array {
        $payload = [
            'input' => [
                [
                    'type' => 'image',
                    'mime_type' => $mimeType,
                    'data' => $imageBase64,
                ],
                [
                    'type' => 'text',
                    'text' => $prompt,
                ],
            ],
            'response_format' => [
                'type' => 'text',
                'mime_type' => 'application/json',
                'schema' => [
                    'type' => 'object',
                    'properties' => $properties,
                    'required' => $required,
                ],
            ],
        ];

        /*
        |----------------------------------------------------------------------
        | Gemini can temporarily return 503 when a model is under heavy load.
        | Retry transient failures using exponential backoff, then fall back
        | to Gemini 3.5 Flash-Lite if Gemini 3.6 Flash remains unavailable.
        |----------------------------------------------------------------------
        */

        $models = [
            'gemini-3.6-flash',
            'gemini-3.5-flash-lite',
        ];

        $lastResponse = null;

        foreach ($models as $modelIndex => $model) {

            $attempts = 4;

            for ($attempt = 0; $attempt < $attempts; $attempt++) {

                $response = Http::timeout(90)
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                        'x-goog-api-key' => $apiKey,
                    ])
                    ->post(
                        'https://generativelanguage.googleapis.com/v1beta/interactions',
                        array_merge(
                            $payload,
                            ['model' => $model]
                        )
                    );

                $lastResponse = $response;

                if ($response->successful()) {
                    break 2;
                }

                $status = $response->status();

                $isTransient = in_array(
                    $status,
                    [408, 429, 500, 502, 503, 504],
                    true
                );

                if (!$isTransient) {
                    throw new RuntimeException(
                        'Gemini image API request failed: ' . $response->body()
                    );
                }

                if ($attempt < $attempts - 1) {
                    $baseDelay = 1000 * (2 ** $attempt);
                    $jitter = random_int(0, 500);
                    usleep(($baseDelay + $jitter) * 1000);
                }
            }

            /*
            | If the primary model exhausted its transient retries, the loop
            | continues with the fallback model.
            */
        }

        if (!$lastResponse || $lastResponse->failed()) {
            $body = $lastResponse
                ? $lastResponse->body()
                : 'No response received from Gemini.';

            throw new RuntimeException(
                'Gemini image API request failed after retries and fallback: ' .
                $body
            );
        }

        $data = $response->json();

        $content = $this->extractModelText($data);

        if (!$content) {
            throw new RuntimeException(
                'Gemini returned an empty image-analysis response.'
            );
        }

        return [
            'content' => $content,
            'data' => $data,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Decode a structured JSON model response
    |--------------------------------------------------------------------------
    */
    private function decodeJsonResult(
        array $response,
        array $requiredFields,
        string $errorMessage
    ): array {
        $content = $response['content'] ?? null;

        if (!$content) {
            throw new RuntimeException(
                'Gemini returned an empty response.'
            );
        }

        $result = json_decode(
            trim($content),
            true
        );

        if (!is_array($result)) {
            $cleaned = preg_replace(
                '/^```(?:json)?\s*|\s*```$/i',
                '',
                trim($content)
            );

            $result = json_decode(
                trim((string) $cleaned),
                true
            );
        }

        if (!is_array($result)) {
            throw new RuntimeException($errorMessage);
        }

        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $result)) {
                throw new RuntimeException($errorMessage);
            }
        }

        return $result;
    }


    /*
    |--------------------------------------------------------------------------
    | Extract model text from Gemini Interactions API response
    |--------------------------------------------------------------------------
    */
    private function extractModelText(array $data): ?string
    {
        foreach (($data['steps'] ?? []) as $step) {
            if (($step['type'] ?? null) !== 'model_output') {
                continue;
            }

            foreach (($step['content'] ?? []) as $part) {
                if (
                    ($part['type'] ?? null) === 'text' &&
                    isset($part['text'])
                ) {
                    return $part['text'];
                }
            }
        }

        if (isset($data['output_text']) && is_string($data['output_text'])) {
            return $data['output_text'];
        }

        return null;
    }


    /*
    |--------------------------------------------------------------------------
    | Format array as bullet list for prompt
    |--------------------------------------------------------------------------
    */
    private function formatList(array $items): string
    {
        if (empty($items)) {
            return '- None';
        }

        return collect($items)
            ->map(function ($item) {
                return '- ' . $item;
            })
            ->implode("\n");
    }
}
