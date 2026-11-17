@extends('layouts.FarmerMaster')

@section('title', 'Fertilizer Management — CARDO-FER')

@section('content')
@php
    /* ---------- Prepare everything once, so the markup below stays simple ---------- */
    $today = now()->startOfDay();
    $todayStr = $today->format('Y-m-d');
    $num = fn($v) => ($v === null || $v === '') ? '' : (float) $v;

    $fieldArea   = $field->area ?? null;
    $fieldUnit   = $field->area_unit ?? null;
    $fieldPlants = $field->plant_count ?? null;
    $fieldPlantsText = $fieldPlants !== null ? number_format($fieldPlants) : '';
    $fieldAcres  = is_numeric($fieldArea)
        ? ($fieldUnit === 'cent' ? round($fieldArea / 100, 2) : ($fieldUnit === 'acre' ? (float) $fieldArea : null))
        : null;

    /* Optional links. A button only appears if its route exists in your project. */
    $weatherUrl = null;
    foreach (['farmer.weather', 'farmer.weather.index', 'farmer.weather.forecast'] as $routeName) {
        if (\Illuminate\Support\Facades\Route::has($routeName)) { $weatherUrl = route($routeName); break; }
    }
    $guideUrl = \Illuminate\Support\Facades\Route::has('farmer.fertilizer') ? route('farmer.fertilizer') : null;

    $rows = [];
    foreach ($applications as $application) {
        $days = null; $status = 'none'; $label = 'No next date'; $rel = ''; $pct = null; $big = ''; $small = ''; $total = 0;

        if ($application->next_application_date) {
            $next  = $application->next_application_date->copy()->startOfDay();
            $days  = (int) $today->diffInDays($next, false);
            $abs   = abs($days);

            if ($days < 0)       { $status = 'overdue';  $label = 'Overdue';   $rel = 'Overdue by '.$abs.($abs === 1 ? ' day' : ' days'); $big = $abs; $small = 'days late'; }
            elseif ($days === 0) { $status = 'today';    $label = 'Due today'; $rel = 'Today'; $big = 'Today'; $small = ''; }
            elseif ($days <= 7)  { $status = 'soon';     $label = 'Due soon';  $rel = $days === 1 ? 'Tomorrow' : 'In '.$days.' days'; $big = $days; $small = $days === 1 ? 'day left' : 'days left'; }
            else                 { $status = 'upcoming'; $label = 'Upcoming';  $rel = 'In '.$days.' days'; $big = $days; $small = 'days left'; }

            $start   = $application->applied_date->copy()->startOfDay();
            $total   = (int) $start->diffInDays($next, false);
            $elapsed = (int) $start->diffInDays($today, false);
            $pct     = $total > 0 ? max(0, min(100, (int) round($elapsed / $total * 100))) : 100;
        }

        $f = $application->fertilizer;
        $qtyRaw = rtrim(rtrim(number_format((float) $application->quantity, 2, '.', ''), '0'), '.');
        $rows[] = [
            'id'         => $application->id,
            'fid'        => $f->id ?? '',
            'name'       => $f->fertilizer_name ?? 'Fertilizer',
            'cat'        => $f->category ?? '',
            'icon'       => strtolower($f->category ?? '') === 'organic' ? 'fa-leaf' : 'fa-flask',
            'qty'        => $qtyRaw.' '.$application->quantity_unit,
            'qty_raw'    => $qtyRaw,
            'qty_unit'   => $application->quantity_unit,
            'applied'    => $application->applied_date->format('d M Y'),
            'applied_ts' => $application->applied_date->timestamp,
            'next'       => $application->next_application_date ? $application->next_application_date->format('d M Y') : '',
            'days'       => $days,
            'status'     => $status,
            'label'      => $label,
            'rel'        => $rel,
            'pct'        => $pct,
            'big'        => $big,
            'small'      => $small,
            'notes'      => $application->notes ?? '',
            'interval'   => $total > 0 ? $total : 30,
            'next_show'  => $application->next_application_date ? $application->next_application_date->format('d M Y') : 'Not set',
            'actionable' => in_array($status, ['overdue', 'today', 'soon']),
            'cta'        => in_array($status, ['overdue', 'today', 'soon']) ? 'I applied it' : 'Apply early',
        ];
    }

    /* A reminder is finished once a newer application of the same fertilizer exists. */
    foreach ($rows as $i => $r) {
        if ($r['days'] === null) continue;
        foreach ($rows as $o) {
            if ($o['id'] !== $r['id'] && (string) $o['fid'] === (string) $r['fid']
                && ($o['applied_ts'] > $r['applied_ts'] || ($o['applied_ts'] === $r['applied_ts'] && $o['id'] > $r['id']))) {
                $rows[$i]['days'] = null; $rows[$i]['status'] = 'done'; $rows[$i]['label'] = 'Done';
                $rows[$i]['rel'] = 'Done · a newer application exists'; $rows[$i]['pct'] = null;
                $rows[$i]['actionable'] = false; $rows[$i]['cta'] = 'Apply again';
                break;
            }
        }
    }

    $stats = ['total' => count($rows), 'overdue' => 0, 'week' => 0, 'upcoming' => 0];
    foreach ($rows as $r) {
        if ($r['days'] === null) continue;
        if ($r['days'] < 0) $stats['overdue']++;
        elseif ($r['days'] <= 7) $stats['week']++;
        else $stats['upcoming']++;
    }
    $hasDated = ($stats['overdue'] + $stats['week'] + $stats['upcoming']) > 0;

    $withDate = array_values(array_filter($rows, fn($r) => $r['days'] !== null));
    usort($withDate, fn($a, $b) => $a['days'] <=> $b['days']);
    $nextUp = $withDate[0] ?? null;

    $fz = [];
    foreach ($fertilizers as $fertilizer) {
        $dosage = $num($fertilizer->dosage_amount);
        $ref    = $num($fertilizer->reference_quantity);
        $hint   = '';
        if ($dosage !== '') {
            $hint = $dosage.' '.$fertilizer->dosage_unit;
            if ($ref !== '') $hint .= ' / '.$ref.' '.$fertilizer->reference_unit;
        }
        $fz[] = [
            'id'      => $fertilizer->id,
            'name'    => $fertilizer->fertilizer_name,
            'cat'     => $fertilizer->category,
            'icon'    => strtolower($fertilizer->category ?? '') === 'organic' ? 'fa-leaf' : 'fa-flask',
            'desc'    => $fertilizer->description,
            'dosage'  => $dosage,
            'unit'    => $fertilizer->dosage_unit,
            'ref'     => $ref,
            'refUnit' => $fertilizer->reference_unit,
            'hint'    => $hint,
        ];
    }
    $categories = array_values(array_unique(array_filter(array_column($fz, 'cat'))));
    $autoOpen = ($errors->any() || old('fertilizer_id')) ? '1' : '0';
@endphp

<div class="fm-page" data-auto-open="{{ $autoOpen }}">
<div class="fm-wrap">

    {{-- ===== Page header ===== --}}
    <header class="fm-head">
        <div>
            <div class="fm-eyebrow"><i class="fa-solid fa-seedling"></i> Fertilizer Management</div>
            <h1 class="fm-title">Never miss a fertilizer day</h1>
            <p class="fm-sub">Record what you applied, see what is due next, and get a suggested quantity for your field.</p>
        </div>
        <div class="fm-head-actions">
            @if($guideUrl)
                <a class="fm-btn fm-btn-ghost" href="{{ $guideUrl }}"><i class="fa-solid fa-book-open"></i> Disease fertilizer guide</a>
            @endif
            <button type="button" class="fm-btn fm-btn-primary" data-open>
                <i class="fa-solid fa-plus"></i> Record application
            </button>
        </div>
    </header>

    @if(session('success'))
        <div class="fm-flash" role="status">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="fm-flash-x" aria-label="Dismiss">&times;</button>
        </div>
    @endif

    {{-- ===== Next reminder + field ===== --}}
    <section class="fm-top">

        @if($nextUp)
            <div class="fm-next fm-s-{{ $nextUp['status'] }}">
                <div class="fm-ring" aria-hidden="true">
                    <svg viewBox="0 0 100 100"><circle class="bg" cx="50" cy="50" r="44" pathLength="100"/><circle class="fg" cx="50" cy="50" r="44" pathLength="100" data-pct="{{ $nextUp['pct'] }}"/></svg>
                    <div class="fm-ring-mid"><b>{{ $nextUp['big'] }}</b><small>{{ $nextUp['small'] }}</small></div>
                </div>
                <div class="fm-next-body">
                    <div class="fm-kicker">Next reminder</div>
                    <h2>{{ $nextUp['name'] }}</h2>
                    <p><span class="fm-pill fm-p-{{ $nextUp['status'] }}">{{ $nextUp['label'] }}</span> {{ $nextUp['rel'] }} &middot; {{ $nextUp['next'] }}</p>
                    <div class="fm-next-actions">
                        <button type="button" class="fm-btn fm-btn-primary" data-open data-quick="1" data-cid="{{ $nextUp['id'] }}" data-fid="{{ $nextUp['fid'] }}" data-fname="{{ $nextUp['name'] }}" data-due="{{ $nextUp['next'] }}" data-applied="{{ $nextUp['applied'] }}" data-qty="{{ $nextUp['qty_raw'] }}" data-unit="{{ $nextUp['qty_unit'] }}" data-interval="{{ $nextUp['interval'] }}"><i class="fa-solid fa-check"></i> {{ $nextUp['cta'] }}</button>
                        @if($weatherUrl)
                            <a class="fm-btn fm-btn-light" href="{{ $weatherUrl }}"><i class="fa-solid fa-cloud-sun-rain"></i> Check weather first</a>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="fm-next fm-s-empty">
                <div class="fm-next-icon"><i class="fa-regular fa-bell"></i></div>
                <div class="fm-next-body">
                    <div class="fm-kicker">Next reminder</div>
                    <h2>No reminders yet</h2>
                    <p>Record an application and set a <strong>next application date</strong>. Your reminder will show up here.</p>
                    <div class="fm-next-actions">
                        <button type="button" class="fm-btn fm-btn-primary" data-open><i class="fa-solid fa-plus"></i> Record first application</button>
                    </div>
                </div>
            </div>
        @endif

        <aside class="fm-field">
            <div class="fm-field-h"><i class="fa-solid fa-tractor"></i> Your field</div>
            @if($field)
                <div class="fm-field-grid">
                    <div><small>Area</small><b>{{ $fieldArea }} {{ $fieldUnit }}</b></div>
                    <div><small>Plants</small><b>{{ $fieldPlantsText }}</b></div>
                </div>
                <p class="fm-field-note">Suggested quantities use these details and the dosage set by your administrator.</p>
                <a class="fm-link" href="{{ route('farmer.field') }}">Edit field details <i class="fa-solid fa-arrow-right"></i></a>
            @else
                <p class="fm-field-note">Add your area and plant count to get a suggested quantity for each fertilizer.</p>
                <a class="fm-btn fm-btn-ghost fm-btn-sm" href="{{ route('farmer.field') }}"><i class="fa-solid fa-plus"></i> Add field details</a>
            @endif
        </aside>
    </section>

    {{-- ===== Stat tiles (also work as filters) ===== --}}
    <section class="fm-tiles" aria-label="Filter by status">
        <button type="button" class="fm-tile fm-t-overdue" data-filter="overdue"><i class="fa-solid fa-triangle-exclamation"></i><b>{{ $stats['overdue'] }}</b><span>Overdue</span></button>
        <button type="button" class="fm-tile fm-t-week" data-filter="week"><i class="fa-solid fa-clock"></i><b>{{ $stats['week'] }}</b><span>Due within 7 days</span></button>
        <button type="button" class="fm-tile fm-t-upcoming" data-filter="upcoming"><i class="fa-solid fa-calendar-check"></i><b>{{ $stats['upcoming'] }}</b><span>Upcoming</span></button>
        <button type="button" class="fm-tile fm-t-all is-on" data-filter="all"><i class="fa-solid fa-list-check"></i><b>{{ $stats['total'] }}</b><span>All applications</span></button>
    </section>

    {{-- ===== Interactive timeline ===== --}}
    @if($hasDated)
        <section class="fm-timeline" aria-label="Reminder timeline">
            <div class="fm-tl-head">
                <h3><i class="fa-solid fa-timeline"></i> Reminder timeline</h3>
                <span>Hover or tap a dot. Click it to jump to the record.</span>
            </div>
            <div class="fm-tl-scroll"><div class="fm-tl" id="fm-tl"></div></div>
        </section>
    @endif

    {{-- ===== List + sidebar ===== --}}
    <div class="fm-main">

    <section class="fm-list-wrap">
        <div class="fm-toolbar">
            <div class="fm-search"><i class="fa-solid fa-magnifying-glass"></i><input type="search" id="fm-search" placeholder="Search by fertilizer or note..." aria-label="Search applications"></div>
            <label class="fm-sort"><span>Sort</span>
                <select id="fm-sort">
                    <option value="next">Next date (soonest)</option>
                    <option value="recent">Recently applied</option>
                    <option value="name">Fertilizer name</option>
                </select>
            </label>
        </div>
        <div class="fm-count" id="fm-count" aria-live="polite"></div>

        <div class="fm-list" id="fm-list">
            @forelse($rows as $r)
                <article class="fm-item fm-s-{{ $r['status'] }}" id="fm-app-{{ $r['id'] }}" data-status="{{ $r['status'] }}" data-days="{{ $r['days'] }}" data-applied="{{ $r['applied_ts'] }}" data-fid="{{ $r['fid'] }}" data-cat="{{ $r['cat'] }}" data-name="{{ $r['name'] }}" data-label="{{ $r['label'] }}" data-rel="{{ $r['rel'] }}" data-next="{{ $r['next'] }}" data-applied-label="{{ $r['applied'] }}" data-search="{{ $r['name'] }} {{ $r['cat'] }} {{ $r['notes'] }}">
                    <div class="fm-item-top">
                        <div class="fm-item-name">
                            <span class="fm-item-ic"><i class="fa-solid {{ $r['icon'] }}"></i></span>
                            <div><b>{{ $r['name'] }}</b><small>{{ $r['cat'] }}</small></div>
                        </div>
                        <span class="fm-pill fm-p-{{ $r['status'] }}">{{ $r['label'] }}</span>
                    </div>

                    <div class="fm-facts">
                        <div><small>Quantity</small><b>{{ $r['qty'] }}</b></div>
                        <div><small>Applied</small><b>{{ $r['applied'] }}</b></div>
                        <div><small>Next date</small><b>{{ $r['next_show'] }}</b><em>{{ $r['rel'] }}</em></div>
                    </div>

                    @if($r['pct'] !== null)
                        <div class="fm-track" title="How far you are between the two dates"><i style="width: {{ $r['pct'] }}%"></i></div>
                    @endif

                    <div class="fm-item-foot">
                        @if($r['notes'])
                            <span class="fm-note"><i class="fa-solid fa-note-sticky"></i> {{ $r['notes'] }}</span>
                        @endif
                        @if($r['actionable'])
                            <button type="button" class="fm-btn fm-btn-primary fm-btn-sm" data-open data-quick="1" data-cid="{{ $r['id'] }}" data-fid="{{ $r['fid'] }}" data-fname="{{ $r['name'] }}" data-due="{{ $r['next'] }}" data-applied="{{ $r['applied'] }}" data-qty="{{ $r['qty_raw'] }}" data-unit="{{ $r['qty_unit'] }}" data-interval="{{ $r['interval'] }}"><i class="fa-solid fa-check"></i> I applied it</button>
                        @else
                            <button type="button" class="fm-btn fm-btn-ghost fm-btn-sm" data-open data-quick="1" data-fid="{{ $r['fid'] }}" data-fname="{{ $r['name'] }}" data-applied="{{ $r['applied'] }}" data-qty="{{ $r['qty_raw'] }}" data-unit="{{ $r['qty_unit'] }}" data-interval="{{ $r['interval'] }}"><i class="fa-solid fa-rotate-right"></i> Apply again</button>
                        @endif
                    </div>
                </article>
            @empty
                <div class="fm-empty">
                    <i class="fa-solid fa-seedling"></i>
                    <h3>No fertilizer applications yet</h3>
                    <p>Once you record your first application, it will appear here with a reminder for the next one.</p>
                    <button type="button" class="fm-btn fm-btn-primary" data-open><i class="fa-solid fa-plus"></i> Record application</button>
                </div>
            @endforelse
        </div>

        <div class="fm-empty fm-nomatch" id="fm-nomatch" hidden>
            <i class="fa-solid fa-filter-circle-xmark"></i>
            <h3>Nothing matches</h3>
            <p>Try another search or show all applications.</p>
            <button type="button" class="fm-btn fm-btn-ghost" id="fm-reset">Show all</button>
        </div>
    </section>

    <aside class="fm-side">

        {{-- Quick calculator --}}
        <div class="fm-widget">
            <div class="fm-widget-h"><i class="fa-solid fa-calculator"></i> Quantity calculator</div>
            <p class="fm-widget-p">Try different plant counts or areas to see how much you need.</p>
            <label class="fm-lbl" for="fm-calc-f">Fertilizer</label>
            <select id="fm-calc-f" class="fm-input">
                <option value="">Choose fertilizer</option>
                @foreach($fz as $f)
                    <option value="{{ $f['id'] }}" data-dosage="{{ $f['dosage'] }}" data-unit="{{ $f['unit'] }}" data-reference="{{ $f['ref'] }}" data-reference-unit="{{ $f['refUnit'] }}">{{ $f['name'] }}</option>
                @endforeach
            </select>
            <div class="fm-calc-row">
                <div><label class="fm-lbl" for="fm-calc-plants">Plants</label><input type="number" min="0" step="1" id="fm-calc-plants" class="fm-input" value="{{ $fieldPlants }}" placeholder="0"></div>
                <div><label class="fm-lbl" for="fm-calc-acres">Acres</label><input type="number" min="0" step="0.01" id="fm-calc-acres" class="fm-input" value="{{ $fieldAcres }}" placeholder="0.00"></div>
            </div>
            <div class="fm-calc-out" id="fm-calc-out" aria-live="polite">
                <div class="fm-calc-v" id="fm-calc-v">Choose a fertilizer</div>
                <div class="fm-calc-n" id="fm-calc-n">The result appears here.</div>
            </div>
            <button type="button" class="fm-btn fm-btn-ghost fm-btn-sm fm-btn-block" id="fm-calc-use" disabled><i class="fa-solid fa-plus"></i> Use in a new record</button>
        </div>

        {{-- Mix donut --}}
        @if($stats['total'])
            <div class="fm-widget">
                <div class="fm-widget-h"><i class="fa-solid fa-chart-pie"></i> Your fertilizer mix</div>
                <div class="fm-mix">
                    <div class="fm-donut"><svg viewBox="0 0 100 100" id="fm-donut"></svg><div class="fm-donut-mid"><b id="fm-donut-n">0</b><small>applications</small></div></div>
                    <ul class="fm-legend" id="fm-legend"></ul>
                </div>
                <div class="fm-last" id="fm-last"></div>
            </div>
        @endif

    </aside>

    </div>

</div>

{{-- ===== Floating button (phones) ===== --}}
<button type="button" class="fm-fab" data-open aria-label="Record application"><i class="fa-solid fa-plus"></i></button>

{{-- ===== Slide-over: record application ===== --}}
<div class="fm-drawer" id="fm-drawer" aria-hidden="true">
    <div class="fm-backdrop" data-close></div>
    <div class="fm-panel" role="dialog" aria-modal="true" aria-labelledby="fm-drawer-title">
        <form action="{{ route('farmer.fertilizer.store') }}" method="POST" id="fm-form" class="fm-form">
            @csrf

            <div class="fm-panel-head">
                <div>
                    <h2 id="fm-drawer-title">Record application</h2>
                    <p>Three quick steps. Takes under a minute.</p>
                </div>
                <button type="button" class="fm-x" data-close aria-label="Close">&times;</button>
            </div>

            <div class="fm-panel-body">

                <div class="fm-quick" id="fm-quick" hidden>
                    <i class="fa-solid fa-circle-check"></i>
                    <div><b id="fm-quick-t"></b><span id="fm-quick-s"></span></div>
                </div>

                @if($errors->any())
                    <div class="fm-errors" role="alert">
                        <b><i class="fa-solid fa-triangle-exclamation"></i> Please correct the following:</b>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Step 1 --}}
                <div class="fm-step">
                    <div class="fm-step-h"><span>1</span> Which fertilizer?</div>

                    <div class="fm-pick-tools">
                        <div class="fm-search fm-search-sm"><i class="fa-solid fa-magnifying-glass"></i><input type="search" id="fm-fsearch" placeholder="Search fertilizer..." aria-label="Search fertilizer"></div>
                        <div class="fm-cats">
                            <button type="button" class="fm-cat is-on" data-cat="">All</button>
                            @foreach($categories as $c)
                                <button type="button" class="fm-cat" data-cat="{{ $c }}">{{ $c }}</button>
                            @endforeach
                        </div>
                    </div>

                    <div class="fm-fgrid" id="fm-fgrid" role="radiogroup" aria-label="Fertilizer">
                        @foreach($fz as $f)
                            <label class="fm-fcard" data-name="{{ $f['name'] }}" data-cat="{{ $f['cat'] }}">
                                <input type="radio" name="fertilizer_id" value="{{ $f['id'] }}" required
                                    data-desc="{{ $f['desc'] }}" data-dosage="{{ $f['dosage'] }}" data-unit="{{ $f['unit'] }}"
                                    data-reference="{{ $f['ref'] }}" data-reference-unit="{{ $f['refUnit'] }}" data-category="{{ $f['cat'] }}"
                                    @checked(old('fertilizer_id') == $f['id'])>
                                <span class="fm-fcard-in">
                                    <span class="fm-fcard-ic"><i class="fa-solid {{ $f['icon'] }}"></i></span>
                                    <span class="fm-fcard-tx"><b>{{ $f['name'] }}</b><small>{{ $f['cat'] }} @if($f['hint']) &middot; {{ $f['hint'] }} @endif</small></span>
                                    <i class="fa-solid fa-circle-check fm-tick"></i>
                                </span>
                            </label>
                        @endforeach
                        <div class="fm-fnone" id="fm-fnone" hidden>No fertilizer found.</div>
                    </div>

                    <div class="fm-info" id="fm-info" hidden>
                        <div class="fm-info-t"><i class="fa-solid fa-circle-info"></i> About this fertilizer</div>
                        <p id="fm-info-desc"></p>
                        <div id="fm-info-dose" class="fm-dose"></div>
                        <div class="fm-suggest" id="fm-suggest" hidden>
                            <div class="fm-suggest-t"><i class="fa-solid fa-calculator"></i> Suggested for your field</div>
                            <div class="fm-suggest-v" id="fm-suggest-v"></div>
                            <div class="fm-suggest-n" id="fm-suggest-n"></div>
                            <button type="button" class="fm-btn fm-btn-ghost fm-btn-sm" id="fm-use" hidden>Use this amount</button>
                        </div>
                    </div>
                </div>

                {{-- Step 2 --}}
                <div class="fm-step">
                    <div class="fm-step-h"><span>2</span> How much did you apply?</div>
                    <div class="fm-qty-row">
                        <input type="number" name="quantity" id="fm-qty" class="fm-input fm-input-lg" value="{{ old('quantity') }}" step="0.01" min="0.01" placeholder="e.g. 2.5" required aria-label="Quantity applied">
                        <div class="fm-units" role="radiogroup" aria-label="Unit">
                            <label class="fm-unit"><input type="radio" name="quantity_unit" value="g" required @checked(old('quantity_unit') === 'g')><span>g</span></label>
                            <label class="fm-unit"><input type="radio" name="quantity_unit" value="kg" required @checked(old('quantity_unit') === 'kg')><span>kg</span></label>
                            <label class="fm-unit"><input type="radio" name="quantity_unit" value="ml" required @checked(old('quantity_unit') === 'ml')><span>ml</span></label>
                            <label class="fm-unit"><input type="radio" name="quantity_unit" value="L" required @checked(old('quantity_unit') === 'L')><span>L</span></label>
                        </div>
                    </div>
                    <p class="fm-hint">The unit is picked for you when you choose a fertilizer. You can change it.</p>
                </div>

                {{-- Step 3 --}}
                <div class="fm-step">
                    <div class="fm-step-h"><span>3</span> When?</div>
                    <div class="fm-dates">
                        <div>
                            <label class="fm-lbl" for="fm-applied">Applied on</label>
                            <input type="date" name="applied_date" id="fm-applied" class="fm-input" value="{{ old('applied_date', $todayStr) }}" required>
                        </div>
                        <div>
                            <label class="fm-lbl" for="fm-next">Remind me again on <em>(optional)</em></label>
                            <input type="date" name="next_application_date" id="fm-next" class="fm-input" value="{{ old('next_application_date') }}">
                        </div>
                    </div>
                    <div class="fm-chips" aria-label="Quick pick">
                        <button type="button" class="fm-chip" data-days="15">+15 days</button>
                        <button type="button" class="fm-chip" data-days="30">+30 days</button>
                        <button type="button" class="fm-chip" data-days="45">+45 days</button>
                        <button type="button" class="fm-chip" data-days="60">+60 days</button>
                        <button type="button" class="fm-chip" data-days="90">+90 days</button>
                        <button type="button" class="fm-chip fm-chip-ghost" data-days="clear">No reminder</button>
                    </div>
                    <p class="fm-hint" id="fm-next-hint">Pick a quick option or choose any date.</p>

                    <label class="fm-lbl fm-lbl-notes" for="fm-notes">Notes <em>(optional)</em></label>
                    <textarea name="notes" id="fm-notes" class="fm-input" rows="3" placeholder="Anything worth remembering about this application...">{{ old('notes') }}</textarea>
                </div>

            </div>

            <div class="fm-panel-foot">
                <button type="button" class="fm-btn fm-btn-ghost" data-close>Cancel</button>
                <button type="submit" class="fm-btn fm-btn-primary" id="fm-submit"><i class="fa-solid fa-floppy-disk"></i> <span id="fm-submit-text">Record application</span></button>
            </div>
        </form>
    </div>
</div>

</div>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@400;500;600&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
.fm-page{--bg:#07190f;--card:#0e271b;--card2:#123822;--cream:#f1f8e9;--sage:#9fbea6;--dim:#7d9884;--gold:#d9a24b;--gold2:#f0c674;--leaf:#4caf50;--line:rgba(213,232,216,.12);--line2:rgba(213,232,216,.2);
  --c-overdue:#ef8c7c;--c-today:#f2a16f;--c-soon:#f0c674;--c-upcoming:#8bd19a;--c-none:#aebec5;
  position:relative;width:100vw;margin-left:calc(50% - 50vw);min-height:100vh;padding:44px 0 110px;overflow-x:clip;font-family:"Inter","Poppins",-apple-system,sans-serif;color:var(--cream);
  background:radial-gradient(900px 500px at 85% -5%,rgba(76,175,80,.12),transparent 60%),linear-gradient(180deg,#071910,#0b2418 55%,#081b12)}
.fm-page *{box-sizing:border-box}
.fm-page button,.fm-page input,.fm-page select,.fm-page textarea{font-family:inherit}
@media(prefers-reduced-motion:reduce){.fm-page *{animation-duration:.001ms!important;transition-duration:.001ms!important}}
.fm-wrap{max-width:1160px;margin:0 auto;padding:0 24px}

/* header */
.fm-head{display:flex;align-items:flex-end;justify-content:space-between;gap:20px;flex-wrap:wrap;margin-bottom:26px}
.fm-eyebrow{display:flex;align-items:center;gap:8px;color:var(--gold);font-size:.78rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase}
.fm-title{font-family:"Fraunces",Georgia,serif;font-weight:500;font-size:clamp(1.9rem,3.6vw,2.8rem);line-height:1.1;letter-spacing:-.02em;margin:10px 0 8px;color:var(--cream)}
.fm-sub{margin:0;color:var(--sage);max-width:560px;line-height:1.7;font-size:.95rem}
.fm-head-actions{display:flex;gap:10px;flex-wrap:wrap}

/* buttons */
.fm-btn{display:inline-flex;align-items:center;justify-content:center;gap:9px;border:1px solid transparent;border-radius:12px;padding:12px 20px;font-weight:700;font-size:.92rem;cursor:pointer;text-decoration:none;transition:transform .15s ease,box-shadow .15s ease,background .15s ease,border-color .15s ease}
.fm-btn-primary{background:linear-gradient(135deg,#e6b25c,#cb8a38);color:#241708}
.fm-btn-primary:hover{color:#241708;transform:translateY(-2px);box-shadow:0 12px 26px rgba(201,134,53,.28)}
.fm-btn-primary:disabled{opacity:.7;cursor:wait;transform:none;box-shadow:none}
.fm-btn-ghost{background:rgba(241,248,233,.05);border-color:var(--line2);color:var(--cream)}
.fm-btn-ghost:hover{color:var(--cream);background:rgba(241,248,233,.1);border-color:rgba(217,162,75,.5)}
.fm-btn-ghost:disabled{opacity:.45;cursor:not-allowed}
.fm-btn-light{background:rgba(241,248,233,.12);border-color:rgba(241,248,233,.25);color:#fff}
.fm-btn-light:hover{color:#fff;background:rgba(241,248,233,.2);transform:translateY(-2px)}
.fm-btn-sm{padding:8px 14px;font-size:.82rem;border-radius:10px}
.fm-btn-block{width:100%}
.fm-link{color:var(--gold2);text-decoration:none;font-weight:600;font-size:.85rem}.fm-link:hover{color:var(--gold2);text-decoration:underline}

.fm-flash{display:flex;align-items:center;gap:12px;margin-bottom:20px;padding:13px 16px;border-radius:14px;background:rgba(76,175,80,.12);border:1px solid rgba(129,199,132,.3);color:#bfe8c3;animation:fmIn .4s ease}
.fm-flash span{flex:1}.fm-flash-x{border:0;background:none;color:inherit;font-size:1.3rem;cursor:pointer;line-height:1}
@keyframes fmIn{from{opacity:0;transform:translateY(-8px)}}

/* next reminder + field */
.fm-top{display:grid;grid-template-columns:minmax(0,1.7fr) minmax(250px,1fr);gap:18px;margin-bottom:18px}
.fm-next{--ac:var(--gold2);display:flex;align-items:center;gap:28px;padding:30px 34px;border-radius:24px;border:1px solid var(--line2);background:linear-gradient(135deg,#103a22,#175227 60%,#1d6a2c);box-shadow:0 22px 50px rgba(0,0,0,.25);position:relative;overflow:hidden}
.fm-next:before{content:"";position:absolute;right:-90px;top:-130px;width:300px;height:300px;border-radius:50%;border:1px solid rgba(241,248,233,.1);box-shadow:0 0 0 50px rgba(241,248,233,.02)}
.fm-next.fm-s-overdue{--ac:var(--c-overdue)}.fm-next.fm-s-today{--ac:var(--c-today)}.fm-next.fm-s-soon{--ac:var(--c-soon)}.fm-next.fm-s-upcoming{--ac:var(--c-upcoming)}
.fm-ring{position:relative;width:132px;height:132px;flex:none}
.fm-ring svg{width:100%;height:100%;transform:rotate(-90deg)}
.fm-ring circle{fill:none;stroke-width:8}.fm-ring .bg{stroke:rgba(241,248,233,.12)}
.fm-ring .fg{stroke:var(--ac);stroke-linecap:round;stroke-dasharray:100;stroke-dashoffset:100;transition:stroke-dashoffset 1.2s ease}
.fm-ring-mid{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center}
.fm-ring-mid b{font-family:"Fraunces",Georgia,serif;font-size:2.1rem;font-weight:500;line-height:1}
.fm-ring-mid small{font-size:.72rem;color:rgba(241,248,233,.7);margin-top:4px}
.fm-next-icon{width:92px;height:92px;flex:none;border-radius:26px;display:grid;place-items:center;font-size:2.4rem;color:var(--gold2);background:rgba(241,248,233,.07);border:1px solid var(--line)}
.fm-next-body{position:relative;min-width:0}
.fm-kicker{font-size:.72rem;letter-spacing:.14em;text-transform:uppercase;color:rgba(241,248,233,.65);font-weight:700}
.fm-next h2{font-family:"Fraunces",Georgia,serif;font-weight:500;font-size:clamp(1.5rem,2.6vw,2.1rem);margin:6px 0 8px;color:#fff;overflow-wrap:anywhere}
.fm-next p{margin:0 0 18px;color:rgba(241,248,233,.8);line-height:1.6;font-size:.93rem}
.fm-next-actions{display:flex;gap:10px;flex-wrap:wrap}
.fm-field{padding:24px;border-radius:24px;border:1px solid var(--line);background:linear-gradient(160deg,rgba(241,248,233,.05),rgba(241,248,233,.015))}
.fm-field-h{font-size:.78rem;letter-spacing:.1em;text-transform:uppercase;color:var(--sage);font-weight:700;margin-bottom:16px;display:flex;gap:8px;align-items:center}.fm-field-h i{color:var(--gold)}
.fm-field-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px}
.fm-field-grid div{padding:12px 14px;border-radius:14px;background:rgba(7,25,16,.55);border:1px solid var(--line)}
.fm-field-grid small{display:block;color:var(--dim);font-size:.74rem;margin-bottom:3px}.fm-field-grid b{font-family:"Fraunces",Georgia,serif;font-size:1.25rem;font-weight:500}
.fm-field-note{font-size:.82rem;line-height:1.6;color:var(--dim);margin:0 0 14px}

/* pills */
.fm-pill{display:inline-flex;align-items:center;gap:5px;padding:4px 11px;border-radius:99px;font-size:.72rem;font-weight:800;border:1px solid transparent;white-space:nowrap}
.fm-p-overdue{background:rgba(239,140,124,.12);color:var(--c-overdue);border-color:rgba(239,140,124,.25)}
.fm-p-today{background:rgba(242,161,111,.12);color:var(--c-today);border-color:rgba(242,161,111,.25)}
.fm-p-soon{background:rgba(240,198,116,.12);color:var(--c-soon);border-color:rgba(240,198,116,.25)}
.fm-p-upcoming{background:rgba(139,209,154,.12);color:var(--c-upcoming);border-color:rgba(139,209,154,.25)}
.fm-p-none,.fm-p-done{background:rgba(174,190,197,.1);color:var(--c-none);border-color:rgba(174,190,197,.2)}
.fm-item.fm-s-done{opacity:.8}.fm-item.fm-s-done .fm-facts em{color:var(--dim)}

/* tiles */
.fm-tiles{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px;margin-bottom:18px}
.fm-tile{--tc:var(--gold2);display:grid;grid-template-columns:auto 1fr;grid-template-rows:auto auto;column-gap:14px;align-items:center;text-align:left;padding:16px 18px;border-radius:18px;border:1px solid var(--line);background:var(--card);color:var(--cream);cursor:pointer;transition:transform .2s ease,border-color .2s ease,background .2s ease}
.fm-tile i{grid-row:1/3;width:44px;height:44px;border-radius:13px;display:grid;place-items:center;color:var(--tc);background:rgba(241,248,233,.06);font-size:1.05rem}
.fm-tile b{font-size:1.7rem;line-height:1;font-weight:800}.fm-tile span{font-size:.8rem;color:var(--sage);margin-top:3px}
.fm-tile:hover{transform:translateY(-3px);border-color:var(--tc)}
.fm-tile.is-on{border-color:var(--tc);background:linear-gradient(135deg,rgba(241,248,233,.07),var(--card))}
.fm-t-overdue{--tc:var(--c-overdue)}.fm-t-week{--tc:var(--c-soon)}.fm-t-upcoming{--tc:var(--c-upcoming)}.fm-t-all{--tc:var(--gold2)}

/* timeline */
.fm-timeline{margin-bottom:26px;padding:20px 22px 12px;border-radius:20px;border:1px solid var(--line);background:linear-gradient(160deg,rgba(241,248,233,.04),rgba(241,248,233,.012))}
.fm-tl-head{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:6px}
.fm-tl-head h3{margin:0;font-size:.9rem;font-weight:700;display:flex;gap:9px;align-items:center}.fm-tl-head h3 i{color:var(--gold)}
.fm-tl-head span{font-size:.78rem;color:var(--dim)}
.fm-tl-scroll{overflow-x:auto;padding-bottom:8px}
.fm-tl{position:relative;height:150px;min-width:640px;margin:0 14px}
.fm-tl-axis{position:absolute;left:0;right:0;top:75px;height:3px;border-radius:3px;background:rgba(241,248,233,.16)}
.fm-tl-zone{position:absolute;top:30px;bottom:30px;border-radius:12px;background:rgba(239,140,124,.07);border:1px dashed rgba(239,140,124,.25)}
.fm-tl-zone span{position:absolute;left:10px;top:6px;font-size:.66rem;letter-spacing:.08em;text-transform:uppercase;color:var(--c-overdue);font-weight:700}
.fm-tl-today{position:absolute;top:22px;bottom:22px;width:2px;background:var(--gold2);opacity:.8}
.fm-tl-today span{position:absolute;bottom:-18px;left:50%;transform:translateX(-50%);font-size:.68rem;font-weight:800;color:var(--gold2);letter-spacing:.04em}
.fm-tl-tick{position:absolute;top:84px;transform:translateX(-50%);font-size:.7rem;color:var(--dim);white-space:nowrap}
.fm-tl-tick:before{content:"";position:absolute;left:50%;top:-9px;width:1px;height:6px;background:rgba(241,248,233,.25)}
.fm-dot{--dc:var(--c-none);position:absolute;width:20px;height:20px;margin:-10px 0 0 -10px;border-radius:50%;border:3px solid #0b2217;background:var(--dc);cursor:pointer;padding:0;transition:transform .15s ease,box-shadow .15s ease;box-shadow:0 0 0 1px rgba(255,255,255,.2)}
.fm-dot:hover,.fm-dot:focus-visible{transform:scale(1.35);box-shadow:0 0 0 4px rgba(241,248,233,.15);outline:none;z-index:5}
.fm-dot.fm-s-overdue{--dc:var(--c-overdue)}.fm-dot.fm-s-today{--dc:var(--c-today)}.fm-dot.fm-s-soon{--dc:var(--c-soon)}.fm-dot.fm-s-upcoming{--dc:var(--c-upcoming)}
.fm-tip{position:absolute;z-index:9;min-width:170px;max-width:230px;padding:10px 12px;border-radius:12px;background:#0a2015;border:1px solid var(--line2);box-shadow:0 14px 30px rgba(0,0,0,.45);font-size:.78rem;line-height:1.5;pointer-events:none;opacity:0;transform:translate(-50%,6px);transition:opacity .12s ease,transform .12s ease}
.fm-tip.on{opacity:1;transform:translate(-50%,0)}
.fm-tip b{display:block;font-size:.84rem}.fm-tip span{color:var(--sage)}

/* main layout */
.fm-main{display:grid;grid-template-columns:minmax(0,1.9fr) minmax(290px,1fr);gap:22px;align-items:start}
.fm-side{position:sticky;top:20px;display:grid;gap:16px}
.fm-widget{padding:20px;border-radius:20px;border:1px solid var(--line);background:linear-gradient(160deg,rgba(241,248,233,.05),rgba(241,248,233,.015))}
.fm-widget-h{display:flex;align-items:center;gap:9px;font-weight:700;font-size:.92rem;margin-bottom:6px}.fm-widget-h i{color:var(--gold)}
.fm-widget-p{margin:0 0 14px;font-size:.8rem;color:var(--dim);line-height:1.55}
.fm-calc-row{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:12px}
.fm-calc-out{margin:14px 0;padding:14px;border-radius:14px;border:1px solid rgba(217,162,75,.25);background:rgba(217,162,75,.07)}
.fm-calc-v{font-size:1.3rem;font-weight:800;color:var(--gold2);overflow-wrap:anywhere}
.fm-calc-n{margin-top:4px;font-size:.74rem;line-height:1.55;color:var(--dim)}
.fm-mix{display:flex;align-items:center;gap:16px;margin-top:12px}
.fm-donut{position:relative;width:118px;height:118px;flex:none}
.fm-donut svg{width:100%;height:100%;transform:rotate(-90deg)}
.fm-donut circle{fill:none;stroke-width:13;transition:stroke-width .15s ease,opacity .15s ease}
.fm-donut circle.dim{opacity:.35}.fm-donut circle.hot{stroke-width:17}
.fm-donut-mid{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center}
.fm-donut-mid b{font-family:"Fraunces",Georgia,serif;font-size:1.7rem;font-weight:500;line-height:1}.fm-donut-mid small{font-size:.66rem;color:var(--dim)}
.fm-legend{list-style:none;margin:0;padding:0;display:grid;gap:8px;flex:1;min-width:0}
.fm-legend li{display:flex;align-items:center;gap:9px;padding:6px 8px;border-radius:9px;font-size:.8rem;cursor:default;transition:background .15s ease}
.fm-legend li:hover{background:rgba(241,248,233,.07)}
.fm-legend i{width:10px;height:10px;border-radius:3px;flex:none}.fm-legend b{margin-left:auto}
.fm-last{margin-top:14px;padding-top:12px;border-top:1px solid var(--line);font-size:.8rem;color:var(--sage);line-height:1.5}

/* toolbar + list */
.fm-toolbar{display:flex;gap:12px;flex-wrap:wrap;align-items:center}
.fm-search{position:relative;flex:1 1 220px}
.fm-search i{position:absolute;left:15px;top:50%;transform:translateY(-50%);color:var(--dim);pointer-events:none;font-size:.85rem}
.fm-search input{width:100%;padding:12px 14px 12px 40px;border-radius:12px;border:1px solid var(--line2);background:#091f15;color:var(--cream);outline:none;font-size:.92rem;transition:border-color .2s ease,box-shadow .2s ease}
.fm-search input:focus{border-color:var(--gold);box-shadow:0 0 0 3px rgba(217,162,75,.14)}
.fm-search input::placeholder{color:rgba(220,235,220,.4)}
.fm-sort{display:flex;align-items:center;gap:8px;font-size:.82rem;color:var(--dim)}
.fm-sort select{padding:11px 12px;border-radius:12px;border:1px solid var(--line2);background:#091f15;color:var(--cream);outline:none;font-size:.88rem}.fm-sort select:focus{border-color:var(--gold)}
.fm-count{margin:14px 2px 12px;font-size:.82rem;color:var(--dim)}
.fm-list{display:grid;gap:14px}
.fm-item{--ac:var(--c-none);position:relative;padding:20px 22px 18px 26px;border-radius:18px;border:1px solid var(--line);background:linear-gradient(135deg,rgba(18,56,34,.55),rgba(9,31,21,.85));transition:transform .2s ease,border-color .2s ease,box-shadow .2s ease;animation:fmIn .4s ease both}
.fm-item:before{content:"";position:absolute;left:0;top:16px;bottom:16px;width:4px;border-radius:0 4px 4px 0;background:var(--ac)}
.fm-item.fm-s-overdue{--ac:var(--c-overdue)}.fm-item.fm-s-today{--ac:var(--c-today)}.fm-item.fm-s-soon{--ac:var(--c-soon)}.fm-item.fm-s-upcoming{--ac:var(--c-upcoming)}
.fm-item:hover{transform:translateY(-2px);border-color:rgba(129,199,132,.3);box-shadow:0 14px 30px rgba(0,0,0,.22)}
.fm-item[hidden]{display:none}
.fm-item.is-flash{animation:fmPulse 1.6s ease 2;border-color:var(--gold)}
@keyframes fmPulse{0%,100%{box-shadow:0 0 0 0 rgba(217,162,75,0)}50%{box-shadow:0 0 0 8px rgba(217,162,75,.22)}}
.fm-new{display:inline-block;margin-left:8px;padding:2px 9px;border-radius:99px;background:var(--gold);color:#241708;font-size:.66rem;font-weight:800;vertical-align:middle}
.fm-item-top{display:flex;align-items:center;justify-content:space-between;gap:12px}
.fm-item-name{display:flex;align-items:center;gap:12px;min-width:0}
.fm-item-ic{width:42px;height:42px;flex:none;border-radius:13px;display:grid;place-items:center;color:var(--gold2);background:rgba(129,199,132,.1);border:1px solid rgba(129,199,132,.14)}
.fm-item-name b{display:block;font-size:1.02rem;overflow-wrap:anywhere}.fm-item-name small{color:var(--dim);font-size:.8rem}
.fm-facts{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px;margin-top:16px}
.fm-facts small{display:block;color:var(--dim);font-size:.74rem;margin-bottom:3px}.fm-facts b{font-size:.95rem;color:#e3efe3}
.fm-facts em{display:block;font-style:normal;font-size:.76rem;color:var(--ac);margin-top:2px;font-weight:600}
.fm-track{height:5px;border-radius:9px;background:rgba(241,248,233,.08);margin-top:16px;overflow:hidden}.fm-track i{display:block;height:100%;border-radius:9px;background:var(--ac)}
.fm-item-foot{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-top:14px;flex-wrap:wrap}
.fm-note{font-size:.82rem;color:var(--dim);line-height:1.5;flex:1 1 220px}.fm-note i{margin-right:5px}
.fm-item-foot .fm-btn{margin-left:auto}

.fm-empty{text-align:center;padding:56px 20px;border-radius:20px;border:1px dashed var(--line2);background:rgba(241,248,233,.02)}
.fm-empty[hidden]{display:none}
.fm-empty i{font-size:2.4rem;color:rgba(159,190,166,.5);margin-bottom:14px}
.fm-empty h3{font-family:"Fraunces",Georgia,serif;font-weight:500;font-size:1.3rem;margin:0 0 8px}
.fm-empty p{color:var(--dim);max-width:420px;margin:0 auto 20px;line-height:1.7;font-size:.92rem}

/* FAB */
.fm-fab{display:none;position:fixed;right:18px;bottom:20px;z-index:40;width:58px;height:58px;border:0;border-radius:50%;background:linear-gradient(135deg,#e6b25c,#cb8a38);color:#241708;font-size:1.3rem;box-shadow:0 12px 28px rgba(0,0,0,.4);cursor:pointer}

/* drawer */
.fm-drawer{position:fixed;inset:0;z-index:1060;visibility:hidden}
.fm-drawer.is-open{visibility:visible}
.fm-backdrop{position:absolute;inset:0;background:rgba(3,12,8,.65);backdrop-filter:blur(3px);opacity:0;transition:opacity .25s ease}
.fm-drawer.is-open .fm-backdrop{opacity:1}
.fm-panel{position:absolute;top:0;right:0;bottom:0;width:min(580px,100%);background:#0b2217;border-left:1px solid var(--line2);box-shadow:-20px 0 60px rgba(0,0,0,.45);transform:translateX(100%);transition:transform .3s cubic-bezier(.2,.8,.2,1)}
.fm-drawer.is-open .fm-panel{transform:none}
.fm-form{display:flex;flex-direction:column;height:100%}
.fm-panel-head{display:flex;justify-content:space-between;align-items:flex-start;gap:12px;padding:22px 26px;border-bottom:1px solid var(--line)}
.fm-panel-head h2{font-family:"Fraunces",Georgia,serif;font-weight:500;font-size:1.45rem;margin:0}.fm-panel-head p{margin:4px 0 0;color:var(--dim);font-size:.85rem}
.fm-x{width:38px;height:38px;border-radius:50%;border:1px solid var(--line2);background:rgba(241,248,233,.05);color:var(--cream);font-size:1.4rem;line-height:1;cursor:pointer;flex:none}.fm-x:hover{background:rgba(241,248,233,.12)}
.fm-panel-body{flex:1;overflow-y:auto;padding:22px 26px}
.fm-panel-foot{display:flex;gap:10px;justify-content:flex-end;padding:16px 26px;border-top:1px solid var(--line);background:#0a2015}
.fm-errors{margin-bottom:18px;padding:14px 16px;border-radius:13px;background:rgba(198,40,40,.1);border:1px solid rgba(239,140,124,.28);color:#f3b2aa;font-size:.88rem}.fm-errors ul{margin:8px 0 0;padding-left:20px}
.fm-quick{display:flex;gap:12px;align-items:flex-start;margin-bottom:20px;padding:14px 16px;border-radius:14px;background:rgba(76,175,80,.12);border:1px solid rgba(129,199,132,.3);animation:fmIn .3s ease}
.fm-quick[hidden]{display:none}
.fm-quick>i{color:var(--c-upcoming);font-size:1.2rem;margin-top:2px}
.fm-quick b{display:block;font-size:.92rem}.fm-quick span{display:block;margin-top:3px;font-size:.82rem;line-height:1.55;color:rgba(224,237,225,.8)}

.fm-step+.fm-step{margin-top:26px;padding-top:24px;border-top:1px solid var(--line)}
.fm-step-h{display:flex;align-items:center;gap:11px;font-weight:700;font-size:1rem;margin-bottom:14px}
.fm-step-h span{width:26px;height:26px;border-radius:50%;display:grid;place-items:center;background:var(--gold);color:#241708;font-size:.8rem;font-weight:800}
.fm-pick-tools{display:grid;gap:10px;margin-bottom:12px}
.fm-search-sm input{padding:10px 14px 10px 38px}
.fm-cats{display:flex;gap:8px;flex-wrap:wrap}
.fm-cat,.fm-chip{border:1px solid var(--line2);background:rgba(241,248,233,.04);color:var(--sage);font-size:.8rem;font-weight:600;padding:6px 14px;border-radius:99px;cursor:pointer;transition:background .15s ease,color .15s ease,border-color .15s ease}
.fm-cat:hover,.fm-chip:hover{border-color:var(--gold);color:var(--cream)}
.fm-cat.is-on{background:var(--gold);border-color:var(--gold);color:#241708}
.fm-chip{border-color:rgba(217,162,75,.4);color:var(--gold2);background:rgba(217,162,75,.07)}.fm-chip:hover{background:var(--gold);color:#241708}
.fm-chip-ghost{border-color:var(--line2);color:var(--sage);background:transparent}

.fm-fgrid{display:grid;grid-template-columns:1fr 1fr;gap:10px;max-height:260px;overflow-y:auto;padding:2px}
.fm-fcard{display:block;cursor:pointer;min-width:0}
.fm-fcard[hidden]{display:none}
.fm-fcard input{position:absolute;opacity:0;pointer-events:none}
.fm-fcard-in{position:relative;display:flex;align-items:center;gap:11px;height:100%;padding:12px;border-radius:14px;border:1px solid var(--line);background:rgba(241,248,233,.03);transition:border-color .15s ease,background .15s ease,transform .15s ease}
.fm-fcard:hover .fm-fcard-in{border-color:rgba(217,162,75,.5);transform:translateY(-1px)}
.fm-fcard input:focus-visible+.fm-fcard-in{outline:2px solid var(--gold2);outline-offset:2px}
.fm-fcard input:checked+.fm-fcard-in{border-color:var(--gold);background:rgba(217,162,75,.1)}
.fm-fcard-ic{width:36px;height:36px;flex:none;border-radius:11px;display:grid;place-items:center;color:var(--gold2);background:rgba(129,199,132,.1);font-size:.9rem}
.fm-fcard-tx{min-width:0;display:block}.fm-fcard-tx b{display:block;font-size:.88rem;overflow-wrap:anywhere}.fm-fcard-tx small{display:block;color:var(--dim);font-size:.72rem;margin-top:2px;overflow-wrap:anywhere}
.fm-tick{position:absolute;top:8px;right:8px;color:var(--gold2);font-size:.95rem;opacity:0;transform:scale(.5);transition:.2s}
.fm-fcard input:checked+.fm-fcard-in .fm-tick{opacity:1;transform:none}
.fm-fnone{grid-column:1/-1;text-align:center;color:var(--dim);padding:18px;font-size:.88rem}

.fm-info{margin-top:14px;padding:16px;border-radius:15px;border:1px solid rgba(129,199,132,.22);background:linear-gradient(135deg,rgba(76,175,80,.08),rgba(18,56,34,.7));animation:fmIn .3s ease}
.fm-info[hidden],.fm-suggest[hidden]{display:none}
.fm-info-t{color:var(--gold2);font-weight:700;font-size:.88rem;margin-bottom:8px}
.fm-info p{margin:0 0 10px;color:rgba(224,237,225,.78);font-size:.86rem;line-height:1.65}
.fm-dose{display:flex;flex-wrap:wrap;gap:6px}
.fm-dose span{display:inline-flex;gap:5px;align-items:center;padding:6px 10px;border-radius:9px;background:rgba(7,25,16,.7);border:1px solid var(--line);font-size:.78rem;color:#cbdcca}.fm-dose strong{color:var(--gold2)}
.fm-suggest{margin-top:12px;padding:13px 14px;border-radius:12px;border:1px solid rgba(217,162,75,.25);background:rgba(217,162,75,.07)}
.fm-suggest-t{font-weight:700;font-size:.82rem;color:var(--gold2);margin-bottom:4px}
.fm-suggest-v{font-size:1.25rem;font-weight:800;color:var(--gold2)}
.fm-suggest-n{margin:4px 0 10px;font-size:.76rem;line-height:1.55;color:var(--dim)}

.fm-qty-row{display:flex;gap:12px;flex-wrap:wrap;align-items:stretch}
.fm-input{width:100%;padding:12px 14px;border-radius:12px;border:1px solid var(--line2);background:#091f15;color:var(--cream);outline:none;font-size:.95rem;min-height:46px;color-scheme:dark;transition:border-color .2s ease,box-shadow .2s ease}
.fm-input:focus{border-color:var(--gold);box-shadow:0 0 0 3px rgba(217,162,75,.14)}
.fm-input::placeholder{color:rgba(220,235,220,.38)}
select.fm-input option{background:#0b2418;color:var(--cream)}
textarea.fm-input{resize:vertical}
.fm-input-lg{flex:1 1 150px;width:auto;font-family:"Fraunces",Georgia,serif;font-size:1.7rem;font-weight:500;padding:10px 16px;min-width:0}
.fm-units{display:inline-flex;padding:4px;gap:2px;border-radius:12px;background:#123822;border:1px solid var(--line)}
.fm-unit{display:block;cursor:pointer}.fm-unit input{position:absolute;opacity:0;pointer-events:none}
.fm-unit span{display:grid;place-items:center;min-width:48px;height:100%;min-height:38px;padding:0 10px;border-radius:9px;font-weight:700;font-size:.9rem;color:var(--sage);transition:background .15s ease,color .15s ease}
.fm-unit:hover span{color:var(--cream)}
.fm-unit input:checked+span{background:var(--gold);color:#241708}
.fm-unit input:focus-visible+span{outline:2px solid var(--gold2)}
.fm-hint{margin:10px 0 0;font-size:.8rem;color:var(--dim);line-height:1.5}
.fm-hint.is-warn{color:var(--c-today)}
.fm-dates{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.fm-lbl{display:block;margin-bottom:7px;font-size:.84rem;font-weight:600;color:#dcebdc}.fm-lbl em{font-style:normal;font-weight:400;color:var(--dim)}
.fm-lbl-notes{margin-top:18px}
.fm-chips{display:flex;flex-wrap:wrap;gap:8px;margin-top:12px}

/* responsive */
@media(max-width:960px){
  .fm-top{grid-template-columns:1fr}
  .fm-tiles{grid-template-columns:repeat(2,minmax(0,1fr))}
  .fm-main{grid-template-columns:1fr}
  .fm-side{position:static;grid-template-columns:1fr 1fr}
}
@media(max-width:768px){
  .fm-page{padding:28px 0 100px}.fm-wrap{padding:0 16px}
  .fm-head .fm-btn-primary{display:none}
  .fm-fab{display:grid;place-items:center}
  .fm-next{flex-direction:column;align-items:flex-start;gap:20px;padding:24px 22px}
  .fm-facts{grid-template-columns:1fr 1fr}.fm-facts div:last-child{grid-column:1/-1}
  .fm-item{padding:18px 16px 16px 20px}
  .fm-side{grid-template-columns:1fr}
}
@media(max-width:560px){
  .fm-tiles{gap:10px}.fm-tile{padding:14px;grid-template-columns:1fr;row-gap:6px}.fm-tile i{grid-row:auto;width:38px;height:38px}
  .fm-panel{width:100%}.fm-panel-head,.fm-panel-body,.fm-panel-foot{padding-left:18px;padding-right:18px}
  .fm-fgrid{grid-template-columns:1fr}.fm-dates{grid-template-columns:1fr}
  .fm-panel-foot .fm-btn{flex:1}
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const $  = (s, r = document) => r.querySelector(s);
    const $$ = (s, r = document) => Array.from(r.querySelectorAll(s));
    const esc = s => String(s).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    const fmtNum = v => Number.isFinite(v) ? new Intl.NumberFormat('en-IN', { maximumFractionDigits: 2 }).format(v) : '';

    /* ================= Field details (from the server) ================= */
    const fieldArea  = @json($fieldArea);
    const fieldAreaUnit = @json($fieldUnit);
    const fieldPlantCount = @json($fieldPlants);

    /* ================= Reminder ring + flash ================= */
    $$('.fm-ring .fg').forEach(c => setTimeout(() => { c.style.strokeDashoffset = 100 - (Number(c.dataset.pct) || 0); }, 250));
    const flash = $('.fm-flash'), flashX = $('.fm-flash-x'); if (flashX) flashX.onclick = () => flash.remove();

    /* ================= Filter / search / sort ================= */
    const list = $('#fm-list'), items = $$('.fm-item'), search = $('#fm-search'), sortSel = $('#fm-sort');
    const countEl = $('#fm-count'), noMatch = $('#fm-nomatch'), tiles = $$('.fm-tile');
    let filter = 'all';

    function matchesFilter(it) {
        const s = it.dataset.status;
        if (filter === 'all') return true;
        if (filter === 'week') return s === 'today' || s === 'soon';
        return s === filter;
    }
    function applyList() {
        const q = (search.value || '').trim().toLowerCase();
        let shown = 0;
        items.forEach(it => {
            const ok = matchesFilter(it) && (!q || it.dataset.search.toLowerCase().includes(q));
            it.hidden = !ok; if (ok) shown++;
        });
        if (items.length) {
            countEl.textContent = 'Showing ' + shown + ' of ' + items.length + ' application' + (items.length === 1 ? '' : 's');
            noMatch.hidden = shown !== 0;
        } else { countEl.textContent = ''; }
    }
    function sortList() {
        const mode = sortSel.value;
        const key = it => it.dataset.days === '' ? Infinity : Number(it.dataset.days);
        items.slice().sort((a, b) => {
            if (mode === 'recent') return Number(b.dataset.applied) - Number(a.dataset.applied);
            if (mode === 'name') return a.dataset.name.localeCompare(b.dataset.name);
            return key(a) - key(b);
        }).forEach(it => list.appendChild(it));
    }
    function setFilter(f) {
        filter = f;
        tiles.forEach(t => t.classList.toggle('is-on', t.dataset.filter === f));
        applyList();
    }
    tiles.forEach(t => t.addEventListener('click', () => setFilter(filter === t.dataset.filter && t.dataset.filter !== 'all' ? 'all' : t.dataset.filter)));
    search.addEventListener('input', applyList);
    sortSel.addEventListener('change', sortList);
    $('#fm-reset').addEventListener('click', () => { search.value = ''; setFilter('all'); });
    sortList(); applyList();

    function jumpTo(it) {
        search.value = ''; setFilter('all');
        it.scrollIntoView({ behavior: 'smooth', block: 'center' });
        it.classList.remove('is-flash'); void it.offsetWidth; it.classList.add('is-flash');
        setTimeout(() => it.classList.remove('is-flash'), 3400);
    }

    /* ================= Timeline ================= */
    const tl = $('#fm-tl');
    if (tl) {
        const dated = items.filter(i => i.dataset.days !== '');
        const days = dated.map(i => Number(i.dataset.days));
        const start = Math.max(-30, Math.min(-7, Math.min.apply(null, days))), end = 60;
        const pos = d => (Math.max(start, Math.min(end, d)) - start) / (end - start) * 100;
        const todayPos = pos(0);
        let h = '<div class="fm-tl-axis"></div>';
        h += '<div class="fm-tl-zone" style="left:0;width:' + todayPos + '%"><span>Overdue</span></div>';
        h += '<div class="fm-tl-today" style="left:' + todayPos + '%"><span>Today</span></div>';
        [14, 28, 42, 56].forEach(n => {
            const dt = new Date(); dt.setDate(dt.getDate() + n);
            h += '<div class="fm-tl-tick" style="left:' + pos(n) + '%">' + dt.toLocaleDateString('en-GB', { day: 'numeric', month: 'short' }) + '</div>';
        });
        tl.innerHTML = h;
        const lanes = [-999, -999, -999], offs = [0, -34, 34];
        dated.map(it => ({ it, p: pos(Number(it.dataset.days)) })).sort((a, b) => a.p - b.p).forEach(o => {
            let lane = lanes.findIndex(l => o.p - l > 4); if (lane < 0) lane = 0; lanes[lane] = o.p;
            const b = document.createElement('button');
            b.type = 'button'; b.className = 'fm-dot fm-s-' + o.it.dataset.status;
            b.style.left = o.p + '%'; b.style.top = (75 + offs[lane]) + 'px';
            b.setAttribute('aria-label', o.it.dataset.name + ', ' + o.it.dataset.label + ', ' + o.it.dataset.next);
            b.addEventListener('mouseenter', () => tip(b, o.it)); b.addEventListener('focus', () => tip(b, o.it));
            b.addEventListener('mouseleave', hideTip); b.addEventListener('blur', hideTip);
            b.addEventListener('click', () => jumpTo(o.it));
            tl.appendChild(b);
        });
        const tipEl = document.createElement('div'); tipEl.className = 'fm-tip'; tl.appendChild(tipEl);
        function tip(b, it) {
            tipEl.innerHTML = '<b>' + esc(it.dataset.name) + '</b><span>' + esc(it.dataset.label) + ' &middot; ' + esc(it.dataset.rel) + '<br>' + esc(it.dataset.next) + '</span>';
            tipEl.style.left = Math.max(95, Math.min(tl.clientWidth - 95, b.offsetLeft)) + 'px';
            tipEl.style.top = (b.offsetTop - 74) + 'px';
            tipEl.classList.add('on');
        }
        function hideTip() { tipEl.classList.remove('on'); }
    }

    /* ================= Fertilizer mix donut ================= */
    const donut = $('#fm-donut');
    if (donut) {
        const counts = {}; items.forEach(i => { const c = i.dataset.cat || 'Other'; counts[c] = (counts[c] || 0) + 1; });
        const names = Object.keys(counts), total = items.length;
        const colors = { Organic: '#8bd19a', Chemical: '#d9a24b' }, extra = ['#9fbea6', '#ef8c7c', '#aebec5'];
        let cum = 0, xi = 0, svg = '<circle cx="50" cy="50" r="38" stroke="rgba(241,248,233,.08)"/>';
        const lg = [];
        names.forEach(n => {
            const col = colors[n] || extra[xi++ % 3], p = counts[n] / total * 100;
            svg += '<circle data-n="' + esc(n) + '" cx="50" cy="50" r="38" pathLength="100" stroke="' + col + '" stroke-dasharray="' + Math.max(p - 1, 0.5) + ' ' + (100 - Math.max(p - 1, 0.5)) + '" stroke-dashoffset="' + (-cum) + '"/>';
            cum += p;
            lg.push('<li data-n="' + esc(n) + '"><i style="background:' + col + '"></i>' + esc(n) + '<b>' + counts[n] + '</b></li>');
        });
        donut.innerHTML = svg; $('#fm-legend').innerHTML = lg.join(''); $('#fm-donut-n').textContent = total;
        $$('#fm-legend li').forEach(li => {
            li.addEventListener('mouseenter', () => $$('#fm-donut circle[data-n]').forEach(c => { const on = c.dataset.n === li.dataset.n; c.classList.toggle('hot', on); c.classList.toggle('dim', !on); }));
            li.addEventListener('mouseleave', () => $$('#fm-donut circle').forEach(c => c.classList.remove('hot', 'dim')));
        });
        const latest = items.slice().sort((a, b) => Number(b.dataset.applied) - Number(a.dataset.applied))[0];
        if (latest) {
            const ago = Math.max(0, Math.round((Date.now() - Number(latest.dataset.applied) * 1000) / 86400000));
            $('#fm-last').innerHTML = 'Last applied: <b>' + esc(latest.dataset.name) + '</b> &middot; ' + (ago === 0 ? 'today' : ago === 1 ? 'yesterday' : ago + ' days ago');
        }
    }

    /* ================= Drawer ================= */
    const drawer = $('#fm-drawer'), form = $('#fm-form');
    const quick = $('#fm-quick'), quickT = $('#fm-quick-t'), quickS = $('#fm-quick-s');
    const sb = $('#fm-submit'), st = $('#fm-submit-text');
    let lastFocus = null, submitLabel = 'Record application';

    function setSubmitLabel(t) { submitLabel = t; st.textContent = t; }

    function openDrawer(fid, o) {
        o = o || {};
        lastFocus = document.activeElement;
        drawer.classList.add('is-open'); drawer.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        if (fid) pickFertilizer(String(fid));

        quick.hidden = true; setSubmitLabel('Record application');

        if (o.quick) {
            if (o.qty) { qty.value = o.qty; }
            if (o.unit) setQtyUnit(o.unit);
            const t = new Date(), n = new Date(); n.setDate(n.getDate() + (Number(o.interval) || 30));
            applied.value = iso(t); next.value = iso(n); nextHintUpdate();
            if (o.cid) {
                quickT.textContent = 'Marking this reminder as done';
                quickS.textContent = o.fname + (o.due ? ' was due on ' + o.due + '. ' : '. ') + 'We filled in your last amount and the same gap until the next reminder. Check the details and confirm.';
                setSubmitLabel('Confirm application');
            } else {
                quickT.textContent = 'Repeating ' + o.fname;
                quickS.textContent = 'Based on your application on ' + o.applied + '. Change anything you need, then save.';
            }
            quick.hidden = false;
        } else if (o.qty) {
            qty.value = o.qty; if (o.unit) setQtyUnit(o.unit);
        }
        setTimeout(() => { const t = $('#fm-fsearch'); if (t && !fid) t.focus(); }, 300);
    }
    function closeDrawer() {
        drawer.classList.remove('is-open'); drawer.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        if (lastFocus && lastFocus.focus) lastFocus.focus();
    }
    document.addEventListener('click', e => {
        const o = e.target.closest('[data-open]');
        if (o) {
            e.preventDefault(); const d = o.dataset;
            openDrawer(d.fid || '', { quick: d.quick === '1', cid: d.cid, fname: d.fname, due: d.due, applied: d.applied, qty: d.qty, unit: d.unit, interval: d.interval });
            return;
        }
        if (e.target.closest('[data-close]')) closeDrawer();
    });
    document.addEventListener('keydown', e => { if (e.key === 'Escape' && drawer.classList.contains('is-open')) closeDrawer(); });

    /* ================= Fertilizer picker ================= */
    const radios = $$('input[name="fertilizer_id"]'), cards = $$('.fm-fcard'), fnone = $('#fm-fnone');
    const fsearch = $('#fm-fsearch'), cats = $$('.fm-cat');
    let catFilter = '';

    function filterCards() {
        const q = (fsearch.value || '').trim().toLowerCase(); let n = 0;
        cards.forEach(c => {
            const ok = (!catFilter || c.dataset.cat === catFilter) && (!q || c.dataset.name.toLowerCase().includes(q));
            c.hidden = !ok; if (ok) n++;
        });
        fnone.hidden = n !== 0;
    }
    fsearch.addEventListener('input', filterCards);
    cats.forEach(b => b.addEventListener('click', () => {
        catFilter = b.dataset.cat; cats.forEach(x => x.classList.toggle('is-on', x === b)); filterCards();
    }));
    function pickFertilizer(id) {
        const r = radios.find(x => x.value === id); if (!r) return;
        r.checked = true; r.dispatchEvent(new Event('change', { bubbles: true }));
        const card = r.closest('.fm-fcard'); if (card) setTimeout(() => card.scrollIntoView({ block: 'nearest', behavior: 'smooth' }), 320);
    }

    /* ---- suggested quantity ---- */
    const info = $('#fm-info'), infoDesc = $('#fm-info-desc'), infoDose = $('#fm-info-dose');
    const sBox = $('#fm-suggest'), sVal = $('#fm-suggest-v'), sNote = $('#fm-suggest-n'), useBtn = $('#fm-use');
    const qty = $('#fm-qty'), unitRadios = $$('input[name="quantity_unit"]');
    let suggestion = null;
    function setQtyUnit(u) { unitRadios.forEach(x => { x.checked = (x.value === u); }); }

    function acres() {
        if (fieldArea === null || fieldArea === undefined || !Number.isFinite(Number(fieldArea))) return null;
        const a = Number(fieldArea);
        return fieldAreaUnit === 'acre' ? a : (fieldAreaUnit === 'cent' ? a / 100 : null);
    }
    /* shared by the drawer and the sidebar calculator */
    function calc(d, du, r, ru, plants, ac) {
        d = Number(d); r = Number(r);
        if (!Number.isFinite(d) || d <= 0 || !Number.isFinite(r) || r <= 0) return null;
        if (ru === 'plant') {
            if (!Number.isFinite(Number(plants)) || plants === null || plants === '') return null;
            return { value: d * (Number(plants) / r), note: 'Based on ' + fmtNum(Number(plants)) + ' plants at ' + fmtNum(d) + ' ' + du + ' per ' + fmtNum(r) + ' plant.' };
        }
        if (ru === 'acre') {
            if (ac === null || ac === '' || !Number.isFinite(Number(ac))) return null;
            return { value: d * (Number(ac) / r), note: 'Based on ' + fmtNum(Number(ac)) + ' acre(s) at ' + fmtNum(d) + ' ' + du + ' per ' + fmtNum(r) + ' acre.' };
        }
        if (ru === 'ha') {
            if (ac === null || ac === '' || !Number.isFinite(Number(ac))) return null;
            const h = Number(ac) * 0.40468564224;
            return { value: d * (h / r), note: 'Based on ' + fmtNum(h) + ' hectare(s) at ' + fmtNum(d) + ' ' + du + ' per ' + fmtNum(r) + ' hectare.' };
        }
        return null;
    }
    function showFertilizer() {
        const r = radios.find(x => x.checked);
        suggestion = null; sBox.hidden = true; useBtn.hidden = true;
        if (!r) { info.hidden = true; return; }
        const d = r.dataset, unit = d.unit || '';
        infoDesc.textContent = d.desc || '';
        infoDesc.style.display = d.desc ? '' : 'none';
        let h = '';
        if (d.dosage) h += '<span><i class="fa-solid fa-scale-balanced"></i> Recommended: <strong>' + esc(d.dosage) + ' ' + esc(unit) + '</strong></span>';
        if (d.reference && d.referenceUnit) h += '<span><i class="fa-solid fa-ruler-combined"></i> Reference: <strong>' + esc(d.dosage) + ' ' + esc(unit) + ' / ' + esc(d.reference) + ' ' + esc(d.referenceUnit) + '</strong></span>';
        h += '<span><i class="fa-solid ' + (d.category === 'Organic' ? 'fa-leaf' : 'fa-flask') + '"></i> Type: <strong>' + esc(d.category || 'Not set') + '</strong></span>';
        infoDose.innerHTML = h;
        info.hidden = false;

        if (['g', 'kg', 'ml', 'L'].includes(unit)) setQtyUnit(unit);

        const c = calc(d.dosage, unit, d.reference, d.referenceUnit, fieldPlantCount, acres());
        if (c) {
            suggestion = { value: c.value, unit };
            sVal.textContent = fmtNum(c.value) + ' ' + unit;
            sNote.textContent = c.note + ' This is an estimate based on the saved field details and administrator-defined dosage.';
            sBox.hidden = false; useBtn.hidden = false;
        } else if (d.referenceUnit === 'L' || d.referenceUnit === 'ml') {
            sVal.textContent = 'Manual quantity required';
            sNote.textContent = 'This fertilizer is registered as ' + fmtNum(Number(d.dosage)) + ' ' + unit + ' per ' + fmtNum(Number(d.reference)) + ' ' + d.referenceUnit + '. Area and plant count alone are not enough to calculate a spray quantity; water volume is also required.';
            sBox.hidden = false;
        }
    }
    radios.forEach(r => r.addEventListener('change', showFertilizer));
    useBtn.addEventListener('click', () => {
        if (!suggestion) return;
        qty.value = Math.round(suggestion.value * 100) / 100;
        setQtyUnit(suggestion.unit); qty.focus();
    });

    /* ================= Sidebar calculator ================= */
    const cF = $('#fm-calc-f'), cP = $('#fm-calc-plants'), cA = $('#fm-calc-acres'), cV = $('#fm-calc-v'), cN = $('#fm-calc-n'), cUse = $('#fm-calc-use');
    let cRes = null;
    function runCalc() {
        cRes = null; cUse.disabled = true;
        const o = cF.options[cF.selectedIndex];
        if (!o || !o.value) { cV.textContent = 'Choose a fertilizer'; cN.textContent = 'The result appears here.'; return; }
        const d = o.dataset, unit = d.unit || '';
        const c = calc(d.dosage, unit, d.reference, d.referenceUnit, cP.value, cA.value);
        if (c) {
            cRes = { fid: o.value, value: Math.round(c.value * 100) / 100, unit };
            cV.textContent = fmtNum(c.value) + ' ' + unit; cN.textContent = c.note; cUse.disabled = false;
        } else if (d.referenceUnit === 'L' || d.referenceUnit === 'ml') {
            cV.textContent = 'Manual quantity required';
            cN.textContent = 'This dosage is per ' + fmtNum(Number(d.reference)) + ' ' + d.referenceUnit + ' of water, so the amount depends on the water you use.';
        } else if (!d.dosage || !d.reference) {
            cV.textContent = 'No dosage set'; cN.textContent = 'Ask your administrator to add a dosage for this fertilizer.';
        } else {
            cV.textContent = 'Enter ' + (d.referenceUnit === 'plant' ? 'plants' : 'acres'); cN.textContent = 'This fertilizer is measured per ' + d.referenceUnit + '.';
        }
    }
    [cF, cP, cA].forEach(x => x.addEventListener('input', runCalc));
    cF.addEventListener('change', runCalc);
    cUse.addEventListener('click', () => { if (cRes) openDrawer(cRes.fid, { qty: cRes.value, unit: cRes.unit }); });
    runCalc();

    /* ================= Dates ================= */
    const applied = $('#fm-applied'), next = $('#fm-next'), nextHint = $('#fm-next-hint');
    const pad = n => String(n).padStart(2, '0');
    const iso = d => d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate());
    function parse(v) { const p = (v || '').split('-').map(Number); return p.length === 3 && p[0] ? new Date(p[0], p[1] - 1, p[2]) : null; }
    function nextHintUpdate() {
        nextHint.classList.remove('is-warn');
        const n = parse(next.value), a = parse(applied.value);
        if (!n) { nextHint.textContent = 'Pick a quick option or choose any date. Leave empty if you do not need a reminder.'; return; }
        const label = n.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
        if (a && n < a) { nextHint.textContent = 'This date is before the applied date. Please check it.'; nextHint.classList.add('is-warn'); return; }
        const t = new Date(); t.setHours(0, 0, 0, 0);
        const days = Math.round((n - t) / 86400000);
        nextHint.textContent = 'Reminder on ' + label + (days > 0 ? ' (in ' + days + ' days)' : days === 0 ? ' (today)' : '');
    }
    $$('.fm-chip').forEach(c => c.addEventListener('click', () => {
        if (c.dataset.days === 'clear') next.value = '';
        else { const base = parse(applied.value) || new Date(); base.setDate(base.getDate() + Number(c.dataset.days)); next.value = iso(base); }
        nextHintUpdate();
    }));
    next.addEventListener('input', nextHintUpdate); applied.addEventListener('change', nextHintUpdate);
    nextHintUpdate();

    /* ================= Submit ================= */
    form.addEventListener('submit', () => {
        if (!form.checkValidity()) return;
        sb.disabled = true; st.textContent = 'Saving…';
        const r = radios.find(x => x.checked);
        try { sessionStorage.setItem('fmSaved', JSON.stringify({ fid: r ? r.value : '', t: Date.now() })); } catch (e) {}
    });
    function resetSubmit() { sb.disabled = false; st.textContent = submitLabel; }
    window.addEventListener('pageshow', resetSubmit);
    window.fmResetSubmit = resetSubmit;

    /* ================= After a successful save: show the new record ================= */
    try {
        const saved = JSON.parse(sessionStorage.getItem('fmSaved') || 'null');
        if (saved) sessionStorage.removeItem('fmSaved');
        if (saved && flash && Date.now() - saved.t < 120000) {
            const mine = items.filter(i => i.dataset.fid === String(saved.fid)).sort((a, b) => Number(b.dataset.applied) - Number(a.dataset.applied))[0];
            if (mine) {
                const nm = mine.querySelector('.fm-item-name b'); if (nm) nm.insertAdjacentHTML('beforeend', '<span class="fm-new">Just added</span>');
                setTimeout(() => jumpTo(mine), 450);
            }
        }
    } catch (e) {}

    /* ================= Init ================= */
    if (radios.some(r => r.checked)) showFertilizer();
    if ($('.fm-page').dataset.autoOpen === '1') openDrawer('');
    /* Deep link from the fertilizer guide: ?fertilizer=ID opens the form with that fertilizer selected */
    const deep = new URLSearchParams(location.search).get('fertilizer');
    if (deep && radios.some(r => r.value === deep)) openDrawer(deep);
});
</script>
@endsection