@extends('layouts.FarmerMaster')

@section('title', 'Analysis Details — CARDO-FER')

@section('content')
@php
    /* ================================================================
       Prepare everything once, so the markup below stays simple.
       Every variable / relation name is the same as in your old page.
       ================================================================ */
    $analysisDate = $analysis->analyzed_at ?? $analysis->created_at;
    $dateObj = $analysisDate ? \Carbon\Carbon::parse($analysisDate) : null;

    $typeLabels = [
        'symptoms'           => ['Symptom analysis', 'fa-stethoscope'],
        'image'              => ['Image analysis', 'fa-camera'],
        'image_and_symptoms' => ['Image + symptoms', 'fa-images'],
    ];
    $typeInfo = $typeLabels[$analysis->analysis_type ?? ''] ?? ['Disease analysis', 'fa-microscope'];

    $status = $analysis->status ?? 'unknown';
    $statusMap = [
        'completed'          => ['Completed', 'ok', 'fa-circle-check'],
        'failed'             => ['Failed', 'bad', 'fa-circle-xmark'],
        'needs_confirmation' => ['Needs confirmation', 'warn', 'fa-circle-question'],
    ];
    $statusInfo = $statusMap[$status] ?? [ucfirst(str_replace('_', ' ', $status)), 'neutral', 'fa-circle-info'];

    /* confidence: some rows store 0-1, others 0-100 */
    $confidence = is_numeric($analysis->ai_confidence_score ?? null)
        ? (float) $analysis->ai_confidence_score
        : (is_numeric($analysis->ai_confidence ?? null) ? (float) $analysis->ai_confidence : null);
    if ($confidence !== null && $confidence >= 0 && $confidence <= 1) { $confidence *= 100; }
    if ($confidence !== null) { $confidence = max(0, min(100, $confidence)); }
    $confLevel = $confidence === null ? null : ($confidence >= 75 ? 'High' : ($confidence >= 50 ? 'Medium' : 'Low'));
    $confClass = $confidence === null ? '' : ($confidence >= 75 ? 'hi' : ($confidence >= 50 ? 'mid' : 'lo'));
    $confNote  = $confidence === null ? '' : ($confidence >= 75
        ? 'The model was fairly sure about this result.'
        : ($confidence >= 50
            ? 'Moderately sure. Compare with your plants before acting.'
            : 'Low certainty. Treat this as a hint and check with an expert.'));

    $diseaseName = $analysis->disease_name ?: 'No disease prediction available';
    $hasDisease  = !empty($analysis->disease_name);

    $imageUrl = !empty($analysis->image_path)
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($analysis->image_path)
        : null;
    $imageName = $analysis->image_original_name ?? ($analysis->image_name ?? null);

    $causes     = $analysis->causes ?? null;
    $prevention = $analysis->prevention ?? null;
    $fertilizer = $analysis->fertilizer ?? null;
    $reason     = $analysis->ai_reason ?? null;

    /* json columns may arrive as strings */
    $asArray = function ($v) {
        if (is_string($v)) { $v = json_decode($v, true) ?? []; }
        return is_array($v) ? $v : [];
    };
    $visibleSymptoms     = $asArray($analysis->visible_symptoms ?? []);
    $possibleDiseases    = $asArray($analysis->possible_diseases ?? []);
    $confirmationAnswers = $asArray($analysis->confirmation_answers ?? []);

    /* split a saved paragraph into separate points (bullets / numbered / sentences) */
    $toPoints = function ($text) {
        if (!filled($text)) { return []; }
        $out = [];
        foreach (preg_split('/\R+/', trim((string) $text)) as $line) {
            $line = trim(preg_replace('/^\s*(?:[-*\x{2022}\x{25CF}\x{25AA}]|\d+[.)])\s*/u', '', $line));
            if ($line !== '') { $out[] = $line; }
        }
        if (count($out) < 2) {
            $sent = preg_split('/(?<=[.!?])\s+(?=[A-Z0-9])/u', trim((string) $text));
            $sent = array_values(array_filter(array_map('trim', $sent)));
            if (count($sent) >= 2) { $out = $sent; }
        }
        return $out;
    };
    $causePoints = $toPoints($causes);
    $stepPoints  = $toPoints($prevention);
    $fertPoints  = $toPoints($fertilizer);

    /* saved symptoms (relation) */
    $srcLabels = ['farmer' => 'You selected', 'confirmed' => 'You confirmed', 'ai' => 'AI found in photo'];
    $srcIcons  = ['farmer' => 'fa-hand-pointer', 'confirmed' => 'fa-circle-check', 'ai' => 'fa-wand-magic-sparkles'];
    $symptoms = []; $srcCount = [];
    foreach (($analysis->symptoms ?? []) as $s) {
        $src = $s->source ?? '';
        $symptoms[] = [
            'name'  => $s->symptom_name,
            'src'   => $src,
            'label' => $srcLabels[$src] ?? ($src !== '' ? ucfirst(str_replace('_', ' ', $src)) : ''),
            'icon'  => $srcIcons[$src] ?? 'fa-circle-check',
        ];
        if ($src !== '') { $srcCount[$src] = ($srcCount[$src] ?? 0) + 1; }
    }

    $visibleNames = [];
    foreach ($visibleSymptoms as $v) {
        $n = is_array($v) ? ($v['symptom_name'] ?? $v['name'] ?? $v['symptom'] ?? null) : $v;
        if (is_string($n) && trim($n) !== '') { $visibleNames[] = trim($n); }
    }

    $possible = [];
    foreach ($possibleDiseases as $p) {
        $n = is_array($p) ? ($p['disease_name'] ?? $p['name'] ?? $p['disease'] ?? null) : $p;
        if (!is_string($n) || trim($n) === '') { continue; }
        $pc = is_array($p) ? ($p['confidence'] ?? $p['probability'] ?? $p['score'] ?? null) : null;
        if (is_numeric($pc)) { $pc = (float) $pc; if ($pc <= 1) { $pc *= 100; } $pc = (int) round(max(0, min(100, $pc))); } else { $pc = null; }
        $possible[] = ['name' => trim($n), 'pct' => $pc, 'top' => strcasecmp(trim($n), (string) $analysis->disease_name) === 0];
    }

    /* confirmation Q&A */
    $qa = [];
    foreach ($confirmationAnswers as $question => $answer) {
        $qText = is_array($answer)
            ? ($answer['question'] ?? (is_string($question) ? $question : 'Confirmation question'))
            : (is_string($question) ? $question : 'Confirmation question');
        $aVal = is_array($answer) ? ($answer['answer'] ?? $answer['response'] ?? 'Not recorded') : $answer;
        if (is_bool($aVal)) { $aVal = $aVal ? 'Yes' : 'No'; }
        elseif (is_array($aVal)) { $aVal = json_encode($aVal, JSON_UNESCAPED_UNICODE); }
        $aVal = is_scalar($aVal) ? (string) $aVal : 'Not recorded';
        $low = strtolower(trim($aVal));
        $tone = in_array($low, ['yes', 'y', 'true', '1'], true) ? 'yes' : (in_array($low, ['no', 'n', 'false', '0'], true) ? 'no' : 'other');
        $qa[] = ['q' => is_string($qText) ? $qText : 'Confirmation question', 'a' => $aVal, 'tone' => $tone];
    }

    /* field info — falls back to the column names the controller really saves */
    $areaVal  = $analysis->field_area ?? null;
    $areaUnit = $analysis->field_area_unit ?? 'units';
    $areaText = is_numeric($areaVal)
        ? rtrim(rtrim(number_format((float) $areaVal, 2, '.', ''), '0'), '.') . ' ' . $areaUnit
        : null;
    $plantVal = $analysis->plant_count ?? ($analysis->field_plant_count ?? null);
    $plantText = is_numeric($plantVal) ? number_format((int) $plantVal) : null;
    $fieldName = $analysis->field_name ?? null;
    $hasField  = $areaText || $plantText || $fieldName;

    /* optional links (hidden when the route does not exist) */
    $R = \Illuminate\Support\Facades\Route::class;
    $guideUrl    = $R::has('farmer.fertilizer') ? route('farmer.fertilizer') : null;
    $reminderUrl = $R::has('farmer.fertilizer.reminder') ? route('farmer.fertilizer.reminder') : null;
    $fieldUrl    = $R::has('farmer.field') ? route('farmer.field') : null;

    $aid = $analysis->id ?? 0;

    /* section list for the sticky nav */
    $nav = [['ad-symptoms', 'Symptoms', 'fa-stethoscope']];
    if ($possible)  { $nav[] = ['ad-possible', 'Also possible', 'fa-code-compare']; }
    if ($qa)        { $nav[] = ['ad-qa', 'Questions', 'fa-comments']; }
    $nav[] = ['ad-causes', 'Causes', 'fa-microscope'];
    $nav[] = ['ad-prevent', 'Prevention', 'fa-shield-halved'];
    $nav[] = ['ad-fert', 'Fertilizer', 'fa-seedling'];
    $nav[] = ['ad-field', 'Field', 'fa-map-location-dot'];

    /* text used by "Copy summary" */
    $summary = [
        'title'      => 'CARDO-FER analysis' . ($dateObj ? ' — ' . $dateObj->format('d M Y') : ''),
        'disease'    => $hasDisease ? $diseaseName : null,
        'confidence' => $confidence !== null ? number_format($confidence, 1) . '%' : null,
        'symptoms'   => array_column($symptoms, 'name'),
        'causes'     => $causePoints ?: (filled($causes) ? [(string) $causes] : []),
        'prevention' => $stepPoints ?: (filled($prevention) ? [(string) $prevention] : []),
        'fertilizer' => $fertPoints ?: (filled($fertilizer) ? [(string) $fertilizer] : []),
    ];
@endphp

<div class="ad-page">
<div class="ad-wrap">

    {{-- ===== Header ===== --}}
    <a href="{{ route('farmer.analysis-history') }}" class="ad-back">
        <i class="fas fa-arrow-left"></i> Back to history
    </a>

    <header class="ad-head">
        <div>
            <div class="ad-eyebrow"><i class="fas fa-file-medical-alt"></i> Analysis report</div>
            <h1 class="ad-title">Analysis Details</h1>
            <p class="ad-sub">Everything saved from this cardamom disease check, with the care steps to follow.</p>
        </div>
        <div class="ad-actions">
            <button type="button" class="ad-btn ad-btn-ghost" id="adCopy">
                <i class="fas fa-copy"></i> <span>Copy summary</span>
            </button>
            <button type="button" class="ad-btn ad-btn-ghost" id="adPrint">
                <i class="fas fa-print"></i> <span>Print</span>
            </button>
            <a href="{{ route('disease.identification') }}" class="ad-btn ad-btn-gold">
                <i class="fas fa-plus-circle"></i> New analysis
            </a>
        </div>
    </header>

    {{-- ===== Hero: photo + result + confidence ===== --}}
    <section class="ad-hero {{ $imageUrl ? 'has-photo' : '' }}" aria-label="Result">
        @if ($imageUrl)
            <button type="button" class="ad-photo" id="adPhoto" aria-label="Enlarge the uploaded plant photo">
                <img src="{{ $imageUrl }}" alt="Cardamom plant submitted for disease analysis" loading="lazy">
                <span class="ad-photo-hint"><i class="fas fa-magnifying-glass-plus"></i> Tap to enlarge</span>
            </button>
        @endif

        <div class="ad-hero-main">
            <div class="ad-tags">
                <span class="ad-status ad-s-{{ $statusInfo[1] }}"><i class="fas {{ $statusInfo[2] }}"></i> {{ $statusInfo[0] }}</span>
                <span class="ad-tag"><i class="fas {{ $typeInfo[1] }}"></i> {{ $typeInfo[0] }}</span>
                @if ($dateObj)
                    <span class="ad-tag"><i class="far fa-calendar"></i> {{ $dateObj->format('d M Y') }} · {{ $dateObj->format('h:i A') }}</span>
                @endif
            </div>

            <div class="ad-kicker">Predicted disease</div>
            <h2 class="ad-disease {{ $hasDisease ? '' : 'is-none' }}">{{ $diseaseName }}</h2>

            @if (!empty($reason))
                <p class="ad-reason">{{ $reason }}</p>
            @elseif ($status === 'failed')
                <p class="ad-reason">This analysis did not complete, so a disease prediction may not be available.</p>
            @else
                <p class="ad-reason">A detailed explanation was not saved for this analysis.</p>
            @endif

            @if ($imageName)
                <div class="ad-file"><i class="fas fa-file-image"></i> {{ $imageName }}</div>
            @endif
        </div>

        <div class="ad-conf">
            @if ($confidence !== null)
                <div class="ad-ring {{ $confClass }}" role="img" aria-label="Confidence {{ number_format($confidence, 1) }} percent">
                    <svg viewBox="0 0 36 36"><circle class="bg" cx="18" cy="18" r="15.9"/><circle class="fg" id="adRing" cx="18" cy="18" r="15.9" data-v="{{ round($confidence, 1) }}" pathLength="100"/></svg>
                    <div class="ad-ring-mid"><b>{{ number_format($confidence, 0) }}<small>%</small></b></div>
                </div>
                <div class="ad-conf-lv ad-lv-{{ $confClass }}">{{ $confLevel }} confidence</div>
                <p class="ad-conf-note">{{ $confNote }}</p>
            @else
                <div class="ad-ring none"><div class="ad-ring-mid"><b>—</b></div></div>
                <div class="ad-conf-lv">No confidence saved</div>
                <p class="ad-conf-note">A confidence score was not recorded for this analysis.</p>
            @endif
        </div>
    </section>

    {{-- ===== Status banner ===== --}}
    @if ($status === 'failed')
        <div class="ad-banner ad-b-bad">
            <i class="fas fa-triangle-exclamation"></i>
            <div><b>This analysis didn't finish.</b> Try again with a clear, well-lit photo of the affected leaf or capsule.</div>
            <a href="{{ route('disease.identification') }}" class="ad-btn ad-btn-sm">Try again</a>
        </div>
    @elseif ($status === 'needs_confirmation')
        <div class="ad-banner ad-b-warn">
            <i class="fas fa-circle-question"></i>
            <div><b>This result is not confirmed yet.</b> Treat it as a possibility. Run a new analysis and answer the confirmation questions for a firmer result.</div>
            <a href="{{ route('disease.identification') }}" class="ad-btn ad-btn-sm">Confirm now</a>
        </div>
    @else
        <div class="ad-banner ad-b-info">
            <i class="fas fa-shield-heart"></i>
            <div>This is an AI estimate, not a guaranteed diagnosis. If the problem keeps spreading, show your plants to an agriculture officer.</div>
        </div>
    @endif

    {{-- ===== Section nav ===== --}}
    <nav class="ad-nav" id="adNav" aria-label="Sections">
        @foreach ($nav as $n)
            <a href="#{{ $n[0] }}" data-target="{{ $n[0] }}"><i class="fas {{ $n[2] }}"></i> {{ $n[1] }}</a>
        @endforeach
    </nav>

    <div class="ad-layout">
        {{-- ================= MAIN COLUMN ================= --}}
        <div class="ad-main">

            {{-- Symptoms --}}
            <section class="ad-card" id="ad-symptoms">
                <div class="ad-card-h">
                    <div class="ad-ic"><i class="fas fa-stethoscope"></i></div>
                    <div class="ad-card-t">
                        <h3>Recorded symptoms</h3>
                        <p>{{ count($symptoms) }} {{ count($symptoms) === 1 ? 'symptom' : 'symptoms' }} saved with this analysis</p>
                    </div>
                </div>

                @if (count($srcCount) > 1)
                    <div class="ad-filter" role="group" aria-label="Filter symptoms">
                        <button type="button" class="on" data-f="all">All <b>{{ count($symptoms) }}</b></button>
                        @foreach ($srcCount as $k => $c)
                            <button type="button" data-f="{{ $k }}">{{ $srcLabels[$k] ?? ucfirst($k) }} <b>{{ $c }}</b></button>
                        @endforeach
                    </div>
                @endif

                @if ($symptoms)
                    <div class="ad-chips" id="adSymList">
                        @foreach ($symptoms as $s)
                            <div class="ad-sym" data-src="{{ $s['src'] }}">
                                <i class="fas {{ $s['icon'] }}"></i>
                                <div>
                                    <span>{{ $s['name'] }}</span>
                                    @if ($s['label'] !== '') <small>{{ $s['label'] }}</small> @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="ad-empty"><i class="fas fa-circle-info"></i> No symptoms were saved for this analysis.</p>
                @endif

                @if ($visibleNames)
                    <div class="ad-sub-h"><i class="fas fa-eye"></i> Also seen in the photo</div>
                    <div class="ad-chips">
                        @foreach ($visibleNames as $vn)
                            <span class="ad-pill"><i class="fas fa-eye"></i> {{ $vn }}</span>
                        @endforeach
                    </div>
                @endif
            </section>

            {{-- Possible diseases --}}
            @if ($possible)
                <section class="ad-card" id="ad-possible">
                    <div class="ad-card-h">
                        <div class="ad-ic"><i class="fas fa-code-compare"></i></div>
                        <div class="ad-card-t">
                            <h3>Other diseases it considered</h3>
                            <p>Possibilities, not confirmed diagnoses</p>
                        </div>
                    </div>
                    <div class="ad-poss">
                        @foreach ($possible as $p)
                            <div class="ad-poss-row {{ $p['top'] ? 'top' : '' }}">
                                <div class="ad-poss-name">
                                    <i class="fas fa-leaf"></i> {{ $p['name'] }}
                                    @if ($p['top']) <em>Final result</em> @endif
                                </div>
                                @if ($p['pct'] !== null)
                                    <div class="ad-poss-bar"><i style="width:{{ $p['pct'] }}%"></i></div>
                                    <div class="ad-poss-pct">{{ $p['pct'] }}%</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Confirmation Q&A --}}
            @if ($qa)
                <section class="ad-card" id="ad-qa">
                    <div class="ad-card-h">
                        <div class="ad-ic"><i class="fas fa-comments"></i></div>
                        <div class="ad-card-t">
                            <h3>Confirmation questions</h3>
                            <p>What you were asked, and what you answered</p>
                        </div>
                    </div>
                    <div class="ad-chat">
                        @foreach ($qa as $row)
                            <div class="ad-q"><i class="fas fa-robot"></i><div>{{ $row['q'] }}</div></div>
                            <div class="ad-a ad-a-{{ $row['tone'] }}"><div>{{ $row['a'] }}</div></div>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Causes --}}
            <section class="ad-card" id="ad-causes">
                <div class="ad-card-h">
                    <div class="ad-ic"><i class="fas fa-microscope"></i></div>
                    <div class="ad-card-t">
                        <h3>Possible causes</h3>
                        <p>Why this problem usually appears</p>
                    </div>
                </div>

                @if (count($causePoints) >= 2)
                    <ul class="ad-points">
                        @foreach ($causePoints as $cp)
                            <li><i class="fas fa-circle-dot"></i><span>{{ $cp }}</span></li>
                        @endforeach
                    </ul>
                @elseif (filled($causes))
                    <div class="ad-text ad-clamp" data-clamp>{{ $causes }}</div>
                @else
                    <p class="ad-empty"><i class="fas fa-circle-info"></i> Cause information was not saved for this analysis.</p>
                @endif
            </section>

            {{-- Prevention --}}
            <section class="ad-card" id="ad-prevent">
                <div class="ad-card-h">
                    <div class="ad-ic"><i class="fas fa-shield-halved"></i></div>
                    <div class="ad-card-t">
                        <h3>Prevention &amp; management</h3>
                        <p>@if (count($stepPoints) >= 2) Tick each step as you do it @else What to do to protect your plants @endif</p>
                    </div>
                    @if (count($stepPoints) >= 2)
                        <div class="ad-progress" id="adProg" data-total="{{ count($stepPoints) }}">
                            <span id="adProgTx">0 of {{ count($stepPoints) }}</span>
                            <div><i id="adProgBar"></i></div>
                        </div>
                    @endif
                </div>

                @if (count($stepPoints) >= 2)
                    <div class="ad-steps" id="adSteps">
                        @foreach ($stepPoints as $i => $sp)
                            <label class="ad-step">
                                <input type="checkbox" data-i="{{ $i }}">
                                <span class="ad-box"><i class="fas fa-check"></i></span>
                                <span class="ad-step-tx">{{ $sp }}</span>
                            </label>
                        @endforeach
                    </div>
                    <p class="ad-note"><i class="fas fa-circle-info"></i> Your ticks are remembered on this device only.</p>
                @elseif (filled($prevention))
                    <div class="ad-text ad-clamp" data-clamp>{{ $prevention }}</div>
                @else
                    <p class="ad-empty"><i class="fas fa-circle-info"></i> Prevention advice was not saved for this analysis.</p>
                @endif
            </section>
        </div>

        {{-- ================= SIDE COLUMN ================= --}}
        <aside class="ad-side">

            {{-- Fertilizer --}}
            <section class="ad-card ad-card-gold" id="ad-fert">
                <div class="ad-card-h">
                    <div class="ad-ic gold"><i class="fas fa-seedling"></i></div>
                    <div class="ad-card-t">
                        <h3>Fertilizer advice</h3>
                        <p>Saved with this analysis</p>
                    </div>
                </div>

                @if (filled($fertilizer))
                    @if (count($fertPoints) >= 2)
                        <ul class="ad-points gold">
                            @foreach ($fertPoints as $fp)
                                <li><i class="fas fa-flask"></i><span>{{ $fp }}</span></li>
                            @endforeach
                        </ul>
                    @else
                        <div class="ad-text ad-clamp" data-clamp>{{ $fertilizer }}</div>
                    @endif
                    <p class="ad-note"><i class="fas fa-triangle-exclamation"></i> Check the dosage in the fertilizer guide before applying.</p>
                @else
                    <p class="ad-empty"><i class="fas fa-circle-info"></i> No fertilizer advice was saved for this analysis.</p>
                @endif

                @if ($guideUrl || $reminderUrl)
                    <div class="ad-links">
                        @if ($guideUrl)
                            <a href="{{ $guideUrl }}" class="ad-btn ad-btn-gold ad-btn-block"><i class="fas fa-book-open"></i> Open fertilizer guide</a>
                        @endif
                        @if ($reminderUrl)
                            <a href="{{ $reminderUrl }}" class="ad-btn ad-btn-ghost ad-btn-block"><i class="fas fa-bell"></i> Set a reminder</a>
                        @endif
                    </div>
                @endif
            </section>

            {{-- Field --}}
            <section class="ad-card" id="ad-field">
                <div class="ad-card-h">
                    <div class="ad-ic"><i class="fas fa-map-location-dot"></i></div>
                    <div class="ad-card-t">
                        <h3>Field details</h3>
                        <p>When the analysis was run</p>
                    </div>
                </div>

                <div class="ad-facts">
                    <div class="ad-fact {{ $areaText ? '' : 'na' }}">
                        <i class="fas fa-ruler-combined"></i>
                        <div><small>Field area</small><b>{{ $areaText ?? 'Not recorded' }}</b></div>
                    </div>
                    <div class="ad-fact {{ $plantText ? '' : 'na' }}">
                        <i class="fas fa-tree"></i>
                        <div><small>Plants</small><b>{{ $plantText ?? 'Not recorded' }}</b></div>
                    </div>
                    <div class="ad-fact {{ $fieldName ? '' : 'na' }}">
                        <i class="fas fa-location-dot"></i>
                        <div><small>Field name</small><b>{{ $fieldName ?? 'Not recorded' }}</b></div>
                    </div>
                </div>

                @if (!$hasField && $fieldUrl)
                    <a href="{{ $fieldUrl }}" class="ad-link"><i class="fas fa-pen"></i> Add your field details</a>
                @endif
            </section>

            {{-- Record --}}
            <section class="ad-card ad-card-quiet">
                <div class="ad-rec">
                    @if ($aid) <div><small>Report</small><b>#{{ $aid }}</b></div> @endif
                    <div><small>Type</small><b>{{ $typeInfo[0] }}</b></div>
                    <div><small>Status</small><b>{{ $statusInfo[0] }}</b></div>
                    @if ($dateObj) <div><small>Saved</small><b>{{ $dateObj->format('d M Y') }}</b></div> @endif
                </div>
            </section>
        </aside>
    </div>

    <div class="ad-foot">
        <a href="{{ route('farmer.analysis-history') }}" class="ad-btn ad-btn-ghost"><i class="fas fa-arrow-left"></i> Back to analysis history</a>
        <a href="{{ route('disease.identification') }}" class="ad-btn ad-btn-gold"><i class="fas fa-plus-circle"></i> New disease analysis</a>
    </div>
</div>

{{-- Photo viewer --}}
@if ($imageUrl)
    <div class="ad-lb" id="adLb" hidden role="dialog" aria-modal="true" aria-label="Plant photo">
        <button type="button" class="ad-lb-x" id="adLbX" aria-label="Close"><i class="fas fa-xmark"></i></button>
        <img src="{{ $imageUrl }}" alt="Uploaded plant photo, enlarged" id="adLbImg">
        <div class="ad-lb-hint">Click the photo to zoom · Esc to close</div>
    </div>
@endif

<div class="ad-toast" id="adToast" role="status" aria-live="polite"></div>
</div>

<style>
.ad-page{--bg:#07190f;--card:#0e271b;--cream:#f1f8e9;--sage:#9fbea6;--dim:#7d9884;--gold:#d9a24b;--gold2:#f0c674;--leaf:#8bd19a;--red:#f0a39a;--amber:#f0c674;--line:rgba(213,232,216,.12);--line2:rgba(213,232,216,.2);
  position:relative;width:100vw;margin-left:calc(50% - 50vw);min-height:100vh;padding:34px 0 90px;overflow-x:clip;font-family:"Inter","Poppins",-apple-system,sans-serif;color:var(--cream);
  background:radial-gradient(900px 520px at 88% -6%,rgba(76,175,80,.13),transparent 60%),radial-gradient(700px 400px at -5% 30%,rgba(217,162,75,.06),transparent 60%),linear-gradient(180deg,#071910,#0b2418 55%,#081b12)}
.ad-page *{box-sizing:border-box}
.ad-page button,.ad-page input{font-family:inherit}
.ad-page a{text-decoration:none}
@media(prefers-reduced-motion:reduce){.ad-page *{animation-duration:.001ms!important;transition-duration:.001ms!important}}
.ad-wrap{max-width:1160px;margin:0 auto;padding:0 24px}
@keyframes adIn{from{opacity:0;transform:translateY(10px)}}

/* header */
.ad-back{display:inline-flex;align-items:center;gap:8px;color:var(--sage);font-size:.84rem;font-weight:600;padding:6px 0;transition:color .15s ease,transform .15s ease}
.ad-back:hover{color:var(--gold2);transform:translateX(-3px)}
.ad-head{display:flex;align-items:flex-end;justify-content:space-between;gap:20px;flex-wrap:wrap;margin:10px 0 22px}
.ad-eyebrow{display:flex;align-items:center;gap:8px;color:var(--gold);font-size:.78rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase}
.ad-title{font-family:"Fraunces",Georgia,serif;font-weight:500;font-size:clamp(1.9rem,3.6vw,2.7rem);line-height:1.1;letter-spacing:-.02em;margin:10px 0 8px;color:var(--cream)}
.ad-sub{margin:0;color:var(--sage);max-width:520px;line-height:1.7;font-size:.95rem}
.ad-actions{display:flex;gap:10px;flex-wrap:wrap}
.ad-btn{display:inline-flex;align-items:center;justify-content:center;gap:9px;padding:11px 18px;border-radius:12px;border:1px solid transparent;font-weight:700;font-size:.85rem;cursor:pointer;transition:transform .15s ease,background .15s ease,border-color .15s ease,box-shadow .15s ease;white-space:nowrap}
.ad-btn:hover{transform:translateY(-2px)}
.ad-btn:focus-visible,.ad-page a:focus-visible,.ad-page button:focus-visible{outline:2px solid var(--gold2);outline-offset:2px}
.ad-btn-gold{background:var(--gold);color:#241708}.ad-btn-gold:hover{background:var(--gold2);color:#241708;box-shadow:0 8px 20px rgba(217,162,75,.25)}
.ad-btn-ghost{background:rgba(241,248,233,.05);border-color:var(--line2);color:var(--cream)}.ad-btn-ghost:hover{border-color:var(--gold);color:var(--gold2)}
.ad-btn-sm{padding:8px 14px;font-size:.78rem;background:rgba(241,248,233,.1);border-color:var(--line2);color:var(--cream)}.ad-btn-sm:hover{background:rgba(241,248,233,.18);color:var(--cream)}
.ad-btn-block{width:100%}

/* hero */
.ad-hero{display:grid;grid-template-columns:minmax(0,1fr) 230px;gap:26px;align-items:center;padding:26px;border-radius:24px;border:1px solid var(--line2);position:relative;overflow:hidden;
  background:linear-gradient(135deg,rgba(18,56,34,.78),rgba(9,31,21,.92));box-shadow:0 22px 50px rgba(0,0,0,.28);animation:adIn .4s ease both}
.ad-hero::before{content:"";position:absolute;right:-60px;top:-70px;width:260px;height:260px;border-radius:50%;background:radial-gradient(circle,rgba(139,209,154,.14),transparent 68%);pointer-events:none}
.ad-hero.has-photo{grid-template-columns:230px minmax(0,1fr) 210px}
.ad-photo{position:relative;border:1px solid var(--line2);padding:0;border-radius:18px;overflow:hidden;background:#061409;cursor:zoom-in;aspect-ratio:1;display:block;width:100%;transition:transform .2s ease,border-color .2s ease}
.ad-photo:hover{transform:scale(1.02);border-color:var(--gold)}
.ad-photo img{width:100%;height:100%;object-fit:cover;display:block}
.ad-photo-hint{position:absolute;left:8px;right:8px;bottom:8px;padding:6px 10px;border-radius:10px;background:rgba(7,25,16,.82);color:var(--cream);font-size:.72rem;font-weight:600;display:flex;align-items:center;justify-content:center;gap:7px;backdrop-filter:blur(6px)}
.ad-hero-main{min-width:0;position:relative}
.ad-tags{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:18px}
.ad-tag,.ad-status{display:inline-flex;align-items:center;gap:7px;padding:6px 12px;border-radius:99px;font-size:.74rem;font-weight:700;border:1px solid var(--line2);background:rgba(241,248,233,.05);color:var(--sage)}
.ad-tag i{color:var(--gold2)}
.ad-s-ok{color:#a9dfad;background:rgba(76,175,80,.12);border-color:rgba(129,199,132,.3)}
.ad-s-bad{color:var(--red);background:rgba(229,115,100,.12);border-color:rgba(240,163,154,.3)}
.ad-s-warn{color:var(--amber);background:rgba(217,162,75,.12);border-color:rgba(240,198,116,.32)}
.ad-s-neutral{color:var(--sage)}
.ad-kicker{font-size:.72rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--leaf)}
.ad-disease{font-family:"Fraunces",Georgia,serif;font-weight:500;font-size:clamp(1.7rem,3.2vw,2.5rem);line-height:1.12;letter-spacing:-.015em;margin:8px 0 12px;overflow-wrap:anywhere}
.ad-disease.is-none{color:var(--dim);font-size:1.5rem}
.ad-reason{margin:0;color:rgba(231,241,232,.8);line-height:1.8;font-size:.93rem;overflow-wrap:anywhere}
.ad-file{display:inline-flex;align-items:center;gap:8px;margin-top:14px;font-size:.76rem;color:var(--dim);overflow-wrap:anywhere}
.ad-conf{display:flex;flex-direction:column;align-items:center;text-align:center;padding:20px 14px;border-radius:20px;background:rgba(7,25,16,.5);border:1px solid var(--line);position:relative}
.ad-ring{position:relative;width:120px;height:120px}
.ad-ring svg{width:100%;height:100%;transform:rotate(-90deg)}
.ad-ring circle{fill:none;stroke-width:3.4}
.ad-ring .bg{stroke:rgba(241,248,233,.1)}
.ad-ring .fg{stroke:var(--leaf);stroke-linecap:round;stroke-dasharray:100;stroke-dashoffset:100;transition:stroke-dashoffset 1.2s cubic-bezier(.2,.8,.2,1)}
.ad-ring.mid .fg{stroke:var(--amber)}.ad-ring.lo .fg{stroke:#e8927f}
.ad-ring-mid{position:absolute;inset:0;display:grid;place-items:center}
.ad-ring-mid b{font-size:1.75rem;font-weight:800;line-height:1}.ad-ring-mid small{font-size:.9rem;font-weight:700;margin-left:1px;color:var(--sage)}
.ad-ring.none .ad-ring-mid b{color:var(--dim)}
.ad-conf-lv{margin-top:12px;font-weight:800;font-size:.9rem}
.ad-lv-hi{color:#a9dfad}.ad-lv-mid{color:var(--amber)}.ad-lv-lo{color:#f0a393}
.ad-conf-note{margin:6px 0 0;font-size:.76rem;line-height:1.55;color:var(--dim)}

/* banner */
.ad-banner{display:flex;align-items:center;gap:14px;margin-top:14px;padding:14px 18px;border-radius:16px;border:1px solid var(--line);font-size:.86rem;line-height:1.6;color:var(--sage);animation:adIn .4s .08s ease both}
.ad-banner>i{font-size:1.2rem;flex:none}
.ad-banner>div{flex:1;min-width:0}.ad-banner b{color:var(--cream)}
.ad-b-info{background:rgba(76,175,80,.07);border-color:rgba(139,209,154,.2)}.ad-b-info>i{color:var(--leaf)}
.ad-b-warn{background:rgba(217,162,75,.09);border-color:rgba(240,198,116,.3)}.ad-b-warn>i{color:var(--amber)}
.ad-b-bad{background:rgba(229,115,100,.09);border-color:rgba(240,163,154,.3)}.ad-b-bad>i{color:var(--red)}

/* nav */
.ad-nav{position:sticky;top:0;z-index:20;display:flex;gap:6px;overflow-x:auto;margin:20px -4px 20px;padding:10px 4px;scrollbar-width:none;
  background:linear-gradient(180deg,rgba(8,27,18,.96) 70%,rgba(8,27,18,0))}
.ad-nav::-webkit-scrollbar{display:none}
.ad-nav a{flex:none;display:inline-flex;align-items:center;gap:8px;padding:9px 15px;border-radius:99px;border:1px solid var(--line2);background:rgba(241,248,233,.04);color:var(--sage);font-size:.8rem;font-weight:600;transition:background .15s ease,color .15s ease,border-color .15s ease}
.ad-nav a:hover{color:var(--cream);border-color:var(--gold)}
.ad-nav a.on{background:var(--gold);border-color:var(--gold);color:#241708}
.ad-nav a.on i{color:#241708}.ad-nav a i{color:var(--gold2);font-size:.78rem}

/* layout + cards */
.ad-layout{display:grid;grid-template-columns:minmax(0,1fr) 340px;gap:20px;align-items:start}
.ad-main,.ad-side{display:grid;gap:18px;min-width:0}
.ad-card{padding:22px;border-radius:20px;border:1px solid var(--line);background:linear-gradient(135deg,rgba(18,56,34,.55),rgba(9,31,21,.85));scroll-margin-top:76px;animation:adIn .4s ease both}
.ad-card-gold{border-color:rgba(217,162,75,.28);background:linear-gradient(135deg,rgba(217,162,75,.09),rgba(9,31,21,.88))}
.ad-card-quiet{padding:16px 18px;background:rgba(241,248,233,.03)}
.ad-card-h{display:flex;align-items:center;gap:14px;margin-bottom:18px;flex-wrap:wrap}
.ad-ic{width:44px;height:44px;border-radius:14px;display:grid;place-items:center;flex:none;color:var(--gold2);background:rgba(217,162,75,.1);border:1px solid rgba(217,162,75,.18)}
.ad-ic.gold{background:rgba(217,162,75,.18)}
.ad-card-t{flex:1;min-width:0}
.ad-card-t h3{margin:0;font-family:"Fraunces",Georgia,serif;font-weight:500;font-size:1.25rem;color:var(--cream)}
.ad-card-t p{margin:3px 0 0;font-size:.78rem;color:var(--dim)}
.ad-empty{display:flex;align-items:center;gap:10px;margin:0;padding:14px 16px;border-radius:12px;border:1px dashed var(--line2);color:var(--dim);font-size:.84rem}
.ad-note{display:flex;gap:9px;align-items:flex-start;margin:14px 0 0;font-size:.76rem;line-height:1.55;color:var(--dim)}.ad-note i{margin-top:3px;color:var(--gold)}
.ad-sub-h{display:flex;align-items:center;gap:9px;margin:20px 0 12px;font-size:.78rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--sage)}.ad-sub-h i{color:var(--gold2)}

/* symptoms */
.ad-filter{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:14px}
.ad-filter button{border:1px solid var(--line2);background:rgba(241,248,233,.04);color:var(--sage);font-size:.78rem;font-weight:600;padding:7px 13px;border-radius:99px;cursor:pointer;transition:background .15s ease,color .15s ease,border-color .15s ease}
.ad-filter button b{margin-left:5px;color:var(--gold2)}.ad-filter button:hover{border-color:var(--gold);color:var(--cream)}
.ad-filter button.on{background:var(--gold);border-color:var(--gold);color:#241708}.ad-filter button.on b{color:#241708}
.ad-chips{display:flex;flex-wrap:wrap;gap:10px}
.ad-sym{display:flex;align-items:center;gap:11px;padding:10px 14px;border-radius:13px;border:1px solid rgba(139,209,154,.2);background:rgba(76,175,80,.08);transition:transform .15s ease,border-color .15s ease;min-width:0}
.ad-sym:hover{transform:translateY(-2px);border-color:rgba(139,209,154,.45)}
.ad-sym[hidden]{display:none}
.ad-sym>i{color:var(--leaf);font-size:.95rem}
.ad-sym span{display:block;font-size:.86rem;font-weight:600;overflow-wrap:anywhere}
.ad-sym small{display:block;margin-top:1px;font-size:.68rem;color:var(--dim)}
.ad-sym[data-src="ai"]>i{color:var(--gold2)}.ad-sym[data-src="ai"]{border-color:rgba(217,162,75,.25);background:rgba(217,162,75,.07)}
.ad-pill{display:inline-flex;align-items:center;gap:8px;padding:8px 13px;border-radius:99px;border:1px solid var(--line2);background:rgba(241,248,233,.04);font-size:.82rem;font-weight:600;color:var(--cream);overflow-wrap:anywhere}.ad-pill i{color:var(--gold2);font-size:.75rem}

/* possible diseases */
.ad-poss{display:grid;gap:10px}
.ad-poss-row{display:grid;grid-template-columns:minmax(0,1.2fr) minmax(0,1fr) 46px;align-items:center;gap:14px;padding:12px 16px;border-radius:14px;border:1px solid var(--line);background:rgba(241,248,233,.03)}
.ad-poss-row:not(:has(.ad-poss-bar)){grid-template-columns:1fr}
.ad-poss-row.top{border-color:var(--gold);background:linear-gradient(135deg,rgba(217,162,75,.13),rgba(241,248,233,.03))}
.ad-poss-name{font-weight:600;font-size:.9rem;display:flex;align-items:center;gap:10px;flex-wrap:wrap;overflow-wrap:anywhere}.ad-poss-name i{color:var(--leaf)}
.ad-poss-name em{font-style:normal;font-size:.65rem;font-weight:800;letter-spacing:.06em;text-transform:uppercase;color:#241708;background:var(--gold);padding:3px 8px;border-radius:99px}
.ad-poss-bar{height:8px;border-radius:9px;background:rgba(241,248,233,.1);overflow:hidden}.ad-poss-bar i{display:block;height:100%;border-radius:9px;background:var(--leaf)}
.ad-poss-row.top .ad-poss-bar i{background:var(--gold)}
.ad-poss-pct{font-weight:800;font-size:.85rem;text-align:right}

/* chat */
.ad-chat{display:grid;gap:10px}
.ad-q{display:flex;gap:10px;align-items:flex-start;max-width:86%}
.ad-q>i{width:32px;height:32px;border-radius:50%;display:grid;place-items:center;flex:none;background:rgba(139,209,154,.14);color:var(--leaf);font-size:.8rem}
.ad-q>div{padding:11px 15px;border-radius:4px 16px 16px 16px;background:rgba(241,248,233,.06);border:1px solid var(--line);font-size:.86rem;line-height:1.6;overflow-wrap:anywhere}
.ad-a{display:flex;justify-content:flex-end;margin-bottom:6px}
.ad-a>div{padding:9px 18px;border-radius:16px 4px 16px 16px;font-size:.85rem;font-weight:700;max-width:80%;overflow-wrap:anywhere;background:var(--gold);color:#241708}
.ad-a-yes>div{background:rgba(76,175,80,.22);color:#b9e6bd;border:1px solid rgba(139,209,154,.35)}
.ad-a-no>div{background:rgba(229,115,100,.16);color:#f3b5ab;border:1px solid rgba(240,163,154,.3)}

/* text + lists */
.ad-text{color:rgba(231,241,232,.82);font-size:.92rem;line-height:1.9;overflow-wrap:anywhere;white-space:pre-line}
.ad-clamp.is-clamped{display:-webkit-box;-webkit-line-clamp:6;-webkit-box-orient:vertical;overflow:hidden}
.ad-more{margin-top:10px;border:0;background:none;color:var(--gold2);font-weight:700;font-size:.82rem;cursor:pointer;padding:4px 0}.ad-more:hover{text-decoration:underline}
.ad-points{list-style:none;margin:0;padding:0;display:grid;gap:10px}
.ad-points li{display:flex;gap:12px;align-items:flex-start;padding:12px 14px;border-radius:13px;background:rgba(241,248,233,.04);border:1px solid var(--line);font-size:.88rem;line-height:1.65;overflow-wrap:anywhere}
.ad-points li i{margin-top:5px;font-size:.62rem;color:var(--leaf);flex:none}
.ad-points.gold li{background:rgba(7,25,16,.45)}.ad-points.gold li i{color:var(--gold2);font-size:.8rem;margin-top:3px}

/* prevention checklist */
.ad-progress{display:flex;align-items:center;gap:12px;font-size:.78rem;font-weight:700;color:var(--sage)}
.ad-progress div{width:110px;height:8px;border-radius:9px;background:rgba(241,248,233,.1);overflow:hidden}
.ad-progress i{display:block;height:100%;width:0;border-radius:9px;background:var(--leaf);transition:width .3s ease}
.ad-progress.all span{color:#a9dfad}
.ad-steps{display:grid;gap:10px}
.ad-step{display:flex;gap:14px;align-items:flex-start;padding:14px 16px;border-radius:14px;border:1px solid var(--line);background:rgba(241,248,233,.03);cursor:pointer;transition:border-color .15s ease,background .15s ease,transform .15s ease;position:relative}
.ad-step:hover{border-color:rgba(217,162,75,.5);transform:translateX(3px)}
.ad-step input{position:absolute;opacity:0;pointer-events:none}
.ad-box{width:26px;height:26px;border-radius:9px;border:2px solid rgba(213,232,216,.35);display:grid;place-items:center;flex:none;color:transparent;font-size:.72rem;transition:all .18s ease;margin-top:1px}
.ad-step-tx{font-size:.9rem;line-height:1.65;overflow-wrap:anywhere;transition:color .15s ease}
.ad-step input:focus-visible+.ad-box{outline:2px solid var(--gold2);outline-offset:2px}
.ad-step input:checked+.ad-box{background:var(--leaf);border-color:var(--leaf);color:#0a1d15;transform:scale(1.08)}
.ad-step:has(input:checked){border-color:rgba(139,209,154,.35);background:rgba(76,175,80,.07)}
.ad-step input:checked~.ad-step-tx{color:var(--dim);text-decoration:line-through;text-decoration-color:rgba(159,190,166,.5)}

/* side */
.ad-links{display:grid;gap:10px;margin-top:18px}
.ad-facts{display:grid;gap:10px}
.ad-fact{display:flex;align-items:center;gap:14px;padding:13px 15px;border-radius:14px;border:1px solid var(--line);background:rgba(241,248,233,.04)}
.ad-fact>i{width:36px;height:36px;border-radius:11px;display:grid;place-items:center;color:var(--gold2);background:rgba(217,162,75,.1);flex:none;font-size:.85rem}
.ad-fact small{display:block;font-size:.72rem;color:var(--dim)}.ad-fact b{display:block;margin-top:2px;font-size:.92rem;overflow-wrap:anywhere}
.ad-fact.na b{color:var(--dim);font-weight:500}.ad-fact.na>i{color:var(--dim);background:rgba(241,248,233,.06)}
.ad-link{display:inline-flex;align-items:center;gap:8px;margin-top:14px;color:var(--gold2);font-weight:700;font-size:.82rem}.ad-link:hover{text-decoration:underline;color:var(--gold2)}
.ad-rec{display:grid;grid-template-columns:1fr 1fr;gap:12px 16px}
.ad-rec small{display:block;font-size:.68rem;color:var(--dim);text-transform:uppercase;letter-spacing:.08em}.ad-rec b{display:block;margin-top:2px;font-size:.84rem;color:var(--sage)}

.ad-foot{display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-top:26px;padding-top:22px;border-top:1px solid var(--line)}

/* lightbox */
.ad-lb{position:fixed;inset:0;z-index:9999;background:rgba(3,10,6,.92);backdrop-filter:blur(6px);display:grid;place-items:center;padding:30px;overflow:auto;animation:adIn .2s ease both}
.ad-lb[hidden]{display:none}
.ad-lb img{max-width:100%;max-height:calc(100vh - 100px);border-radius:14px;cursor:zoom-in;transition:transform .25s ease;box-shadow:0 30px 70px rgba(0,0,0,.5)}
.ad-lb img.zoom{transform:scale(1.9);cursor:zoom-out}
.ad-lb-x{position:fixed;top:18px;right:18px;width:44px;height:44px;border-radius:50%;border:1px solid var(--line2);background:rgba(241,248,233,.1);color:var(--cream);font-size:1.1rem;cursor:pointer;z-index:2}
.ad-lb-x:hover{background:var(--gold);color:#241708}
.ad-lb-hint{position:fixed;left:0;right:0;bottom:16px;text-align:center;color:var(--sage);font-size:.78rem}

/* toast */
.ad-toast{position:fixed;left:50%;bottom:26px;transform:translate(-50%,24px);opacity:0;pointer-events:none;z-index:10000;padding:12px 20px;border-radius:14px;background:#123822;border:1px solid rgba(139,209,154,.4);color:var(--cream);font-size:.86rem;font-weight:600;box-shadow:0 14px 34px rgba(0,0,0,.4);transition:opacity .25s ease,transform .25s ease}
.ad-toast.show{opacity:1;transform:translate(-50%,0)}

/* responsive */
@media(max-width:1020px){
  .ad-hero.has-photo{grid-template-columns:200px minmax(0,1fr)}
  .ad-hero.has-photo .ad-conf{grid-column:1/-1;flex-direction:row;text-align:left;gap:20px;justify-content:center}
  .ad-hero.has-photo .ad-ring{width:96px;height:96px;flex:none}
  .ad-layout{grid-template-columns:1fr}
}
@media(max-width:760px){
  .ad-hero,.ad-hero.has-photo{grid-template-columns:1fr;padding:20px}
  .ad-photo{aspect-ratio:16/10}
  .ad-conf,.ad-hero.has-photo .ad-conf{flex-direction:row;text-align:left;gap:18px;justify-content:flex-start;padding:16px}
  .ad-ring,.ad-hero.has-photo .ad-ring{width:84px;height:84px;flex:none}
  .ad-ring-mid b{font-size:1.3rem}
  .ad-conf-lv{margin-top:0}
  .ad-poss-row{grid-template-columns:1fr 46px}.ad-poss-row .ad-poss-name{grid-column:1/-1}.ad-poss-row .ad-poss-bar{grid-column:1}
  .ad-q,.ad-a>div{max-width:96%}
  .ad-progress{width:100%}
}
@media(max-width:520px){
  .ad-wrap{padding:0 16px}
  .ad-actions{width:100%}.ad-actions .ad-btn{flex:1;padding:11px 10px}
  .ad-banner{flex-wrap:wrap}.ad-banner .ad-btn{width:100%}
  .ad-foot .ad-btn{width:100%}
  .ad-card{padding:18px}
}

/* print: a clean light report */
@media print{
  .ad-page{width:auto;margin:0;background:#fff!important;color:#111;padding:0}
  .ad-actions,.ad-nav,.ad-back,.ad-foot,.ad-links,.ad-banner .ad-btn,.ad-filter,.ad-more,.ad-photo-hint,.ad-toast{display:none!important}
  .ad-hero,.ad-card,.ad-card-gold,.ad-banner,.ad-conf,.ad-poss-row,.ad-points li,.ad-step,.ad-fact,.ad-sym{background:#fff!important;border-color:#ccc!important;box-shadow:none!important;color:#111!important;animation:none!important}
  .ad-title,.ad-disease,.ad-card-t h3,.ad-reason,.ad-text,.ad-points li,.ad-step-tx,.ad-fact b,.ad-q>div,.ad-ring-mid b{color:#111!important}
  .ad-card-t p,.ad-sub,.ad-kicker,.ad-fact small,.ad-conf-note,.ad-note,.ad-file{color:#555!important}
  .ad-ring .fg{stroke-dashoffset:calc(100 - var(--v,0))!important}
  .ad-clamp.is-clamped{display:block;-webkit-line-clamp:unset;overflow:visible}
  .ad-layout{grid-template-columns:1fr}
  .ad-card{break-inside:avoid}
}
</style>

<script>
(function () {
    var AID = @json($aid);
    var SUMMARY = @json($summary);
    var $ = function (s, r) { return (r || document).querySelector(s); };
    var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };

    /* ----- toast ----- */
    var toastEl = $('#adToast'), toastT;
    function toast(msg) {
        toastEl.textContent = msg; toastEl.classList.add('show');
        clearTimeout(toastT); toastT = setTimeout(function () { toastEl.classList.remove('show'); }, 2200);
    }

    /* ----- confidence ring animates in ----- */
    var ring = $('#adRing');
    if (ring) {
        var v = parseFloat(ring.getAttribute('data-v')) || 0;
        ring.parentNode.parentNode.style.setProperty('--v', v);
        requestAnimationFrame(function () { setTimeout(function () { ring.style.strokeDashoffset = String(100 - v); }, 120); });
    }

    /* ----- print + copy ----- */
    $('#adPrint').addEventListener('click', function () { window.print(); });

    function buildText() {
        var L = [SUMMARY.title, ''];
        if (SUMMARY.disease) L.push('Disease: ' + SUMMARY.disease + (SUMMARY.confidence ? ' (' + SUMMARY.confidence + ' confidence)' : ''));
        if (SUMMARY.symptoms.length) L.push('Symptoms: ' + SUMMARY.symptoms.join(', '));
        function block(h, a) { if (a.length) { L.push('', h + ':'); a.forEach(function (x) { L.push('- ' + x); }); } }
        block('Causes', SUMMARY.causes); block('Prevention', SUMMARY.prevention); block('Fertilizer', SUMMARY.fertilizer);
        return L.join('\n');
    }
    function fallbackCopy(t) {
        var ta = document.createElement('textarea'); ta.value = t; ta.style.position = 'fixed'; ta.style.opacity = '0';
        document.body.appendChild(ta); ta.select();
        var ok = false; try { ok = document.execCommand('copy'); } catch (e) {}
        document.body.removeChild(ta); return ok;
    }
    $('#adCopy').addEventListener('click', function () {
        var t = buildText();
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(t).then(function () { toast('Summary copied'); },
                function () { toast(fallbackCopy(t) ? 'Summary copied' : 'Could not copy'); });
        } else { toast(fallbackCopy(t) ? 'Summary copied' : 'Could not copy'); }
    });

    /* ----- symptom filter ----- */
    var fbtns = $$('.ad-filter button');
    fbtns.forEach(function (b) {
        b.addEventListener('click', function () {
            var f = b.getAttribute('data-f');
            fbtns.forEach(function (x) { x.classList.toggle('on', x === b); });
            $$('#adSymList .ad-sym').forEach(function (s) { s.hidden = !(f === 'all' || s.getAttribute('data-src') === f); });
        });
    });

    /* ----- read more on long paragraphs ----- */
    $$('[data-clamp]').forEach(function (el) {
        if (el.scrollHeight <= 0) return;
        el.classList.add('is-clamped');
        if (el.scrollHeight - el.clientHeight < 8) { el.classList.remove('is-clamped'); return; }
        var b = document.createElement('button'); b.type = 'button'; b.className = 'ad-more'; b.textContent = 'Read more';
        b.addEventListener('click', function () {
            var c = el.classList.toggle('is-clamped'); b.textContent = c ? 'Read more' : 'Show less';
        });
        el.parentNode.insertBefore(b, el.nextSibling);
    });

    /* ----- prevention checklist (remembered per analysis, this device only) ----- */
    var steps = $$('#adSteps input'), prog = $('#adProg');
    if (steps.length) {
        var KEY = 'cardofer-ad-steps-' + AID, saved = [];
        try { saved = JSON.parse(localStorage.getItem(KEY) || '[]') || []; } catch (e) {}
        function paint() {
            var n = steps.filter(function (s) { return s.checked; }).length;
            $('#adProgTx').textContent = n + ' of ' + steps.length;
            $('#adProgBar').style.width = (n / steps.length * 100) + '%';
            prog.classList.toggle('all', n === steps.length);
            return n;
        }
        steps.forEach(function (s) { s.checked = saved.indexOf(parseInt(s.getAttribute('data-i'), 10)) > -1; });
        paint();
        steps.forEach(function (s) {
            s.addEventListener('change', function () {
                var n = paint();
                try { localStorage.setItem(KEY, JSON.stringify(steps.filter(function (x) { return x.checked; }).map(function (x) { return parseInt(x.getAttribute('data-i'), 10); }))); } catch (e) {}
                if (n === steps.length) toast('All steps done. Well done!');
            });
        });
    }

    /* ----- photo viewer ----- */
    var lb = $('#adLb'), photo = $('#adPhoto');
    if (lb && photo) {
        var lbImg = $('#adLbImg');
        function openLb() { lb.hidden = false; document.body.style.overflow = 'hidden'; $('#adLbX').focus(); }
        function closeLb() { lb.hidden = true; lbImg.classList.remove('zoom'); document.body.style.overflow = ''; photo.focus(); }
        photo.addEventListener('click', openLb);
        $('#adLbX').addEventListener('click', closeLb);
        lbImg.addEventListener('click', function (e) { e.stopPropagation(); lbImg.classList.toggle('zoom'); });
        lb.addEventListener('click', function (e) { if (e.target === lb) closeLb(); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !lb.hidden) closeLb(); });
    }

    /* ----- section nav: highlight the section being read ----- */
    var links = $$('#adNav a');
    var map = {}; links.forEach(function (a) { map[a.getAttribute('data-target')] = a; });
    function mark(id) { links.forEach(function (a) { a.classList.toggle('on', a === map[id]); }); }
    links.forEach(function (a) {
        a.addEventListener('click', function (e) {
            var t = document.getElementById(a.getAttribute('data-target'));
            if (!t) return;
            e.preventDefault();
            t.scrollIntoView({ behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth', block: 'start' });
            mark(a.getAttribute('data-target'));
        });
    });
    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) { if (en.isIntersecting) mark(en.target.id); });
        }, { rootMargin: '-90px 0px -60% 0px' });
        links.forEach(function (a) { var t = document.getElementById(a.getAttribute('data-target')); if (t) io.observe(t); });
    }
})();
</script>
@endsection