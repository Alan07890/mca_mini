@extends('layouts.AdminMaster')

@section('title', 'Symptoms Management')

@section('content')

<style>

    /* =========================================================
       CARDO-FER — SYMPTOMS LIST
    ========================================================= */

    .symptoms-list-page {
        width: 100%;
        padding: 5px 18px 24px;
        color: #f1f8e9;
    }

    /* =========================================================
       HEADER
    ========================================================= */

    .symptoms-list-header {
        background:
            linear-gradient(
                120deg,
                #0d2d1d 0%,
                #123c17 50%,
                #1b5e20 100%
            );

        border: 1px solid rgba(129, 199, 132, 0.12);
        border-radius: 22px;

        padding: 38px 42px 34px;

        color: #fff;

        box-shadow:
            0 16px 40px rgba(0, 0, 0, 0.22);

        position: relative;
        overflow: hidden;

        margin-bottom: 22px;
    }

    .symptoms-list-header::before {
        content: "";

        position: absolute;

        width: 220px;
        height: 220px;

        right: -70px;
        top: -90px;

        border: 1px solid rgba(255, 213, 79, 0.12);

        border-radius: 50%;
    }

    .symptoms-list-header::after {
        content: "";

        position: absolute;

        width: 120px;
        height: 120px;

        right: 180px;
        bottom: -58px;

        border: 1px solid rgba(129, 199, 132, 0.10);

        border-radius: 50%;
    }

    .symptoms-list-header h3 {
        position: relative;
        z-index: 1;

        font-weight: 700;

        margin: 0 0 7px;

        font-size: 1.65rem;

        letter-spacing: -0.3px;
    }

    .symptoms-list-header p {
        position: relative;
        z-index: 1;

        margin: 0;

        color: #c6dcc9;

        font-weight: 300;

        font-size: 0.92rem;
    }

    /* =========================================================
       TOOLBAR
    ========================================================= */

    .symptoms-toolbar {
        width: 100%;

        background: #10251b;

        border: 1px solid rgba(129, 199, 132, 0.10);

        border-radius: 17px;

        padding: 15px 18px;

        box-shadow:
            0 10px 28px rgba(0, 0, 0, 0.20);

        margin-bottom: 20px;

        display: flex;

        justify-content: space-between;

        align-items: center;

        gap: 14px;

        flex-wrap: wrap;
    }

    .symptoms-search {
        display: flex;
        align-items: center;

        background: #0b1d14;

        border: 1px solid #284b35;

        border-radius: 12px;

        padding: 5px 8px 5px 13px;

        flex: 1 1 300px;

        max-width: 700px;

        transition:
            border-color .18s ease,
            box-shadow .18s ease;
    }

    .symptoms-search:focus-within {
        border-color: #81c784;

        box-shadow:
            0 0 0 4px rgba(129, 199, 132, 0.08);
    }

    .symptoms-search i {
        color: #81c784;

        font-size: 0.9rem;

        flex-shrink: 0;
    }

    .symptoms-search input {
        width: 100%;

        border: none;

        outline: none;

        background: transparent;

        color: #f1f8e9;

        padding: 9px 10px;

        font-size: 0.9rem;
    }

    .symptoms-search input::placeholder {
        color: #75917b;
    }

    .symptom-result-count {
        display: inline-flex;

        align-items: center;

        gap: 7px;

        padding: 8px 12px;

        border-radius: 999px;

        background:
            rgba(129, 199, 132, 0.08);

        border:
            1px solid rgba(129, 199, 132, 0.14);

        color: #a9c9ae;

        font-size: 11px;

        font-weight: 600;

        white-space: nowrap;
    }

    .symptom-result-count strong {
        color: #81c784;
    }

    .btn-add-symptom {
        background:
            linear-gradient(
                120deg,
                #2e7d32,
                #4caf50
            );

        color: #fff;

        border: none;

        padding: 10px 18px;

        border-radius: 12px;

        font-weight: 600;

        text-decoration: none;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 8px;

        white-space: nowrap;

        box-shadow:
            0 8px 18px rgba(46, 125, 50, 0.20);

        transition:
            transform .18s ease,
            box-shadow .18s ease;
    }

    .btn-add-symptom:hover {
        color: #fff;

        transform: translateY(-2px);

        box-shadow:
            0 11px 23px rgba(46, 125, 50, 0.30);
    }

    /* =========================================================
       TABLE CARD
    ========================================================= */

    .symptoms-table-card {
        width: 100%;

        background: #10251b;

        border:
            1px solid rgba(129, 199, 132, 0.10);

        border-radius: 18px;

        box-shadow:
            0 12px 32px rgba(0, 0, 0, 0.22);

        overflow: hidden;
    }

    .symptoms-table-wrap {
        width: 100%;

        overflow-x: auto;

        max-height: 610px;

        overflow-y: auto;
    }

    .symptoms-table {
        width: 100%;

        min-width: 900px;

        margin: 0;

        border-collapse: collapse;
    }

    .symptoms-table thead th {
        position: sticky;

        top: 0;

        z-index: 2;

        background: #0d2d1d;

        color: #f1f8e9;

        padding: 15px 20px;

        font-size: 0.78rem;

        font-weight: 600;

        text-transform: uppercase;

        letter-spacing: 0.5px;

        border: none;
    }

    .symptoms-table tbody td {
        padding: 14px 20px;

        vertical-align: middle;

        border-bottom:
            1px solid rgba(129, 199, 132, 0.08);

        font-size: 0.9rem;

        color: #d5e6d7;

        background: #10251b;
    }

    .symptoms-table tbody tr {
        transition:
            background .15s ease;
    }

    .symptoms-table tbody tr:hover td {
        background: #163321;
    }

    .symptoms-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* =========================================================
       COLUMN SIZING
    ========================================================= */

    .symptoms-table th:nth-child(1),
    .symptoms-table td:nth-child(1) {
        width: 150px;
    }

    .symptoms-table th:nth-child(2),
    .symptoms-table td:nth-child(2) {
        width: 150px;
    }

    .symptoms-table th:nth-child(3),
    .symptoms-table td:nth-child(3) {
        width: auto;
    }

    .symptoms-table th:nth-child(4),
    .symptoms-table td:nth-child(4) {
        width: 270px;
    }

    /* =========================================================
       ID BADGE
    ========================================================= */

    .symptom-id-badge {
        display: inline-flex;

        align-items: center;

        background:
            rgba(129, 199, 132, 0.10);

        border:
            1px solid rgba(129, 199, 132, 0.18);

        color: #81c784;

        font-weight: 600;

        font-size: 0.78rem;

        padding: 5px 11px;

        border-radius: 30px;
    }

    /* =========================================================
       PHOTO
    ========================================================= */

    .symptom-photo-button {
        position: relative;

        width: 74px;
        height: 58px;

        padding: 0;

        border: none;

        border-radius: 11px;

        overflow: hidden;

        background: #07150f;

        border:
            1px solid rgba(129, 199, 132, 0.18);

        cursor: pointer;

        box-shadow:
            0 5px 12px rgba(0, 0, 0, 0.20);

        transition:
            transform .18s ease,
            border-color .18s ease,
            box-shadow .18s ease;
    }

    .symptom-photo-button:hover {
        transform:
            translateY(-2px) scale(1.03);

        border-color:
            rgba(255, 213, 79, 0.55);

        box-shadow:
            0 8px 18px rgba(0, 0, 0, 0.30);
    }

    .symptom-photo-button img {
        display: block;

        width: 100%;
        height: 100%;

        object-fit: cover;
    }

    .symptom-photo-button::after {
        content: "";

        position: absolute;

        inset: 0;

        background:
            linear-gradient(
                135deg,
                transparent 40%,
                rgba(255, 255, 255, 0.08)
            );

        pointer-events: none;
    }

    .symptom-photo-missing {
        width: 74px;
        height: 58px;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 11px;

        background:
            rgba(129, 199, 132, 0.06);

        border:
            1px dashed rgba(129, 199, 132, 0.18);

        color:
            rgba(169, 201, 174, 0.45);

        font-size: 17px;
    }

    /* =========================================================
       SYMPTOM NAME
    ========================================================= */

    .symptom-name-cell {
        display: inline-flex;

        align-items: center;

        gap: 9px;

        font-weight: 500;

        color: #f1f8e9;
    }

    .symptom-name-cell i {
        color: #ffd54f;

        font-size: 0.85rem;
    }

    /* =========================================================
       ACTIONS
    ========================================================= */

    .symptom-action-group {
        display: inline-flex;

        align-items: center;

        justify-content: flex-end;

        gap: 7px;
    }

    .btn-edit-symptom {
        border:
            1px solid rgba(255, 213, 79, 0.18);

        padding: 7px 13px;

        border-radius: 30px;

        font-size: 0.78rem;

        font-weight: 600;

        display: inline-flex;

        align-items: center;

        gap: 6px;

        background:
            rgba(255, 213, 79, 0.09);

        color:
            #ffd54f;

        text-decoration: none;

        transition:
            background .18s ease,
            color .18s ease,
            transform .18s ease;
    }

    .btn-edit-symptom:hover {
        background: #ffd54f;

        color: #18210d;

        transform: translateY(-1px);
    }

    .btn-delete-symptom {
        border:
            1px solid rgba(226, 131, 107, 0.16);

        padding: 7px 13px;

        border-radius: 30px;

        font-size: 0.78rem;

        font-weight: 600;

        display: inline-flex;

        align-items: center;

        gap: 6px;

        background:
            rgba(226, 131, 107, 0.08);

        color:
            #e2836b;

        transition:
            background .18s ease,
            color .18s ease,
            transform .18s ease;

        cursor: pointer;
    }

    .btn-delete-symptom:hover {
        background: #e2836b;

        color: #fff;

        transform: translateY(-1px);
    }

    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .empty-state {
        text-align: center;

        padding: 60px 20px;

        color: #829b86;
    }

    .empty-state i {
        display: block;

        font-size: 2.4rem;

        color: #81c784;

        margin-bottom: 13px;

        opacity: 0.75;
    }

    .empty-state p {
        margin: 0;
    }

    /* =========================================================
       IMAGE MODAL
    ========================================================= */

    .symptom-image-modal {
        position: fixed;

        inset: 0;

        z-index: 2000;

        display: none;

        align-items: center;

        justify-content: center;

        padding: 25px;

        background:
            rgba(3, 12, 8, 0.78);

        backdrop-filter: blur(6px);
    }

    .symptom-image-modal.show {
        display: flex;

        animation:
            modalFade .2s ease both;
    }

    .symptom-image-modal-card {
        position: relative;

        width: min(720px, 100%);

        max-height: 90vh;

        overflow: hidden;

        background: #10251b;

        border:
            1px solid rgba(129, 199, 132, 0.20);

        border-radius: 20px;

        box-shadow:
            0 30px 80px rgba(0, 0, 0, 0.50);

        animation:
            modalScale .22s ease both;
    }

    .symptom-modal-image {
        display: block;

        width: 100%;

        max-height: 70vh;

        object-fit: contain;

        background: #07150f;
    }

    .symptom-modal-caption {
        padding: 15px 18px 17px;

        color: #f1f8e9;

        font-size: 14px;

        font-weight: 600;
    }

    .symptom-modal-caption span {
        display: block;

        margin-top: 3px;

        color: #a9c9ae;

        font-size: 11px;

        font-weight: 400;
    }

    .symptom-modal-close {
        position: absolute;

        top: 12px;
        right: 12px;

        width: 38px;
        height: 38px;

        display: flex;

        align-items: center;
        justify-content: center;

        border: 1px solid rgba(255, 255, 255, 0.12);

        border-radius: 11px;

        background:
            rgba(7, 21, 15, 0.82);

        color: #fff;

        cursor: pointer;

        transition:
            background .18s ease,
            transform .18s ease;
    }

    .symptom-modal-close:hover {
        background:
            rgba(226, 131, 107, 0.85);

        transform: scale(1.05);
    }

    /* =========================================================
       ANIMATIONS
    ========================================================= */

    @keyframes modalFade {

        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes modalScale {

        from {
            opacity: 0;

            transform: scale(.96);
        }

        to {
            opacity: 1;

            transform: scale(1);
        }
    }

    /* =========================================================
       TABLET
    ========================================================= */

    @media (max-width: 900px) {

        .symptoms-list-page {
            padding-left: 10px;
            padding-right: 10px;
        }

        .symptoms-list-header {
            padding-top: 25px;
            padding-bottom: 25px;
        }
    }

    /* =========================================================
       MOBILE
    ========================================================= */

    @media (max-width: 576px) {

        .symptoms-list-page {
            padding:
                5px 6px 20px;
        }

        .symptoms-list-header {
            padding: 25px 20px;
        }

        .symptoms-list-header h3 {
            font-size: 1.4rem;
        }

        .symptoms-toolbar {
            align-items: stretch;
        }

        .symptoms-search {
            max-width: none;

            flex-basis: 100%;
        }

        .btn-add-symptom {
            width: 100%;
        }

        .symptom-result-count {
            width: 100%;

            justify-content: center;
        }

        .symptoms-table thead th,
        .symptoms-table tbody td {
            white-space: nowrap;
        }

        .symptom-action-group {
            justify-content: flex-start;
        }

        .btn-edit-symptom,
        .btn-delete-symptom {
            padding: 7px 11px;
        }

        .symptom-image-modal {
            padding: 12px;
        }

        .symptom-image-modal-card {
            border-radius: 15px;
        }
    }

</style>


<div class="symptoms-list-page">

    <!-- =====================================================
         HEADER
    ====================================================== -->

    <div class="symptoms-list-header">

        <h3>
            🌿 All Symptoms
        </h3>

        <p>
            Manage all cardamom symptoms used by the
            disease prediction system.
        </p>

    </div>


    <!-- =====================================================
         TOOLBAR
    ====================================================== -->

    <div class="symptoms-toolbar">

        <div class="symptoms-search">

            <i class="fa-solid fa-magnifying-glass"></i>

            <input
                type="text"
                id="symptomSearch"
                placeholder="Search symptom..."
                autocomplete="off"
                aria-label="Search symptoms"
            >

        </div>


        <div
            class="symptom-result-count"
            id="symptomResultCount"
        >

            <i class="fa-solid fa-list"></i>

            <span>
                Showing
                <strong id="visibleSymptomCount">0</strong>
                symptoms
            </span>

        </div>


        <a
            href="{{ route('symptoms') }}"
            class="btn-add-symptom"
        >

            <i class="fa-solid fa-plus"></i>

            Add New Symptom

        </a>

    </div>


    <!-- =====================================================
         TABLE
    ====================================================== -->

    <div class="symptoms-table-card">

        <div class="symptoms-table-wrap">

            <table
                class="symptoms-table"
                id="symptomsTable"
            >

                <thead>

                    <tr>

                        <th>
                            Symptom ID
                        </th>

                        <th>
                            Photo
                        </th>

                        <th>
                            Symptom Name
                        </th>

                        <th class="text-end">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody id="symptomsTableBody">

                    @forelse($symptoms as $symptom)

                        <tr
                            data-symptom-name="{{ strtolower($symptom->symptom_name) }}"
                        >

                            <!-- ID -->

                            <td>

                                <span class="symptom-id-badge">

                                    #{{ $symptom->symptom_id }}

                                </span>

                            </td>


                            <!-- PHOTO -->

                            <td>

                                @if($symptom->symptom_image)

                                    <button
                                        type="button"
                                        class="symptom-photo-button"
                                        data-image="{{ asset('storage/' . $symptom->symptom_image) }}"
                                        data-name="{{ $symptom->symptom_name }}"
                                        aria-label="View photo of {{ $symptom->symptom_name }}"
                                        title="View symptom photo"
                                    >

                                        <img
                                            src="{{ asset('storage/' . $symptom->symptom_image) }}"
                                            alt="Symptom: {{ $symptom->symptom_name }}"
                                            loading="lazy"
                                        >

                                    </button>

                                @else

                                    <div
                                        class="symptom-photo-missing"
                                        title="No photo uploaded"
                                    >

                                        <i class="fa-regular fa-image"></i>

                                    </div>

                                @endif

                            </td>


                            <!-- SYMPTOM NAME -->

                            <td>

                                <span class="symptom-name-cell">

                                    <i class="fa-solid fa-leaf"></i>

                                    {{ $symptom->symptom_name }}

                                </span>

                            </td>


                            <!-- ACTIONS -->

                            <td class="text-end">

                                <div class="symptom-action-group">

                                    <a
                                        href="{{ route('symptom_edit', $symptom->symptom_id) }}"
                                        class="btn-edit-symptom"
                                    >

                                        <i class="fa-solid fa-pen"></i>

                                        Edit

                                    </a>


                                    <form
                                        action="{{ route('symptom_delete', $symptom->symptom_id) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this symptom?');"
                                    >

                                        @csrf

                                        @method('DELETE')


                                        <button
                                            type="submit"
                                            class="btn-delete-symptom"
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

                            <td colspan="4">

                                <div class="empty-state">

                                    <i class="fa-solid fa-seedling"></i>

                                    <p>
                                        No symptoms added yet.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


<!-- =========================================================
     IMAGE PREVIEW MODAL
========================================================= -->

<div
    class="symptom-image-modal"
    id="symptomImageModal"
    aria-hidden="true"
>

    <div class="symptom-image-modal-card">

        <button
            type="button"
            class="symptom-modal-close"
            id="symptomModalClose"
            aria-label="Close image preview"
        >

            <i class="fa-solid fa-xmark"></i>

        </button>


        <img
            src=""
            alt=""
            class="symptom-modal-image"
            id="symptomModalImage"
        >


        <div class="symptom-modal-caption">

            <div id="symptomModalName">
                Symptom
            </div>

            <span>
                Cardo-Fer symptom photograph
            </span>

        </div>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    /* =====================================================
       SEARCH
    ===================================================== */

    const searchInput =
        document.getElementById('symptomSearch');

    const tableBody =
        document.getElementById('symptomsTableBody');

    const visibleCount =
        document.getElementById('visibleSymptomCount');


    const rows = tableBody
        ? Array.from(
            tableBody.querySelectorAll(
                'tr[data-symptom-name]'
            )
        )
        : [];


    function updateVisibleCount() {

        if (!visibleCount) {
            return;
        }

        const count =
            rows.filter(function (row) {

                return row.style.display !== 'none';

            }).length;


        visibleCount.textContent = count;
    }


    updateVisibleCount();


    if (searchInput) {

        searchInput.addEventListener(
            'input',
            function () {

                const filter =
                    this.value
                        .toLowerCase()
                        .trim();


                rows.forEach(function (row) {

                    const name =
                        row.dataset.symptomName || '';


                    row.style.display =
                        name.includes(filter)
                            ? ''
                            : 'none';

                });


                updateVisibleCount();

            }
        );

    }


    /* =====================================================
       IMAGE PREVIEW MODAL
    ===================================================== */

    const modal =
        document.getElementById(
            'symptomImageModal'
        );

    const modalImage =
        document.getElementById(
            'symptomModalImage'
        );

    const modalName =
        document.getElementById(
            'symptomModalName'
        );

    const modalClose =
        document.getElementById(
            'symptomModalClose'
        );


    function openImageModal(image, name) {

        if (!modal || !modalImage) {
            return;
        }


        modalImage.src = image;

        modalImage.alt =
            'Symptom: ' + name;


        if (modalName) {

            modalName.textContent =
                name;

        }


        modal.classList.add('show');

        modal.setAttribute(
            'aria-hidden',
            'false'
        );

        document.body.style.overflow =
            'hidden';
    }


    function closeImageModal() {

        if (!modal) {
            return;
        }


        modal.classList.remove('show');

        modal.setAttribute(
            'aria-hidden',
            'true'
        );


        if (modalImage) {

            modalImage.src = '';

            modalImage.alt = '';

        }


        document.body.style.overflow = '';
    }


    document
        .querySelectorAll('.symptom-photo-button')
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    openImageModal(
                        this.dataset.image,
                        this.dataset.name
                    );

                }
            );

        });


    if (modalClose) {

        modalClose.addEventListener(
            'click',
            closeImageModal
        );

    }


    if (modal) {

        modal.addEventListener(
            'click',
            function (event) {

                if (event.target === modal) {

                    closeImageModal();

                }

            }
        );

    }


    document.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key === 'Escape' &&
                modal &&
                modal.classList.contains('show')
            ) {

                closeImageModal();

            }

        }
    );

});

</script>

@endsection