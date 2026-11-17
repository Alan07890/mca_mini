@extends('layouts.FarmerMaster')

@section('title', 'Analysis History — CARDO-FER')

@section('content')
@php
    /* ================================================================
       Prepare every row once. Variable names ($analyses, ->symptoms,
       ->visible_symptoms ...) are the same as your old page.
       ================================================================ */
    $R = \Illuminate\Support\Facades\Route::class;

    /* link to the Analysis Details page: the first route name that exists is used */
    $detailsRoute = null;
    foreach ([
        'farmer.analysis-history.show', 'farmer.analysis-history.details', 'farmer.analysis-history.view',
        'farmer.analysis-details', 'farmer.analysis.show', 'farmer.analysis.details', 'farmer.history.show',
    ] as $name) {
        if ($R::has($name)) { $detailsRoute = $name; break; }
    }

    $typeMap = [
        'symptoms'           => ['Symptoms', 'fa-stethoscope'],
        'image'              => ['Photo', 'fa-camera'],
        'image_and_symptoms' => ['Photo + symptoms', 'fa-images'],
    ];

    $rows = [];
    $diseaseTally = []; $confSum = 0; $confN = 0;
    $cnt = ['all' => 0, 'completed' => 0, 'pending' => 0, 'failed' => 0];
    $typeCnt = [];

    foreach ($analyses as $a) {
        $dateRaw = ($a->analyzed_at ?? null) ?: ($a->created_at ?? null);
        $d = $dateRaw ? \Carbon\Carbon::parse($dateRaw) : null;

        $type = (string) ($a->analysis_type ?? '');
        $typeInfo = $typeMap[$type] ?? [ucwords(str_replace('_', ' ', $type)) ?: 'Analysis', 'fa-microscope'];

        $sv = strtolower((string) ($a->status ?? 'completed'));
        $bucket = $sv === 'failed' ? 'failed' : (in_array($sv, ['needs_confirmation', 'pending'], true) ? 'pending' : 'completed');
        $statusLabel = ucwords(str_replace('_', ' ', $sv));

        $conf = null;
        if (is_numeric($a->ai_confidence_score ?? null)) { $conf = (float) $a->ai_confidence_score; }
        elseif (is_numeric($a->ai_confidence ?? null))   { $conf = (float) $a->ai_confidence; }
        if ($conf !== null && $conf >= 0 && $conf <= 1) { $conf *= 100; }
        if ($conf !== null) { $conf = max(0, min(100, $conf)); $confSum += $conf; $confN++; }
        $confClass = $conf === null ? 'none' : ($conf >= 75 ? 'hi' : ($conf >= 50 ? 'mid' : 'lo'));

        /* symptoms: saved relation first, otherwise the image snapshot */
        $names = [];
        foreach (($a->symptoms ?? []) as $s) { if (!empty($s->symptom_name)) { $names[] = $s->symptom_name; } }
        if (!$names) {
            $vs = $a->visible_symptoms ?? [];
            if (is_string($vs)) { $vs = json_decode($vs, true) ?? []; }
            if (is_array($vs)) {
                foreach ($vs as $v) {
                    $n = is_array($v) ? ($v['symptom_name'] ?? $v['name'] ?? $v['symptom'] ?? null) : $v;
                    if (is_string($n) && trim($n) !== '') { $names[] = trim($n); }
                }
            }
        }

        $disease = ($a->disease_name ?? null) ?: null;
        if ($disease) { $diseaseTally[$disease] = ($diseaseTally[$disease] ?? 0) + 1; }

        $url = null;
        if ($detailsRoute && !empty($a->id)) {
            try { $url = route($detailsRoute, $a->id); } catch (\Throwable $e) { $url = null; }
        }

        $cnt['all']++; $cnt[$bucket]++;
        $typeCnt[$type] = ($typeCnt[$type] ?? 0) + 1;

        $rows[] = [
            'id'      => $a->id ?? 0,
            'url'     => $url,
            'disease' => $disease,
            'day'     => $d ? $d->format('d') : '—',
            'mon'     => $d ? $d->format('M') : '',
            'year'    => $d ? $d->format('Y') : '',
            'time'    => $d ? $d->format('h:i A') : '',
            'ts'      => $d ? (int) $d->format('U') : 0,
            'type'    => $type,
            'typeLbl' => $typeInfo[0],
            'typeIc'  => $typeInfo[1],
            'bucket'  => $bucket,
            'status'  => $statusLabel,
            'conf'    => $conf,
            'confCls' => $confClass,
            'syms'    => array_slice($names, 0, 4),
            'more'    => max(0, count($names) - 4),
            'search'  => strtolower(($disease ?? '') . ' ' . implode(' ', $names) . ' ' . $typeInfo[0]),
        ];
    }

    arsort($diseaseTally);
    $topDisease = $diseaseTally ? array_key_first($diseaseTally) : null;
    $avgConf = $confN ? $confSum / $confN : null;
    $total = $analyses->total();
    $paged = $analyses->hasPages();
    $scope = $paged ? 'on this page' : 'overall';
    $top3 = array_slice($diseaseTally, 0, 4, true);
@endphp

<div class="ah-page">
<div class="ah-wrap">

    {{-- ===== Header ===== --}}
    <header class="ah-head">
        <div>
            <div class="ah-eyebrow"><i class="fas fa-clock-rotate-left"></i> Farmer records</div>
            <h1 class="ah-title">Analysis History</h1>
            <p class="ah-sub">Every cardamom disease check you have run, with the result and confidence. Open any record for the full report and care steps.</p>
        </div>
        <a href="{{ route('disease.identification') }}" class="ah-btn ah-btn-gold">
            <i class="fas fa-microscope"></i> New analysis
        </a>
    </header>

    @if ($rows)

        {{-- ===== Stats ===== --}}
        <section class="ah-stats" aria-label="Summary">
            <div class="ah-stat">
                <div class="ah-stat-ic"><i class="fas fa-folder-open"></i></div>
                <div><b>{{ number_format($total) }}</b><span>Saved {{ $total === 1 ? 'analysis' : 'analyses' }}</span></div>
            </div>
            <div class="ah-stat">
                <div class="ah-stat-ic"><i class="fas fa-circle-check"></i></div>
                <div><b>{{ $cnt['completed'] }}</b><span>Completed {{ $scope }}</span></div>
            </div>
            <div class="ah-stat">
                <div class="ah-stat-ic"><i class="fas fa-gauge-high"></i></div>
                <div>
                    <b>{{ $avgConf !== null ? number_format($avgConf, 0) . '%' : '—' }}</b>
                    <span>Average confidence {{ $scope }}</span>
                </div>
            </div>
            <div class="ah-stat">
                <div class="ah-stat-ic"><i class="fas fa-bug"></i></div>
                <div>
                    <b class="ah-stat-txt">{{ $topDisease ?? '—' }}</b>
                    <span>Most frequent {{ $scope }}</span>
                </div>
            </div>
        </section>

        {{-- ===== Toolbar ===== --}}
        <section class="ah-tools" aria-label="Find a record">
            <div class="ah-search">
                <i class="fas fa-magnifying-glass"></i>
                <input type="search" id="ahSearch" placeholder="Search by disease, symptom or type" autocomplete="off" aria-label="Search records">
                <button type="button" id="ahClear" aria-label="Clear search" hidden><i class="fas fa-xmark"></i></button>
            </div>

            <div class="ah-seg" role="group" aria-label="Filter by status" id="ahStatus">
                <button type="button" class="on" data-f="all">All <b>{{ $cnt['all'] }}</b></button>
                <button type="button" data-f="completed">Completed <b>{{ $cnt['completed'] }}</b></button>
                <button type="button" data-f="pending">Needs confirmation <b>{{ $cnt['pending'] }}</b></button>
                <button type="button" data-f="failed">Failed <b>{{ $cnt['failed'] }}</b></button>
            </div>

            <label class="ah-sort">
                <i class="fas fa-arrow-down-wide-short"></i>
                <select id="ahSort" aria-label="Sort records">
                    <option value="new">Newest first</option>
                    <option value="old">Oldest first</option>
                    <option value="conf">Highest confidence</option>
                </select>
            </label>
        </section>

        @if (count($top3) > 1 || count($typeCnt) > 1)
            <div class="ah-quick" aria-label="Quick filters">
                @foreach ($top3 as $dn => $dc)
                    <button type="button" class="ah-q" data-q="{{ strtolower($dn) }}"><i class="fas fa-leaf"></i> {{ $dn }} <b>{{ $dc }}</b></button>
                @endforeach
                @if (count($typeCnt) > 1)
                    @foreach ($typeCnt as $tk => $tc)
                        <button type="button" class="ah-q ah-q-type" data-q="{{ strtolower($typeMap[$tk][0] ?? $tk) }}"><i class="fas {{ $typeMap[$tk][1] ?? 'fa-microscope' }}"></i> {{ $typeMap[$tk][0] ?? ucwords(str_replace('_', ' ', $tk)) }} <b>{{ $tc }}</b></button>
                    @endforeach
                @endif
            </div>
        @endif

        <div class="ah-meta" id="ahMeta" aria-live="polite">Showing {{ count($rows) }} of {{ count($rows) }}</div>

        {{-- ===== Records ===== --}}
        <section class="ah-list" id="ahList">
            @foreach ($rows as $r)
                @if ($r['url'])
                    <a href="{{ $r['url'] }}" class="ah-row ah-clickable"
                @else
                    <div class="ah-row"
                @endif
                     data-status="{{ $r['bucket'] }}" data-ts="{{ $r['ts'] }}" data-conf="{{ $r['conf'] ?? -1 }}"
                     data-search="{{ $r['search'] }}">

                    <div class="ah-date" aria-label="{{ $r['day'] }} {{ $r['mon'] }} {{ $r['year'] }}">
                        <b>{{ $r['day'] }}</b><span>{{ $r['mon'] }}</span><small>{{ $r['year'] }}</small>
                    </div>

                    <div class="ah-main">
                        <div class="ah-main-top">
                            <h3 class="ah-disease {{ $r['disease'] ? '' : 'is-none' }}">{{ $r['disease'] ?: 'Not identified' }}</h3>
                            <span class="ah-pill ah-st-{{ $r['bucket'] }}">{{ $r['status'] }}</span>
                        </div>
                        <div class="ah-info">
                            <span><i class="fas {{ $r['typeIc'] }}"></i> {{ $r['typeLbl'] }}</span>
                            @if ($r['time'] !== '') <span><i class="far fa-clock"></i> {{ $r['time'] }}</span> @endif
                        </div>
                        @if ($r['syms'])
                            <div class="ah-syms">
                                @foreach ($r['syms'] as $sn)
                                    <span class="ah-sym">{{ $sn }}</span>
                                @endforeach
                                @if ($r['more'] > 0) <span class="ah-sym ah-sym-more">+{{ $r['more'] }} more</span> @endif
                            </div>
                        @else
                            <div class="ah-syms"><span class="ah-nosym">No symptom snapshot saved</span></div>
                        @endif
                    </div>

                    <div class="ah-conf ah-c-{{ $r['confCls'] }}">
                        @if ($r['conf'] !== null)
                            <div class="ah-conf-n">{{ number_format($r['conf'], 0) }}<small>%</small></div>
                            <div class="ah-conf-bar"><i data-w="{{ round($r['conf']) }}"></i></div>
                            <div class="ah-conf-l">{{ $r['conf'] >= 75 ? 'High' : ($r['conf'] >= 50 ? 'Medium' : 'Low') }}</div>
                        @else
                            <div class="ah-conf-n">—</div>
                            <div class="ah-conf-l">No score</div>
                        @endif
                    </div>

                    @if ($r['url'])
                        <div class="ah-go"><span>View report</span><i class="fas fa-arrow-right"></i></div>
                    @endif

                @if ($r['url'])
                    </a>
                @else
                    </div>
                @endif
            @endforeach
        </section>

        <div class="ah-nomatch" id="ahNone" hidden>
            <i class="fas fa-magnifying-glass"></i>
            <b>No records match</b>
            <span>Try a different word or remove a filter.</span>
            <button type="button" id="ahReset">Clear all filters</button>
        </div>

        {{-- ===== Pagination ===== --}}
        @if ($paged)
            <nav class="ah-pager" aria-label="Pagination">
                <span>Showing {{ $analyses->firstItem() }}–{{ $analyses->lastItem() }} of {{ $analyses->total() }} · Page {{ $analyses->currentPage() }} of {{ $analyses->lastPage() }}</span>
                <div class="ah-pager-links">
                    <a class="ah-pg {{ $analyses->onFirstPage() ? 'disabled' : '' }}" href="{{ $analyses->previousPageUrl() ?: '#' }}" @if ($analyses->onFirstPage()) aria-disabled="true" tabindex="-1" @endif><i class="fas fa-chevron-left"></i> Previous</a>
                    <a class="ah-pg {{ $analyses->hasMorePages() ? '' : 'disabled' }}" href="{{ $analyses->nextPageUrl() ?: '#' }}" @if (!$analyses->hasMorePages()) aria-disabled="true" tabindex="-1" @endif>Next <i class="fas fa-chevron-right"></i></a>
                </div>
            </nav>
            <p class="ah-hint"><i class="fas fa-circle-info"></i> Search, filters and sorting work on the records of this page.</p>
        @endif

    @else

        {{-- ===== Empty ===== --}}
        <section class="ah-empty">
            <div class="ah-empty-ic"><i class="fas fa-leaf"></i></div>
            <h3>No disease analyses yet</h3>
            <p>Run your first check with a photo of the plant or a few symptoms. The result and care steps will be saved here.</p>
            <div class="ah-empty-steps">
                <span><i class="fas fa-camera"></i> Upload a photo</span>
                <i class="fas fa-chevron-right"></i>
                <span><i class="fas fa-wand-magic-sparkles"></i> Get the result</span>
                <i class="fas fa-chevron-right"></i>
                <span><i class="fas fa-folder-open"></i> Saved here</span>
            </div>
            <a href="{{ route('disease.identification') }}" class="ah-btn ah-btn-gold"><i class="fas fa-microscope"></i> Identify a disease</a>
        </section>

    @endif
</div>
</div>

<style>
.ah-page{--bg:#07190f;--card:#0e271b;--cream:#f1f8e9;--sage:#9fbea6;--dim:#7d9884;--gold:#d9a24b;--gold2:#f0c674;--leaf:#8bd19a;--line:rgba(213,232,216,.12);--line2:rgba(213,232,216,.2);
  position:relative;width:100vw;margin-left:calc(50% - 50vw);min-height:100vh;padding:44px 0 90px;overflow-x:clip;font-family:"Inter","Poppins",-apple-system,sans-serif;color:var(--cream);
  background:radial-gradient(900px 520px at 88% -6%,rgba(76,175,80,.13),transparent 60%),radial-gradient(700px 400px at -5% 35%,rgba(217,162,75,.06),transparent 60%),linear-gradient(180deg,#071910,#0b2418 55%,#081b12)}
.ah-page *{box-sizing:border-box}
.ah-page button,.ah-page input,.ah-page select{font-family:inherit}
.ah-page a{text-decoration:none}
@media(prefers-reduced-motion:reduce){.ah-page *{animation-duration:.001ms!important;transition-duration:.001ms!important}}
.ah-wrap{max-width:1160px;margin:0 auto;padding:0 24px}
@keyframes ahIn{from{opacity:0;transform:translateY(10px)}}

.ah-head{display:flex;align-items:flex-end;justify-content:space-between;gap:20px;flex-wrap:wrap;margin-bottom:26px}
.ah-eyebrow{display:flex;align-items:center;gap:8px;color:var(--gold);font-size:.78rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase}
.ah-title{font-family:"Fraunces",Georgia,serif;font-weight:500;font-size:clamp(1.9rem,3.6vw,2.8rem);line-height:1.1;letter-spacing:-.02em;margin:10px 0 8px;color:var(--cream)}
.ah-sub{margin:0;color:var(--sage);max-width:560px;line-height:1.7;font-size:.95rem}
.ah-btn{display:inline-flex;align-items:center;justify-content:center;gap:9px;padding:12px 20px;border-radius:12px;border:1px solid transparent;font-weight:700;font-size:.86rem;cursor:pointer;transition:transform .15s ease,background .15s ease,box-shadow .15s ease;white-space:nowrap}
.ah-btn:hover{transform:translateY(-2px)}
.ah-btn-gold{background:var(--gold);color:#241708}.ah-btn-gold:hover{background:var(--gold2);color:#241708;box-shadow:0 8px 20px rgba(217,162,75,.25)}
.ah-page a:focus-visible,.ah-page button:focus-visible,.ah-page input:focus-visible,.ah-page select:focus-visible{outline:2px solid var(--gold2);outline-offset:2px}

/* stats */
.ah-stats{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px;margin-bottom:20px}
.ah-stat{display:flex;align-items:center;gap:14px;padding:18px;border-radius:18px;border:1px solid var(--line);background:var(--card);min-width:0;animation:ahIn .4s ease both}
.ah-stat:nth-child(2){animation-delay:.04s}.ah-stat:nth-child(3){animation-delay:.08s}.ah-stat:nth-child(4){animation-delay:.12s}
.ah-stat>div:last-child{min-width:0}
.ah-stat-ic{width:46px;height:46px;border-radius:14px;display:grid;place-items:center;color:var(--gold2);background:rgba(241,248,233,.06);font-size:1.05rem;flex:none}
.ah-stat b{display:block;font-size:1.45rem;font-weight:800;line-height:1.1}
.ah-stat .ah-stat-txt{font-size:1rem;line-height:1.3;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.ah-stat span{display:block;margin-top:3px;font-size:.76rem;color:var(--sage);line-height:1.4}

/* toolbar */
.ah-tools{display:flex;gap:12px;align-items:center;flex-wrap:wrap;margin-bottom:14px}
.ah-search{position:relative;flex:1 1 280px;min-width:0}
.ah-search>i{position:absolute;left:15px;top:50%;transform:translateY(-50%);color:var(--dim);font-size:.85rem;pointer-events:none}
.ah-search input{width:100%;padding:12px 40px 12px 40px;border-radius:12px;border:1px solid var(--line2);background:#091f15;color:var(--cream);outline:none;font-size:.92rem;transition:border-color .2s ease,box-shadow .2s ease}
.ah-search input:focus{border-color:var(--gold);box-shadow:0 0 0 3px rgba(217,162,75,.14)}
.ah-search input::placeholder{color:rgba(220,235,220,.4)}
.ah-search input::-webkit-search-cancel-button{display:none}
.ah-search button{position:absolute;right:8px;top:50%;transform:translateY(-50%);width:30px;height:30px;border:0;border-radius:50%;background:rgba(241,248,233,.08);color:var(--sage);cursor:pointer}
.ah-search button:hover{background:var(--gold);color:#241708}.ah-search button[hidden]{display:none}
.ah-seg{display:flex;padding:4px;gap:2px;border-radius:12px;background:#123822;border:1px solid var(--line);overflow-x:auto;scrollbar-width:none}
.ah-seg::-webkit-scrollbar{display:none}
.ah-seg button{border:0;background:none;color:var(--sage);font-weight:600;font-size:.78rem;padding:9px 12px;border-radius:9px;cursor:pointer;transition:background .15s ease,color .15s ease;white-space:nowrap}
.ah-seg button b{margin-left:5px;color:var(--gold2)}.ah-seg button:hover{color:var(--cream)}
.ah-seg button.on{background:var(--gold);color:#241708}.ah-seg button.on b{color:#241708}
.ah-sort{display:flex;align-items:center;gap:9px;padding:0 12px;height:46px;border-radius:12px;border:1px solid var(--line2);background:#091f15;color:var(--gold2)}
.ah-sort select{border:0;background:transparent;color:var(--cream);font-size:.84rem;font-weight:600;outline:none;cursor:pointer;padding-right:4px}
.ah-sort select option{background:#0e271b;color:var(--cream)}

.ah-quick{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:6px}
.ah-q{border:1px solid var(--line2);background:rgba(241,248,233,.04);color:var(--sage);font-size:.78rem;font-weight:600;padding:7px 13px;border-radius:99px;cursor:pointer;transition:background .15s ease,color .15s ease,border-color .15s ease}
.ah-q i{margin-right:6px;color:var(--leaf);font-size:.72rem}.ah-q-type i{color:var(--gold2)}
.ah-q b{margin-left:5px;color:var(--gold2)}.ah-q:hover{border-color:var(--gold);color:var(--cream)}
.ah-q.on{background:var(--gold);border-color:var(--gold);color:#241708}.ah-q.on i,.ah-q.on b{color:#241708}
.ah-meta{margin:14px 2px 12px;font-size:.78rem;color:var(--dim)}

/* rows */
.ah-list{display:grid;gap:12px}
.ah-row{display:grid;grid-template-columns:64px minmax(0,1fr) 120px auto;align-items:center;gap:20px;padding:16px 20px;border-radius:18px;border:1px solid var(--line);color:var(--cream);
  background:linear-gradient(135deg,rgba(18,56,34,.55),rgba(9,31,21,.85));transition:transform .2s ease,border-color .2s ease,box-shadow .2s ease;animation:ahIn .4s ease both}
.ah-row[hidden]{display:none}
.ah-clickable{cursor:pointer}
.ah-clickable:hover{transform:translateY(-3px);border-color:rgba(217,162,75,.5);box-shadow:0 16px 32px rgba(0,0,0,.25);color:var(--cream)}
.ah-date{display:flex;flex-direction:column;align-items:center;justify-content:center;width:64px;padding:9px 0;border-radius:14px;background:rgba(241,248,233,.06);border:1px solid var(--line);line-height:1.1}
.ah-date b{font-size:1.4rem;font-weight:800}.ah-date span{margin-top:2px;font-size:.74rem;font-weight:700;color:var(--gold2);text-transform:uppercase;letter-spacing:.08em}.ah-date small{margin-top:2px;font-size:.66rem;color:var(--dim)}
.ah-main{min-width:0}
.ah-main-top{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.ah-disease{margin:0;font-size:1.08rem;font-weight:700;overflow-wrap:anywhere}.ah-disease.is-none{color:var(--dim);font-weight:500}
.ah-pill{display:inline-flex;align-items:center;padding:4px 10px;border-radius:99px;font-size:.68rem;font-weight:800;border:1px solid transparent;white-space:nowrap}
.ah-st-completed{color:#a9dfad;background:rgba(76,175,80,.12);border-color:rgba(129,199,132,.25)}
.ah-st-pending{color:var(--gold2);background:rgba(217,162,75,.12);border-color:rgba(240,198,116,.3)}
.ah-st-failed{color:#f0a39a;background:rgba(229,115,100,.12);border-color:rgba(240,163,154,.3)}
.ah-info{display:flex;gap:16px;flex-wrap:wrap;margin-top:7px;font-size:.78rem;color:var(--sage)}.ah-info i{margin-right:6px;color:var(--gold2)}
.ah-syms{display:flex;flex-wrap:wrap;gap:6px;margin-top:11px}
.ah-sym{display:inline-block;padding:4px 10px;border-radius:99px;background:rgba(241,248,233,.06);border:1px solid var(--line);font-size:.72rem;color:rgba(231,241,232,.8)}
.ah-sym-more{color:var(--gold2);border-color:rgba(217,162,75,.25)}
.ah-nosym{font-size:.74rem;color:var(--dim)}
.ah-conf{text-align:center}
.ah-conf-n{font-size:1.5rem;font-weight:800;line-height:1}.ah-conf-n small{font-size:.8rem;color:var(--sage);margin-left:1px}
.ah-conf-bar{height:7px;border-radius:9px;background:rgba(241,248,233,.1);overflow:hidden;margin:9px 0 6px}
.ah-conf-bar i{display:block;height:100%;width:0;border-radius:9px;background:var(--leaf);transition:width .9s cubic-bezier(.2,.8,.2,1)}
.ah-c-mid .ah-conf-bar i{background:#f0c674}.ah-c-lo .ah-conf-bar i{background:#e8927f}
.ah-conf-l{font-size:.7rem;font-weight:700;color:var(--dim);text-transform:uppercase;letter-spacing:.06em}
.ah-c-hi .ah-conf-l{color:#a9dfad}.ah-c-mid .ah-conf-l{color:#f0c674}.ah-c-lo .ah-conf-l{color:#f0a393}.ah-c-none .ah-conf-n{color:var(--dim)}
.ah-go{display:flex;align-items:center;gap:9px;color:var(--gold2);font-size:.78rem;font-weight:700;white-space:nowrap}
.ah-go i{transition:transform .2s ease}.ah-clickable:hover .ah-go i{transform:translateX(5px)}

.ah-nomatch{display:grid;justify-items:center;gap:6px;padding:44px 20px;border-radius:18px;border:1px dashed var(--line2);color:var(--dim);text-align:center;font-size:.86rem}
.ah-nomatch[hidden]{display:none}.ah-nomatch>i{font-size:1.6rem;color:var(--gold2);margin-bottom:6px}.ah-nomatch b{color:var(--cream);font-size:1rem}
.ah-nomatch button{margin-top:10px;border:1px solid var(--line2);background:rgba(241,248,233,.05);color:var(--gold2);font-weight:700;padding:9px 16px;border-radius:10px;cursor:pointer}
.ah-nomatch button:hover{background:var(--gold);color:#241708}

.ah-pager{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-top:20px;padding-top:18px;border-top:1px solid var(--line);font-size:.82rem;color:var(--dim)}
.ah-pager-links{display:flex;gap:10px}
.ah-pg{display:inline-flex;align-items:center;gap:9px;padding:10px 16px;border-radius:11px;border:1px solid var(--line2);background:rgba(241,248,233,.05);color:var(--cream);font-weight:700;font-size:.82rem;transition:border-color .15s ease,color .15s ease,transform .15s ease}
.ah-pg:hover{border-color:var(--gold);color:var(--gold2);transform:translateY(-2px)}
.ah-pg.disabled{opacity:.4;pointer-events:none}
.ah-hint{margin:12px 0 0;font-size:.74rem;color:var(--dim)}.ah-hint i{color:var(--gold);margin-right:6px}

/* empty */
.ah-empty{display:grid;justify-items:center;text-align:center;padding:56px 24px;border-radius:24px;border:1px solid var(--line);background:linear-gradient(135deg,rgba(18,56,34,.55),rgba(9,31,21,.85))}
.ah-empty-ic{width:74px;height:74px;border-radius:22px;display:grid;place-items:center;color:var(--leaf);background:rgba(76,175,80,.12);border:1px solid rgba(139,209,154,.25);font-size:1.8rem;margin-bottom:18px}
.ah-empty h3{margin:0 0 8px;font-family:"Fraunces",Georgia,serif;font-weight:500;font-size:1.6rem}
.ah-empty p{margin:0 0 22px;max-width:460px;color:var(--sage);line-height:1.7;font-size:.92rem}
.ah-empty-steps{display:flex;align-items:center;gap:12px;flex-wrap:wrap;justify-content:center;margin-bottom:26px;color:var(--dim);font-size:.78rem}
.ah-empty-steps span{display:inline-flex;align-items:center;gap:8px;padding:9px 14px;border-radius:99px;border:1px solid var(--line2);background:rgba(241,248,233,.04);color:var(--sage);font-weight:600}.ah-empty-steps span i{color:var(--gold2)}

@media(max-width:960px){
  .ah-stats{grid-template-columns:repeat(2,minmax(0,1fr))}
  .ah-row{grid-template-columns:60px minmax(0,1fr) 100px}
  .ah-go{display:none}
}
@media(max-width:620px){
  .ah-wrap{padding:0 16px}
  .ah-stats{gap:10px}.ah-stat{padding:14px;gap:11px}.ah-stat-ic{width:40px;height:40px}.ah-stat b{font-size:1.2rem}
  .ah-row{grid-template-columns:54px minmax(0,1fr);gap:14px;padding:14px;position:relative}
  .ah-row{align-items:start}
  .ah-date{width:54px}.ah-date b{font-size:1.2rem}
  .ah-stat .ah-stat-txt{white-space:normal}
  .ah-conf{grid-column:1/-1;display:grid;grid-template-columns:auto 1fr auto;align-items:center;gap:12px;text-align:left;padding-top:12px;border-top:1px solid var(--line)}
  .ah-conf-n{font-size:1.2rem}.ah-conf-bar{margin:0}
  .ah-head .ah-btn{width:100%}
  .ah-sort{width:100%}.ah-sort select{flex:1}
  .ah-pager{justify-content:center;text-align:center}
}
</style>

<script>
(function () {
    var $ = function (s, r) { return (r || document).querySelector(s); };
    var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };
    var list = $('#ahList');
    if (!list) return;

    /* confidence bars grow in */
    requestAnimationFrame(function () {
        setTimeout(function () { $$('.ah-conf-bar i').forEach(function (b) { b.style.width = b.getAttribute('data-w') + '%'; }); }, 120);
    });

    var rows = $$('.ah-row', list), total = rows.length;
    var q = '', status = 'all', quick = '';
    var search = $('#ahSearch'), clear = $('#ahClear'), meta = $('#ahMeta'), none = $('#ahNone');

    function apply() {
        var n = 0;
        rows.forEach(function (r) {
            var hay = r.getAttribute('data-search');
            var ok = (status === 'all' || r.getAttribute('data-status') === status)
                  && (!q || hay.indexOf(q) > -1)
                  && (!quick || hay.indexOf(quick) > -1);
            r.hidden = !ok; if (ok) n++;
        });
        meta.textContent = (n === total) ? 'Showing ' + total + ' of ' + total : 'Showing ' + n + ' of ' + total + ' on this page';
        none.hidden = n !== 0;
        clear.hidden = !search.value;
    }

    var t;
    search.addEventListener('input', function () {
        clearTimeout(t);
        t = setTimeout(function () { q = search.value.trim().toLowerCase(); apply(); }, 120);
        clear.hidden = !search.value;
    });
    clear.addEventListener('click', function () { search.value = ''; q = ''; apply(); search.focus(); });

    var seg = $$('#ahStatus button');
    seg.forEach(function (b) {
        b.addEventListener('click', function () {
            status = b.getAttribute('data-f');
            seg.forEach(function (x) { x.classList.toggle('on', x === b); });
            apply();
        });
    });

    var qs = $$('.ah-q');
    qs.forEach(function (b) {
        b.addEventListener('click', function () {
            var v = b.getAttribute('data-q');
            quick = (quick === v) ? '' : v;
            qs.forEach(function (x) { x.classList.toggle('on', x.getAttribute('data-q') === quick); });
            apply();
        });
    });

    $('#ahSort').addEventListener('change', function () {
        var m = this.value;
        rows.sort(function (a, b) {
            if (m === 'conf') return parseFloat(b.getAttribute('data-conf')) - parseFloat(a.getAttribute('data-conf'));
            var d = parseInt(b.getAttribute('data-ts'), 10) - parseInt(a.getAttribute('data-ts'), 10);
            return m === 'old' ? -d : d;
        });
        rows.forEach(function (r) { list.appendChild(r); });
    });

    $('#ahReset').addEventListener('click', function () {
        search.value = ''; q = ''; status = 'all'; quick = '';
        seg.forEach(function (x) { x.classList.toggle('on', x.getAttribute('data-f') === 'all'); });
        qs.forEach(function (x) { x.classList.remove('on'); });
        apply();
    });
})();
</script>
@endsection