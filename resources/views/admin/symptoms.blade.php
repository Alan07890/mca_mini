@extends('layouts.AdminMaster')

@section('title', 'Symptom Management')

@section('content')

<style>

    /* =========================================================
       CARDO-FER — SYMPTOM MANAGEMENT
       Full Width Dark Green / Gold Theme
    ========================================================= */

    .symptom-page {
        width: 100%;
        min-height: calc(100vh - 150px);
        padding: 8px 18px 30px;
        color: #F1F8E9;
    }

    /* =========================================================
       MAIN CARD
    ========================================================= */

    .symptom-card {
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
            symptomFade .45s ease both;
    }

    /* =========================================================
       HEADER
    ========================================================= */

    .symptom-header {
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

    .symptom-header::before {
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

    .symptom-header::after {
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

    .symptom-badge {
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

    .symptom-badge i {
        color: #FFD54F;
    }

    .symptom-header h2 {
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

    .symptom-header p {
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

    .symptom-body {
        padding: 38px 42px 32px;
    }

    .symptom-section-title {
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

    .symptom-section-title i {
        color: #FFD54F;
        font-size: 16px;
    }

    .symptom-section-subtitle {
        margin: 0 0 24px;

        color: #a9c9ae;

        font-size: 13px;
    }

    /* =========================================================
       FORM AREA
    ========================================================= */

    .symptom-form-area {
        width: 100%;
    }

    /* =========================================================
       LABEL
    ========================================================= */

    .symptom-label {
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

    .symptom-input {
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

    .symptom-input:focus-within {
        border-color: #FFD54F;

        box-shadow:
            0 0 0 3px
            rgba(255, 213, 79, 0.11);
    }

    .symptom-input .icon-box {
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

    .symptom-input input {
        width: 100%;

        border: none;
        outline: none;

        background: transparent;

        padding: 14px 16px;

        color: #F1F8E9;

        font-size: 13.5px;
    }

    .symptom-input input::placeholder {
        color:
            rgba(241, 248, 233, 0.35);
    }

    /* =========================================================
       IMAGE UPLOAD AREA
    ========================================================= */

    .symptom-image-upload {
        margin-top: 22px;
    }

    .symptom-upload-box {
        position: relative;

        width: 100%;

        min-height: 250px;

        display: flex;
        align-items: center;
        justify-content: center;

        padding: 20px;

        border:
            1px dashed rgba(129, 199, 132, 0.30);

        border-radius: 16px;

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
        width: 58px;
        height: 58px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 17px;

        margin-bottom: 14px;

        background:
            rgba(76, 175, 80, 0.11);

        border:
            1px solid rgba(129, 199, 132, 0.20);

        color:
            #81C784;

        font-size: 23px;
    }

    .symptom-upload-title {
        margin: 0;

        color:
            #F1F8E9;

        font-size: 14px;
        font-weight: 700;
    }

    .symptom-upload-subtitle {
        margin: 7px 0 0;

        color:
            rgba(169, 201, 174, 0.65);

        font-size: 11.5px;

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
       IMAGE PREVIEW
    ========================================================= */

    .symptom-image-preview {
        display: none;

        position: relative;

        width: 100%;

        min-height: 250px;

        border-radius: 16px;

        overflow: hidden;

        background:
            #07150f;

        border:
            1px solid rgba(129, 199, 132, 0.20);
    }

    .symptom-image-preview.show {
        display: block;

        animation:
            imagePreviewIn .35s ease both;
    }

    .symptom-preview-image {
        display: block;

        width: 100%;
        height: 280px;

        object-fit: cover;
    }

    .symptom-preview-overlay {
        position: absolute;

        left: 0;
        right: 0;
        bottom: 0;

        padding:
            45px 18px 17px;

        background:
            linear-gradient(
                transparent,
                rgba(0, 0, 0, 0.75)
            );

        pointer-events: none;
    }

    .symptom-preview-name {
        color:
            #F1F8E9;

        font-size: 12px;
        font-weight: 600;
    }

    .symptom-remove-image {
        position: absolute;

        top: 12px;
        right: 12px;

        width: 34px;
        height: 34px;

        display: flex;
        align-items: center;
        justify-content: center;

        border: none;

        border-radius: 10px;

        background:
            rgba(7, 21, 15, 0.80);

        border:
            1px solid rgba(255,255,255,0.15);

        color:
            #F1F8E9;

        cursor: pointer;

        transition:
            transform .18s ease,
            background .18s ease,
            color .18s ease;

        z-index: 3;
    }

    .symptom-remove-image:hover {
        transform:
            scale(1.06);

        background:
            rgba(226, 131, 107, 0.85);

        color:
            #ffffff;
    }

    .symptom-image-info {
        display: flex;

        align-items: center;
        justify-content: space-between;

        gap: 10px;

        margin-top: 10px;
    }

    .symptom-image-info-text {
        color:
            rgba(169, 201, 174, 0.60);

        font-size: 10.5px;
    }

    .symptom-image-info-valid {
        color:
            #81C784;

        font-size: 10.5px;
        font-weight: 600;
    }

    /* =========================================================
       ALERTS
    ========================================================= */

    .symptom-success {
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

    .symptom-success i {
        color: #81C784;
        margin-top: 2px;
    }

    .symptom-error {
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

    .symptom-error ul {
        margin: 0;
        padding-left: 18px;
    }

    /* =========================================================
       ACTIONS
    ========================================================= */

    .symptom-actions {
        display: flex;

        width: 100%;

        gap: 12px;

        flex-wrap: wrap;

        margin-top: 24px;
    }

    .btn-save-symptom,
    .btn-view-symptoms {
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

    /* =========================================================
       SAVE BUTTON
    ========================================================= */

    .btn-save-symptom {
        border: none;

        background:
            linear-gradient(
                180deg,
                #FFD54F,
                #d5893f
            );

        color: #241708;

        cursor: pointer;
    }

    .btn-save-symptom:hover {
        transform:
            translateY(-2px);

        box-shadow:
            0 14px 28px -14px
            rgba(255, 213, 79, 0.60);

        color: #241708;
    }

    .btn-save-symptom.loading {
        pointer-events: none;

        opacity: .78;
    }

    .btn-save-symptom .save-spinner {
        display: none;

        width: 15px;
        height: 15px;

        border:
            2px solid rgba(36,23,8,.30);

        border-top-color:
            #241708;

        border-radius: 50%;

        animation:
            saveSpin .65s linear infinite;
    }

    .btn-save-symptom.loading .save-spinner {
        display: inline-block;
    }

    .btn-save-symptom.loading .save-icon {
        display: none;
    }

    /* =========================================================
       VIEW BUTTON
    ========================================================= */

    .btn-view-symptoms {
        border:
            1px solid rgba(129, 199, 132, 0.30);

        background:
            transparent;

        color: #a9c9ae;

        text-decoration: none;
    }

    .btn-view-symptoms:hover {
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
       FOOTNOTE
    ========================================================= */

    .symptom-note {
        margin-top: 22px;

        padding-top: 16px;

        border-top:
            1px solid rgba(241, 248, 233, 0.08);

        text-align:
            center;

        color:
            rgba(169, 201, 174, 0.60);

        font-size: 11px;
    }

    /* =========================================================
       ANIMATIONS
    ========================================================= */

    @keyframes symptomFade {

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

    @keyframes saveSpin {

        to {
            transform:
                rotate(360deg);
        }
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 768px) {

        .symptom-page {
            padding:
                5px 10px 24px;
        }

        .symptom-header,
        .symptom-body {
            padding-left: 25px;
            padding-right: 25px;
        }

        .symptom-header h2 {
            font-size: 26px;
        }

        .symptom-preview-image {
            height: 230px;
        }
    }

    @media (max-width: 576px) {

        .symptom-page {
            padding:
                5px 6px 20px;
        }

        .symptom-header,
        .symptom-body {
            padding:
                24px 20px;
        }

        .symptom-header h2 {
            font-size: 24px;
        }

        .btn-save-symptom,
        .btn-view-symptoms {
            flex-basis: 100%;
        }

        .symptom-upload-box,
        .symptom-image-preview {
            min-height: 210px;
        }

        .symptom-preview-image {
            height: 205px;
        }
    }

</style>


<div class="symptom-page">

    <div class="symptom-card">

        {{-- =================================================
             HEADER
        ================================================== --}}

        <div class="symptom-header">

            <span class="symptom-badge">

                <i class="fa-solid fa-leaf"></i>

                Cardo-Fer · Disease Intelligence

            </span>


            <h2>
                Disease Symptom Management
            </h2>


            <p>
                Create and maintain the cardamom disease symptoms
                used by the Cardo-Fer disease prediction system.
                Add a clear symptom photograph so farmers can
                identify symptoms visually.
            </p>

        </div>


        {{-- =================================================
             BODY
        ================================================== --}}

        <div class="symptom-body">

            <div class="symptom-section-title">

                <i class="fa-solid fa-plus-circle"></i>

                Add Disease Symptom

            </div>


            <p class="symptom-section-subtitle">

                Enter the symptom name and upload a clear photograph
                of the symptom for the farmer disease identification page.

            </p>


            <div class="symptom-form-area">

                <form
                    action="{{ route('symptom_insert') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    id="symptomForm"
                >

                    @csrf


                    {{-- =================================================
                         SYMPTOM NAME
                    ================================================== --}}

                    <label
                        class="symptom-label"
                        for="symptom_name"
                    >
                        Symptom Name
                    </label>


                    <div class="symptom-input">

                        <span class="icon-box">

                            <i class="fa-solid fa-stethoscope"></i>

                        </span>


                        <input
                            type="text"
                            id="symptom_name"
                            name="symptom_name"
                            placeholder="e.g. Yellowing of leaves"
                            value="{{ old('symptom_name') }}"
                            autocomplete="off"
                            required
                        >

                    </div>


                    {{-- =================================================
                         SYMPTOM IMAGE
                    ================================================== --}}

                    <div class="symptom-image-upload">

                        <label
                            class="symptom-label"
                            for="symptom_image"
                        >
                            Symptom Photo
                        </label>


                        {{-- Upload area --}}

                        <div
                            class="symptom-upload-box"
                            id="symptomUploadBox"
                        >

                            <div
                                class="symptom-upload-content"
                                id="symptomUploadContent"
                            >

                                <div class="symptom-upload-icon">

                                    <i class="fa-solid fa-camera"></i>

                                </div>


                                <p class="symptom-upload-title">

                                    Upload Symptom Photograph

                                </p>


                                <p class="symptom-upload-subtitle">

                                    Click here or drag and drop an image.

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
                                required
                            >

                        </div>


                        {{-- Image preview --}}

                        <div
                            class="symptom-image-preview"
                            id="symptomImagePreview"
                        >

                            <img
                                src=""
                                alt="Symptom image preview"
                                class="symptom-preview-image"
                                id="symptomPreview"
                            >


                            <div class="symptom-preview-overlay">

                                <div
                                    class="symptom-preview-name"
                                    id="symptomPreviewName"
                                >
                                    Selected symptom image
                                </div>

                            </div>


                            <button
                                type="button"
                                class="symptom-remove-image"
                                id="removeSymptomImage"
                                aria-label="Remove selected image"
                                title="Remove image"
                            >

                                <i class="fa-solid fa-xmark"></i>

                            </button>

                        </div>


                        <div
                            class="symptom-image-info"
                            id="symptomImageInfo"
                            style="display:none;"
                        >

                            <span
                                class="symptom-image-info-text"
                                id="symptomImageFileName"
                            ></span>


                            <span class="symptom-image-info-valid">

                                <i class="fa-solid fa-circle-check"></i>

                                Ready to upload

                            </span>

                        </div>

                    </div>


                    {{-- =================================================
                         VALIDATION ERRORS
                    ================================================== --}}

                    @if($errors->any())

                        <div
                            class="symptom-error"
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
                         SUCCESS MESSAGE
                    ================================================== --}}

                    @if(session('success'))

                        <div
                            class="symptom-success"
                            role="alert"
                        >

                            <i class="fa-solid fa-circle-check"></i>

                            <span>
                                {{ session('success') }}
                            </span>

                        </div>

                    @endif


                    {{-- =================================================
                         BUTTONS
                    ================================================== --}}

                    <div class="symptom-actions">

                        <button
                            type="submit"
                            class="btn-save-symptom"
                            id="saveSymptomButton"
                        >

                            <i
                                class="fa-solid fa-floppy-disk save-icon"
                            ></i>

                            <span class="save-text">
                                Save Symptom
                            </span>

                            <span class="save-spinner"></span>

                        </button>


                        <a
                            href="{{ route('symptomsview') }}"
                            class="btn-view-symptoms"
                        >

                            <i class="fa-solid fa-list"></i>

                            View Symptoms

                        </a>

                    </div>

                </form>

            </div>


            <div class="symptom-note">

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
        document.getElementById('symptomForm');

    const imageInput =
        document.getElementById('symptom_image');

    const uploadBox =
        document.getElementById('symptomUploadBox');

    const uploadContent =
        document.getElementById('symptomUploadContent');

    const previewBox =
        document.getElementById('symptomImagePreview');

    const preview =
        document.getElementById('symptomPreview');

    const previewName =
        document.getElementById('symptomPreviewName');

    const removeButton =
        document.getElementById('removeSymptomImage');

    const imageInfo =
        document.getElementById('symptomImageInfo');

    const imageFileName =
        document.getElementById('symptomImageFileName');

    const symptomName =
        document.getElementById('symptom_name');

    const saveButton =
        document.getElementById('saveSymptomButton');

    const saveText =
        saveButton
            ? saveButton.querySelector('.save-text')
            : null;


    /* =========================================================
       IMAGE PREVIEW
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


        /* Validate file type */

        if (!allowedTypes.includes(file.type)) {

            alert(
                'Please select a JPG, JPEG, PNG or WEBP image.'
            );

            clearImage();

            return;
        }


        /* Validate file size */

        if (file.size > maxSize) {

            alert(
                'The image must be smaller than 2MB.'
            );

            clearImage();

            return;
        }


        const imageUrl =
            URL.createObjectURL(file);


        preview.src =
            imageUrl;


        const enteredName =
            symptomName.value.trim();


        previewName.textContent =
            enteredName
                ? enteredName
                : file.name;


        imageFileName.textContent =
            file.name;


        uploadBox.style.display =
            'none';


        previewBox.classList.add(
            'show'
        );


        imageInfo.style.display =
            'flex';


        /* Update preview name when symptom name changes */

        if (enteredName) {
            previewName.textContent =
                enteredName;
        }

    }


    /* =========================================================
       CLEAR IMAGE
    ========================================================= */

    function clearImage() {

        imageInput.value = '';

        preview.src = '';

        previewBox.classList.remove(
            'show'
        );

        imageInfo.style.display =
            'none';

        imageFileName.textContent =
            '';

        uploadBox.style.display =
            'flex';

    }


    /* =========================================================
       FILE CHANGE
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
       REMOVE IMAGE
    ========================================================= */

    if (removeButton) {

        removeButton.addEventListener(
            'click',
            function (event) {

                event.preventDefault();

                clearImage();

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


                /*
                 * Assign dropped file to the file input.
                 * DataTransfer is supported by modern browsers.
                 */

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
       UPDATE PREVIEW TITLE
    ========================================================= */

    if (symptomName) {

        symptomName.addEventListener(
            'input',
            function () {

                if (!previewBox.classList.contains('show')) {
                    return;
                }

                const file =
                    imageInput.files[0];

                previewName.textContent =
                    this.value.trim()
                        ? this.value.trim()
                        : (file ? file.name : 'Selected symptom image');

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

                if (!imageInput.files.length) {
                    return;
                }

                saveButton.classList.add(
                    'loading'
                );

                saveButton.disabled =
                    true;

                if (saveText) {

                    saveText.textContent =
                        'Saving Symptom...';

                }

            }
        );

    }

});

</script>

@endsection