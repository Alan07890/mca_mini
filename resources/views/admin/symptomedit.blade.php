@extends('layouts.AdminMaster')

@section('title', 'Edit Symptom')

@section('content')

<style>

    /* =========================================================
       CARDO-FER — EDIT SYMPTOM
       Full Width Dark Green / Gold Theme
    ========================================================= */

    .symptom-edit-page {
        width: 100%;
        min-height: calc(100vh - 150px);
        padding: 8px 18px 30px;
        color: #F1F8E9;
    }

    /* =========================================================
       MAIN CARD
    ========================================================= */

    .symptom-edit-card {
        width: 100%;
        max-width: none;
        margin: 0;

        background: #10251b;

        border:
            1px solid rgba(129, 199, 132, 0.18);

        border-radius: 20px;
        overflow: hidden;

        box-shadow:
            0 25px 55px rgba(0, 0, 0, 0.30);

        animation:
            symptomEditFade .45s ease both;
    }

    /* =========================================================
       HEADER
    ========================================================= */

    .symptom-edit-header {
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

    .symptom-edit-header::before {
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

    .symptom-edit-header::after {
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

    .symptom-edit-badge {
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

    .symptom-edit-badge i {
        color: #FFD54F;
    }

    .symptom-edit-header h2 {
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

    .symptom-edit-header p {
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

    .symptom-edit-body {
        padding: 38px 42px 32px;
    }

    .symptom-edit-section-title {
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

    .symptom-edit-section-title i {
        color: #FFD54F;
        font-size: 16px;
    }

    .symptom-edit-section-subtitle {
        margin: 0 0 24px;

        color: #a9c9ae;

        font-size: 13px;
    }

    /* =========================================================
       FORM AREA
    ========================================================= */

    .symptom-edit-form-area {
        width: 100%;
    }

    /* =========================================================
       LABEL
    ========================================================= */

    .symptom-edit-label {
        display: block;

        margin-bottom: 8px;

        color: #81C784;

        font-size: 11px;
        font-weight: 700;

        text-transform: uppercase;
        letter-spacing: .6px;
    }

    /* =========================================================
       TEXT INPUT
    ========================================================= */

    .symptom-edit-input {
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

    .symptom-edit-input:focus-within {
        border-color: #FFD54F;

        box-shadow:
            0 0 0 3px
            rgba(255, 213, 79, 0.11);
    }

    .symptom-edit-input .icon-box {
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

    .symptom-edit-input input {
        width: 100%;

        border: none;
        outline: none;

        background: transparent;

        padding: 14px 16px;

        color: #F1F8E9;

        font-size: 13.5px;
    }

    .symptom-edit-input input:focus {
        color: #F1F8E9;
    }

    .symptom-edit-input input::placeholder {
        color:
            rgba(241, 248, 233, 0.35);
    }

    /* =========================================================
       IMAGE SECTION
    ========================================================= */

    .symptom-image-section {
        margin-top: 24px;
    }

    .symptom-image-grid {
        display: grid;

        grid-template-columns:
            minmax(280px, 0.85fr)
            minmax(320px, 1.15fr);

        gap: 18px;

        align-items: stretch;
    }

    /* =========================================================
       CURRENT IMAGE
    ========================================================= */

    .symptom-current-image-card {
        background:
            rgba(7, 21, 15, 0.72);

        border:
            1px solid rgba(129, 199, 132, 0.16);

        border-radius: 16px;

        padding: 14px;
    }

    .symptom-image-card-title {
        display: flex;
        align-items: center;
        gap: 7px;

        margin-bottom: 11px;

        color: #D7F2D9;

        font-size: 11px;
        font-weight: 700;

        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .symptom-image-card-title i {
        color: #FFD54F;
    }

    .symptom-current-image-wrapper {
        position: relative;

        width: 100%;

        min-height: 240px;

        overflow: hidden;

        border-radius: 13px;

        background:
            #07150f;

        border:
            1px solid rgba(129, 199, 132, 0.12);
    }

    .symptom-current-image {
        display: block;

        width: 100%;
        height: 275px;

        object-fit: cover;
    }

    .symptom-no-image {
        min-height: 275px;

        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;

        gap: 10px;

        color:
            rgba(169, 201, 174, 0.60);

        text-align: center;
    }

    .symptom-no-image i {
        font-size: 30px;
        color: rgba(129, 199, 132, 0.45);
    }

    .symptom-current-badge {
        position: absolute;

        left: 12px;
        bottom: 12px;

        display: inline-flex;
        align-items: center;
        gap: 6px;

        padding: 6px 9px;

        border-radius: 999px;

        background:
            rgba(7, 21, 15, 0.82);

        border:
            1px solid rgba(129, 199, 132, 0.20);

        color:
            #A5D6A7;

        font-size: 10px;
        font-weight: 600;
    }

    /* =========================================================
       REPLACE IMAGE
    ========================================================= */

    .symptom-replace-image-card {
        background:
            rgba(7, 21, 15, 0.72);

        border:
            1px solid rgba(129, 199, 132, 0.16);

        border-radius: 16px;

        padding: 14px;
    }

    .symptom-upload-box {
        position: relative;

        width: 100%;

        min-height: 240px;

        display: flex;
        align-items: center;
        justify-content: center;

        padding: 20px;

        border:
            1px dashed rgba(129, 199, 132, 0.30);

        border-radius: 13px;

        background:
            linear-gradient(
                145deg,
                rgba(76, 175, 80, 0.06),
                rgba(241, 248, 233, 0.015)
            );

        cursor: pointer;

        transition:
            border-color .2s ease,
            background .2s ease,
            transform .2s ease,
            box-shadow .2s ease;

        overflow: hidden;
    }

    .symptom-upload-box:hover {
        border-color:
            rgba(255, 213, 79, 0.55);

        background:
            linear-gradient(
                145deg,
                rgba(76, 175, 80, 0.10),
                rgba(255, 213, 79, 0.035)
            );

        box-shadow:
            0 12px 30px rgba(0, 0, 0, 0.18);
    }

    .symptom-upload-box.dragging {
        border-color: #FFD54F;

        background:
            rgba(255, 213, 79, 0.08);

        transform:
            scale(1.005);
    }

    .symptom-upload-content {
        width: 100%;

        display: flex;
        flex-direction: column;

        align-items: center;
        justify-content: center;

        text-align: center;
    }

    .symptom-upload-icon {
        width: 54px;
        height: 54px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 16px;

        margin-bottom: 13px;

        background:
            rgba(76, 175, 80, 0.11);

        border:
            1px solid rgba(129, 199, 132, 0.20);

        color:
            #81C784;

        font-size: 21px;
    }

    .symptom-upload-title {
        margin: 0;

        color:
            #F1F8E9;

        font-size: 13.5px;
        font-weight: 700;
    }

    .symptom-upload-subtitle {
        margin: 7px 0 0;

        color:
            rgba(169, 201, 174, 0.65);

        font-size: 11px;

        line-height: 1.6;
    }

    .symptom-upload-subtitle strong {
        color: #FFD54F;
    }

    .symptom-file-input {
        position: absolute;

        inset: 0;

        width: 100%;
        height: 100%;

        opacity: 0;

        cursor: pointer;
    }

    /* =========================================================
       NEW IMAGE PREVIEW
    ========================================================= */

    .symptom-new-preview {
        display: none;

        position: relative;

        width: 100%;

        min-height: 240px;

        border-radius: 13px;

        overflow: hidden;

        background:
            #07150f;

        border:
            1px solid rgba(255, 213, 79, 0.20);

        animation:
            imagePreviewIn .30s ease both;
    }

    .symptom-new-preview.show {
        display: block;
    }

    .symptom-preview-image {
        display: block;

        width: 100%;
        height: 275px;

        object-fit: cover;
    }

    .symptom-preview-overlay {
        position: absolute;

        left: 0;
        right: 0;
        bottom: 0;

        padding:
            45px 18px 16px;

        background:
            linear-gradient(
                transparent,
                rgba(0, 0, 0, 0.78)
            );

        pointer-events: none;
    }

    .symptom-preview-label {
        color:
            #FFD54F;

        font-size: 10px;
        font-weight: 700;

        text-transform: uppercase;
        letter-spacing: .7px;
    }

    .symptom-preview-file {
        margin-top: 2px;

        color:
            #F1F8E9;

        font-size: 11px;
    }

    .symptom-remove-new-image {
        position: absolute;

        top: 11px;
        right: 11px;

        width: 34px;
        height: 34px;

        display: flex;
        align-items: center;
        justify-content: center;

        border: none;

        border-radius: 10px;

        background:
            rgba(7, 21, 15, 0.82);

        border:
            1px solid rgba(255,255,255,0.14);

        color:
            #F1F8E9;

        cursor: pointer;

        transition:
            transform .18s ease,
            background .18s ease;
    }

    .symptom-remove-new-image:hover {
        transform:
            scale(1.06);

        background:
            rgba(226, 131, 107, 0.85);
    }

    /* =========================================================
       IMAGE NOTE
    ========================================================= */

    .symptom-image-note {
        margin-top: 10px;

        display: flex;
        align-items: center;

        gap: 7px;

        color:
            rgba(169, 201, 174, 0.58);

        font-size: 10.5px;
    }

    .symptom-image-note i {
        color: #81C784;
    }

    /* =========================================================
       ERROR
    ========================================================= */

    .symptom-edit-error {
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

    .symptom-edit-error ul {
        margin: 0;
        padding-left: 18px;
    }

    /* =========================================================
       UPDATE BUTTON
    ========================================================= */

    .symptom-edit-update {
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

        cursor: pointer;

        transition:
            transform .18s ease,
            box-shadow .18s ease,
            opacity .18s ease;
    }

    .symptom-edit-update:hover {
        transform:
            translateY(-2px);

        box-shadow:
            0 14px 28px -14px
            rgba(255, 213, 79, 0.60);

        color: #241708;
    }

    .symptom-edit-update.loading {
        pointer-events: none;
        opacity: .78;
    }

    .symptom-edit-update .update-spinner {
        display: none;

        width: 15px;
        height: 15px;

        border:
            2px solid rgba(36, 23, 8, .30);

        border-top-color:
            #241708;

        border-radius: 50%;

        animation:
            updateSpin .65s linear infinite;
    }

    .symptom-edit-update.loading .update-spinner {
        display: inline-block;
    }

    .symptom-edit-update.loading .update-icon {
        display: none;
    }

    /* =========================================================
       BOTTOM ACTIONS
    ========================================================= */

    .symptom-edit-bottom {
        display: flex;

        width: 100%;

        gap: 12px;

        margin-top: 12px;

        flex-wrap: wrap;
    }

    .symptom-edit-delete,
    .symptom-edit-cancel {
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

    /* =========================================================
       DELETE
    ========================================================= */

    .symptom-edit-delete {
        border:
            1px solid rgba(226, 131, 107, 0.25);

        background:
            rgba(226, 131, 107, 0.07);

        color: #e8a28f;

        cursor: pointer;
    }

    .symptom-edit-delete:hover {
        background:
            rgba(226, 131, 107, 0.14);

        border-color:
            rgba(226, 131, 107, 0.38);

        color:
            #ffb2a0;

        transform:
            translateY(-2px);
    }

    /* =========================================================
       CANCEL
    ========================================================= */

    .symptom-edit-cancel {
        border:
            1px solid rgba(129, 199, 132, 0.24);

        background:
            transparent;

        color: #a9c9ae;

        text-decoration: none;
    }

    .symptom-edit-cancel:hover {
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
       FOOT NOTE
    ========================================================= */

    .symptom-edit-note {
        margin-top: 22px;

        padding-top: 16px;

        border-top:
            1px solid rgba(241, 248, 233, 0.08);

        text-align:
            center;

        color:
            rgba(169, 201, 174, 0.60);

        font-size:
            11px;
    }

    /* =========================================================
       ANIMATIONS
    ========================================================= */

    @keyframes symptomEditFade {

        from {
            opacity: 0;

            transform:
                translateY(12px);
        }

        to {
            opacity: 1;

            transform:
                translateY(0);
        }
    }

    @keyframes imagePreviewIn {

        from {
            opacity: 0;

            transform:
                scale(.98);
        }

        to {
            opacity: 1;

            transform:
                scale(1);
        }
    }

    @keyframes updateSpin {

        to {
            transform:
                rotate(360deg);
        }
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 900px) {

        .symptom-image-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {

        .symptom-edit-page {
            padding:
                5px 10px 24px;
        }

        .symptom-edit-header,
        .symptom-edit-body {
            padding-left: 25px;
            padding-right: 25px;
        }

        .symptom-edit-header h2 {
            font-size: 26px;
        }

        .symptom-current-image,
        .symptom-preview-image {
            height: 250px;
        }
    }

    @media (max-width: 576px) {

        .symptom-edit-page {
            padding:
                5px 6px 20px;
        }

        .symptom-edit-header,
        .symptom-edit-body {
            padding:
                24px 20px;
        }

        .symptom-edit-header h2 {
            font-size: 24px;
        }

        .symptom-edit-delete,
        .symptom-edit-cancel {
            flex-basis: 100%;
        }

        .symptom-current-image,
        .symptom-preview-image {
            height: 210px;
        }

        .symptom-upload-box,
        .symptom-new-preview {
            min-height: 210px;
        }
    }

</style>


<div class="symptom-edit-page">

    <div class="symptom-edit-card">

        {{-- =================================================
             HEADER
        ================================================== --}}

        <div class="symptom-edit-header">

            <span class="symptom-edit-badge">

                <i class="fa-solid fa-pen"></i>

                Cardo-Fer · Disease Intelligence

            </span>


            <h2>
                Edit Symptom
            </h2>


            <p>
                Update the symptom name or replace its photograph
                used by the Cardo-Fer disease identification system.
            </p>

        </div>


        {{-- =================================================
             BODY
        ================================================== --}}

        <div class="symptom-edit-body">

            <div class="symptom-edit-section-title">

                <i class="fa-solid fa-stethoscope"></i>

                Symptom Details

            </div>


            <p class="symptom-edit-section-subtitle">

                Make the required changes below. You can keep the
                existing photograph or upload a new one.

            </p>


            <div class="symptom-edit-form-area">

                {{-- =================================================
                     UPDATE FORM
                ================================================== --}}

                <form
                    action="{{ route('symptom_update', $symptom->symptom_id) }}"
                    method="POST"
                    enctype="multipart/form-data"
                    id="symptomEditForm"
                >

                    @csrf

                    @method('PUT')


                    {{-- =================================================
                         SYMPTOM NAME
                    ================================================== --}}

                    <label
                        class="symptom-edit-label"
                        for="symptom_name"
                    >
                        Symptom Name
                    </label>


                    <div class="symptom-edit-input">

                        <span class="icon-box">

                            <i class="fa-solid fa-stethoscope"></i>

                        </span>


                        <input
                            type="text"
                            id="symptom_name"
                            name="symptom_name"
                            value="{{ old('symptom_name', $symptom->symptom_name) }}"
                            autocomplete="off"
                            required
                        >

                    </div>


                    {{-- =================================================
                         IMAGE
                    ================================================== --}}

                    <div class="symptom-image-section">

                        <label class="symptom-edit-label">
                            Symptom Photograph
                        </label>


                        <div class="symptom-image-grid">


                            {{-- =================================================
                                 CURRENT IMAGE
                            ================================================== --}}

                            <div class="symptom-current-image-card">

                                <div class="symptom-image-card-title">

                                    <i class="fa-solid fa-image"></i>

                                    Current Photo

                                </div>


                                <div class="symptom-current-image-wrapper">

                                    @if($symptom->symptom_image)

                                        <img
                                            src="{{ asset('storage/' . $symptom->symptom_image) }}"
                                            alt="Current symptom: {{ $symptom->symptom_name }}"
                                            class="symptom-current-image"
                                        >


                                        <span class="symptom-current-badge">

                                            <i class="fa-solid fa-circle-check"></i>

                                            Current image

                                        </span>

                                    @else

                                        <div class="symptom-no-image">

                                            <i class="fa-regular fa-image"></i>

                                            <span>
                                                No symptom photo uploaded
                                            </span>

                                        </div>

                                    @endif

                                </div>

                            </div>


                            {{-- =================================================
                                 REPLACE IMAGE
                            ================================================== --}}

                            <div class="symptom-replace-image-card">

                                <div class="symptom-image-card-title">

                                    <i class="fa-solid fa-camera"></i>

                                    Replace Photo

                                </div>


                                {{-- Upload box --}}

                                <div
                                    class="symptom-upload-box"
                                    id="symptomUploadBox"
                                >

                                    <div
                                        class="symptom-upload-content"
                                        id="symptomUploadContent"
                                    >

                                        <div class="symptom-upload-icon">

                                            <i class="fa-solid fa-cloud-arrow-up"></i>

                                        </div>


                                        <p class="symptom-upload-title">

                                            Choose a new symptom photograph

                                        </p>


                                        <p class="symptom-upload-subtitle">

                                            Click or drag and drop.

                                            <br>

                                            <strong>
                                                JPG, JPEG, PNG or WEBP
                                            </strong>

                                            · Maximum 2MB

                                        </p>

                                    </div>


                                    <input
                                        type="file"
                                        name="symptom_image"
                                        id="symptom_image"
                                        class="symptom-file-input"
                                        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                    >

                                </div>


                                {{-- New image preview --}}

                                <div
                                    class="symptom-new-preview"
                                    id="symptomNewPreview"
                                >

                                    <img
                                        src=""
                                        alt="New symptom image preview"
                                        class="symptom-preview-image"
                                        id="symptomPreview"
                                    >


                                    <div class="symptom-preview-overlay">

                                        <div class="symptom-preview-label">
                                            New photo
                                        </div>

                                        <div
                                            class="symptom-preview-file"
                                            id="symptomPreviewFile"
                                        >
                                            Selected image
                                        </div>

                                    </div>


                                    <button
                                        type="button"
                                        class="symptom-remove-new-image"
                                        id="removeSymptomImage"
                                        title="Remove selected replacement image"
                                        aria-label="Remove selected replacement image"
                                    >

                                        <i class="fa-solid fa-xmark"></i>

                                    </button>

                                </div>


                                <div class="symptom-image-note">

                                    <i class="fa-solid fa-circle-info"></i>

                                    Leave this empty to keep the current photo.

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         VALIDATION ERRORS
                    ================================================== --}}

                    @if($errors->any())

                        <div
                            class="symptom-edit-error"
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


                    {{-- =================================================
                         UPDATE BUTTON
                    ================================================== --}}

                    <button
                        type="submit"
                        class="symptom-edit-update"
                        id="symptomUpdateButton"
                    >

                        <i
                            class="fa-solid fa-floppy-disk update-icon"
                        ></i>

                        <span class="update-text">
                            Update Symptom
                        </span>

                        <span class="update-spinner"></span>

                    </button>

                </form>


                {{-- =================================================
                     DELETE + CANCEL
                ================================================== --}}

                <form
                    action="{{ route('symptom_delete', $symptom->symptom_id) }}"
                    method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this symptom? This action cannot be undone.');"
                >

                    @csrf

                    @method('DELETE')


                    <div class="symptom-edit-bottom">

                        <button
                            type="submit"
                            class="symptom-edit-delete"
                        >

                            <i class="fa-solid fa-trash"></i>

                            Delete Symptom

                        </button>


                        <a
                            href="{{ route('symptomsview') }}"
                            class="symptom-edit-cancel"
                        >

                            <i class="fa-solid fa-arrow-left"></i>

                            Back to Symptoms

                        </a>

                    </div>

                </form>

            </div>


            <div class="symptom-edit-note">

                🌿 Cardo-Fer · Cardamom Disease Intelligence
                &amp; Fertilizer Recommendation System

            </div>

        </div>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       ELEMENTS
    ========================================================= */

    const form =
        document.getElementById('symptomEditForm');

    const imageInput =
        document.getElementById('symptom_image');

    const uploadBox =
        document.getElementById('symptomUploadBox');

    const previewBox =
        document.getElementById('symptomNewPreview');

    const preview =
        document.getElementById('symptomPreview');

    const previewFile =
        document.getElementById('symptomPreviewFile');

    const removeButton =
        document.getElementById('removeSymptomImage');

    const updateButton =
        document.getElementById('symptomUpdateButton');

    const updateText =
        updateButton
            ? updateButton.querySelector('.update-text')
            : null;


    /* =========================================================
       PREVIEW NEW IMAGE
    ========================================================= */

    function showImagePreview(file) {

        if (!file) {
            return;
        }


        const allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];

        const maxSize =
            2 * 1024 * 1024;


        if (!allowedTypes.includes(file.type)) {

            alert(
                'Please select a JPG, JPEG, PNG or WEBP image.'
            );

            clearNewImage();

            return;
        }


        if (file.size > maxSize) {

            alert(
                'The image must be smaller than 2MB.'
            );

            clearNewImage();

            return;
        }


        const imageUrl =
            URL.createObjectURL(file);


        preview.src =
            imageUrl;


        preview.alt =
            'New symptom image preview';


        previewFile.textContent =
            file.name;


        uploadBox.style.display =
            'none';


        previewBox.classList.add(
            'show'
        );

    }


    /* =========================================================
       CLEAR NEW IMAGE
    ========================================================= */

    function clearNewImage() {

        imageInput.value =
            '';

        preview.src =
            '';

        previewBox.classList.remove(
            'show'
        );

        uploadBox.style.display =
            'flex';

    }


    /* =========================================================
       FILE SELECT
    ========================================================= */

    if (imageInput) {

        imageInput.addEventListener(
            'change',
            function () {

                const file =
                    this.files[0];

                showImagePreview(file);

            }
        );

    }


    /* =========================================================
       REMOVE NEW IMAGE
    ========================================================= */

    if (removeButton) {

        removeButton.addEventListener(
            'click',
            function () {

                clearNewImage();

            }
        );

    }


    /* =========================================================
       DRAG & DROP
    ========================================================= */

    if (uploadBox) {

        [
            'dragenter',
            'dragover'
        ].forEach(function (eventName) {

            uploadBox.addEventListener(
                eventName,
                function (event) {

                    event.preventDefault();

                    uploadBox.classList.add(
                        'dragging'
                    );

                }
            );

        });


        [
            'dragleave',
            'drop'
        ].forEach(function (eventName) {

            uploadBox.addEventListener(
                eventName,
                function (event) {

                    event.preventDefault();

                    uploadBox.classList.remove(
                        'dragging'
                    );

                }
            );

        });


        uploadBox.addEventListener(
            'drop',
            function (event) {

                const files =
                    event.dataTransfer.files;


                if (!files || !files.length) {
                    return;
                }


                const file =
                    files[0];


                try {

                    const dataTransfer =
                        new DataTransfer();

                    dataTransfer.items.add(file);

                    imageInput.files =
                        dataTransfer.files;

                } catch (error) {

                    console.warn(
                        'Could not assign dropped file:',
                        error
                    );

                }


                showImagePreview(file);

            }
        );

    }


    /* =========================================================
       FORM SUBMIT FEEDBACK
    ========================================================= */

    if (form) {

        form.addEventListener(
            'submit',
            function () {

                updateButton.classList.add(
                    'loading'
                );

                updateButton.disabled =
                    true;

                if (updateText) {

                    updateText.textContent =
                        'Updating Symptom...';

                }

            }
        );

    }

});

</script>

@endsection