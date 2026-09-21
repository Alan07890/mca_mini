@extends('layouts.AdminMaster')

@section('title', 'Disease Management')

@section('content')

<style>
    /* =========================================================
       CARDO-FER DISEASE MANAGEMENT
       Full-width Dark Green / Gold Theme
    ========================================================= */

    .disease-page {
        width: 100%;
        min-height: calc(100vh - 150px);
        color: #F1F8E9;
        padding: 8px 18px 30px;
    }

    /* =========================================================
       MAIN CARD
    ========================================================= */

    .disease-card {
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
            diseaseFadeUp .45s ease both;
    }

    /* =========================================================
       HEADER
    ========================================================= */

    .disease-header {
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

    .disease-header::before {
        content: "";

        position: absolute;

        width: 260px;
        height: 260px;

        right: -100px;
        top: -120px;

        border-radius: 50%;

        border:
            1px solid rgba(255, 255, 255, 0.11);
    }

    .disease-header::after {
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

    .disease-badge {
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

    .disease-badge i {
        color: #FFD54F;
    }

    .disease-header h2 {
        position: relative;
        z-index: 2;

        margin: 14px 0 7px;

        font-family:
            'Fraunces',
            Georgia,
            serif;

        font-size: 29px;

        font-weight: 500;

        color: #F1F8E9;
    }

    .disease-header p {
        position: relative;
        z-index: 2;

        max-width: 850px;

        margin: 0;

        color:
            rgba(241, 248, 233, 0.72);

        font-size: 13px;

        line-height: 1.7;
    }

    /* =========================================================
       BODY
    ========================================================= */

    .disease-body {
        padding: 38px 42px 32px;
    }

    .disease-section-title {
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

    .disease-section-title i {
        color: #FFD54F;
        font-size: 16px;
    }

    .disease-section-subtitle {
        margin: 0 0 24px;

        color: #a9c9ae;

        font-size: 13px;
    }

    /* =========================================================
       FORM AREA
    ========================================================= */

    .disease-form-area {
        width: 100%;
    }

    .disease-label {
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

    .disease-input {
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

    .disease-input:focus-within {
        border-color: #FFD54F;

        box-shadow:
            0 0 0 3px
            rgba(255, 213, 79, 0.11);
    }

    .disease-input-icon {
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

    .disease-input input {
        width: 100%;

        border: none;

        outline: none;

        background: transparent;

        padding: 14px 16px;

        color: #F1F8E9;

        font-size: 13.5px;
    }

    .disease-input input::placeholder {
        color:
            rgba(241, 248, 233, 0.35);
    }

    /* =========================================================
       ALERTS
    ========================================================= */

    .disease-success {
        display: flex;
        align-items: flex-start;

        gap: 10px;

        margin-top: 17px;

        padding: 12px 14px;

        border-radius: 11px;

        background:
            rgba(76, 175, 80, 0.09);

        border:
            1px solid rgba(76, 175, 80, 0.22);

        color: #A5D6A7;

        font-size: 12.5px;
    }

    .disease-success i {
        color: #81C784;

        margin-top: 2px;
    }

    .disease-error {
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

    .disease-error ul {
        margin: 0;

        padding-left: 18px;
    }

    /* =========================================================
       ACTIONS
    ========================================================= */

    .disease-actions {
        display: flex;

        width: 100%;

        gap: 12px;

        flex-wrap: wrap;

        margin-top: 24px;
    }

    .disease-btn-save,
    .disease-btn-view {
        flex: 1 1 0;

        min-width: 220px;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 8px;

        padding: 12px 20px;

        border-radius: 999px;

        font-size: 13.5px;
        font-weight: 700;

        transition:
            transform .18s ease,
            box-shadow .18s ease,
            background .18s ease,
            border-color .18s ease,
            color .18s ease;
    }

    /* SAVE */

    .disease-btn-save {
        border: none;

        background:
            linear-gradient(
                180deg,
                #FFD54F,
                #d5893f
            );

        color: #241708;
    }

    .disease-btn-save:hover {
        transform: translateY(-2px);

        box-shadow:
            0 14px 28px -14px
            rgba(255, 213, 79, 0.60);

        color: #241708;
    }

    /* VIEW */

    .disease-btn-view {
        border:
            1px solid rgba(129, 199, 132, 0.30);

        background: transparent;

        color: #a9c9ae;

        text-decoration: none;
    }

    .disease-btn-view:hover {
        background:
            rgba(129, 199, 132, 0.08);

        border-color: #81C784;

        color: #F1F8E9;

        transform: translateY(-2px);
    }

    /* =========================================================
       FOOT NOTE
    ========================================================= */

    .disease-note {
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

    @keyframes diseaseFadeUp {

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

        .disease-page {
            padding: 5px 10px 24px;
        }

        .disease-header,
        .disease-body {
            padding-left: 25px;
            padding-right: 25px;
        }

        .disease-header h2 {
            font-size: 26px;
        }

    }

    @media (max-width: 576px) {

        .disease-page {
            padding:
                5px 6px 20px;
        }

        .disease-body,
        .disease-header {
            padding: 24px 20px;
        }

        .disease-header h2 {
            font-size: 24px;
        }

        .disease-btn-save,
        .disease-btn-view {
            flex-basis: 100%;
        }

    }
</style>


<div class="disease-page">

    <div class="disease-card">

        {{-- =================================================
             HEADER
        ================================================== --}}

        <div class="disease-header">

            <span class="disease-badge">

                <i class="fa-solid fa-leaf"></i>

                Cardo-Fer · Disease Intelligence

            </span>


            <h2>
                Disease Category Management
            </h2>


            <p>
                Create and maintain the cardamom disease categories
                used throughout the Cardo-Fer disease intelligence
                and recommendation system.
            </p>

        </div>


        {{-- =================================================
             BODY
        ================================================== --}}

        <div class="disease-body">

            <div class="disease-section-title">

                <i class="fa-solid fa-plus-circle"></i>

                Add Disease Category

            </div>


            <p class="disease-section-subtitle">

                Enter the disease name below and save it to the
                Cardo-Fer disease catalogue.

            </p>


            <div class="disease-form-area">

                <form
                    action="{{ route('diseasecategory_insert') }}"
                    method="POST"
                >

                    @csrf


                    {{-- Disease Name --}}

                    <div>

                        <label
                            class="disease-label"
                            for="diseases_name"
                        >
                            Disease Name
                        </label>


                        <div class="disease-input">

                            <span class="disease-input-icon">

                                <i class="fa-solid fa-leaf"></i>

                            </span>


                            <input
                                type="text"
                                id="diseases_name"
                                name="diseases_name"
                                value="{{ old('diseases_name') }}"
                                placeholder="e.g. Cardamom Blight"
                                autocomplete="off"
                                required
                            >

                        </div>

                    </div>


                    {{-- Success --}}

                    @if(session('success'))

                        <div
                            class="disease-success"
                            role="alert"
                        >

                            <i class="fa-solid fa-circle-check"></i>

                            <span>
                                {{ session('success') }}
                            </span>

                        </div>

                    @endif


                    {{-- Validation Errors --}}

                    @if($errors->any())

                        <div
                            class="disease-error"
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


                    {{-- Buttons --}}

                    <div class="disease-actions">

                        <button
                            type="submit"
                            class="disease-btn-save"
                        >

                            <i class="fa-solid fa-floppy-disk"></i>

                            Save Disease

                        </button>


                        <a
                            href="{{ route('categoryview') }}"
                            class="disease-btn-view"
                        >

                            <i class="fa-solid fa-list"></i>

                            View Diseases

                        </a>

                    </div>

                </form>

            </div>


            <div class="disease-note">

                🌿 Cardo-Fer · Cardamom Disease Intelligence
                &amp; Fertilizer Recommendation System

            </div>

        </div>

    </div>

</div>

@endsection