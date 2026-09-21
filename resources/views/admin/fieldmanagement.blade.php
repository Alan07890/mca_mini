@extends('layouts.AdminMaster')

@section('title', 'Field Management')

@section('content')

<style>
    .field-page {
        color: #f1f8e9;
    }

    .field-panel {
        background: linear-gradient(145deg, #102a20, #0c2119);
        border: 1px solid rgba(157, 184, 163, 0.25);
        border-radius: 22px;
        padding: 30px;
        margin-bottom: 28px;
    }

    .field-panel-title {
        display: flex;
        align-items: center;
        gap: 12px;
        font-family: Georgia, serif;
        font-size: 25px;
        color: #f1f8e9;
        margin: 0;
    }

    .field-panel-title i {
        color: #ffd54f;
        font-size: 22px;
    }

    .field-divider {
        height: 1px;
        background: rgba(157, 184, 163, 0.20);
        margin: 22px 0 25px;
    }

    /* Header */

    .field-heading {
        margin-bottom: 28px;
    }

    .field-heading small {
        display: block;
        color: #81c784;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .field-heading h1 {
        color: #f1f8e9;
        font-family: Georgia, serif;
        font-size: 34px;
        margin: 0 0 7px;
    }

    .field-heading p {
        color: #9db8a3;
        margin: 0;
        font-size: 15px;
    }

    /* Summary */

    .field-summary {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }

    .summary-box {
        background: #102a20;
        border: 1px solid rgba(157, 184, 163, 0.22);
        border-radius: 16px;
        padding: 20px;
        position: relative;
        overflow: hidden;
    }

    .summary-box::after {
        content: "";
        position: absolute;
        width: 70px;
        height: 70px;
        right: -25px;
        top: -25px;
        border-radius: 50%;
        background: rgba(129, 199, 132, 0.06);
    }

    .summary-icon {
        width: 42px;
        height: 42px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(129, 199, 132, 0.10);
        border: 1px solid rgba(129, 199, 132, 0.18);
        color: #81c784;
        margin-bottom: 14px;
    }

    .summary-label {
        color: #9db8a3;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .7px;
        margin-bottom: 5px;
    }

    .summary-value {
        color: #f1f8e9;
        font-size: 25px;
        font-weight: 700;
    }

    .summary-value span {
        color: #9db8a3;
        font-size: 13px;
        font-weight: 500;
    }

    /* Filters */

    .field-filters {
        display: grid;
        grid-template-columns: minmax(250px, 1fr) 180px 180px auto;
        gap: 12px;
        align-items: center;
    }

    .field-input,
    .field-select {
        width: 100%;
        height: 48px;
        background: #091d15;
        border: 1px solid rgba(157, 184, 163, 0.25);
        border-radius: 12px;
        color: #f1f8e9;
        padding: 0 15px;
        outline: none;
        font-size: 14px;
    }

    .field-input::placeholder {
        color: #6f8a76;
    }

    .field-input:focus,
    .field-select:focus {
        border-color: #81c784;
        box-shadow: 0 0 0 3px rgba(129, 199, 132, 0.08);
    }

    .field-select option {
        background: #102a20;
        color: #f1f8e9;
    }

    .field-search {
        position: relative;
    }

    .field-search i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #8eaa96;
    }

    .field-search input {
        padding-left: 43px;
    }

    .field-btn {
        height: 48px;
        border: 0;
        border-radius: 24px;
        padding: 0 22px;
        background: linear-gradient(135deg, #ffd54f, #e9a83e);
        color: #101810;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        transition: .2s ease;
        white-space: nowrap;
    }

    .field-btn:hover {
        transform: translateY(-1px);
        filter: brightness(1.05);
    }

    /* Table */

    .field-table-wrap {
        overflow-x: auto;
    }

    .field-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 950px;
    }

    .field-table th {
        padding: 15px 14px;
        text-align: left;
        color: #81c784;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .7px;
        border-bottom: 1px solid rgba(157, 184, 163, 0.25);
        white-space: nowrap;
    }

    .field-table td {
        padding: 17px 14px;
        color: #c4d2c7;
        font-size: 14px;
        border-bottom: 1px solid rgba(157, 184, 163, 0.12);
        vertical-align: middle;
    }

    .field-table tbody tr {
        transition: background .2s ease;
    }

    .field-table tbody tr:hover {
        background: rgba(129, 199, 132, 0.035);
    }

    .farmer-info {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .farmer-avatar {
        width: 40px;
        height: 40px;
        border-radius: 11px;
        background: #dcefe0;
        color: #1b5e20;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        flex-shrink: 0;
    }

    .farmer-name {
        color: #f1f8e9;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .farmer-role {
        color: #6f8a76;
        font-size: 12px;
    }

    .field-email {
        color: #9db8a3;
    }

    .field-number {
        color: #f1f8e9;
        font-weight: 700;
    }

    .unit-badge {
        display: inline-flex;
        align-items: center;
        padding: 7px 13px;
        border-radius: 20px;
        background: rgba(129, 199, 132, 0.10);
        border: 1px solid rgba(129, 199, 132, 0.18);
        color: #81c784;
        font-size: 12px;
        font-weight: 700;
    }

    .density {
        color: #81c784;
        font-weight: 600;
        white-space: nowrap;
    }

    .created-date {
        color: #8ea494;
        white-space: nowrap;
    }

    /* Actions */

    .field-actions {
        display: flex;
        gap: 7px;
    }

    .action-btn {
        width: 40px;
        height: 38px;
        border-radius: 10px;
        background: transparent;
        border: 1px solid rgba(157, 184, 163, 0.22);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: .2s ease;
        color: #a9c0ae;
    }

    .action-btn:hover {
        background: rgba(129, 199, 132, 0.08);
        border-color: #81c784;
        color: #81c784;
    }

    .action-btn.edit:hover {
        color: #ffd54f;
        border-color: rgba(255, 213, 79, .5);
        background: rgba(255, 213, 79, .06);
    }

    .action-btn.delete:hover {
        color: #ff8a80;
        border-color: rgba(255, 138, 128, .5);
        background: rgba(255, 138, 128, .06);
    }

    /* Record count */

    .record-count {
        float: right;
        color: #81c784;
        background: rgba(129, 199, 132, 0.08);
        border: 1px solid rgba(129, 199, 132, 0.15);
        border-radius: 20px;
        padding: 7px 13px;
        font-size: 12px;
        font-weight: 700;
    }

    /* Empty */

    .empty-state {
        text-align: center;
        padding: 55px 20px;
        color: #6f8a76;
    }

    .empty-state i {
        font-size: 40px;
        color: #52725d;
        margin-bottom: 15px;
    }

    .empty-state h4 {
        color: #b7c8ba;
        margin-bottom: 5px;
    }

    /* Pagination */

    .field-pagination {
        padding-top: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #7f9987;
        font-size: 13px;
    }

    .field-pagination nav {
        margin-left: auto;
    }

    .field-pagination .pagination {
        margin: 0;
    }

    .field-pagination .page-link {
        background: transparent;
        border-color: rgba(157, 184, 163, .2);
        color: #9db8a3;
    }

    .field-pagination .page-item.active .page-link {
        background: #1b5e20;
        border-color: #1b5e20;
        color: #f1f8e9;
    }

    /* Modal */

    .field-modal {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(0, 10, 6, .78);
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .field-modal.show {
        display: flex;
    }

    .field-modal-box {
        width: 100%;
        max-width: 560px;
        max-height: 90vh;
        overflow-y: auto;
        background: #102a20;
        border: 1px solid rgba(157, 184, 163, .25);
        border-radius: 20px;
        padding: 28px;
        box-shadow: 0 25px 70px rgba(0,0,0,.45);
    }

    .field-modal-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 18px;
        border-bottom: 1px solid rgba(157,184,163,.18);
        margin-bottom: 22px;
    }

    .field-modal-head h3 {
        margin: 0;
        color: #f1f8e9;
        font-family: Georgia, serif;
    }

    .modal-close {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        border: 1px solid rgba(157,184,163,.2);
        background: transparent;
        color: #9db8a3;
        cursor: pointer;
    }

    .modal-close:hover {
        color: #ffd54f;
        border-color: #ffd54f;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        padding: 14px 0;
        border-bottom: 1px solid rgba(157,184,163,.10);
    }

    .detail-label {
        color: #7f9987;
        font-size: 13px;
    }

    .detail-value {
        color: #f1f8e9;
        font-weight: 600;
        text-align: right;
    }

    .modal-form-group {
        margin-bottom: 18px;
    }

    .modal-form-group label {
        display: block;
        color: #81c784;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .7px;
        margin-bottom: 8px;
    }

    .modal-actions {
        display: flex;
        gap: 10px;
        margin-top: 22px;
    }

    .modal-save {
        border: 0;
        border-radius: 22px;
        padding: 11px 22px;
        background: linear-gradient(135deg, #ffd54f, #e9a83e);
        color: #101810;
        font-weight: 700;
        cursor: pointer;
    }

    .modal-cancel {
        border: 1px solid rgba(157,184,163,.25);
        border-radius: 22px;
        padding: 10px 20px;
        background: transparent;
        color: #a9c0ae;
        cursor: pointer;
    }

    .delete-warning {
        text-align: center;
        padding: 10px 0;
    }

    .delete-warning i {
        font-size: 42px;
        color: #ff8a80;
        margin-bottom: 15px;
    }

    .delete-warning h4 {
        color: #f1f8e9;
        margin-bottom: 8px;
    }

    .delete-warning p {
        color: #8ea494;
        margin: 0;
    }

    .delete-confirm {
        border: 0;
        border-radius: 22px;
        padding: 11px 22px;
        background: #b71c1c;
        color: white;
        font-weight: 700;
        cursor: pointer;
    }

    /* Responsive */

    @media (max-width: 1100px) {
        .field-summary {
            grid-template-columns: repeat(2, 1fr);
        }

        .field-filters {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 650px) {
        .field-panel {
            padding: 20px;
        }

        .field-summary {
            grid-template-columns: 1fr;
        }

        .field-filters {
            grid-template-columns: 1fr;
        }

        .field-heading h1 {
            font-size: 28px;
        }
    }
</style>

<div class="field-page">

    {{-- PAGE HEADING --}}
    <div class="field-heading">
        <small>FARM MANAGEMENT</small>
        <h1>Field Management</h1>
        <p>View and manage field information submitted by Cardo-Fer farmers.</p>
    </div>

    {{-- SUMMARY --}}
    <div class="field-summary">

        <div class="summary-box">
            <div class="summary-icon">
                <i class="fa-solid fa-map"></i>
            </div>
            <div class="summary-label">Total Field Records</div>
            <div class="summary-value">{{ $totalFields }}</div>
        </div>

        <div class="summary-box">
            <div class="summary-icon">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="summary-label">Farmers With Fields</div>
            <div class="summary-value">{{ $totalFarmers }}</div>
        </div>

        <div class="summary-box">
            <div class="summary-icon">
                <i class="fa-solid fa-ruler-combined"></i>
            </div>
            <div class="summary-label">Total Field Area</div>
            <div class="summary-value">
                {{ number_format($totalAreaInAcres, 2) }}
                <span>acres</span>
            </div>
        </div>

        <div class="summary-box">
            <div class="summary-icon">
                <i class="fa-solid fa-seedling"></i>
            </div>
            <div class="summary-label">Total Cardamom Plants</div>
            <div class="summary-value">{{ number_format($totalPlants) }}</div>
        </div>

    </div>


    {{-- FIELD RECORDS --}}
    <div class="field-panel">

        <div>
            <span class="record-count">
                {{ $fields->total() }} records
            </span>

            <h2 class="field-panel-title">
                <i class="fa-solid fa-list"></i>
                Field Records
            </h2>

            <div class="field-divider"></div>
        </div>


        {{-- FILTERS --}}
        <form method="GET" action="{{ route('admin.field-management') }}">

            <div class="field-filters">

                <div class="field-search">
                    <i class="fa-solid fa-magnifying-glass"></i>

                    <input
                        type="text"
                        name="search"
                        class="field-input"
                        placeholder="Search farmer name or email..."
                        value="{{ request('search') }}"
                    >
                </div>

                <select name="unit" class="field-select">
                    <option value="">All Units</option>
                    <option value="acre" {{ request('unit') === 'acre' ? 'selected' : '' }}>
                        Acre
                    </option>
                    <option value="cent" {{ request('unit') === 'cent' ? 'selected' : '' }}>
                        Cent
                    </option>
                </select>

                <select name="sort" class="field-select">
                    <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>
                        Newest
                    </option>
                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>
                        Oldest
                    </option>
                    <option value="largest" {{ request('sort') === 'largest' ? 'selected' : '' }}>
                        Largest Area
                    </option>
                    <option value="plants" {{ request('sort') === 'plants' ? 'selected' : '' }}>
                        Most Plants
                    </option>
                </select>

                <button type="submit" class="field-btn">
                    <i class="fa-solid fa-filter"></i>
                    Apply
                </button>

            </div>

        </form>


        <div class="field-divider"></div>


        {{-- TABLE --}}
        <div class="field-table-wrap">

            @if($fields->count())

                <table class="field-table">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Farmer</th>
                            <th>Email</th>
                            <th>Field Area</th>
                            <th>Unit</th>
                            <th>Plants</th>
                            <th>Plant Density</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($fields as $field)

                            @php
                                $areaInAcres = $field->area_unit === 'cent'
                                    ? ((float) $field->area / 100)
                                    : (float) $field->area;

                                $density = $areaInAcres > 0
                                    ? ((int) $field->plant_count / $areaInAcres)
                                    : 0;

                                $farmerName = $field->user->name ?? 'Unknown Farmer';

                                $initial = strtoupper(substr($farmerName, 0, 1));
                            @endphp

                            <tr>

                                <td>
                                    {{ $loop->iteration + ($fields->currentPage() - 1) * $fields->perPage() }}
                                </td>

                                <td>
                                    <div class="farmer-info">

                                        <div class="farmer-avatar">
                                            {{ $initial }}
                                        </div>

                                        <div>
                                            <div class="farmer-name">
                                                {{ $farmerName }}
                                            </div>

                                            <div class="farmer-role">
                                                Farmer
                                            </div>
                                        </div>

                                    </div>
                                </td>

                                <td>
                                    <span class="field-email">
                                        {{ $field->user->email ?? '—' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="field-number">
                                        {{ number_format((float) $field->area, 2) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="unit-badge">
                                        {{ ucfirst($field->area_unit) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="field-number">
                                        {{ number_format($field->plant_count) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="density">
                                        {{ number_format($density) }} plants/acre
                                    </span>
                                </td>

                                <td>
                                    <span class="created-date">
                                        {{ $field->created_at->format('d M Y') }}
                                    </span>
                                </td>

                                <td>

                                    <div class="field-actions">

                                        {{-- VIEW --}}
                                        <button
                                            type="button"
                                            class="action-btn view-btn"
                                            title="View"
                                            data-id="{{ $field->id }}"
                                            data-name="{{ $farmerName }}"
                                            data-email="{{ $field->user->email ?? '—' }}"
                                            data-area="{{ number_format((float) $field->area, 2) }}"
                                            data-unit="{{ ucfirst($field->area_unit) }}"
                                            data-plants="{{ number_format($field->plant_count) }}"
                                            data-density="{{ number_format($density) }}"
                                            data-created="{{ $field->created_at->format('d M Y') }}"
                                        >
                                            <i class="fa-regular fa-eye"></i>
                                        </button>


                                        {{-- EDIT
                                        <button
                                            type="button"
                                            class="action-btn edit edit-btn"
                                            title="Edit"
                                            data-id="{{ $field->id }}"
                                            data-area="{{ $field->area }}"
                                            data-unit="{{ $field->area_unit }}"
                                            data-plants="{{ $field->plant_count }}"
                                        >
                                            <i class="fa-solid fa-pen"></i>
                                        </button> --}}


                                        {{-- DELETE --}}
                                        <button
                                            type="button"
                                            class="action-btn delete delete-btn"
                                            title="Delete"
                                            data-id="{{ $field->id }}"
                                        >
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            @else

                <div class="empty-state">
                    <i class="fa-solid fa-tree"></i>

                    <h4>No Field Records Found</h4>

                    <p>
                        No farmer field information matches your current search.
                    </p>
                </div>

            @endif

        </div>


        {{-- PAGINATION --}}
        @if($fields->hasPages())

            <div class="field-pagination">

                <span>
                    Showing
                    {{ $fields->firstItem() ?? 0 }}
                    –
                    {{ $fields->lastItem() ?? 0 }}
                    of
                    {{ $fields->total() }}
                    records
                </span>

                {{ $fields->withQueryString()->links() }}

            </div>

        @endif

    </div>

</div>


{{-- ========================================================= --}}
{{-- VIEW MODAL --}}
{{-- ========================================================= --}}

<div class="field-modal" id="viewModal">

    <div class="field-modal-box">

        <div class="field-modal-head">

            <h3>
                <i class="fa-solid fa-tree" style="color:#ffd54f;"></i>
                Field Details
            </h3>

            <button class="modal-close" type="button" data-close="viewModal">
                <i class="fa-solid fa-xmark"></i>
            </button>

        </div>


        <div class="detail-row">
            <span class="detail-label">Farmer</span>
            <span class="detail-value" id="viewName">—</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Email</span>
            <span class="detail-value" id="viewEmail">—</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Field Area</span>
            <span class="detail-value" id="viewArea">—</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Unit</span>
            <span class="detail-value" id="viewUnit">—</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Cardamom Plants</span>
            <span class="detail-value" id="viewPlants">—</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Plant Density</span>
            <span class="detail-value" id="viewDensity">—</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Registered</span>
            <span class="detail-value" id="viewCreated">—</span>
        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- EDIT MODAL --}}
{{-- ========================================================= --}}

{{-- <div class="field-modal" id="editModal">

    <div class="field-modal-box">

        <div class="field-modal-head">

            <h3>
                <i class="fa-solid fa-pen" style="color:#ffd54f;"></i>
                Edit Field
            </h3>

            <button class="modal-close" type="button" data-close="editModal">
                <i class="fa-solid fa-xmark"></i>
            </button>

        </div>


        <form method="POST" id="editFieldForm">

            @csrf
            @method('PUT')

            <div class="modal-form-group">

                <label>Field Area</label>

                <input
                    type="number"
                    name="area"
                    id="editArea"
                    class="field-input"
                    step="0.01"
                    min="0.01"
                    required
                >

            </div>


            <div class="modal-form-group">

                <label>Area Unit</label>

                <select
                    name="area_unit"
                    id="editUnit"
                    class="field-select"
                    required
                >
                    <option value="acre">Acre</option>
                    <option value="cent">Cent</option>
                </select>

            </div>


            <div class="modal-form-group">

                <label>Cardamom Plants</label>

                <input
                    type="number"
                    name="plant_count"
                    id="editPlants"
                    class="field-input"
                    min="1"
                    required
                >

            </div>


            <div class="modal-actions">

                <button type="submit" class="modal-save">
                    <i class="fa-solid fa-check"></i>
                    Save Changes
                </button>

                <button type="button" class="modal-cancel" data-close="editModal">
                    Cancel
                </button>

            </div>

        </form>

    </div>

</div> --}}


{{-- ========================================================= --}}
{{-- DELETE MODAL --}}
{{-- ========================================================= --}}

<div class="field-modal" id="deleteModal">

    <div class="field-modal-box">

        <div class="field-modal-head">

            <h3>
                Delete Field
            </h3>

            <button class="modal-close" type="button" data-close="deleteModal">
                <i class="fa-solid fa-xmark"></i>
            </button>

        </div>


        <div class="delete-warning">

            <i class="fa-solid fa-triangle-exclamation"></i>

            <h4>Delete this field record?</h4>

            <p>
                This action cannot be undone.
            </p>

        </div>


        <form method="POST" id="deleteFieldForm">

            @csrf
            @method('DELETE')

            <div class="modal-actions">

                <button type="submit" class="delete-confirm">
                    <i class="fa-solid fa-trash"></i>
                    Delete
                </button>

                <button
                    type="button"
                    class="modal-cancel"
                    data-close="deleteModal"
                >
                    Cancel
                </button>

            </div>

        </form>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    /* ==========================
       VIEW
    ========================== */

    document.querySelectorAll('.view-btn').forEach(function (button) {

        button.addEventListener('click', function () {

            document.getElementById('viewName').textContent =
                this.dataset.name;

            document.getElementById('viewEmail').textContent =
                this.dataset.email;

            document.getElementById('viewArea').textContent =
                this.dataset.area;

            document.getElementById('viewUnit').textContent =
                this.dataset.unit;

            document.getElementById('viewPlants').textContent =
                this.dataset.plants;

            document.getElementById('viewDensity').textContent =
                this.dataset.density + ' plants/acre';

            document.getElementById('viewCreated').textContent =
                this.dataset.created;

            document.getElementById('viewModal').classList.add('show');

        });

    });


    /* ==========================
       EDIT
    ========================== */

    document.querySelectorAll('.edit-btn').forEach(function (button) {

        button.addEventListener('click', function () {

            document.getElementById('editArea').value =
                this.dataset.area;

            document.getElementById('editUnit').value =
                this.dataset.unit;

            document.getElementById('editPlants').value =
                this.dataset.plants;

            document.getElementById('editFieldForm').action =
                `{{ url('/field-management') }}/${this.dataset.id}`;

            document.getElementById('editModal').classList.add('show');

        });

    });


    /* ==========================
       DELETE
    ========================== */

    document.querySelectorAll('.delete-btn').forEach(function (button) {

        button.addEventListener('click', function () {

            document.getElementById('deleteFieldForm').action =
                `{{ url('/field-management') }}/${this.dataset.id}`;

            document.getElementById('deleteModal').classList.add('show');

        });

    });


    /* ==========================
       CLOSE MODALS
    ========================== */

    document.querySelectorAll('[data-close]').forEach(function (button) {

        button.addEventListener('click', function () {

            document
                .getElementById(this.dataset.close)
                .classList.remove('show');

        });

    });


    /* ==========================
       CLICK OUTSIDE
    ========================== */

    document.querySelectorAll('.field-modal').forEach(function (modal) {

        modal.addEventListener('click', function (event) {

            if (event.target === modal) {
                modal.classList.remove('show');
            }

        });

    });


    /* ==========================
       ESC KEY
    ========================== */

    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape') {

            document.querySelectorAll('.field-modal').forEach(function (modal) {
                modal.classList.remove('show');
            });

        }

    });

});

</script>

@endsection