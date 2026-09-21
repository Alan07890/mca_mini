@extends('layouts.AdminMaster')

@section('content')

<style>
    .symptom-reg-list-page {
        width: 100%;
        padding: 5px 18px 24px;
    }

    /* =========================
       MAIN CARD
    ========================== */

    .symptom-reg-list-card {
        width: 100%;
        max-width: none;
        margin: 0;

        background: #10251b;

        border: 1px solid rgba(129, 199, 132, 0.10);
        border-radius: 22px;

        box-shadow:
            0 16px 40px rgba(0, 0, 0, 0.22);

        overflow: hidden;
    }

    /* =========================
       HEADER
    ========================== */

    .symptom-reg-list-header {
        position: relative;

        background:
            linear-gradient(
                120deg,
                #0d2d1d 0%,
                #123c17 52%,
                #1b5e20 100%
            );

        color: #fff;

        padding: 38px 42px 34px;

        overflow: hidden;
    }

    .symptom-reg-list-header::before {
        content: "";

        position: absolute;

        width: 240px;
        height: 240px;

        right: -75px;
        top: -95px;

        border: 1px solid rgba(255, 213, 79, 0.12);

        border-radius: 50%;
    }

    .symptom-reg-list-header::after {
        content: "";

        position: absolute;

        width: 120px;
        height: 120px;

        left: -45px;
        bottom: -48px;

        border: 1px solid rgba(129, 199, 132, 0.10);

        border-radius: 50%;
    }

    .symptom-reg-list-title {
        position: relative;
        z-index: 1;

        display: flex;
        align-items: center;

        gap: 12px;

        font-size: 1.7rem;
        font-weight: 700;

        margin-bottom: 7px;

        color: #fff;
    }

    .symptom-reg-list-title i {
        width: 44px;
        height: 44px;

        display: inline-flex;

        align-items: center;
        justify-content: center;

        border-radius: 12px;

        background:
            rgba(255, 213, 79, 0.10);

        border:
            1px solid rgba(255, 213, 79, 0.12);

        color: #ffd54f;

        font-size: 1rem;
    }

    .symptom-reg-list-subtitle {
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

    .symptom-reg-list-body {
        padding: 38px 42px 32px;
    }

    /* =========================
       SUCCESS MESSAGE
    ========================== */

    .success-message {
        display: flex;
        align-items: center;

        gap: 9px;

        background:
            rgba(76, 175, 80, 0.08);

        border:
            1px solid rgba(129, 199, 132, 0.22);

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
       TOOLBAR
    ========================== */

    .toolbar {
        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 14px;

        flex-wrap: wrap;

        margin-bottom: 20px;
    }

    .search-box {
        position: relative;

        flex: 1 1 320px;
    }

    .search-box i {
        position: absolute;

        left: 15px;
        top: 50%;

        transform: translateY(-50%);

        color: #81c784;

        font-size: 0.85rem;
    }

    .search-box input {
        width: 100%;

        border:
            1px solid #294936;

        border-radius: 12px;

        background: #0b1d14;

        color: #f1f8e9;

        padding:
            13px 15px 13px 42px;

        font-size: 0.9rem;

        outline: none;

        transition:
            border-color .18s ease,
            box-shadow .18s ease;
    }

    .search-box input::placeholder {
        color: #718b77;
    }

    .search-box input:focus {
        border-color: #81c784;

        box-shadow:
            0 0 0 4px
            rgba(129, 199, 132, 0.08);
    }

    .btn-new-registration {
        background:
            linear-gradient(
                120deg,
                #2e7d32,
                #4caf50
            );

        color: #fff;

        border: none;

        padding: 11px 20px;

        border-radius: 12px;

        font-weight: 600;

        font-size: 0.86rem;

        text-decoration: none;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 8px;

        box-shadow:
            0 8px 18px
            rgba(46, 125, 50, 0.20);

        transition:
            transform .18s ease,
            box-shadow .18s ease;
    }

    .btn-new-registration:hover {
        color: #fff;

        transform:
            translateY(-2px);

        box-shadow:
            0 11px 23px
            rgba(46, 125, 50, 0.30);
    }

    /* =========================
       TABLE
    ========================== */

    .registration-table-wrap {
        width: 100%;

        border:
            1px solid #294936;

        border-radius: 16px;

        overflow-x: auto;

        background: #10251b;
    }

    .registration-table {
        width: 100%;

        min-width: 850px;

        margin: 0;

        border-collapse: collapse;

        font-size: 0.88rem;
    }

    .registration-table thead {
        background:
            linear-gradient(
                120deg,
                #0d2d1d,
                #1b5e20
            );
    }

    .registration-table thead th {
        color: #f1f8e9;

        font-weight: 600;

        padding: 15px 16px;

        border: none;

        white-space: nowrap;

        font-size: 0.78rem;

        text-transform: uppercase;

        letter-spacing: 0.4px;
    }

    .registration-table tbody tr {
        transition:
            background 0.15s ease;
    }

    .registration-table tbody tr:hover td {
        background: #163321;
    }

    .registration-table tbody td {
        padding: 15px 16px;

        vertical-align: middle;

        border-bottom:
            1px solid
            rgba(129, 199, 132, 0.08);

        color: #d7e7d9;

        background: #10251b;
    }

    .registration-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* =========================
       REGISTRATION ID
    ========================== */

    .registration-id {
        display: inline-flex;

        align-items: center;

        background:
            rgba(129, 199, 132, 0.10);

        border:
            1px solid
            rgba(129, 199, 132, 0.18);

        color: #81c784;

        font-size: 0.78rem;

        font-weight: 600;

        padding: 5px 11px;

        border-radius: 20px;
    }

    /* =========================
       DISEASE
    ========================== */

    .disease-name {
        display: flex;

        align-items: center;

        gap: 8px;

        color: #f1f8e9;

        font-weight: 600;
    }

    .disease-name i {
        color: #ffd54f;
    }

    /* =========================
       SYMPTOM BADGES
    ========================== */

    .symptom-badge {
        display: inline-block;

        background:
            linear-gradient(
                120deg,
                rgba(76, 175, 80, 0.10),
                rgba(255, 213, 79, 0.04)
            );

        border:
            1px solid
            rgba(129, 199, 132, 0.25);

        color: #b8d7bc;

        font-size: 0.76rem;

        font-weight: 500;

        padding: 5px 10px;

        border-radius: 20px;

        margin: 2px 4px 2px 0;
    }

    /* =========================
       ACTIONS
    ========================== */

    .action-group {
        display: flex;

        align-items: center;

        gap: 7px;

        flex-wrap: wrap;
    }

    .btn-edit-registration {
        border:
            1px solid
            rgba(255, 213, 79, 0.18);

        background:
            rgba(255, 213, 79, 0.08);

        color: #ffd54f;

        padding: 8px 14px;

        border-radius: 10px;

        font-size: 0.78rem;

        font-weight: 600;

        text-decoration: none;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 6px;

        transition:
            background .18s ease,
            color .18s ease,
            border-color .18s ease,
            transform .18s ease;
    }

    .btn-edit-registration:hover {
        background: #ffd54f;

        color: #18210d;

        border-color: #ffd54f;

        transform: translateY(-1px);

        box-shadow:
            0 7px 15px
            rgba(255, 213, 79, 0.14);
    }

    .btn-delete-registration {
        border:
            1px solid
            rgba(226, 131, 107, 0.18);

        background:
            rgba(226, 131, 107, 0.08);

        color: #e2836b;

        padding: 7px 13px;

        border-radius: 10px;

        font-size: 0.78rem;

        font-weight: 600;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 6px;

        transition:
            background .18s ease,
            color .18s ease,
            border-color .18s ease,
            transform .18s ease;
    }

    .btn-delete-registration:hover {
        background: #e2836b;

        border-color: #e2836b;

        color: #fff;

        transform: translateY(-1px);
    }

    /* =========================
       EMPTY STATE
    ========================== */

    .empty-state {
        text-align: center;

        padding: 55px 20px;

        color: #77907c;
    }

    .empty-state i {
        display: block;

        color: #81c784;

        font-size: 2.5rem;

        margin-bottom: 12px;
    }

    .no-results-state {
        display: none;
    }

    /* =========================
       LARGE SCREENS
    ========================== */

    @media (min-width: 1200px) {

        .registration-table thead th,
        .registration-table tbody td {
            padding-left: 20px;
            padding-right: 20px;
        }

        .registration-table tbody td:nth-child(1) {
            width: 150px;
        }

        .registration-table tbody td:nth-child(2) {
            width: 260px;
        }

        .registration-table tbody td:nth-child(4) {
            width: 230px;
        }

    }

    /* =========================
       TABLET
    ========================== */

    @media (max-width: 900px) {

        .symptom-reg-list-header,
        .symptom-reg-list-body {
            padding-left: 25px;
            padding-right: 25px;
        }

    }

    /* =========================
       MOBILE
    ========================== */

    @media (max-width: 700px) {

        .symptom-reg-list-page {
            padding:
                5px 10px 24px;
        }

        .symptom-reg-list-header,
        .symptom-reg-list-body {
            padding-top: 25px;
            padding-bottom: 25px;
        }

        .symptom-reg-list-title {
            font-size: 1.45rem;
        }

        .toolbar {
            align-items: stretch;
        }

        .search-box {
            flex-basis: 100%;
        }

        .btn-new-registration {
            width: 100%;
        }

        .action-group {
            justify-content: flex-start;
        }

    }

    @media (max-width: 576px) {

        .symptom-reg-list-page {
            padding:
                5px 6px 20px;
        }

        .symptom-reg-list-header,
        .symptom-reg-list-body {
            padding:
                24px 20px;
        }

        .symptom-reg-list-title {
            font-size: 1.35rem;
        }

    }
</style>


<div class="symptom-reg-list-page">

    <div class="symptom-reg-list-card">

        <!-- =========================
             HEADER
        ========================== -->

        <div class="symptom-reg-list-header">

            <div class="symptom-reg-list-title">

                <i class="fa-solid fa-list-check"></i>

                <span>
                    Disease Symptoms Registration List
                </span>

            </div>


            <p class="symptom-reg-list-subtitle">
                View and manage all disease-to-symptom mappings.
            </p>

        </div>


        <!-- =========================
             BODY
        ========================== -->

        <div class="symptom-reg-list-body">

            @if(session('success'))

                <div class="success-message">

                    <i class="fa-solid fa-circle-check"></i>

                    <span>
                        {{ session('success') }}
                    </span>

                </div>

            @endif


            <!-- =========================
                 TOOLBAR
            ========================== -->

            <div class="toolbar">

                <div class="search-box">

                    <i class="fa-solid fa-magnifying-glass"></i>

                    <input
                        type="text"
                        id="regSearch"
                        placeholder="Search by disease or symptom..."
                        autocomplete="off"
                    >

                </div>


                <a
                    href="{{ route('symptom_reg') }}"
                    class="btn-new-registration"
                >

                    <i class="fa-solid fa-plus"></i>

                    New Registration

                </a>

            </div>


            @if($symptomRegs->count() > 0)

                <!-- =========================
                     TABLE
                ========================== -->

                <div class="registration-table-wrap">

                    <table
                        class="registration-table"
                        id="regTable"
                    >

                        <thead>

                            <tr>

                                <th>
                                    Registration ID
                                </th>

                                <th>
                                    Disease Category
                                </th>

                                <th>
                                    Symptoms
                                </th>

                                <th>
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @php
                                $grouped =
                                    $symptomRegs->groupBy('category_id');
                            @endphp


                            @foreach($grouped as $categoryId => $regsForCategory)

                                @php
                                    $first =
                                        $regsForCategory->first();
                                @endphp


                                <tr
                                    data-search="{{ strtolower(
                                        $first->category->diseases_name . ' ' .
                                        $regsForCategory
                                            ->pluck('symptom.symptom_name')
                                            ->implode(' ')
                                    ) }}"
                                >

                                    <!-- Registration ID -->

                                    <td>

                                        <span class="registration-id">
                                            #{{ $categoryId }}
                                        </span>

                                    </td>


                                    <!-- Disease -->

                                    <td>

                                        <div class="disease-name">

                                            <i class="fa-solid fa-leaf"></i>

                                            {{ $first->category->diseases_name }}

                                        </div>

                                    </td>


                                    <!-- Symptoms -->

                                    <td>

                                        @foreach($regsForCategory as $reg)

                                            <span class="symptom-badge">
                                                {{ $reg->symptom->symptom_name }}
                                            </span>

                                        @endforeach

                                    </td>


                                    <!-- Actions -->

                                    <td>

                                        <div class="action-group">

                                            <a
                                                href="{{ route('symptom_reg_edit', $categoryId) }}"
                                                class="btn-edit-registration"
                                            >

                                                <i class="fa-solid fa-pen"></i>

                                                Edit

                                            </a>


                                            <form
                                                action="{{ route('symptom_reg_delete', $categoryId) }}"
                                                method="POST"
                                                onsubmit="return confirm('Remove all symptom mappings for this disease?');"
                                            >

                                                @csrf

                                                @method('DELETE')


                                                <button
                                                    type="submit"
                                                    class="btn-delete-registration"
                                                >

                                                    <i class="fa-solid fa-trash"></i>

                                                    Delete

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                <!-- =========================
                     NO SEARCH RESULTS
                ========================== -->

                <div
                    class="empty-state no-results-state"
                    id="noResultsRow"
                >

                    <i class="fa-solid fa-seedling"></i>

                    <p class="mb-0">
                        No matching registrations found.
                    </p>

                </div>


            @else

                <!-- =========================
                     EMPTY DATABASE
                ========================== -->

                <div class="empty-state">

                    <i class="fa-solid fa-seedling"></i>

                    <p class="mb-0">
                        No disease symptom registrations found yet.
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {

        const searchInput =
            document.getElementById('regSearch');

        const table =
            document.getElementById('regTable');

        const noResults =
            document.getElementById('noResultsRow');


        if (!searchInput || !table) {
            return;
        }


        const rows =
            table.querySelectorAll('tbody tr');


        searchInput.addEventListener(
            'input',
            function () {

                const term =
                    this.value.trim().toLowerCase();

                let visibleCount = 0;


                rows.forEach(function (row) {

                    const haystack =
                        row.getAttribute('data-search') || '';

                    const matches =
                        haystack.includes(term);


                    row.style.display =
                        matches ? '' : 'none';


                    if (matches) {
                        visibleCount++;
                    }

                });


                if (noResults) {

                    noResults.style.display =
                        visibleCount === 0
                            ? 'block'
                            : 'none';

                }

            }
        );

    });
</script>

@endsection