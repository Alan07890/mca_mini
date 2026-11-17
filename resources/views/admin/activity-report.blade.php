@extends('layouts.AdminMaster')

@section('title', 'User Activity & Reports')

@push('styles')
<style>
    :root {
        --ar-card: #10291d;
        --ar-green: #4CAF50;
        --ar-green-soft: #81C784;
        --ar-gold: #FFD54F;
        --ar-cream: #F1F8E9;
        --ar-muted: #AAC5AE;
        --ar-line: rgba(241,248,233,.10);
        --ar-line-strong: rgba(129,199,132,.20);
    }

    .ar-wrap {
        padding: 10px 0 34px;
        color: var(--ar-cream);
        font-family: inherit;
    }

    .ar-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 18px;
        flex-wrap: wrap;
        margin-bottom: 22px;
    }

    .ar-head-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .ar-head-icon {
        width: 56px;
        height: 56px;
        flex: 0 0 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        background: linear-gradient(135deg, #4CAF50, #2E7D32);
        color: #fff;
        box-shadow: 0 16px 34px -20px rgba(76,175,80,.65);
    }

    .ar-header h2 {
        margin: 0;
        color: var(--ar-cream);
        font-family: 'Fraunces', Georgia, serif;
        font-size: 29px;
        font-weight: 500;
    }

    .ar-header p {
        margin: 5px 0 0;
        color: var(--ar-muted);
        font-size: 13px;
    }

    .ar-nav-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 16px;
        border-radius: 999px;
        text-decoration: none;
        color: #fff;
        background: #2E7D32;
        border: 1px solid rgba(255,255,255,.10);
        font-size: 12px;
        font-weight: 700;
    }

    .ar-nav-btn:hover {
        color: #fff;
        background: #388E3C;
    }

    .ar-summary {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 15px;
        margin-bottom: 17px;
    }

    .ar-stat {
        padding: 17px 18px;
        border-radius: 18px;
        background: linear-gradient(180deg, rgba(241,248,233,.06), rgba(241,248,233,.018));
        border: 1px solid var(--ar-line-strong);
        box-shadow: 0 18px 36px -28px rgba(0,0,0,.85);
    }

    .ar-stat-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .ar-stat-icon {
        width: 43px;
        height: 43px;
        border-radius: 13px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(76,175,80,.13);
        border: 1px solid rgba(76,175,80,.23);
        color: var(--ar-green-soft);
    }

    .ar-stat.gold .ar-stat-icon {
        background: rgba(255,213,79,.10);
        border-color: rgba(255,213,79,.20);
        color: var(--ar-gold);
    }

    .ar-stat-label {
        color: var(--ar-muted);
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .55px;
        font-weight: 700;
    }

    .ar-stat-value {
        margin-top: 12px;
        color: var(--ar-cream);
        font-size: 28px;
        font-weight: 800;
        line-height: 1;
    }

    .ar-chart-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.6fr) minmax(0, 1fr);
        gap: 16px;
        margin-bottom: 16px;
    }

    .ar-chart-grid.equal {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .ar-card {
        min-width: 0;
        padding: 18px;
        border-radius: 18px;
        background: var(--ar-card);
        border: 1px solid var(--ar-line-strong);
        box-shadow: 0 20px 42px -30px rgba(0,0,0,.9);
    }

    .ar-card-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 12px;
    }

    .ar-card-title {
        margin: 0;
        color: var(--ar-cream);
        font-size: 14px;
        font-weight: 700;
    }

    .ar-card-sub {
        margin: 4px 0 0;
        color: var(--ar-muted);
        font-size: 11.5px;
    }

    .ar-chart-box {
        height: 300px;
    }

    .ar-chart-box.small {
        height: 270px;
    }

    .ar-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 9px;
        border-radius: 999px;
        background: rgba(76,175,80,.10);
        border: 1px solid rgba(76,175,80,.18);
        color: var(--ar-green-soft);
        font-size: 10px;
        font-weight: 800;
        white-space: nowrap;
    }

    .ar-pill.gold {
        background: rgba(255,213,79,.09);
        border-color: rgba(255,213,79,.17);
        color: var(--ar-gold);
    }

    .ar-pill.gray {
        background: rgba(241,248,233,.05);
        border-color: rgba(241,248,233,.10);
        color: var(--ar-muted);
    }

    .ar-filters {
        display: grid;
        grid-template-columns: minmax(0, 1.25fr) 155px 155px 145px 145px auto;
        gap: 10px;
        margin-bottom: 16px;
    }

    .ar-control {
        width: 100%;
        padding: 11px 13px;
        border: 1px solid var(--ar-line-strong);
        border-radius: 999px;
        background: #07150f;
        color: var(--ar-cream);
        font-size: 12px;
        outline: none;
    }

    .ar-control:focus {
        border-color: var(--ar-gold);
        box-shadow: 0 0 0 3px rgba(255,213,79,.10);
    }

    .ar-control option {
        background: #07150f;
        color: var(--ar-cream);
    }

    .ar-filter-btn,
    .ar-reset {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 11px 15px;
        border-radius: 999px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 800;
    }

    .ar-filter-btn {
        border: 0;
        background: var(--ar-gold);
        color: #2e2410;
    }

    .ar-reset {
        color: var(--ar-muted);
        border: 1px solid var(--ar-line-strong);
        background: rgba(241,248,233,.03);
    }

    .ar-reset:hover {
        color: var(--ar-cream);
    }

    .ar-table-card {
        overflow: hidden;
        padding: 0;
    }

    .ar-inner-pad {
        padding: 18px 18px 0;
    }

    .ar-table-wrap {
        overflow-x: auto;
    }

    .ar-table {
        width: 100%;
        min-width: 850px;
        border-collapse: collapse;
        color: var(--ar-cream);
        font-size: 12px;
    }

    .ar-table th {
        padding: 13px 15px;
        background: rgba(241,248,233,.025);
        border-bottom: 1px solid var(--ar-line);
        color: var(--ar-muted);
        text-align: left;
        text-transform: uppercase;
        letter-spacing: .45px;
        font-size: 10px;
        white-space: nowrap;
    }

    .ar-table td {
        padding: 14px 15px;
        border-bottom: 1px solid rgba(241,248,233,.07);
        vertical-align: middle;
    }

    .ar-table tr:last-child td {
        border-bottom: 0;
    }

    .ar-user {
        color: var(--ar-cream);
        font-weight: 700;
    }

    .ar-email,
    .ar-detail-sub {
        margin-top: 3px;
        color: var(--ar-muted);
        font-size: 10.5px;
    }

    .ar-detail-main {
        color: var(--ar-cream);
        font-weight: 700;
    }

    .ar-empty {
        padding: 36px 20px !important;
        text-align: center !important;
        color: var(--ar-muted) !important;
    }

    .ar-empty i {
        display: block;
        margin-bottom: 8px;
        color: var(--ar-green-soft);
        font-size: 25px;
    }

    .ar-pagination {
        padding: 14px 16px;
        border-top: 1px solid var(--ar-line);
    }

    .ar-pagination .pagination {
        margin-bottom: 0;
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
    }

    /* Keep Laravel pagination compact and consistent with Bootstrap 5.
       This also prevents the default Tailwind SVG arrow from stretching. */
    .ar-pagination .page-link {
        min-width: 36px;
        text-align: center;
        padding: 8px 11px;
        color: var(--ar-cream);
        background: #10291d;
        border: 1px solid var(--ar-line-strong);
        border-radius: 8px !important;
        font-size: 12px;
        text-decoration: none;
        box-shadow: none;
    }

    .ar-pagination .page-link:hover {
        color: #fff;
        background: #1B5E20;
        border-color: rgba(129,199,132,.45);
    }

    .ar-pagination .page-item.active .page-link {
        color: #102016;
        background: var(--ar-green-soft);
        border-color: var(--ar-green-soft);
    }

    .ar-pagination .page-item.disabled .page-link {
        color: #718c77;
        background: #0b1f15;
        border-color: var(--ar-line);
    }

    .ar-pagination svg {
        display: inline-block !important;
        width: 1rem !important;
        height: 1rem !important;
        max-width: 1rem !important;
        vertical-align: middle;
    }

    @media (max-width: 1100px) {
        .ar-summary { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .ar-chart-grid,
        .ar-chart-grid.equal { grid-template-columns: 1fr; }
        .ar-filters { grid-template-columns: 1fr 1fr; }
    }

    @media (max-width: 650px) {
        .ar-summary { grid-template-columns: 1fr; }
        .ar-filters { grid-template-columns: 1fr; }
        .ar-header h2 { font-size: 24px; }
    }
</style>
@endpush

@section('content')
@if(!empty($pdfMode))
<style>
    /* Printable report mode: hide the normal admin chrome while retaining one Blade view. */
    html, body { background:#fff !important; color:#1b2b20 !important; margin:0 !important; padding:0 !important; }
    .cardofer-bg, .cf-sidebar-backdrop, .cf-sidebar, .cf-topnav, .cf-admin-footer, .cf-dashboard-menu { display:none !important; }
    .cf-main, .cf-main .cf-page-content { margin:0 !important; padding:0 !important; min-height:0 !important; background:#fff !important; color:#1b2b20 !important; }
    .ar-pdf-wrap, .ar-pdf-wrap * { box-sizing:border-box; }
    .ar-pdf-wrap { padding:24px; color:#1b2b20; font:12px/1.45 Arial,sans-serif; background:#fff; }
    .arp-top { display:flex; justify-content:space-between; align-items:flex-start; gap:16px; margin-bottom:18px; border-bottom:3px solid #1b5e20; padding-bottom:14px; }
    .ar-pdf-wrap h1 { margin:0; font-size:23px; color:#123c17; }
    .ar-pdf-wrap h2 { color:#1b5e20; font-size:16px; margin:24px 0 8px; break-after:avoid; }
    .arp-muted { color:#526457; margin-top:5px; }
    .arp-print-actions { display:flex; gap:8px; }
    .arp-print-actions button { border:0; border-radius:6px; padding:9px 12px; background:#1b5e20; color:#fff; font-weight:700; cursor:pointer; }
    .arp-print-actions button.secondary { background:#526457; }
    .arp-summary { display:grid; grid-template-columns:repeat(5,minmax(0,1fr)); gap:8px; margin:14px 0 20px; }
    .arp-stat { border:1px solid #c6d8c8; border-radius:7px; padding:10px; background:#f4f8f1; }
    .arp-stat span { display:block; color:#526457; font-size:10px; }
    .arp-stat strong { display:block; color:#123c17; font-size:20px; margin-top:4px; }
    .ar-pdf-wrap table { width:100%; border-collapse:collapse; margin-bottom:16px; font-size:10px; color:#1b2b20; }
    .ar-pdf-wrap th { background:#1b5e20 !important; color:#fff !important; text-align:left; padding:7px 6px; -webkit-print-color-adjust:exact; print-color-adjust:exact; }
    .ar-pdf-wrap td { border:1px solid #d4dfd4; padding:6px; vertical-align:top; overflow-wrap:anywhere; }
    .ar-pdf-wrap tbody tr:nth-child(even) { background:#f7faf6 !important; -webkit-print-color-adjust:exact; print-color-adjust:exact; }
    .arp-section-note { color:#5c6d60; font-size:10px; margin-top:-3px; }
    .arp-page-footer { margin-top:20px; border-top:1px solid #c6d8c8; padding-top:8px; color:#526457; font-size:10px; }
    @page { size:A4 landscape; margin:12mm; }
    @media print {
        .ar-pdf-wrap { padding:0 !important; font-size:10px; }
        .arp-print-actions { display:none !important; }
        .arp-summary { grid-template-columns:repeat(5,minmax(0,1fr)); }
        .ar-pdf-wrap h2 { page-break-after:avoid; }
        .ar-pdf-wrap table { page-break-inside:auto; }
        .ar-pdf-wrap tr { page-break-inside:avoid; page-break-after:auto; }
        .ar-pdf-wrap thead { display:table-header-group; }
        .arp-top { page-break-after:avoid; }
    }
    @media screen and (max-width:800px) {
        .ar-pdf-wrap { padding:12px; }
        .arp-summary { grid-template-columns:repeat(2,minmax(0,1fr)); }
        .arp-top { flex-direction:column; }
    }
</style>
<div class="ar-pdf-wrap">
<div class="arp-top">
        <div>
            <h1>CARDO-FER — Admin Activity Report</h1>
            <div class="arp-muted">Disease analysis, fertilizer applications and registered farmer fields</div>
            <div class="arp-muted">Generated on {{ now()->format('d M Y, h:i A') }}</div>
        </div>
        <div class="arp-print-actions">
            <button type="button" onclick="window.print()">Save as PDF / Print</button>
            <button type="button" class="secondary" onclick="window.close()">Close</button>
        </div>
    </div>

    <div class="arp-summary">
        <div class="arp-stat"><span>Total Farmers</span><strong>{{ number_format($totalFarmers) }}</strong></div>
        <div class="arp-stat"><span>Disease Analyses</span><strong>{{ number_format($totalAnalyses) }}</strong></div>
        <div class="arp-stat"><span>Completed Analyses</span><strong>{{ number_format($completedAnalyses) }}</strong></div>
        <div class="arp-stat"><span>Fertilizer Applications</span><strong>{{ number_format($totalFertilizerApplications) }}</strong></div>
        <div class="arp-stat"><span>Active Farmers · 30 Days</span><strong>{{ number_format($activeFarmers) }}</strong></div>
    </div>

    <h2>Disease Analysis Records ({{ $analyses->count() }})</h2>
    <div class="arp-section-note">The selected search and analysis filters have been applied to this section.</div>
    <table>
        <thead><tr><th>ID</th><th>Farmer</th><th>Email</th><th>Analysis Type</th><th>Predicted Disease</th><th>Status</th><th>Confidence</th><th>Symptoms</th><th>Analyzed At</th></tr></thead>
        <tbody>
        @forelse ($analyses as $analysis)
            <tr>
                <td>{{ $analysis->id }}</td>
                <td>{{ $analysis->farmer_name ?: optional($analysis->user)->name ?: 'Unknown Farmer' }}</td>
                <td>{{ $analysis->farmer_email ?: optional($analysis->user)->email ?: '—' }}</td>
                <td>{{ match ($analysis->analysis_type) { 'image_and_symptoms' => 'Image + Symptoms', 'image' => 'Image Analysis', 'symptoms' => 'Symptom Analysis', default => ucwords(str_replace('_', ' ', (string) ($analysis->analysis_type ?: 'Analysis'))) } }}</td>
                <td>{{ $analysis->disease_name ?: 'Disease not identified' }}</td>
                <td>{{ ucwords(str_replace('_', ' ', (string) ($analysis->status ?: 'Unknown'))) }}</td>
                <td>{{ $analysis->ai_confidence_score !== null ? round((float) $analysis->ai_confidence_score) . '%' : ($analysis->ai_confidence ?: 'Not available') }}</td>
                <td>{{ $analysis->symptoms->pluck('symptom_name')->filter()->implode(', ') ?: '—' }}</td>
                <td>{{ $analysis->analyzed_at ? \Illuminate\Support\Carbon::parse($analysis->analyzed_at)->format('d M Y, h:i A') : '—' }}</td>
            </tr>
        @empty
            <tr><td colspan="9">No disease analysis records match the selected filters.</td></tr>
        @endforelse
        </tbody>
    </table>

    <h2>Fertilizer Application Records ({{ $fertilizerApplications->count() }})</h2>
    <table>
        <thead><tr><th>ID</th><th>Farmer</th><th>Email</th><th>Fertilizer</th><th>Quantity</th><th>Applied Date</th><th>Next Application</th><th>Notes</th></tr></thead>
        <tbody>
        @forelse ($fertilizerApplications as $application)
            <tr>
                <td>{{ $application->id }}</td>
                <td>{{ optional($application->user)->name ?: 'Unknown Farmer' }}</td>
                <td>{{ optional($application->user)->email ?: '—' }}</td>
                <td>{{ optional($application->fertilizer)->fertilizer_name ?: 'Unknown Fertilizer' }}</td>
                <td>{{ $application->quantity }} {{ $application->quantity_unit }}</td>
                <td>{{ $application->applied_date ? \Illuminate\Support\Carbon::parse($application->applied_date)->format('d M Y') : '—' }}</td>
                <td>{{ $application->next_application_date ? \Illuminate\Support\Carbon::parse($application->next_application_date)->format('d M Y') : 'Not scheduled' }}</td>
                <td>{{ $application->notes ?: '—' }}</td>
            </tr>
        @empty
            <tr><td colspan="8">No fertilizer applications recorded.</td></tr>
        @endforelse
        </tbody>
    </table>

    <h2>Farmer Field Records ({{ $fields->count() }})</h2>
    <table>
        <thead><tr><th>Field ID</th><th>Farmer</th><th>Email</th><th>Area</th><th>Area Unit</th><th>Plant Count</th><th>Last Updated</th></tr></thead>
        <tbody>
        @forelse ($fields as $field)
            <tr>
                <td>{{ $field->id }}</td>
                <td>{{ optional($field->user)->name ?: 'Unknown Farmer' }}</td>
                <td>{{ optional($field->user)->email ?: '—' }}</td>
                <td>{{ $field->area }}</td>
                <td>{{ $field->area_unit }}</td>
                <td>{{ number_format((int) $field->plant_count) }}</td>
                <td>{{ $field->updated_at ? $field->updated_at->format('d M Y, h:i A') : '—' }}</td>
            </tr>
        @empty
            <tr><td colspan="7">No farmer field records found.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div class="arp-page-footer">CARDO-FER · AI Smart Farming System · Confidential administrative report</div>
</div>
<script>
    window.addEventListener('load', function () {
        setTimeout(function () { window.print(); }, 350);
    });
</script>
@else
<div class="ar-wrap">

    <div class="ar-header">
        <div class="ar-head-left">
            <div class="ar-head-icon">
                <i class="fa-solid fa-chart-line"></i>
            </div>

            <div>
                <h2>User Activity &amp; Reports</h2>
                <p>Monitor farmer disease analyses, fertilizer activity and overall system usage.</p>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2 align-items-center">
            <a href="{{ route('admin.activity-report.excel', request()->query()) }}" class="ar-nav-btn" title="Download all report records as an Excel workbook">
                <i class="fa-solid fa-file-excel"></i>
                Export Excel
            </a>
            <a href="{{ route('admin.activity-report.pdf', request()->query()) }}" class="ar-nav-btn" target="_blank" rel="noopener" title="Open a print-ready report; choose Save as PDF in the print dialog">
                <i class="fa-solid fa-file-pdf"></i>
                Export PDF
            </a>
            <a href="{{ route('users') }}" class="ar-nav-btn">
                <i class="fa-solid fa-users"></i>
                User Management
            </a>
        </div>
    </div>


    <div class="ar-summary">

        <div class="ar-stat">
            <div class="ar-stat-top">
                <div class="ar-stat-label">Total Farmers</div>
                <span class="ar-stat-icon">
                    <i class="fa-solid fa-user-group"></i>
                </span>
            </div>
            <div class="ar-stat-value">{{ number_format($totalFarmers) }}</div>
        </div>

        <div class="ar-stat gold">
            <div class="ar-stat-top">
                <div class="ar-stat-label">Disease Analyses</div>
                <span class="ar-stat-icon">
                    <i class="fa-solid fa-microscope"></i>
                </span>
            </div>
            <div class="ar-stat-value">{{ number_format($totalAnalyses) }}</div>
        </div>

        <div class="ar-stat">
            <div class="ar-stat-top">
                <div class="ar-stat-label">Fertilizer Applications</div>
                <span class="ar-stat-icon">
                    <i class="fa-solid fa-flask"></i>
                </span>
            </div>
            <div class="ar-stat-value">{{ number_format($totalFertilizerApplications) }}</div>
        </div>

        <div class="ar-stat gold">
            <div class="ar-stat-top">
                <div class="ar-stat-label">Active Farmers · 30 Days</div>
                <span class="ar-stat-icon">
                    <i class="fa-solid fa-bolt"></i>
                </span>
            </div>
            <div class="ar-stat-value">{{ number_format($activeFarmers) }}</div>
        </div>

    </div>


    <div class="ar-chart-grid">

        <div class="ar-card">
            <div class="ar-card-head">
                <div>
                    <h3 class="ar-card-title">Activity Trend</h3>
                    <p class="ar-card-sub">Daily disease analyses and fertilizer applications over the last 14 days.</p>
                </div>

                <span class="ar-pill">
                    <i class="fa-solid fa-calendar-week"></i>
                    14 Days
                </span>
            </div>

            <div class="ar-chart-box">
                <canvas id="activityTrendChart"></canvas>
            </div>
        </div>


        <div class="ar-card">
            <div class="ar-card-head">
                <div>
                    <h3 class="ar-card-title">Activity Distribution</h3>
                    <p class="ar-card-sub">Breakdown of recorded disease and fertilizer activity.</p>
                </div>
            </div>

            <div class="ar-chart-box">
                <canvas id="activityDistributionChart"></canvas>
            </div>
        </div>

    </div>


    <div class="ar-chart-grid equal">

        <div class="ar-card">
            <div class="ar-card-head">
                <div>
                    <h3 class="ar-card-title">Most Predicted Diseases</h3>
                    <p class="ar-card-sub">Diseases appearing most frequently in the analysis history.</p>
                </div>
            </div>

            <div class="ar-chart-box small">
                <canvas id="diseaseChart"></canvas>
            </div>
        </div>


        <div class="ar-card">
            <div class="ar-card-head">
                <div>
                    <h3 class="ar-card-title">Most Recorded Fertilizers</h3>
                    <p class="ar-card-sub">Fertilizers recorded by farmers in the application diary.</p>
                </div>
            </div>

            <div class="ar-chart-box small">
                <canvas id="fertilizerChart"></canvas>
            </div>
        </div>

    </div>


    <div class="ar-card ar-table-card">

        <div class="ar-inner-pad">

            <div class="ar-card-head">
                <div>
                    <h3 class="ar-card-title">Analysis Records</h3>
                    <p class="ar-card-sub">Search and review recorded disease-identification activity.</p>
                </div>

                <span class="ar-pill gray">
                    {{ number_format($completedAnalyses) }} completed
                </span>
            </div>

            <form method="GET" action="{{ route('admin.activity-report') }}" class="ar-filters">

                <input
                    type="text"
                    name="search"
                    class="ar-control"
                    value="{{ request('search') }}"
                    placeholder="Search farmer, email or disease..."
                >

                <select name="analysis_type" class="ar-control">
                    <option value="">All analysis types</option>
                    <option value="symptoms" @selected(request('analysis_type') === 'symptoms')>
                        Symptom Analysis
                    </option>
                    <option value="image" @selected(request('analysis_type') === 'image')>
                        Image Analysis
                    </option>
                    <option value="image_and_symptoms" @selected(request('analysis_type') === 'image_and_symptoms')>
                        Image + Symptoms
                    </option>
                </select>

                <select name="status" class="ar-control">
                    <option value="">All statuses</option>
                    <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                    <option value="needs_confirmation" @selected(request('status') === 'needs_confirmation')>
                        Needs Confirmation
                    </option>
                    <option value="failed" @selected(request('status') === 'failed')>Failed</option>
                </select>

                <input type="date" name="from_date" class="ar-control" value="{{ request('from_date') }}" aria-label="From date">
                <input type="date" name="to_date" class="ar-control" value="{{ request('to_date') }}" aria-label="To date">

                <div class="d-flex gap-2">
                    <button type="submit" class="ar-filter-btn">
                        <i class="fa-solid fa-filter me-1"></i>
                        Filter
                    </button>

                    <a href="{{ route('admin.activity-report') }}" class="ar-reset">
                        Reset
                    </a>
                </div>

            </form>
        </div>


        <div class="ar-table-wrap">

            <table class="ar-table">

                <thead>
                    <tr>
                        <th>Farmer</th>
                        <th>Activity</th>
                        <th>Disease</th>
                        <th>AI Confidence</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>

                @forelse ($analyses as $analysis)

                    @php
                        $typeLabel = match ($analysis->analysis_type) {
                            'image_and_symptoms' => 'Image + Symptoms',
                            'image' => 'Image Analysis',
                            'symptoms' => 'Symptom Analysis',
                            default => ucwords(
                                str_replace(
                                    '_',
                                    ' ',
                                    $analysis->analysis_type ?? 'Analysis'
                                )
                            ),
                        };

                        $confidence = $analysis->ai_confidence_score !== null
                            ? round((float) $analysis->ai_confidence_score) . '%'
                            : ($analysis->ai_confidence ?: 'N/A');
                    @endphp

                    <tr>

                        <td>
                            <div class="ar-user">
                                {{ $analysis->farmer_name ?: optional($analysis->user)->name ?: 'Unknown Farmer' }}
                            </div>

                            <div class="ar-email">
                                {{ $analysis->farmer_email ?: optional($analysis->user)->email ?: '—' }}
                            </div>
                        </td>

                        <td>
                            <span class="ar-pill">
                                <i class="fa-solid fa-wave-square"></i>
                                {{ $typeLabel }}
                            </span>
                        </td>

                        <td>
                            <div class="ar-detail-main">
                                {{ $analysis->disease_name ?: 'Unknown Disease' }}
                            </div>

                            <div class="ar-detail-sub">
                                {{ $analysis->symptoms->count() }}
                                stored symptom record{{ $analysis->symptoms->count() === 1 ? '' : 's' }}
                            </div>
                        </td>

                        <td>
                            <span class="ar-pill gold">
                                <i class="fa-solid fa-bullseye"></i>
                                {{ $confidence }}
                            </span>
                        </td>

                        <td>

                            @if ($analysis->status === 'completed')

                                <span class="ar-pill">
                                    <i class="fa-solid fa-circle-check"></i>
                                    Completed
                                </span>

                            @elseif ($analysis->status === 'needs_confirmation')

                                <span class="ar-pill gold">
                                    <i class="fa-solid fa-circle-question"></i>
                                    Needs Confirmation
                                </span>

                            @else

                                <span class="ar-pill gray">
                                    {{ ucwords(str_replace('_', ' ', $analysis->status ?? 'Unknown')) }}
                                </span>

                            @endif

                        </td>

                        <td>
                            {{ optional($analysis->analyzed_at)->format('d M Y, h:i A') }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="ar-empty">
                            <i class="fa-solid fa-chart-column"></i>
                            No analysis records found.
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        @if ($analyses->hasPages())
            <div class="ar-pagination">
                {{ $analyses->links('pagination::bootstrap-5') }}
            </div>
        @endif

    </div>


    <div class="ar-card ar-table-card mt-3">

        <div class="ar-inner-pad">

            <div class="ar-card-head">

                <div>
                    <h3 class="ar-card-title">Recent User Activity</h3>
                    <p class="ar-card-sub">Latest disease-analysis and fertilizer-application records.</p>
                </div>

                <span class="ar-pill gray">
                    {{ $recentActivities->count() }} records
                </span>

            </div>

        </div>


        <div class="ar-table-wrap">

            <table class="ar-table">

                <thead>
                    <tr>
                        <th>Farmer</th>
                        <th>Activity</th>
                        <th>Details</th>
                        <th>Additional Information</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>

                @forelse ($recentActivities as $activity)

                    <tr>

                        <td>
                            <div class="ar-user">
                                {{ $activity['user_name'] }}
                            </div>

                            <div class="ar-email">
                                {{ $activity['user_email'] ?: '—' }}
                            </div>
                        </td>

                        <td>

                            @if ($activity['activity_type'] === 'Fertilizer Application')

                                <span class="ar-pill gold">
                                    <i class="fa-solid fa-flask"></i>
                                    Fertilizer Application
                                </span>

                            @else

                                <span class="ar-pill">
                                    <i class="fa-solid fa-microscope"></i>
                                    {{ $activity['activity_type'] }}
                                </span>

                            @endif

                        </td>

                        <td>
                            <div class="ar-detail-main">
                                {{ $activity['details'] }}
                            </div>
                        </td>

                        <td>
                            <div class="ar-detail-sub">
                                {{ $activity['meta'] }}
                            </div>
                        </td>

                        <td>
                            {{ $activity['date'] }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="ar-empty">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                            No recent user activity found.
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>


    <div class="ar-card ar-table-card mt-3">
        <div class="ar-inner-pad">
            <div class="ar-card-head">
                <div>
                    <h3 class="ar-card-title">Fertilizer Application Report</h3>
                    <p class="ar-card-sub">Farmer-recorded fertilizer usage and upcoming application dates.</p>
                </div>
                <span class="ar-pill gold">Latest 15 records</span>
            </div>
        </div>
        <div class="ar-table-wrap">
            <table class="ar-table">
                <thead>
                    <tr>
                        <th>Farmer</th><th>Fertilizer</th><th>Quantity</th><th>Applied Date</th><th>Next Application</th><th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($fertilizerApplications as $application)
                        <tr>
                            <td>
                                <div class="ar-user">{{ optional($application->user)->name ?: 'Unknown Farmer' }}</div>
                                <div class="ar-email">{{ optional($application->user)->email ?: '—' }}</div>
                            </td>
                            <td><div class="ar-detail-main">{{ optional($application->fertilizer)->fertilizer_name ?: 'Unknown Fertilizer' }}</div></td>
                            <td><span class="ar-pill gold">{{ $application->quantity }} {{ $application->quantity_unit }}</span></td>
                            <td>{{ $application->applied_date ? \Illuminate\Support\Carbon::parse($application->applied_date)->format('d M Y') : '—' }}</td>
                            <td>{{ $application->next_application_date ? \Illuminate\Support\Carbon::parse($application->next_application_date)->format('d M Y') : 'Not scheduled' }}</td>
                            <td><div class="ar-detail-sub">{{ $application->notes ?: '—' }}</div></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="ar-empty"><i class="fa-solid fa-flask"></i>No fertilizer applications have been recorded.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="ar-card ar-table-card mt-3">
        <div class="ar-inner-pad">
            <div class="ar-card-head">
                <div>
                    <h3 class="ar-card-title">Farmer Field Report</h3>
                    <p class="ar-card-sub">Registered field area and plant counts from farmer profiles.</p>
                </div>
                <a href="{{ route('admin.field-management') }}" class="ar-nav-btn"><i class="fa-solid fa-seedling"></i> Field Management</a>
            </div>
        </div>
        <div class="ar-table-wrap">
            <table class="ar-table">
                <thead><tr><th>Farmer</th><th>Area</th><th>Plant Count</th><th>Last Updated</th></tr></thead>
                <tbody>
                    @forelse ($fields as $field)
                        <tr>
                            <td>
                                <div class="ar-user">{{ optional($field->user)->name ?: 'Unknown Farmer' }}</div>
                                <div class="ar-email">{{ optional($field->user)->email ?: '—' }}</div>
                            </td>
                            <td><span class="ar-pill">{{ $field->area }} {{ $field->area_unit }}</span></td>
                            <td>{{ number_format((int) $field->plant_count) }}</td>
                            <td>{{ $field->updated_at ? $field->updated_at->format('d M Y, h:i A') : '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="ar-empty"><i class="fa-solid fa-seedling"></i>No farmer field records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endif
@endsection

@if(empty($pdfMode))
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const textColor = '#AAC5AE';
    const lightColor = '#F1F8E9';

    new Chart(
        document.getElementById('activityTrendChart'),
        {
            type: 'line',

            data: {
                labels: @json($trendLabels),

                datasets: [
                    {
                        label: 'Disease Analyses',
                        data: @json($analysisTrendData),
                        borderColor: '#4CAF50',
                        backgroundColor: 'rgba(76,175,80,.12)',
                        fill: true,
                        tension: .35,
                        pointRadius: 3,
                        pointHoverRadius: 5
                    },

                    {
                        label: 'Fertilizer Applications',
                        data: @json($fertilizerTrendData),
                        borderColor: '#FFD54F',
                        backgroundColor: 'rgba(255,213,79,.09)',
                        fill: true,
                        tension: .35,
                        pointRadius: 3,
                        pointHoverRadius: 5
                    }
                ]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        position: 'bottom',

                        labels: {
                            color: lightColor
                        }
                    }
                },

                scales: {
                    x: {
                        ticks: {
                            color: textColor,
                            font: { size: 10 }
                        },

                        grid: {
                            color: 'rgba(241,248,233,.05)'
                        }
                    },

                    y: {
                        beginAtZero: true,

                        ticks: {
                            color: textColor,
                            precision: 0
                        },

                        grid: {
                            color: 'rgba(241,248,233,.06)'
                        }
                    }
                }
            }
        }
    );


    new Chart(
        document.getElementById('activityDistributionChart'),
        {
            type: 'doughnut',

            data: {
                labels: [
                    'Symptom Analysis',
                    'Image Analysis',
                    'Image + Symptoms',
                    'Fertilizer Applications'
                ],

                datasets: [{
                    data: [
                        {{ $symptomAnalysisCount }},
                        {{ $imageAnalysisCount }},
                        {{ $imageAndSymptomsCount }},
                        {{ $totalFertilizerApplications }}
                    ],

                    backgroundColor: [
                        '#4CAF50',
                        '#2E7D32',
                        '#81C784',
                        '#FFD54F'
                    ],

                    borderColor: '#10291D',
                    borderWidth: 3
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '66%',

                plugins: {
                    legend: {
                        position: 'bottom',

                        labels: {
                            color: lightColor,
                            boxWidth: 12
                        }
                    }
                }
            }
        }
    );


    new Chart(
        document.getElementById('diseaseChart'),
        {
            type: 'bar',

            data: {
                labels: @json($diseaseLabels),

                datasets: [{
                    label: 'Analyses',
                    data: @json($diseaseData),
                    backgroundColor: '#4CAF50',
                    borderRadius: 7,
                    borderSkipped: false
                }]
            },

            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        display: false
                    }
                },

                scales: {
                    x: {
                        beginAtZero: true,

                        ticks: {
                            color: textColor,
                            precision: 0
                        },

                        grid: {
                            color: 'rgba(241,248,233,.06)'
                        }
                    },

                    y: {
                        ticks: {
                            color: lightColor,
                            font: { size: 10 }
                        },

                        grid: {
                            display: false
                        }
                    }
                }
            }
        }
    );


    new Chart(
        document.getElementById('fertilizerChart'),
        {
            type: 'bar',

            data: {
                labels: @json($fertilizerLabels),

                datasets: [{
                    label: 'Applications',
                    data: @json($fertilizerData),
                    backgroundColor: '#FFD54F',
                    borderRadius: 7,
                    borderSkipped: false
                }]
            },

            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        display: false
                    }
                },

                scales: {
                    x: {
                        beginAtZero: true,

                        ticks: {
                            color: textColor,
                            precision: 0
                        },

                        grid: {
                            color: 'rgba(241,248,233,.06)'
                        }
                    },

                    y: {
                        ticks: {
                            color: lightColor,
                            font: { size: 10 }
                        },

                        grid: {
                            display: false
                        }
                    }
                }
            }
        }
    );

});
</script>
@endpush
@endif
