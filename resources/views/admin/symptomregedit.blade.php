@extends('layouts.AdminMaster')

@section('content')

<style>
    .symptom-reg-edit-page {
        width: 100%;
        padding: 5px 18px 24px;
    }

    /* =========================
       MAIN CARD
    ========================== */

    .symptom-reg-edit-card {
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

    .symptom-reg-edit-header {
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

    .symptom-reg-edit-header::before {
        content: "";

        position: absolute;

        width: 240px;
        height: 240px;

        right: -75px;
        top: -95px;

        border: 1px solid rgba(255, 213, 79, 0.12);
        border-radius: 50%;
    }

    .symptom-reg-edit-header::after {
        content: "";

        position: absolute;

        width: 120px;
        height: 120px;

        left: -45px;
        bottom: -48px;

        border: 1px solid rgba(129, 199, 132, 0.10);
        border-radius: 50%;
    }

    .symptom-reg-edit-title {
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

    .symptom-reg-edit-title i {
        width: 44px;
        height: 44px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        border-radius: 12px;

        background: rgba(255, 213, 79, 0.10);
        border: 1px solid rgba(255, 213, 79, 0.12);

        color: #ffd54f;

        font-size: 1rem;
    }

    .symptom-reg-edit-subtitle {
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

    .symptom-reg-edit-body {
        padding: 38px 42px 32px;
    }

    /* =========================
       LABEL
    ========================== */

    .edit-form-label {
        display: flex;
        align-items: center;

        gap: 8px;

        font-size: 0.9rem;
        font-weight: 600;

        color: #f1f8e9;

        margin-bottom: 9px;
    }

    .edit-form-label i {
        color: #81c784;
    }

    /* =========================
       DISEASE SELECT
    ========================== */

    .edit-select {
        width: 100%;

        border: 1px solid #294936;
        border-radius: 12px;

        background: #172c20;

        padding: 13px 14px;

        font-size: 0.92rem;

        color: #9eb7a3;

        cursor: not-allowed;

        outline: none;
    }

    .edit-select:disabled {
        background: #172c20;
        border-color: #294936;
        opacity: 1;
        color: #9eb7a3;
    }

    .edit-select option {
        background: #10251b;
        color: #f1f8e9;
    }

    /* =========================
       COUNT
    ========================== */

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

    .section-head-edit {
        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 10px;

        margin-bottom: 12px;
    }

    /* =========================
       SEARCH
    ========================== */

    .symptom-search-edit {
        position: relative;

        margin-bottom: 16px;
    }

    .symptom-search-edit i {
        position: absolute;

        left: 15px;
        top: 50%;

        transform: translateY(-50%);

        color: #81c784;

        font-size: 0.85rem;
    }

    .symptom-search-edit input {
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

    .symptom-search-edit input::placeholder {
        color: #718b77;
    }

    .symptom-search-edit input:focus {
        border-color: #81c784;

        box-shadow:
            0 0 0 4px rgba(129, 199, 132, 0.08);
    }

    /* =========================
       SYMPTOMS CONTAINER
    ========================== */

    .symptoms-edit-container {
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

    .symptoms-edit-container::-webkit-scrollbar {
        width: 7px;
    }

    .symptoms-edit-container::-webkit-scrollbar-track {
        background: #0a1d15;
    }

    .symptoms-edit-container::-webkit-scrollbar-thumb {
        background: #396b43;

        border-radius: 10px;
    }

    .symptoms-edit-container::-webkit-scrollbar-thumb:hover {
        background: #4f8a58;
    }

    /* =========================
       GRID
    ========================== */

    .symptom-edit-grid {
        display: grid;

        grid-template-columns:
            repeat(3, minmax(0, 1fr));

        gap: 11px;
    }

    .symptom-edit-card {
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

    .symptom-edit-card:hover {
        border-color: #4f8a58;

        background: #163321;

        transform: translateY(-1px);

        box-shadow:
            0 6px 16px rgba(0, 0, 0, 0.16);
    }

    .symptom-edit-card.checked-active {
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

    .symptom-edit-checkbox {
        width: 18px;
        height: 18px;

        margin: 0;

        accent-color: #4caf50;

        flex-shrink: 0;

        cursor: pointer;
    }

    .symptom-edit-card label {
        margin: 0;

        cursor: pointer;

        font-size: 0.88rem;
        font-weight: 500;

        color: #d7e7d9;

        line-height: 1.35;
    }

    .symptom-edit-card.checked-active label {
        color: #f1f8e9;
    }

    /* =========================
       NO RESULTS
    ========================== */

    .no-results-edit {
        display: none;

        text-align: center;

        padding: 25px 10px;

        color: #77907c;

        font-size: 0.88rem;
    }

    .no-results-edit i {
        color: #81c784;
    }

    /* =========================
       VALIDATION
    ========================== */

    .invalid-edit-message {
        margin-top: 7px;

        color: #e2836b;

        font-size: 0.8rem;

        font-weight: 500;
    }

    /* =========================
       SUCCESS
    ========================== */

    .success-edit-message {
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

    .success-edit-message i {
        color: #81c784;
    }

    /* =========================
       ACTIONS
    ========================== */

    .edit-actions {
        display: flex;

        width: 100%;

        align-items: center;

        flex-wrap: wrap;

        gap: 12px;

        margin-top: 24px;
    }

    .btn-edit-cancel,
    .btn-update-registration {
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

    .btn-edit-cancel {
        background: transparent;

        border: 1px solid #385340;

        color: #a9c9ae;

        text-decoration: none;
    }

    .btn-edit-cancel:hover {
        background: #163321;

        border-color: #4f6f56;

        color: #f1f8e9;

        transform: translateY(-2px);
    }

    /* UPDATE */

    .btn-update-registration {
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

    .btn-update-registration:hover {
        transform: translateY(-2px);

        color: #fff;

        box-shadow:
            0 11px 23px rgba(46, 125, 50, 0.30);
    }

    /* =========================
       TABLET
    ========================== */

    @media (max-width: 1000px) {

        .symptom-edit-grid {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }

    }

    /* =========================
       MOBILE
    ========================== */

    @media (max-width: 768px) {

        .symptom-reg-edit-page {
            padding:
                5px 10px 24px;
        }

        .symptom-reg-edit-header,
        .symptom-reg-edit-body {
            padding-left: 25px;
            padding-right: 25px;
        }

        .symptom-reg-edit-title {
            font-size: 1.45rem;
        }

    }

    @media (max-width: 600px) {

        .symptom-edit-grid {
            grid-template-columns: 1fr;
        }

    }

    @media (max-width: 576px) {

        .symptom-reg-edit-page {
            padding:
                5px 6px 20px;
        }

        .symptom-reg-edit-header,
        .symptom-reg-edit-body {
            padding: 24px 20px;
        }

        .symptom-reg-edit-title {
            font-size: 1.35rem;
        }

        .edit-actions {
            flex-direction: column;
        }

        .btn-edit-cancel,
        .btn-update-registration {
            width: 100%;
            min-width: 0;
        }

    }
</style>


<div class="symptom-reg-edit-page">

    <div class="symptom-reg-edit-card">

        <!-- =========================
             HEADER
        ========================== -->

        <div class="symptom-reg-edit-header">

            <div class="symptom-reg-edit-title">

                <i class="fa-solid fa-pen-to-square"></i>

                <span>
                    Edit Disease Symptoms Registration
                </span>

            </div>


            <p class="symptom-reg-edit-subtitle">
                Update the symptoms linked to this disease.
            </p>

        </div>


        <!-- =========================
             BODY
        ========================== -->

        <div class="symptom-reg-edit-body">

            @if(session('success'))

                <div class="success-edit-message">

                    <i class="fa-solid fa-circle-check"></i>

                    <span>
                        {{ session('success') }}
                    </span>

                </div>

            @endif


            <form
                action="{{ route('symptom_reg_update', $category->category_id) }}"
                method="POST"
            >

                @csrf
                @method('PUT')


                <!-- =========================
                     DISEASE
                ========================== -->

                <div class="mb-4">

                    <label class="edit-form-label">

                        <i class="fa-solid fa-leaf"></i>

                        Disease

                    </label>


                    <select
                        name="category_id"
                        class="edit-select @error('category_id') is-invalid @enderror"
                        disabled
                    >

                        @foreach($categories as $categoryOption)

                            <option
                                value="{{ $categoryOption->category_id }}"
                                {{ $categoryOption->category_id == $category->category_id ? 'selected' : '' }}
                            >
                                {{ $categoryOption->diseases_name }}
                            </option>

                        @endforeach

                    </select>


                    <input
                        type="hidden"
                        name="category_id"
                        value="{{ $category->category_id }}"
                    >


                    @error('category_id')

                        <div class="invalid-edit-message">

                            <i class="fa-solid fa-triangle-exclamation me-1"></i>

                            {{ $message }}

                        </div>

                    @enderror

                </div>


                <!-- =========================
                     SYMPTOMS
                ========================== -->

                <div>

                    <div class="section-head-edit">

                        <label class="edit-form-label mb-0">

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

                    <div class="symptom-search-edit">

                        <i class="fa-solid fa-magnifying-glass"></i>

                        <input
                            type="text"
                            id="symptomSearch"
                            placeholder="Search symptoms..."
                            autocomplete="off"
                        >

                    </div>


                    <!-- SYMPTOMS -->

                    <div class="symptoms-edit-container">

                        <div
                            class="symptom-edit-grid"
                            id="symptomGrid"
                        >

                            @foreach($symptoms as $symptom)

                                <div
                                    class="symptom-edit-card"
                                    data-name="{{ strtolower($symptom->symptom_name) }}"
                                >

                                    <input
                                        class="symptom-edit-checkbox"
                                        type="checkbox"
                                        name="symptom_id[]"
                                        value="{{ $symptom->symptom_id }}"
                                        id="symptom_{{ $symptom->symptom_id }}"
                                        {{ in_array($symptom->symptom_id, old('symptom_id', $selectedSymptomIds)) ? 'checked' : '' }}
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
                            class="no-results-edit"
                            id="noResults"
                        >

                            <i class="fa-solid fa-seedling fa-lg mb-2 d-block"></i>

                            No matching symptoms found.

                        </div>

                    </div>


                    @error('symptom_id')

                        <div class="invalid-edit-message">

                            <i class="fa-solid fa-triangle-exclamation me-1"></i>

                            {{ $message }}

                        </div>

                    @enderror


                    @error('symptom_id.*')

                        <div class="invalid-edit-message">

                            <i class="fa-solid fa-triangle-exclamation me-1"></i>

                            {{ $message }}

                        </div>

                    @enderror

                </div>


                <!-- =========================
                     ACTIONS
                ========================== -->

                <div class="edit-actions">

                    <a
                        href="{{ route('symptom_regs') }}"
                        class="btn-edit-cancel"
                    >

                        <i class="fa-solid fa-rotate-left"></i>

                        Cancel

                    </a>


                    <button
                        type="submit"
                        class="btn-update-registration"
                    >

                        <i class="fa-solid fa-floppy-disk"></i>

                        Update Disease Symptoms

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
            document.querySelectorAll('.symptom-edit-card');

        const noResults =
            document.getElementById('noResults');

        const selectedCount =
            document.getElementById('selectedCount');


        function updateCount() {

            const checked =
                document.querySelectorAll(
                    '.symptom-edit-checkbox:checked'
                ).length;

            selectedCount.textContent =
                checked + ' selected';
        }


        function toggleCardStyle(card) {

            const checkbox =
                card.querySelector('.symptom-edit-checkbox');

            card.classList.toggle(
                'checked-active',
                checkbox.checked
            );
        }


        cards.forEach(function (card) {

            const checkbox =
                card.querySelector('.symptom-edit-checkbox');


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