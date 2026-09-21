@extends('layouts.AdminMaster')

@section('content')

<style>
    .symptom-reg-page {
        width: 100%;
        padding: 5px 18px 24px;
    }

    /* =========================
       MAIN CARD
    ========================== */

    .symptom-reg-card {
        width: 100%;
        max-width: none;
        margin: 0;

        background: #10251b;

        border: 1px solid rgba(129, 199, 132, 0.10);
        border-radius: 22px;

        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.22);

        overflow: hidden;
    }

    /* =========================
       HEADER
    ========================== */

    .symptom-reg-header {
        position: relative;

        background: linear-gradient(
            120deg,
            #0d2d1d 0%,
            #123c17 52%,
            #1b5e20 100%
        );

        color: #fff;

        padding: 38px 42px 34px;

        overflow: hidden;
    }

    .symptom-reg-header::before {
        content: "";

        position: absolute;

        width: 240px;
        height: 240px;

        right: -75px;
        top: -95px;

        border: 1px solid rgba(255, 213, 79, 0.12);
        border-radius: 50%;
    }

    .symptom-reg-header::after {
        content: "";

        position: absolute;

        width: 120px;
        height: 120px;

        left: -45px;
        bottom: -48px;

        border: 1px solid rgba(129, 199, 132, 0.10);
        border-radius: 50%;
    }

    .symptom-reg-title {
        position: relative;
        z-index: 1;

        display: flex;
        align-items: center;

        gap: 12px;

        font-size: 1.7rem;
        font-weight: 700;

        color: #ffffff;

        margin-bottom: 7px;
    }

    .symptom-reg-title i {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        width: 44px;
        height: 44px;

        border-radius: 12px;

        background: rgba(255, 213, 79, 0.10);

        border: 1px solid rgba(255, 213, 79, 0.12);

        color: #ffd54f;

        font-size: 1rem;
    }

    .symptom-reg-subtitle {
        position: relative;
        z-index: 1;

        margin: 0;

        max-width: 900px;

        font-size: 0.92rem;
        font-weight: 300;

        color: #c6dcc9;
    }

    /* =========================
       BODY
    ========================== */

    .symptom-reg-body {
        padding: 38px 42px 32px;
    }

    /* =========================
       FORM LABEL
    ========================== */

    .symptom-form-label {
        display: flex;
        align-items: center;

        gap: 8px;

        font-size: 0.9rem;
        font-weight: 600;

        color: #f1f8e9;

        margin-bottom: 9px;
    }

    .symptom-form-label i {
        color: #81c784;
    }

    /* =========================
       SELECT
    ========================== */

    .symptom-select {
        width: 100%;

        border: 1px solid #294936;
        border-radius: 12px;

        background: #0b1d14;

        padding: 13px 14px;

        font-size: 0.92rem;

        color: #f1f8e9;

        transition:
            border-color .18s ease,
            box-shadow .18s ease,
            background .18s ease;
    }

    .symptom-select:focus {
        border-color: #81c784;

        box-shadow:
            0 0 0 4px rgba(129, 199, 132, 0.08);

        outline: none;

        background: #0d2117;
    }

    .symptom-select option {
        background: #10251b;
        color: #f1f8e9;
    }

    /* =========================
       SECTION HEADER
    ========================== */

    .symptom-section-head {
        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 10px;

        margin-bottom: 12px;
    }

    .selected-count {
        font-size: 0.78rem;
        font-weight: 600;

        color: #ffd54f;

        background: rgba(255, 213, 79, 0.09);

        border: 1px solid rgba(255, 213, 79, 0.15);

        padding: 5px 12px;

        border-radius: 20px;

        white-space: nowrap;
    }

    /* =========================
       SEARCH
    ========================== */

    .symptom-search {
        position: relative;

        margin-bottom: 16px;
    }

    .symptom-search i {
        position: absolute;

        left: 15px;
        top: 50%;

        transform: translateY(-50%);

        color: #81c784;

        font-size: 0.85rem;
    }

    .symptom-search input {
        width: 100%;

        border: 1px solid #294936;
        border-radius: 12px;

        background: #0b1d14;

        color: #f1f8e9;

        padding: 13px 15px 13px 42px;

        font-size: 0.9rem;

        outline: none;

        transition:
            border-color .18s ease,
            box-shadow .18s ease;
    }

    .symptom-search input::placeholder {
        color: #718b77;
    }

    .symptom-search input:focus {
        border-color: #81c784;

        box-shadow:
            0 0 0 4px rgba(129, 199, 132, 0.08);
    }

    /* =========================
       SYMPTOMS CONTAINER
    ========================== */

    .symptoms-container {
        width: 100%;

        border: 1px solid #294936;
        border-radius: 16px;

        padding: 18px;

        background:
            linear-gradient(
                160deg,
                #0d2117 0%,
                #10251b 100%
            );

        max-height: 420px;

        overflow-y: auto;
    }

    .symptoms-container::-webkit-scrollbar {
        width: 7px;
    }

    .symptoms-container::-webkit-scrollbar-track {
        background: #0a1d15;
    }

    .symptoms-container::-webkit-scrollbar-thumb {
        background: #396b43;

        border-radius: 10px;
    }

    .symptoms-container::-webkit-scrollbar-thumb:hover {
        background: #4f8a58;
    }

    /* =========================
       SYMPTOM GRID
    ========================== */

    .symptom-grid {
        display: grid;

        grid-template-columns:
            repeat(3, minmax(0, 1fr));

        gap: 11px;
    }

    .symptom-card {
        background: #12291e;

        border: 1px solid #294936;

        border-radius: 12px;

        padding: 13px 14px;

        display: flex;
        align-items: center;

        gap: 10px;

        cursor: pointer;

        transition:
            border-color .18s ease,
            background .18s ease,
            transform .18s ease,
            box-shadow .18s ease;
    }

    .symptom-card:hover {
        border-color: #4f8a58;

        background: #163321;

        transform: translateY(-1px);

        box-shadow:
            0 6px 16px rgba(0, 0, 0, 0.16);
    }

    .symptom-card.checked-active {
        border-color: #4f9b58;

        background:
            linear-gradient(
                120deg,
                #173722 0%,
                #172b20 100%
            );

        box-shadow:
            0 4px 13px rgba(46, 125, 50, 0.14);
    }

    .symptom-checkbox {
        width: 18px;
        height: 18px;

        margin: 0;

        accent-color: #4caf50;

        flex-shrink: 0;

        cursor: pointer;
    }

    .symptom-card label {
        margin: 0;

        cursor: pointer;

        font-size: 0.88rem;
        font-weight: 500;

        color: #d7e7d9;

        line-height: 1.35;
    }

    .symptom-card.checked-active label {
        color: #f1f8e9;
    }

    /* =========================
       NO RESULTS
    ========================== */

    .no-results {
        display: none;

        text-align: center;

        padding: 25px 10px;

        color: #77907c;

        font-size: 0.88rem;
    }

    .no-results i {
        color: #81c784;
    }

    /* =========================
       VALIDATION
    ========================== */

    .invalid-message {
        margin-top: 7px;

        color: #e2836b;

        font-size: 0.8rem;

        font-weight: 500;
    }

    /* =========================
       SUCCESS
    ========================== */

    .success-message {
        display: flex;

        align-items: center;

        gap: 9px;

        background: rgba(76, 175, 80, 0.08);

        border: 1px solid rgba(129, 199, 132, 0.22);

        color: #a9d8ae;

        padding: 12px 15px;

        border-radius: 12px;

        font-size: 0.88rem;

        font-weight: 500;

        margin-bottom: 22px;
    }

    .success-message i {
        color: #81c784;
    }

    /* =========================
       ACTIONS
    ========================== */

    .symptom-reg-actions {
        display: flex;

        width: 100%;

        align-items: center;

        flex-wrap: wrap;

        gap: 12px;

        margin-top: 24px;
    }

    .btn-cancel-reg,
    .btn-save-reg {
        flex: 1 1 0;

        min-width: 220px;

        padding: 12px 22px;

        border-radius: 12px;

        font-weight: 600;

        font-size: 0.88rem;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 8px;

        transition:
            transform .18s ease,
            box-shadow .18s ease,
            background .18s ease,
            border-color .18s ease,
            color .18s ease;
    }

    /* CANCEL */

    .btn-cancel-reg {
        background: transparent;

        border: 1px solid #385340;

        color: #a9c9ae;

        text-decoration: none;
    }

    .btn-cancel-reg:hover {
        background: #163321;

        border-color: #4f6f56;

        color: #f1f8e9;

        transform: translateY(-2px);
    }

    /* SAVE */

    .btn-save-reg {
        border: none;

        background:
            linear-gradient(
                120deg,
                #2e7d32,
                #4caf50
            );

        color: #fff;

        box-shadow:
            0 8px 18px rgba(46, 125, 50, 0.22);
    }

    .btn-save-reg:hover {
        transform: translateY(-2px);

        color: #fff;

        box-shadow:
            0 11px 23px rgba(46, 125, 50, 0.30);
    }

    /* =========================
       MOBILE / TABLET
    ========================== */

    @media (max-width: 1000px) {

        .symptom-grid {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }

    }

    @media (max-width: 768px) {

        .symptom-reg-page {
            padding:
                5px 10px 24px;
        }

        .symptom-reg-header,
        .symptom-reg-body {
            padding-left: 25px;
            padding-right: 25px;
        }

        .symptom-reg-title {
            font-size: 1.45rem;
        }

    }

    @media (max-width: 600px) {

        .symptom-grid {
            grid-template-columns: 1fr;
        }

    }

    @media (max-width: 576px) {

        .symptom-reg-page {
            padding:
                5px 6px 20px;
        }

        .symptom-reg-header,
        .symptom-reg-body {
            padding: 24px 20px;
        }

        .symptom-reg-title {
            font-size: 1.35rem;
        }

        .symptom-reg-actions {
            flex-direction: column;
        }

        .btn-cancel-reg,
        .btn-save-reg {
            width: 100%;
            min-width: 0;
        }

    }
</style>


<div class="symptom-reg-page">

    <div class="symptom-reg-card">

        <!-- =========================
             HEADER
        ========================== -->

        <div class="symptom-reg-header">

            <div class="symptom-reg-title">

                <i class="fa-solid fa-link"></i>

                <span>
                    Disease with Symptoms Registration
                </span>

            </div>


            <p class="symptom-reg-subtitle">
                Link diseases with their corresponding symptoms.
            </p>

        </div>


        <!-- =========================
             BODY
        ========================== -->

        <div class="symptom-reg-body">

            @if(session('success'))

                <div class="success-message">

                    <i class="fa-solid fa-circle-check"></i>

                    <span>
                        {{ session('success') }}
                    </span>

                </div>

            @endif


            <form
                action="{{ route('symptom_reg_insert') }}"
                method="POST"
            >

                @csrf


                <!-- =========================
                     DISEASE
                ========================== -->

                <div class="mb-4">

                    <label class="symptom-form-label">

                        <i class="fa-solid fa-leaf"></i>

                        Disease

                    </label>


                    <select
                        name="category_id"
                        class="symptom-select @error('category_id') is-invalid @enderror"
                    >

                        <option
                            value=""
                            disabled
                            {{ old('category_id') ? '' : 'selected' }}
                        >
                            -- Select Disease --
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

                        <div class="invalid-message">

                            <i class="fa-solid fa-triangle-exclamation me-1"></i>

                            {{ $message }}

                        </div>

                    @enderror

                </div>


                <!-- =========================
                     SYMPTOMS
                ========================== -->

                <div>

                    <div class="symptom-section-head">

                        <label class="symptom-form-label mb-0">

                            <i class="fa-solid fa-stethoscope"></i>

                            Select Symptoms

                        </label>


                        <span
                            class="selected-count"
                            id="selectedCount"
                        >
                            0 selected
                        </span>

                    </div>


                    <!-- SEARCH -->

                    <div class="symptom-search">

                        <i class="fa-solid fa-magnifying-glass"></i>

                        <input
                            type="text"
                            id="symptomSearch"
                            placeholder="Search symptoms..."
                            autocomplete="off"
                        >

                    </div>


                    <!-- SYMPTOMS -->

                    <div class="symptoms-container">

                        <div
                            class="symptom-grid"
                            id="symptomGrid"
                        >

                            @foreach($symptoms as $symptom)

                                <div
                                    class="symptom-card"
                                    data-name="{{ strtolower($symptom->symptom_name) }}"
                                >

                                    <input
                                        class="symptom-checkbox"
                                        type="checkbox"
                                        name="symptom_id[]"
                                        value="{{ $symptom->symptom_id }}"
                                        id="symptom_{{ $symptom->symptom_id }}"
                                        {{ in_array($symptom->symptom_id, old('symptom_id', [])) ? 'checked' : '' }}
                                    >


                                    <label
                                        for="symptom_{{ $symptom->symptom_id }}"
                                    >
                                        {{ $symptom->symptom_name }}
                                    </label>

                                </div>

                            @endforeach

                        </div>


                        <div
                            class="no-results"
                            id="noResults"
                        >

                            <i class="fa-solid fa-seedling fa-lg mb-2 d-block"></i>

                            No matching symptoms found.

                        </div>

                    </div>


                    @error('symptom_id')

                        <div class="invalid-message">

                            <i class="fa-solid fa-triangle-exclamation me-1"></i>

                            {{ $message }}

                        </div>

                    @enderror


                    @error('symptom_id.*')

                        <div class="invalid-message">

                            <i class="fa-solid fa-triangle-exclamation me-1"></i>

                            {{ $message }}

                        </div>

                    @enderror

                </div>


                <!-- =========================
                     ACTIONS
                ========================== -->

                <div class="symptom-reg-actions">

                    <a
                        href="{{ route('symptom_regs') }}"
                        class="btn-cancel-reg"
                    >

                        <i class="fa-solid fa-rotate-left"></i>

                        Cancel

                    </a>


                    <button
                        type="submit"
                        class="btn-save-reg"
                    >

                        <i class="fa-solid fa-link"></i>

                        Register Disease Symptoms

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {

        const searchInput =
            document.getElementById('symptomSearch');

        const cards =
            document.querySelectorAll('.symptom-card');

        const noResults =
            document.getElementById('noResults');

        const selectedCount =
            document.getElementById('selectedCount');


        function updateCount() {

            const checked =
                document.querySelectorAll(
                    '.symptom-checkbox:checked'
                ).length;

            selectedCount.textContent =
                checked + ' selected';
        }


        function toggleCardStyle(card) {

            const checkbox =
                card.querySelector('.symptom-checkbox');

            card.classList.toggle(
                'checked-active',
                checkbox.checked
            );
        }


        cards.forEach(function (card) {

            const checkbox =
                card.querySelector('.symptom-checkbox');


            toggleCardStyle(card);


            checkbox.addEventListener(
                'change',
                function () {

                    toggleCardStyle(card);

                    updateCount();

                }
            );


            card.addEventListener(
                'click',
                function (event) {

                    const target =
                        event.target.tagName.toLowerCase();


                    if (
                        target !== 'input' &&
                        target !== 'label'
                    ) {

                        checkbox.checked =
                            !checkbox.checked;

                        checkbox.dispatchEvent(
                            new Event('change')
                        );

                    }

                }
            );

        });


        searchInput.addEventListener(
            'input',
            function () {

                const term =
                    this.value.trim().toLowerCase();

                let visibleCount = 0;


                cards.forEach(function (card) {

                    const name =
                        card.getAttribute('data-name');

                    const matches =
                        name.includes(term);

                    card.style.display =
                        matches ? 'flex' : 'none';


                    if (matches) {
                        visibleCount++;
                    }

                });


                noResults.style.display =
                    visibleCount === 0
                        ? 'block'
                        : 'none';

            }
        );


        updateCount();

    });
</script>

@endsection