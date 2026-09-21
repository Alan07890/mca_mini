@extends('layouts.AdminMaster')

@section('title', 'Edit Disease Category')

@section('content')

<style>
    /* =========================================================
       CARDO-FER — EDIT DISEASE CATEGORY
       Full Width Dark Green / Gold Theme
    ========================================================= */

    .disease-edit-page {
        width: 100%;
        min-height: calc(100vh - 150px);
        padding: 8px 18px 30px;
        color: #F1F8E9;
    }

    /* =========================================================
       MAIN CARD
    ========================================================= */

    .disease-edit-card {
        width: 100%;
        max-width: none;
        margin: 0;

        background: #10251b;

        border: 1px solid rgba(129, 199, 132, 0.18);
        border-radius: 20px;

        overflow: hidden;

        box-shadow:
            0 25px 55px rgba(0, 0, 0, 0.30);

        animation:
            diseaseEditFade .45s ease both;
    }

    /* =========================================================
       HEADER
    ========================================================= */

    .disease-edit-header {
        position: relative;
        overflow: hidden;

        padding: 38px 42px 34px;

        background:
            linear-gradient(
                135deg,
                #0e3515 0%,
                #1B5E20 58%,
                #2E7D32 100%
            );

        border-bottom:
            1px solid rgba(129, 199, 132, 0.18);
    }

    .disease-edit-header::before {
        content: "";

        position: absolute;

        width: 260px;
        height: 260px;

        right: -105px;
        top: -125px;

        border-radius: 50%;

        border:
            1px solid rgba(255, 255, 255, 0.12);
    }

    .disease-edit-header::after {
        content: "";

        position: absolute;

        width: 135px;
        height: 135px;

        left: -65px;
        bottom: -80px;

        border-radius: 50%;

        border:
            1px solid rgba(255, 255, 255, 0.08);
    }

    .disease-edit-badge {
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

        letter-spacing: 1px;

        text-transform: uppercase;

        font-weight: 700;
    }

    .disease-edit-badge i {
        color: #FFD54F;
    }

    .disease-edit-header h2 {
        position: relative;
        z-index: 2;

        margin: 14px 0 7px;

        color: #F1F8E9;

        font-family:
            'Fraunces',
            Georgia,
            serif;

        font-size: 29px;

        font-weight: 500;
    }

    .disease-edit-header p {
        position: relative;
        z-index: 2;

        margin: 0;

        max-width: 850px;

        color:
            rgba(241, 248, 233, 0.72);

        font-size: 13px;

        line-height: 1.7;
    }

    /* =========================================================
       BODY
    ========================================================= */

    .disease-edit-body {
        padding: 38px 42px 32px;
    }

    .disease-edit-section-title {
        display: flex;
        align-items: center;

        gap: 9px;

        margin-bottom: 7px;

        color: #F1F8E9;

        font-family:
            'Fraunces',
            Georgia,
            serif;

        font-size: 21px;
    }

    .disease-edit-section-title i {
        color: #FFD54F;
        font-size: 16px;
    }

    .disease-edit-section-subtitle {
        margin: 0 0 24px;

        color: #a9c9ae;

        font-size: 13px;
    }

    /* =========================================================
       FORM AREA
    ========================================================= */

    .disease-edit-form-area {
        width: 100%;
    }

    /* =========================================================
       LABEL
    ========================================================= */

    .disease-edit-label {
        display: block;

        margin-bottom: 8px;

        color: #81C784;

        font-size: 11px;

        font-weight: 700;

        text-transform: uppercase;

        letter-spacing: .6px;
    }

    /* =========================================================
       INPUT
    ========================================================= */

    .disease-edit-input {
        display: flex;
        align-items: stretch;

        width: 100%;

        overflow: hidden;

        background: #07150f;

        border:
            1px solid rgba(129, 199, 132, 0.24);

        border-radius: 12px;

        transition:
            border-color .18s ease,
            box-shadow .18s ease;
    }

    .disease-edit-input:focus-within {
        border-color: #FFD54F;

        box-shadow:
            0 0 0 3px
            rgba(255, 213, 79, 0.11);
    }

    .disease-edit-input .icon-box {
        width: 56px;

        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        background:
            rgba(76, 175, 80, 0.09);

        border-right:
            1px solid rgba(129, 199, 132, 0.12);

        color: #81C784;
    }

    .disease-edit-input input {
        width: 100%;

        border: none;

        outline: none;

        background: transparent;

        padding: 14px 16px;

        color: #F1F8E9;

        font-size: 13.5px;
    }

    .disease-edit-input input:focus {
        color: #F1F8E9;
    }

    .disease-edit-input input::placeholder {
        color:
            rgba(241, 248, 233, 0.35);
    }

    /* =========================================================
       ERROR
    ========================================================= */

    .disease-edit-error {
        margin-top: 17px;

        padding: 12px 14px;

        border-radius: 11px;

        background:
            rgba(226, 131, 107, 0.08);

        border:
            1px solid rgba(226, 131, 107, 0.20);

        color: #e8a28f;

        font-size: 12.5px;
    }

    .disease-edit-error ul {
        margin: 0;

        padding-left: 18px;
    }

    /* =========================================================
       UPDATE BUTTON
    ========================================================= */

    .disease-edit-update {
        width: 100%;

        margin-top: 23px;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 8px;

        padding: 12px 20px;

        border: none;

        border-radius: 999px;

        background:
            linear-gradient(
                180deg,
                #FFD54F,
                #d5893f
            );

        color: #241708;

        font-size: 13.5px;

        font-weight: 700;

        transition:
            transform .18s ease,
            box-shadow .18s ease;
    }

    .disease-edit-update:hover {
        transform:
            translateY(-2px);

        box-shadow:
            0 14px 28px -14px
            rgba(255, 213, 79, 0.60);

        color: #241708;
    }

    /* =========================================================
       BOTTOM ACTIONS
    ========================================================= */

    .disease-edit-bottom {
        display: flex;

        width: 100%;

        gap: 12px;

        margin-top: 12px;

        flex-wrap: wrap;
    }

    .disease-edit-delete,
    .disease-edit-cancel {
        flex: 1 1 0;

        min-width: 220px;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 8px;

        padding: 12px 18px;

        border-radius: 999px;

        font-size: 13px;

        font-weight: 600;

        transition:
            background .18s ease,
            border-color .18s ease,
            color .18s ease,
            transform .18s ease;
    }

    /* DELETE */

    .disease-edit-delete {
        border:
            1px solid rgba(226, 131, 107, 0.25);

        background:
            rgba(226, 131, 107, 0.07);

        color: #e8a28f;
    }

    .disease-edit-delete:hover {
        background:
            rgba(226, 131, 107, 0.14);

        border-color:
            #e2836b;

        color: #ffb2a0;

        transform:
            translateY(-2px);
    }

    /* CANCEL */

    .disease-edit-cancel {
        border:
            1px solid rgba(129, 199, 132, 0.24);

        background:
            transparent;

        color: #a9c9ae;

        text-decoration: none;
    }

    .disease-edit-cancel:hover {
        background:
            rgba(129, 199, 132, 0.08);

        border-color:
            #81C784;

        color:
            #F1F8E9;

        transform:
            translateY(-2px);
    }

    /* =========================================================
       NOTE
    ========================================================= */

    .disease-edit-note {
        margin-top: 22px;

        padding-top: 16px;

        border-top:
            1px solid rgba(241, 248, 233, 0.08);

        text-align: center;

        color:
            rgba(169, 201, 174, 0.60);

        font-size: 11px;
    }

    /* =========================================================
       ANIMATION
    ========================================================= */

    @keyframes diseaseEditFade {

        from {
            opacity: 0;
            transform: translateY(12px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }

    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 768px) {

        .disease-edit-page {
            padding:
                5px 10px 24px;
        }

        .disease-edit-header,
        .disease-edit-body {
            padding-left: 25px;
            padding-right: 25px;
        }

        .disease-edit-header h2 {
            font-size: 26px;
        }

    }

    @media (max-width: 576px) {

        .disease-edit-page {
            padding:
                5px 6px 20px;
        }

        .disease-edit-header,
        .disease-edit-body {
            padding: 24px 20px;
        }

        .disease-edit-header h2 {
            font-size: 24px;
        }

        .disease-edit-delete,
        .disease-edit-cancel {
            flex-basis: 100%;
        }

    }
</style>


<div class="disease-edit-page">

    <div class="disease-edit-card">

        {{-- =================================================
             HEADER
        ================================================== --}}

        <div class="disease-edit-header">

            <span class="disease-edit-badge">

                <i class="fa-solid fa-pen"></i>

                Cardo-Fer · Disease Intelligence

            </span>


            <h2>
                Edit Disease Category
            </h2>


            <p>
                Update the disease name used by the Cardo-Fer
                disease intelligence and recommendation system.
            </p>

        </div>


        {{-- =================================================
             BODY
        ================================================== --}}

        <div class="disease-edit-body">

            <div class="disease-edit-section-title">

                <i class="fa-solid fa-pen-to-square"></i>

                Disease Details

            </div>


            <p class="disease-edit-section-subtitle">

                Make the required changes below and save the
                updated disease category.

            </p>


            <div class="disease-edit-form-area">

                {{-- =================================================
                     UPDATE FORM
                ================================================== --}}

                <form
                    action="{{ route('category_update', $category->category_id) }}"
                    method="POST"
                >

                    @csrf
                    @method('PUT')


                    <label
                        class="disease-edit-label"
                        for="diseases_name"
                    >
                        Disease Name
                    </label>


                    <div class="disease-edit-input">

                        <span class="icon-box">

                            <i class="fa-solid fa-leaf"></i>

                        </span>


                        <input
                            type="text"
                            id="diseases_name"
                            name="diseases_name"
                            value="{{ old('diseases_name', $category->diseases_name) }}"
                            required
                        >

                    </div>


                    @if($errors->any())

                        <div
                            class="disease-edit-error"
                            role="alert"
                        >

                            <ul>

                                @foreach($errors->all() as $error)

                                    <li>
                                        {{ $error }}
                                    </li>

                                @endforeach

                            </ul>

                        </div>

                    @endif


                    <button
                        type="submit"
                        class="disease-edit-update"
                    >

                        <i class="fa-solid fa-floppy-disk"></i>

                        Update Disease

                    </button>

                </form>


                {{-- =================================================
                     DELETE + CANCEL
                ================================================== --}}

                <form
                    action="{{ route('category_delete', $category->category_id) }}"
                    method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this disease category? This action cannot be undone.');"
                >

                    @csrf
                    @method('DELETE')


                    <div class="disease-edit-bottom">

                        <button
                            type="submit"
                            class="disease-edit-delete"
                        >

                            <i class="fa-solid fa-trash"></i>

                            Delete Disease

                        </button>


                        <a
                            href="{{ route('categoryview') }}"
                            class="disease-edit-cancel"
                        >

                            <i class="fa-solid fa-arrow-left"></i>

                            Back to Diseases

                        </a>

                    </div>

                </form>

            </div>


            <div class="disease-edit-note">

                🌿 Cardo-Fer · Cardamom Disease Intelligence
                &amp; Fertilizer Recommendation System

            </div>

        </div>

    </div>

</div>

@endsection