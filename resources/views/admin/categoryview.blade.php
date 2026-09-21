@extends('layouts.AdminMaster')

@section('title', 'Disease Categories')

@section('content')

<style>
    /* =========================================================
       CARDO-FER — DISEASE CATEGORY LIST
       Full Width Dark Green / Gold Theme
    ========================================================= */

    .disease-list-page {
        width: 100%;

        min-height: calc(100vh - 150px);

        padding: 8px 18px 30px;

        color: #F1F8E9;
    }

    /* =========================================================
       HEADER
    ========================================================= */

    .disease-list-header {
        position: relative;

        overflow: hidden;

        width: 100%;

        padding: 38px 42px 34px;

        margin-bottom: 18px;

        border-radius: 20px;

        background:
            linear-gradient(
                135deg,
                #0e3515 0%,
                #1B5E20 58%,
                #2E7D32 100%
            );

        border:
            1px solid rgba(129, 199, 132, 0.18);

        box-shadow:
            0 20px 40px -25px
            rgba(0, 0, 0, 0.65);
    }

    .disease-list-header::before {
        content: "";

        position: absolute;

        width: 260px;
        height: 260px;

        right: -105px;
        top: -130px;

        border-radius: 50%;

        border:
            1px solid rgba(255, 255, 255, 0.10);
    }

    .disease-list-header::after {
        content: "";

        position: absolute;

        width: 120px;
        height: 120px;

        right: 190px;
        bottom: -70px;

        border-radius: 50%;

        border:
            1px solid rgba(255, 255, 255, 0.07);
    }

    .disease-list-badge {
        position: relative;

        z-index: 2;

        display: inline-flex;

        align-items: center;

        gap: 8px;

        padding: 7px 13px;

        border-radius: 999px;

        background:
            rgba(255, 255, 255, 0.10);

        border:
            1px solid rgba(255, 255, 255, 0.12);

        color: #D7F2D9;

        font-size: 10.5px;

        font-weight: 700;

        letter-spacing: 1px;

        text-transform: uppercase;
    }

    .disease-list-badge i {
        color: #FFD54F;
    }

    .disease-list-header h2 {
        position: relative;

        z-index: 2;

        margin: 13px 0 6px;

        color: #F1F8E9;

        font-family:
            'Fraunces',
            Georgia,
            serif;

        font-size: 29px;

        font-weight: 500;
    }

    .disease-list-header p {
        position: relative;

        z-index: 2;

        margin: 0;

        max-width: 900px;

        color:
            rgba(241, 248, 233, 0.72);

        font-size: 12.8px;

        line-height: 1.7;
    }

    /* =========================================================
       TOOLBAR
    ========================================================= */

    .disease-toolbar {
        width: 100%;

        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 12px;

        flex-wrap: wrap;

        padding: 13px;

        margin-bottom: 16px;

        background: #10251b;

        border:
            1px solid rgba(129, 199, 132, 0.18);

        border-radius: 16px;

        box-shadow:
            0 14px 30px -25px
            rgba(0, 0, 0, 0.8);
    }

    .disease-search {
        position: relative;

        flex: 1 1 300px;

        max-width: 720px;
    }

    .disease-search i {
        position: absolute;

        left: 15px;

        top: 50%;

        transform:
            translateY(-50%);

        color: #81C784;

        font-size: 13px;

        pointer-events: none;
    }

    .disease-search input {
        width: 100%;

        padding:
            11px 14px 11px 40px;

        border:
            1px solid rgba(129, 199, 132, 0.23);

        border-radius: 999px;

        outline: none;

        background: #07150f;

        color: #F1F8E9;

        font-size: 13px;

        transition:
            border-color .18s ease,
            box-shadow .18s ease;
    }

    .disease-search input::placeholder {
        color:
            rgba(241, 248, 233, 0.36);
    }

    .disease-search input:focus {
        border-color: #FFD54F;

        box-shadow:
            0 0 0 3px
            rgba(255, 213, 79, 0.11);
    }

    .disease-list-count {
        color: #a9c9ae;

        font-size: 11.5px;

        white-space: nowrap;
    }

    .btn-add-disease {
        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 8px;

        padding: 10px 18px;

        border: none;

        border-radius: 999px;

        background:
            linear-gradient(
                180deg,
                #FFD54F,
                #d5893f
            );

        color: #241708;

        font-size: 12.5px;

        font-weight: 700;

        text-decoration: none;

        white-space: nowrap;

        transition:
            transform .18s ease,
            box-shadow .18s ease;
    }

    .btn-add-disease:hover {
        transform:
            translateY(-2px);

        box-shadow:
            0 13px 27px -15px
            rgba(255, 213, 79, 0.65);

        color: #241708;
    }

    /* =========================================================
       TABLE CARD
    ========================================================= */

    .disease-table-card {
        width: 100%;

        overflow: hidden;

        border:
            1px solid rgba(129, 199, 132, 0.18);

        border-radius: 18px;

        background: #10251b;

        box-shadow:
            0 20px 40px -30px
            rgba(0, 0, 0, 0.9);
    }

    .disease-table-wrap {
        width: 100%;

        max-height: 560px;

        overflow-x: auto;

        overflow-y: auto;
    }

    .disease-table {
        width: 100%;

        min-width: 750px;

        border-collapse: collapse;

        color: #F1F8E9;
    }

    .disease-table thead th {
        position: sticky;

        top: 0;

        z-index: 3;

        padding: 15px 20px;

        background: #0e3515;

        border-bottom:
            1px solid rgba(129, 199, 132, 0.20);

        color: #81C784;

        font-size: 10.5px;

        font-weight: 700;

        letter-spacing: .7px;

        text-transform: uppercase;

        text-align: left;

        white-space: nowrap;
    }

    .disease-table tbody td {
        padding: 15px 20px;

        border-bottom:
            1px solid rgba(241, 248, 233, 0.08);

        color: #a9c9ae;

        font-size: 13px;

        vertical-align: middle;

        background: #10251b;
    }

    .disease-table tbody tr {
        transition:
            background .15s ease;
    }

    .disease-table tbody tr:hover td {
        background:
            rgba(129, 199, 132, 0.035);
    }

    .disease-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* =========================================================
       COLUMN SIZING
    ========================================================= */

    .disease-table th:nth-child(1),
    .disease-table td:nth-child(1) {
        width: 180px;
    }

    .disease-table th:nth-child(2),
    .disease-table td:nth-child(2) {
        width: auto;
    }

    .disease-table th:nth-child(3),
    .disease-table td:nth-child(3) {
        width: 280px;
    }

    /* =========================================================
       ID BADGE
    ========================================================= */

    .disease-id-badge {
        display: inline-flex;

        align-items: center;

        justify-content: center;

        min-width: 48px;

        padding: 5px 10px;

        border-radius: 999px;

        background:
            rgba(76, 175, 80, 0.11);

        border:
            1px solid rgba(76, 175, 80, 0.20);

        color: #81C784;

        font-size: 11px;

        font-weight: 700;
    }

    /* =========================================================
       DISEASE NAME
    ========================================================= */

    .disease-name-cell {
        display: inline-flex;

        align-items: center;

        gap: 9px;

        color: #F1F8E9;

        font-weight: 600;
    }

    .disease-name-cell i {
        width: 32px;

        height: 32px;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        border-radius: 9px;

        background:
            rgba(76, 175, 80, 0.10);

        color: #81C784;

        font-size: 12px;
    }

    /* =========================================================
       ACTIONS
    ========================================================= */

    .disease-action-group {
        display: inline-flex;

        align-items: center;

        justify-content: flex-end;

        gap: 7px;

        flex-wrap: wrap;
    }

    .btn-edit-disease {
        display: inline-flex;

        align-items: center;

        gap: 6px;

        padding: 7px 12px;

        border:
            1px solid rgba(255, 213, 79, 0.23);

        border-radius: 999px;

        background:
            rgba(255, 213, 79, 0.08);

        color: #FFD54F;

        font-size: 11.5px;

        font-weight: 600;

        text-decoration: none;

        transition:
            background .18s ease,
            border-color .18s ease,
            color .18s ease,
            transform .18s ease;
    }

    .btn-edit-disease:hover {
        background:
            rgba(255, 213, 79, 0.15);

        border-color:
            rgba(255, 213, 79, 0.42);

        color:
            #FFE58A;

        transform:
            translateY(-1px);
    }

    .btn-delete-disease {
        display: inline-flex;

        align-items: center;

        gap: 6px;

        padding: 7px 12px;

        border:
            1px solid rgba(226, 131, 107, 0.22);

        border-radius: 999px;

        background:
            rgba(226, 131, 107, 0.07);

        color: #e8a28f;

        font-size: 11.5px;

        font-weight: 600;

        transition:
            background .18s ease,
            border-color .18s ease,
            color .18s ease,
            transform .18s ease;
    }

    .btn-delete-disease:hover {
        background:
            rgba(226, 131, 107, 0.14);

        border-color:
            rgba(226, 131, 107, 0.38);

        color:
            #ffb2a0;

        transform:
            translateY(-1px);
    }

    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .disease-empty-state {
        text-align: center;

        padding: 55px 20px;

        color: #a9c9ae;
    }

    .disease-empty-state i {
        display: flex;

        align-items: center;

        justify-content: center;

        width: 52px;

        height: 52px;

        margin:
            0 auto 13px;

        border-radius: 15px;

        background:
            rgba(76, 175, 80, 0.09);

        color: #81C784;

        font-size: 20px;
    }

    .disease-empty-state p {
        margin: 0;

        font-size: 12.5px;
    }

    /* =========================================================
       PAGINATION
    ========================================================= */

    .disease-pagination {
        display: flex;

        justify-content: center;

        padding: 18px 10px 4px;
    }

    .disease-pagination .pagination {
        margin: 0;
    }

    .disease-pagination .page-link {
        background: #10251b;

        border:
            1px solid rgba(129, 199, 132, 0.18);

        color: #a9c9ae;
    }

    .disease-pagination .page-link:hover {
        background:
            rgba(129, 199, 132, 0.10);

        color: #F1F8E9;
    }

    .disease-pagination .active .page-link {
        background: #1B5E20;

        border-color: #4CAF50;

        color: #F1F8E9;
    }

    /* =========================================================
       ANIMATION
    ========================================================= */

    .disease-list-header,
    .disease-toolbar,
    .disease-table-card {
        animation:
            diseaseListFade .45s ease both;
    }

    .disease-toolbar {
        animation-delay: .08s;
    }

    .disease-table-card {
        animation-delay: .16s;
    }

    @keyframes diseaseListFade {

        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }

    }

    /* =========================================================
       TABLET
    ========================================================= */

    @media (max-width: 900px) {

        .disease-list-page {
            padding-left: 10px;
            padding-right: 10px;
        }

        .disease-list-header {
            padding-left: 28px;
            padding-right: 28px;
        }

    }

    /* =========================================================
       MOBILE
    ========================================================= */

    @media (max-width: 700px) {

        .disease-toolbar {
            align-items: stretch;
        }

        .disease-search {
            max-width: none;

            flex-basis: 100%;
        }

        .disease-list-count {
            order: 3;

            width: 100%;
        }

        .btn-add-disease {
            width: 100%;
        }

    }

    @media (max-width: 576px) {

        .disease-list-page {
            padding:
                5px 6px 20px;
        }

        .disease-list-header {
            padding: 25px 20px;
        }

        .disease-list-header h2 {
            font-size: 24px;
        }

        .disease-table {
            min-width: 650px;
        }

        .disease-toolbar {
            padding: 12px;
        }

    }
</style>


<div class="disease-list-page">


    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="disease-list-header">

        <span class="disease-list-badge">

            <i class="fa-solid fa-leaf"></i>

            Cardo-Fer · Disease Intelligence

        </span>


        <h2>
            Disease Categories
        </h2>


        <p>
            Manage the cardamom disease categories used by the
            Cardo-Fer disease prediction and recommendation system.
        </p>

    </div>


    {{-- =====================================================
         TOOLBAR
    ====================================================== --}}

    <div class="disease-toolbar">


        <div class="disease-search">

            <i class="fa-solid fa-magnifying-glass"></i>


            <input
                type="text"
                id="categorySearch"
                placeholder="Search disease category..."
                autocomplete="off"
            >

        </div>


        <span
            id="diseaseListCount"
            class="disease-list-count"
        ></span>


        <a
            href="{{ route('category') }}"
            class="btn-add-disease"
        >

            <i class="fa-solid fa-plus"></i>

            Add New Disease

        </a>

    </div>


    {{-- =====================================================
         TABLE
    ====================================================== --}}

    <div class="disease-table-card">

        <div class="disease-table-wrap">

            <table
                class="disease-table"
                id="categoryTable"
            >

                <thead>

                    <tr>

                        <th>
                            Category ID
                        </th>

                        <th>
                            Disease Name
                        </th>

                        <th class="text-end">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($diseases_categories as $category)

                        <tr
                            class="disease-row"
                            data-search="{{ strtolower($category->diseases_name) }}"
                        >

                            {{-- ID --}}

                            <td>

                                <span class="disease-id-badge">

                                    #{{ $category->category_id }}

                                </span>

                            </td>


                            {{-- Disease Name --}}

                            <td>

                                <span class="disease-name-cell">

                                    <i class="fa-solid fa-leaf"></i>

                                    {{ $category->diseases_name }}

                                </span>

                            </td>


                            {{-- Actions --}}

                            <td class="text-end">

                                <div
                                    class="disease-action-group"
                                >

                                    {{-- Edit --}}

                                    <a
                                        href="{{ route('category_edit', $category->category_id) }}"
                                        class="btn-edit-disease"
                                        title="Edit disease"
                                    >

                                        <i class="fa-solid fa-pen"></i>

                                        Edit

                                    </a>


                                    {{-- Delete --}}

                                    <form
                                        action="{{ route('category_delete', $category->category_id) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this disease category?');"
                                    >

                                        @csrf

                                        @method('DELETE')


                                        <button
                                            type="submit"
                                            class="btn-delete-disease"
                                            title="Delete disease"
                                        >

                                            <i class="fa-solid fa-trash"></i>

                                            Delete

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="3">

                                <div
                                    class="disease-empty-state"
                                >

                                    <i
                                        class="fa-solid fa-seedling"
                                    ></i>


                                    <p>
                                        No disease categories have been added yet.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse


                    {{-- No Search Results --}}

                    <tr
                        id="diseaseNoResults"
                        style="display:none;"
                    >

                        <td colspan="3">

                            <div
                                class="disease-empty-state"
                            >

                                <i
                                    class="fa-solid fa-magnifying-glass"
                                ></i>


                                <p>
                                    No disease categories match your search.
                                </p>

                            </div>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>


    {{-- =====================================================
         PAGINATION
    ====================================================== --}}

    @if(method_exists($diseases_categories ?? null, 'links'))

        <div class="disease-pagination">

            {{ $diseases_categories->links() }}

        </div>

    @endif


</div>


<script>
document.addEventListener(
    'DOMContentLoaded',
    function () {

        /* =====================================================
           ELEMENTS
        ===================================================== */

        const searchInput =
            document.getElementById(
                'categorySearch'
            );


        const rows =
            document.querySelectorAll(
                '#categoryTable .disease-row'
            );


        const noResults =
            document.getElementById(
                'diseaseNoResults'
            );


        const countElement =
            document.getElementById(
                'diseaseListCount'
            );


        /* =====================================================
           SEARCH
        ===================================================== */

        function filterDiseases() {

            const filter =
                searchInput.value
                    .toLowerCase()
                    .trim();


            let visible = 0;


            rows.forEach(
                function (row) {

                    const diseaseName =
                        row.dataset.search || '';


                    const matches =
                        diseaseName.includes(
                            filter
                        );


                    row.style.display =
                        matches
                            ? ''
                            : 'none';


                    if (matches) {
                        visible++;
                    }

                }
            );


            /* Show "No results" */

            if (noResults) {

                noResults.style.display =
                    (
                        visible === 0 &&
                        rows.length > 0
                    )
                        ? ''
                        : 'none';

            }


            /* Update count */

            if (countElement) {

                countElement.textContent =
                    rows.length
                        ? `${visible} of ${rows.length} diseases`
                        : '';

            }

        }


        if (searchInput) {

            searchInput.addEventListener(
                'input',
                filterDiseases
            );


            filterDiseases();

        }

    }
);
</script>

@endsection