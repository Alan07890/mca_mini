@extends('layouts.AdminMaster')

@section('title', 'Fertilizer Management')

@section('content')

<style>
    /* =========================================================
       FERTILIZER MANAGEMENT
       Scoped styles for this page only
    ========================================================= */

    .fert-wrap {
        --fert-bg-deep: #0a1d15;
        --fert-bg-1: #1B5E20;
        --fert-bg-2: #2E7D32;
        --fert-leaf: #4CAF50;
        --fert-leaf-light: #81C784;
        --fert-cream: #F1F8E9;
        --fert-sage: #a9c9ae;
        --fert-gold: #FFD54F;
        --fert-gold-deep: #d5893f;
        --fert-line: rgba(241,248,233,0.10);
        --fert-line-strong: rgba(241,248,233,0.18);
        --fert-danger: #e2836b;

        font-family: 'Inter', Arial, sans-serif;
        color: var(--fert-cream);

        background: #0a1d15;
        padding: 28px;
        border-radius: 18px;
        min-height: calc(100vh - 180px);
    }

    /* ===== Header ===== */

    .fert-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 26px;
    }

    .fert-eyebrow {
        font-size: 11.5px;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--fert-leaf-light);
        margin-bottom: 8px;
        display: block;
    }

    .fert-header h1 {
        font-family: 'Fraunces', Georgia, serif;
        font-weight: 500;
        font-size: 28px;
        color: var(--fert-cream);
        margin: 0;
    }

    .fert-header p {
        margin-top: 6px;
        font-size: 13.5px;
        color: var(--fert-sage);
    }

    /* ===== Cards ===== */

    .fert-card {
        background: #10251b;
        border: 1px solid rgba(129,199,132,0.18);
        border-radius: 18px;
        padding: 26px;
        margin-bottom: 26px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.35);
    }

    .fert-card-title {
        display: flex;
        align-items: center;
        gap: 10px;

        font-family: 'Fraunces', Georgia, serif;
        font-size: 19px;
        font-weight: 500;

        color: var(--fert-cream);

        margin-bottom: 20px;
        padding-bottom: 16px;

        border-bottom: 1px solid var(--fert-line);
    }

    .fert-card-title i {
        color: var(--fert-gold);
        font-size: 16px;
    }

    /* ===== Alert ===== */

    .fert-alert {
        border: 1px solid rgba(129,199,132,0.3);
        background: rgba(76,175,80,0.10);
        color: var(--fert-leaf-light);

        border-radius: 12px;
        padding: 12px 16px;

        font-size: 13.5px;
        margin-bottom: 22px;
    }

    /* ===== Form ===== */

    .fert-label {
        font-size: 12px;
        letter-spacing: .4px;
        text-transform: uppercase;
        font-weight: 600;

        color: var(--fert-leaf-light);

        margin-bottom: 7px;
        display: block;
    }

    .fert-wrap .form-control,
    .fert-wrap .form-select {
        background: #07150f !important;
        border: 1px solid rgba(129,199,132,0.25) !important;

        border-radius: 10px;

        color: var(--fert-cream) !important;

        font-size: 14px;
        padding: 11px 14px;
    }

    .fert-wrap .form-control::placeholder {
        color: rgba(241,248,233,0.40) !important;
    }

    .fert-wrap .form-control:focus,
    .fert-wrap .form-select:focus {
        background: #07150f !important;

        color: var(--fert-cream) !important;

        border-color: var(--fert-gold) !important;

        box-shadow:
            0 0 0 3px rgba(255,213,79,0.15);
    }

    .fert-wrap .form-select option {
        background: var(--fert-bg-deep);
        color: var(--fert-cream);
    }

    .fert-wrap .invalid-feedback {
        color: var(--fert-danger);
        font-size: 12px;
    }

    .fert-wrap .is-invalid {
        border-color: var(--fert-danger) !important;
    }

    /* ===== Buttons ===== */

    .fert-btn-primary {
        background:
            linear-gradient(
                180deg,
                var(--fert-gold),
                var(--fert-gold-deep)
            );

        border: none;

        color: #241708;

        font-weight: 700;
        font-size: 14px;

        border-radius: 999px;

        padding: 10px 22px;

        transition:
            transform .15s ease,
            box-shadow .15s ease;
    }

    .fert-btn-primary:hover {
        color: #241708;

        transform: translateY(-1px);

        box-shadow:
            0 14px 26px -12px rgba(255,213,79,0.55);
    }

    .fert-btn-secondary {
        background: transparent;

        border: 1px solid var(--fert-line-strong);

        color: var(--fert-sage);

        font-weight: 600;
        font-size: 14px;

        border-radius: 999px;

        padding: 10px 22px;

        transition:
            border-color .15s ease,
            color .15s ease;
    }

    .fert-btn-secondary:hover {
        border-color: var(--fert-leaf-light);
        color: var(--fert-cream);
    }

    /* ===== Dynamic dosage sections ===== */

    .fert-dose-note {
        margin-top: 7px;

        font-size: 11.5px;

        color: var(--fert-sage);
    }

    .fert-hidden {
        display: none !important;
    }

    /* ===== Table ===== */

    .fert-table {
        width: 100%;

        border-collapse: separate;
        border-spacing: 0;

        color: var(--fert-cream);
    }

    .fert-table thead th {
        font-size: 11.5px;

        letter-spacing: .5px;

        text-transform: uppercase;

        color: var(--fert-leaf-light);

        font-weight: 600;

        border-bottom:
            1px solid var(--fert-line-strong);

        padding: 10px 12px;

        text-align: left;
    }

    .fert-table tbody td {
        padding: 14px 12px;

        border-bottom:
            1px solid var(--fert-line);

        font-size: 13.8px;

        vertical-align: middle;

        color: var(--fert-cream);
    }

    .fert-table tbody tr:hover {
        background: rgba(241,248,233,0.03);
    }

    .fert-desc-cell {
        color: var(--fert-sage) !important;
        max-width: 320px;
    }

    .fert-amount {
        font-weight: 700;

        color: var(--fert-cream);

        white-space: nowrap;
    }

    .fert-dose-sub {
        display: block;

        margin-top: 4px;

        font-size: 11px;

        color: var(--fert-sage);
    }

    /* ===== Category badge ===== */

    .fert-badge {
        display: inline-block;

        padding: 5px 12px;

        border-radius: 999px;

        font-size: 11.5px;

        font-weight: 600;

        letter-spacing: .3px;
    }

    .fert-badge-organic {
        background: rgba(76,175,80,0.14);

        color: var(--fert-leaf-light);

        border: 1px solid rgba(76,175,80,0.3);
    }

    .fert-badge-inorganic {
        background: rgba(255,213,79,0.12);

        color: var(--fert-gold);

        border:
            1px solid rgba(255,213,79,0.28);
    }

    /* ===== Action buttons ===== */

    .fert-action-btn {
        background: #07150f;

        border:
            1px solid var(--fert-line-strong);

        color: var(--fert-sage);

        border-radius: 8px;

        padding: 6px 10px;

        font-size: 12.5px;

        margin-right: 6px;

        transition:
            border-color .15s ease,
            color .15s ease;
    }

    .fert-action-btn:hover {
        color: var(--fert-cream);
    }

    .fert-action-btn.edit:hover {
        border-color: var(--fert-leaf-light);
    }

    .fert-action-btn.delete:hover {
        border-color: var(--fert-danger);
        color: var(--fert-danger);
    }

    /* ===== Empty ===== */

    .fert-empty {
        text-align: center;

        padding: 40px 10px;

        color: var(--fert-sage);

        font-size: 13.5px;
    }

    /* ===== Modal ===== */

    .fert-wrap .modal-content {
        background: var(--fert-bg-deep);

        border:
            1px solid var(--fert-line-strong);

        border-radius: 16px;

        color: var(--fert-cream);
    }

    .fert-wrap .modal-header {
        border-bottom:
            1px solid var(--fert-line);
    }

    .fert-wrap .modal-footer {
        border-top:
            1px solid var(--fert-line);
    }

    .fert-wrap .modal-title {
        font-family:
            'Fraunces', Georgia, serif;

        font-weight: 500;

        color: var(--fert-cream);
    }

    .fert-wrap .btn-close {
        filter:
            invert(1)
            grayscale(1)
            brightness(1.8);
    }

    /* ===== Responsive ===== */

    @media (max-width: 768px) {

        .fert-wrap {
            padding: 18px;
        }

    }

    @media (max-width: 576px) {

        .fert-table thead {
            display: none;
        }

        .fert-table,
        .fert-table tbody,
        .fert-table tr,
        .fert-table td {
            display: block;
            width: 100%;
        }

        .fert-table tr {
            border:
                1px solid var(--fert-line-strong);

            border-radius: 12px;

            margin-bottom: 12px;

            padding: 8px 4px;
        }

        .fert-table td {
            border-bottom: none;

            padding: 8px 12px;
        }

        .fert-table td::before {
            content: attr(data-label);

            display: block;

            font-size: 10.5px;

            text-transform: uppercase;

            letter-spacing: .4px;

            color: var(--fert-leaf-light);

            margin-bottom: 3px;
        }

        .fert-amount {
            white-space: normal;
        }

        .fert-action-btn {
            margin-bottom: 5px;
        }
    }
</style>


<div class="fert-wrap">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="fert-header">

        <div>

            <span class="fert-eyebrow">
                Admin · Inputs Catalog
            </span>

            <h1>
                Fertilizer Management
            </h1>

            <p>
                Add and maintain the fertilizers recommended to farmers.
            </p>

        </div>

    </div>


    {{-- =====================================================
         SUCCESS MESSAGE
    ====================================================== --}}

    @if (session('success'))

        <div class="fert-alert">

            <i class="fa-solid fa-circle-check me-2"></i>

            {{ session('success') }}

        </div>

    @endif


    @if (session('error'))

        <div class="fert-alert" style="border-color: rgba(226,131,107,0.35); background: rgba(226,131,107,0.10); color: var(--fert-danger);">

            <i class="fa-solid fa-circle-exclamation me-2"></i>

            {{ session('error') }}

        </div>

    @endif


    {{-- =====================================================
         ADD FERTILIZER
    ====================================================== --}}

    <div class="fert-card">

        <div class="fert-card-title">

            <i class="fa-solid fa-flask"></i>

            Add Fertilizer

        </div>


        <form
            method="POST"
            action="{{ route('fertilizer_insert') }}"
            id="addFertilizerForm"
        >

            @csrf


            <div class="row g-3">


                {{-- Fertilizer Name --}}
                <div class="col-md-6">

                    <label
                        for="fertilizer_name"
                        class="fert-label"
                    >
                        Fertilizer Name
                    </label>

                    <input
                        type="text"
                        id="fertilizer_name"
                        name="fertilizer_name"
                        value="{{ old('fertilizer_name') }}"
                        class="form-control @error('fertilizer_name') is-invalid @enderror"
                        placeholder="e.g. Compost"
                        required
                    >

                    @error('fertilizer_name')

                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Category --}}
                <div class="col-md-6">

                    <label
                        for="category"
                        class="fert-label"
                    >
                        Category
                    </label>

                    <select
                        id="category"
                        name="category"
                        class="form-select @error('category') is-invalid @enderror"
                        required
                    >

                        <option
                            value=""
                            disabled
                            {{ old('category') ? '' : 'selected' }}
                        >
                            Select category
                        </option>

                        <option
                            value="Organic"
                            {{ old('category') === 'Organic' ? 'selected' : '' }}
                        >
                            Organic
                        </option>

                        <option
                            value="Inorganic"
                            {{ old('category') === 'Inorganic' ? 'selected' : '' }}
                        >
                            Inorganic
                        </option>

                    </select>

                    @error('category')

                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- =================================================
                     ORGANIC DOSAGE
                ================================================== --}}

                <div
                    class="col-md-6"
                    id="organicDosageAmountGroup"
                >

                    <label
                        for="dosage_amount"
                        class="fert-label"
                        id="dosageAmountLabel"
                    >
                        Dosage Amount
                    </label>

                    <input
                        type="number"
                        id="dosage_amount"
                        name="dosage_amount"
                        value="{{ old('dosage_amount') }}"
                        class="form-control @error('dosage_amount') is-invalid @enderror"
                        placeholder="e.g. 2"
                        min="0.01"
                        step="0.01"
                    >

                    @error('dosage_amount')

                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Organic / Inorganic dosage unit --}}
                <div
                    class="col-md-6"
                    id="dosageUnitGroup"
                >

                    <label
                        for="dosage_unit"
                        class="fert-label"
                    >
                        Unit
                    </label>

                    <select
                        id="dosage_unit"
                        name="dosage_unit"
                        class="form-select @error('dosage_unit') is-invalid @enderror"
                    >

                        <option value="" disabled selected>
                            Select unit
                        </option>

                        <option
                            value="g"
                            {{ old('dosage_unit') === 'g' ? 'selected' : '' }}
                        >
                            Grams (g)
                        </option>

                        <option
                            value="kg"
                            {{ old('dosage_unit') === 'kg' ? 'selected' : '' }}
                        >
                            Kilograms (kg)
                        </option>

                        <option
                            value="ml"
                            {{ old('dosage_unit') === 'ml' ? 'selected' : '' }}
                        >
                            Milliliters (ml)
                        </option>

                        <option
                            value="L"
                            {{ old('dosage_unit') === 'L' ? 'selected' : '' }}
                        >
                            Liters (L)
                        </option>

                    </select>

                    @error('dosage_unit')

                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>

                    @enderror

                    <div class="fert-dose-note">
                        Example: 1 kg per plant, 25 kg per hectare, or 250 g per 100 L of water.
                    </div>

                </div>


                {{-- =================================================
                     INORGANIC WATER QUANTITY
                ================================================== --}}

                <div
                    class="col-md-6"
                    id="referenceQuantityGroup"
                >

                    <label
                        for="reference_quantity"
                        class="fert-label"
                    >
                        Reference Quantity
                    </label>

                    <input
                        type="number"
                        id="reference_quantity"
                        name="reference_quantity"
                        value="{{ old('reference_quantity') }}"
                        class="form-control @error('reference_quantity') is-invalid @enderror"
                        placeholder="e.g. 500"
                        min="0.01"
                        step="0.01"
                    >

                    @error('reference_quantity')

                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                <div
                    class="col-md-6"
                    id="referenceUnitGroup"
                >

                    <label
                        for="reference_unit"
                        class="fert-label"
                    >
                        Reference Unit
                    </label>

                    <select
                        id="reference_unit"
                        name="reference_unit"
                        class="form-select @error('reference_unit') is-invalid @enderror"
                    >

                        <option
                            value=""
                            disabled
                            {{ old('reference_unit') ? '' : 'selected' }}
                        >
                            Select reference
                        </option>

                        <option
                            value="plant"
                            {{ old('reference_unit') === 'plant' ? 'selected' : '' }}
                        >
                            Per plant
                        </option>

                        <option
                            value="ha"
                            {{ old('reference_unit') === 'ha' ? 'selected' : '' }}
                        >
                            Per hectare (ha)
                        </option>

                        <option
                            value="acre"
                            {{ old('reference_unit') === 'acre' ? 'selected' : '' }}
                        >
                            Per acre
                        </option>

                        <option
                            value="L"
                            {{ old('reference_unit') === 'L' ? 'selected' : '' }}
                        >
                            Per liter (L)
                        </option>

                        <option
                            value="ml"
                            {{ old('reference_unit') === 'ml' ? 'selected' : '' }}
                        >
                            Per milliliter (ml)
                        </option>

                    </select>

                    @error('reference_unit')

                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Description --}}
                <div class="col-12">

                    <label
                        for="description"
                        class="fert-label"
                    >
                        Description
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="3"
                        class="form-control @error('description') is-invalid @enderror"
                        placeholder="Briefly describe this fertilizer..."
                        required
                    >{{ old('description') }}</textarea>

                    @error('description')

                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

            </div>


            <div class="d-flex gap-2 mt-4">

                <button
                    type="submit"
                    class="fert-btn-primary"
                >
                    Add Fertilizer
                </button>

                <button
                    type="reset"
                    class="fert-btn-secondary"
                    id="resetAddFertilizer"
                >
                    Cancel
                </button>

            </div>

        </form>

    </div>



    {{-- =========================================================
         DISEASE → FERTILIZER MAPPING
    ========================================================== --}}

    <div class="fert-card">

        <div class="fert-card-title">

            <i class="fa-solid fa-link"></i>

            Disease → Fertilizer Mapping

        </div>


        <form
            method="POST"
            action="{{ route('fertilizer_mapping_store') }}"
        >

            @csrf

            <div class="row g-3">

                {{-- Disease --}}
                <div class="col-md-6">

                    <label
                        for="mapping_category_id"
                        class="fert-label"
                    >
                        Disease
                    </label>

                    <select
                        id="mapping_category_id"
                        name="category_id"
                        class="form-select @error('category_id') is-invalid @enderror"
                        required
                    >

                        <option
                            value=""
                            disabled
                            selected
                        >
                            Select disease
                        </option>

                        @foreach($categories as $category)

                            <option
                                value="{{ $category->category_id }}"
                                {{ old('category_id') == $category->category_id ? 'selected' : '' }}
                            >
                                {{ $category->diseases_name }}
                            </option>

                        @endforeach

                    </select>

                    @error('category_id')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Fertilizer --}}
                <div class="col-md-6">

                    <label
                        for="mapping_fertilizer_id"
                        class="fert-label"
                    >
                        Fertilizer
                    </label>

                    <select
                        id="mapping_fertilizer_id"
                        name="fertilizer_id"
                        class="form-select @error('fertilizer_id') is-invalid @enderror"
                        required
                    >

                        <option
                            value=""
                            disabled
                            selected
                        >
                            Select fertilizer
                        </option>

                        @foreach($fertilizers as $fertilizer)

                            <option
                                value="{{ $fertilizer->id }}"
                                {{ old('fertilizer_id') == $fertilizer->id ? 'selected' : '' }}
                            >
                                {{ $fertilizer->fertilizer_name }}
                            </option>

                        @endforeach

                    </select>

                    @error('fertilizer_id')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>


            <div class="mt-4">

                <button
                    type="submit"
                    class="fert-btn-primary"
                >

                    <i class="fa-solid fa-link me-1"></i>

                    Map Fertilizer

                </button>

            </div>

        </form>

    </div>


    {{-- =========================================================
         DISEASE FERTILIZER MAPPING LIST
    ========================================================== --}}

    <div class="fert-card">

        <div class="fert-card-title">

            <i class="fa-solid fa-list-check"></i>

            Disease Fertilizer Mappings

        </div>


        <div class="table-responsive">

            <table class="fert-table">

                <thead>

                    <tr>

                        <th>
                            Disease
                        </th>

                        <th>
                            Fertilizer
                        </th>

                        <th>
                            Category
                        </th>

                        <th>
                            Dosage
                        </th>

                        <th>
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($diseaseFertilizers as $mapping)

                        <tr>

                            <td data-label="Disease">

                                {{ $mapping->disease?->diseases_name ?? 'Unknown Disease' }}

                            </td>


                            <td data-label="Fertilizer">

                                {{ $mapping->fertilizer?->fertilizer_name ?? 'Unknown Fertilizer' }}

                            </td>


                            <td data-label="Category">

                                @if($mapping->fertilizer)

                                    <span
                                        class="fert-badge {{
                                            $mapping->fertilizer->category === 'Organic'
                                                ? 'fert-badge-organic'
                                                : 'fert-badge-inorganic'
                                        }}"
                                    >

                                        {{ $mapping->fertilizer->category }}

                                    </span>

                                @else

                                    —

                                @endif

                            </td>


                            <td data-label="Dosage">

                                @if($mapping->fertilizer)

                                    {{ $mapping->fertilizer->dosage_amount }}
                                    {{ $mapping->fertilizer->dosage_unit }}

                                    @if(
                                        $mapping->fertilizer->reference_quantity &&
                                        $mapping->fertilizer->reference_unit
                                    )

                                        /
                                        {{ $mapping->fertilizer->reference_quantity }}
                                        {{ $mapping->fertilizer->reference_unit }}

                                    @elseif($mapping->fertilizer->category === 'Organic')

                                        / plant

                                    @endif

                                @else

                                    —

                                @endif

                            </td>


                            <td data-label="Action">

                                <form
                                    method="POST"
                                    action="{{ route(
                                        'fertilizer_mapping_delete',
                                        $mapping->id
                                    ) }}"
                                    onsubmit="return confirm('Remove this fertilizer mapping?');"
                                >

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="fert-action-btn delete"
                                    >

                                        <i class="fa-solid fa-trash"></i>

                                        Remove

                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5">

                                <div class="fert-empty">

                                    No disease-fertilizer mappings added yet.

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- =====================================================
         FERTILIZER LIST
    ====================================================== --}}

    <div class="fert-card">

        <div class="fert-card-title">

            <i class="fa-solid fa-list"></i>

            Fertilizer List

        </div>


        <div class="table-responsive">

            <table class="fert-table">

                <thead>

                    <tr>

                        <th>
                            Fertilizer Name
                        </th>

                        <th>
                            Dosage
                        </th>

                        <th>
                            Description
                        </th>

                        <th>
                            Category
                        </th>

                        <th>
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($fertilizers as $fertilizer)

                        <tr>

                            {{-- Name --}}
                            <td data-label="Fertilizer Name">

                                {{ $fertilizer->fertilizer_name }}

                            </td>


                            {{-- Dosage --}}
                            <td data-label="Dosage">

                                <span class="fert-amount">

                                    @if (
                                        $fertilizer->dosage_amount &&
                                        $fertilizer->dosage_unit
                                    )

                                        {{ $fertilizer->dosage_amount }}
                                        {{ $fertilizer->dosage_unit }}

                                        @if (
                                            $fertilizer->reference_quantity &&
                                            $fertilizer->reference_unit
                                        )

                                            /
                                            {{ $fertilizer->reference_quantity }}
                                            {{ $fertilizer->reference_unit }}

                                        @elseif($fertilizer->category === 'Organic')

                                            / plant

                                        @endif

                                    @else

                                        Not set

                                    @endif

                                </span>

                            </td>


                            {{-- Description --}}
                            <td
                                data-label="Description"
                                class="fert-desc-cell"
                            >

                                {{ $fertilizer->description }}

                            </td>


                            {{-- Category --}}
                            <td data-label="Category">

                                <span
                                    class="fert-badge
                                        {{ $fertilizer->category === 'Organic'
                                            ? 'fert-badge-organic'
                                            : 'fert-badge-inorganic' }}"
                                >

                                    {{ $fertilizer->category }}

                                </span>

                            </td>


                            {{-- Actions --}}
                            <td data-label="Actions">

                                {{-- Edit --}}
                                <button
                                    type="button"
                                    class="fert-action-btn edit"

                                    data-bs-toggle="modal"
                                    data-bs-target="#editFertilizerModal"

                                    data-id="{{ $fertilizer->id }}"

                                    data-name="{{ $fertilizer->fertilizer_name }}"

                                    data-category="{{ $fertilizer->category }}"

                                    data-dosage-amount="{{ $fertilizer->dosage_amount }}"

                                    data-dosage-unit="{{ $fertilizer->dosage_unit }}"

                                    data-reference-quantity="{{ $fertilizer->reference_quantity }}"

                                    data-reference-unit="{{ $fertilizer->reference_unit }}"

                                    data-description="{{ $fertilizer->description }}"

                                    data-action="{{ route('fertilizer_update', $fertilizer->id) }}"
                                >

                                    <i class="fa-solid fa-pen"></i>
                                    Edit

                                </button>


                                {{-- Delete --}}
                                <button
                                    type="button"
                                    class="fert-action-btn delete"

                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteFertilizerModal"

                                    data-name="{{ $fertilizer->fertilizer_name }}"

                                    data-action="{{ route('fertilizer_delete', $fertilizer->id) }}"
                                >

                                    <i class="fa-solid fa-trash"></i>
                                    Delete

                                </button>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5">

                                <div class="fert-empty">
                                    No fertilizers added yet.
                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- =========================================================
     EDIT FERTILIZER MODAL
========================================================= --}}

<div
    class="modal fade fert-wrap"
    id="editFertilizerModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                method="POST"
                id="editFertilizerForm"
                action=""
            >

                @csrf

                @method('PUT')


                <div class="modal-header">

                    <h5 class="modal-title">
                        Edit Fertilizer
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>

                </div>


                <div class="modal-body">


                    {{-- Fertilizer name --}}
                    <div class="mb-3">

                        <label
                            for="edit_name"
                            class="fert-label"
                        >
                            Fertilizer Name
                        </label>

                        <input
                            type="text"
                            id="edit_name"
                            name="fertilizer_name"
                            class="form-control"
                            required
                        >

                    </div>


                    {{-- Category --}}
                    <div class="mb-3">

                        <label
                            for="edit_category"
                            class="fert-label"
                        >
                            Category
                        </label>

                        <select
                            id="edit_category"
                            name="category"
                            class="form-select"
                            required
                        >

                            <option value="Organic">
                                Organic
                            </option>

                            <option value="Inorganic">
                                Inorganic
                            </option>

                        </select>

                    </div>


                    {{-- Dosage amount --}}
                    <div class="mb-3">

                        <label
                            for="edit_dosage_amount"
                            class="fert-label"
                            id="editDosageAmountLabel"
                        >
                            Dosage Amount
                        </label>

                        <input
                            type="number"
                            id="edit_dosage_amount"
                            name="dosage_amount"
                            class="form-control"
                            min="0.01"
                            step="0.01"
                            required
                        >

                    </div>


                    {{-- Dosage unit --}}
                    <div class="mb-3">

                        <label
                            for="edit_dosage_unit"
                            class="fert-label"
                        >
                            Unit
                        </label>

                        <select
                            id="edit_dosage_unit"
                            name="dosage_unit"
                            class="form-select"
                            required
                        >

                            <option value="g">
                                Grams (g)
                            </option>

                            <option value="kg">
                                Kilograms (kg)
                            </option>

                            <option value="ml">
                                Milliliters (ml)
                            </option>

                            <option value="L">
                                Liters (L)
                            </option>

                        </select>

                    </div>


                    {{-- Inorganic reference quantity --}}
                    <div
                        class="mb-3"
                        id="editReferenceQuantityGroup"
                    >

                        <label
                            for="edit_reference_quantity"
                            class="fert-label"
                        >
                            Reference Quantity
                        </label>

                        <input
                            type="number"
                            id="edit_reference_quantity"
                            name="reference_quantity"
                            class="form-control"
                            min="0.01"
                            step="0.01"
                        >

                    </div>


                    {{-- Inorganic reference unit --}}
                    <div
                        class="mb-3"
                        id="editReferenceUnitGroup"
                    >

                        <label
                            for="edit_reference_unit"
                            class="fert-label"
                        >
                            Reference Unit
                        </label>

                        <select
                            id="edit_reference_unit"
                            name="reference_unit"
                            class="form-select"
                        >

                            <option value="plant">
                                Per plant
                            </option>

                            <option value="ha">
                                Per hectare (ha)
                            </option>

                            <option value="acre">
                                Per acre
                            </option>

                            <option value="L">
                                Per liter (L)
                            </option>

                            <option value="ml">
                                Per milliliter (ml)
                            </option>

                        </select>

                    </div>


                    {{-- Description --}}
                    <div class="mb-1">

                        <label
                            for="edit_description"
                            class="fert-label"
                        >
                            Description
                        </label>

                        <textarea
                            id="edit_description"
                            name="description"
                            rows="3"
                            class="form-control"
                            required
                        ></textarea>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="fert-btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="fert-btn-primary"
                    >
                        Save Changes
                    </button>

                </div>


            </form>

        </div>

    </div>

</div>


{{-- =========================================================
     DELETE CONFIRMATION MODAL
========================================================= --}}

<div
    class="modal fade fert-wrap"
    id="deleteFertilizerModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title">
                    Delete Fertilizer
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>

            </div>


            <div class="modal-body">

                Are you sure you want to delete

                <strong id="deleteFertilizerName"></strong>?

                This cannot be undone.

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="fert-btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Cancel
                </button>


                <form
                    method="POST"
                    id="deleteFertilizerForm"
                    action=""
                >

                    @csrf

                    @method('DELETE')

                    <button
                        type="submit"
                        class="fert-btn-primary"
                        style="
                            background:
                                linear-gradient(
                                    180deg,
                                    #e2836b,
                                    #c9634a
                                );
                            color:#2b0d06;
                        "
                    >

                        Delete

                    </button>

                </form>

            </div>


        </div>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {


    /* =========================================================
       ELEMENTS
    ========================================================= */

    const categorySelect =
        document.getElementById('category');

    const dosageAmount =
        document.getElementById('dosage_amount');

    const dosageUnit =
        document.getElementById('dosage_unit');

    const dosageAmountLabel =
        document.getElementById('dosageAmountLabel');

    const referenceQuantityGroup =
        document.getElementById('referenceQuantityGroup');

    const referenceQuantity =
        document.getElementById('reference_quantity');

    const referenceUnitGroup =
        document.getElementById('referenceUnitGroup');

    const referenceUnit =
        document.getElementById('reference_unit');


    /* =========================================================
       SHOW / HIDE ADD-FORM FIELDS
    ========================================================= */

    function updateAddFormFields() {

        dosageAmountLabel.textContent = 'Dosage Amount';

        referenceQuantityGroup
            .classList
            .remove('fert-hidden');

        referenceUnitGroup
            .classList
            .remove('fert-hidden');

        dosageAmount.required = true;
        dosageUnit.required = true;
        referenceQuantity.required = true;
        referenceUnit.required = true;

    }


    /* =========================================================
       CATEGORY CHANGE
    ========================================================= */

    categorySelect.addEventListener(
        'change',
        updateAddFormFields
    );


    /* =========================================================
       INITIAL FORM STATE
    ========================================================= */

    updateAddFormFields();


    /* =========================================================
       RESET ADD FORM
    ========================================================= */

    const resetButton =
        document.getElementById('resetAddFertilizer');

    resetButton.addEventListener(
        'click',
        function () {

            setTimeout(function () {

                categorySelect.value = '';

                dosageAmount.value = '';

                dosageUnit.value = '';

                referenceQuantity.value = '';

                referenceUnit.value = '';

                updateAddFormFields();

            }, 0);

        }
    );


    /* =========================================================
       EDIT MODAL
    ========================================================= */

    const editModal =
        document.getElementById(
            'editFertilizerModal'
        );


    const editCategory =
        document.getElementById(
            'edit_category'
        );


    const editDosageAmount =
        document.getElementById(
            'edit_dosage_amount'
        );


    const editDosageUnit =
        document.getElementById(
            'edit_dosage_unit'
        );


    const editDosageAmountLabel =
        document.getElementById(
            'editDosageAmountLabel'
        );


    const editReferenceQuantityGroup =
        document.getElementById(
            'editReferenceQuantityGroup'
        );


    const editReferenceQuantity =
        document.getElementById(
            'edit_reference_quantity'
        );


    const editReferenceUnitGroup =
        document.getElementById(
            'editReferenceUnitGroup'
        );


    const editReferenceUnit =
        document.getElementById(
            'edit_reference_unit'
        );


    /*
     * Update Edit modal fields
     */

    function updateEditFields() {

        editDosageAmountLabel.textContent =
            'Dosage Amount';

        editReferenceQuantityGroup
            .classList
            .remove('fert-hidden');

        editReferenceUnitGroup
            .classList
            .remove('fert-hidden');

        editReferenceQuantity.required = true;
        editReferenceUnit.required = true;

        editDosageUnit.innerHTML = `
            <option value="g">
                Grams (g)
            </option>

            <option value="kg">
                Kilograms (kg)
            </option>

            <option value="ml">
                Milliliters (ml)
            </option>

            <option value="L">
                Liters (L)
            </option>
        `;

    }


    /*
     * Category change in Edit modal
     */

    editCategory.addEventListener(
        'change',
        updateEditFields
    );


    /*
     * Populate Edit modal
     */

    editModal.addEventListener(
        'show.bs.modal',
        function (event) {

            const btn =
                event.relatedTarget;


            document
                .getElementById(
                    'editFertilizerForm'
                )
                .action =
                    btn.getAttribute(
                        'data-action'
                    );


            document
                .getElementById('edit_name')
                .value =
                    btn.getAttribute(
                        'data-name'
                    );


            editCategory.value =
                btn.getAttribute(
                    'data-category'
                );


            editDosageAmount.value =
                btn.getAttribute(
                    'data-dosage-amount'
                ) || '';


            /*
             * Set category-specific fields first
             */

            updateEditFields();


            editDosageUnit.value =
                btn.getAttribute(
                    'data-dosage-unit'
                ) || '';


            editReferenceQuantity.value =
                btn.getAttribute(
                    'data-reference-quantity'
                ) || '';


            editReferenceUnit.value =
                btn.getAttribute(
                    'data-reference-unit'
                ) || '';


            document
                .getElementById(
                    'edit_description'
                )
                .value =
                    btn.getAttribute(
                        'data-description'
                    ) || '';

        }
    );


    /* =========================================================
       DELETE MODAL
    ========================================================= */

    const deleteModal =
        document.getElementById(
            'deleteFertilizerModal'
        );


    deleteModal.addEventListener(
        'show.bs.modal',
        function (event) {

            const btn =
                event.relatedTarget;


            document
                .getElementById(
                    'deleteFertilizerForm'
                )
                .action =
                    btn.getAttribute(
                        'data-action'
                    );


            document
                .getElementById(
                    'deleteFertilizerName'
                )
                .textContent =
                    btn.getAttribute(
                        'data-name'
                    );

        }
    );


});

</script>

@endsection