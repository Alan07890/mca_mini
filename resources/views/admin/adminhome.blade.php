@extends('layouts.AdminMaster')

@section('title', 'Admin Dashboard')

@section('content')

<style>

    /* =========================================================
       CARDO-FER ADMIN DASHBOARD
       ========================================================= */

    .cf-dashboard {
        --cf-bg: #0a1d15;
        --cf-bg-deep: #07150f;
        --cf-card: #10251b;
        --cf-green: #4CAF50;
        --cf-green-light: #81C784;
        --cf-cream: #F1F8E9;
        --cf-sage: #a9c9ae;
        --cf-gold: #FFD54F;
        --cf-gold-deep: #d5893f;
        --cf-line: rgba(241,248,233,0.10);
        --cf-line-strong: rgba(129,199,132,0.18);

        background:
            radial-gradient(
                circle at 92% 5%,
                rgba(76,175,80,0.13),
                transparent 28%
            ),
            radial-gradient(
                circle at 5% 90%,
                rgba(255,213,79,0.05),
                transparent 25%
            ),
            var(--cf-bg);

        color: var(--cf-cream);
        font-family: 'Inter', Arial, sans-serif;
        min-height: calc(100vh - 150px);
        padding: 28px;
        border-radius: 18px;
        overflow: hidden;
    }


    /* =========================================================
       WELCOME
       ========================================================= */

    .cf-welcome {
        position: relative;
        overflow: hidden;

        background:
            linear-gradient(
                135deg,
                rgba(27,94,32,0.88),
                rgba(7,25,16,0.98)
            );

        border:
            1px solid var(--cf-line-strong);

        border-radius: 22px;
        padding: 38px;
        margin-bottom: 28px;

        box-shadow:
            0 25px 50px rgba(0,0,0,0.30);

        opacity: 0;
        transform: translateY(18px);

        animation:
            cfFadeUp .6s ease forwards;
    }

    .cf-welcome::before {
        content: "";

        position: absolute;

        width: 320px;
        height: 320px;

        right: -130px;
        top: -150px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(129,199,132,0.20),
                transparent 68%
            );
    }

    .cf-welcome::after {
        content: "🌿";

        position: absolute;

        right: 70px;
        bottom: 22px;

        font-size: 92px;

        opacity: .08;

        transform: rotate(-15deg);

        pointer-events: none;
    }

    .cf-welcome-content {
        position: relative;
        z-index: 2;
        max-width: 820px;
    }

    .cf-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;

        color: var(--cf-green-light);

        font-size: 11px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;

        margin-bottom: 12px;
    }

    .cf-eyebrow-dot {
        width: 7px;
        height: 7px;

        border-radius: 50%;

        background: var(--cf-gold);

        box-shadow:
            0 0 12px rgba(255,213,79,0.55);

        animation:
            cfPulse 2s ease-in-out infinite;
    }

    .cf-welcome h1 {
        margin: 0;

        font-family:
            'Fraunces',
            Georgia,
            serif;

        font-size:
            clamp(32px, 4vw, 48px);

        line-height: 1.08;
        font-weight: 500;

        color: var(--cf-cream);
    }

    .cf-welcome h1 span {
        color: var(--cf-gold);
    }

    .cf-welcome-message {
        max-width: 700px;

        margin: 16px 0 0;

        color: var(--cf-sage);

        font-size: 15px;
        line-height: 1.7;
    }

    .cf-live-row {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 18px;

        margin-top: 23px;

        color: var(--cf-sage);
        font-size: 13px;
    }

    .cf-live-item {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .cf-live-item i {
        color: var(--cf-gold);
    }

    #cfClock {
        color: var(--cf-cream);
        font-weight: 700;
    }


    /* =========================================================
       SECTION TITLE
       ========================================================= */

    .cf-section-heading {
        display: flex;
        justify-content: space-between;
        align-items: end;

        margin: 25px 0 14px;

        opacity: 0;
        transform: translateY(12px);

        animation:
            cfFadeUp .6s ease .15s forwards;
    }

    .cf-section-heading h2 {
        margin: 0;

        font-family:
            'Fraunces',
            Georgia,
            serif;

        font-size: 21px;
        font-weight: 500;

        color: var(--cf-cream);
    }

    .cf-section-heading p {
        margin: 4px 0 0;

        color: var(--cf-sage);

        font-size: 12px;
    }


    /* =========================================================
       QUICK ACTIONS
       ========================================================= */

    .cf-action-grid {
        display: grid;

        grid-template-columns:
            repeat(4, minmax(0, 1fr));

        gap: 16px;
    }

    .cf-action-card {
        position: relative;

        display: flex;
        flex-direction: column;

        min-height: 180px;

        padding: 22px;

        background:
            linear-gradient(
                180deg,
                rgba(241,248,233,0.045),
                rgba(241,248,233,0.012)
            );

        border:
            1px solid var(--cf-line-strong);

        border-radius: 17px;

        text-decoration: none;
        color: inherit;

        opacity: 0;

        transform:
            translateY(22px);

        transition:
            transform .22s ease,
            border-color .22s ease,
            box-shadow .22s ease,
            background .22s ease;

        cursor: pointer;
    }

    .cf-action-card:nth-child(1) {
        animation:
            cfFadeUp .55s ease .22s forwards;
    }

    .cf-action-card:nth-child(2) {
        animation:
            cfFadeUp .55s ease .30s forwards;
    }

    .cf-action-card:nth-child(3) {
        animation:
            cfFadeUp .55s ease .38s forwards;
    }

    .cf-action-card:nth-child(4) {
        animation:
            cfFadeUp .55s ease .46s forwards;
    }

    .cf-action-card:hover {
        transform:
            translateY(-6px);

        border-color:
            rgba(129,199,132,0.42);

        background:
            linear-gradient(
                180deg,
                rgba(241,248,233,0.065),
                rgba(241,248,233,0.018)
            );

        box-shadow:
            0 20px 40px rgba(0,0,0,0.30);
    }

    .cf-action-icon {
        width: 48px;
        height: 48px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 14px;

        background:
            rgba(76,175,80,0.12);

        border:
            1px solid rgba(76,175,80,0.20);

        color:
            var(--cf-green-light);

        font-size: 20px;

        transition:
            transform .22s ease;
    }

    .cf-action-card:hover .cf-action-icon {
        transform:
            scale(1.08)
            rotate(-3deg);
    }

    .cf-action-card:nth-child(2) .cf-action-icon {
        background:
            rgba(255,213,79,0.10);

        border-color:
            rgba(255,213,79,0.20);

        color:
            var(--cf-gold);
    }

    .cf-action-card:nth-child(3) .cf-action-icon {
        background:
            rgba(129,199,132,0.10);

        color:
            var(--cf-green-light);
    }

    .cf-action-card:nth-child(4) .cf-action-icon {
        background:
            rgba(255,213,79,0.08);

        color:
            var(--cf-gold);
    }

    .cf-action-card h3 {
        margin: 18px 0 6px;

        font-size: 15px;
        font-weight: 700;

        color:
            var(--cf-cream);
    }

    .cf-action-card p {
        margin: 0;

        max-width: 240px;

        color:
            var(--cf-sage);

        font-size: 12.5px;
        line-height: 1.55;
    }

    .cf-action-arrow {
        position: absolute;

        right: 20px;
        bottom: 20px;

        color:
            rgba(241,248,233,0.30);

        transition:
            transform .2s ease,
            color .2s ease;
    }

    .cf-action-card:hover .cf-action-arrow {
        transform:
            translateX(5px);

        color:
            var(--cf-gold);
    }


    /* =========================================================
       ANIMATED CARDAMOM POD
       ========================================================= */

    .cardamom-action-icon {
        position: relative;
        overflow: hidden;
    }

    .cardamom-action-icon::before {
        content: "";

        position: absolute;

        width: 38px;
        height: 38px;

        border-radius: 50%;

        background:
            rgba(129,199,132,0.10);

        filter: blur(7px);

        animation:
            cardamomGlow 3s ease-in-out infinite;
    }

    .cardamom-pod {
        position: relative;

        z-index: 2;

        width: 17px;
        height: 29px;

        border-radius:
            65% 40% 65% 40%;

        background:
            linear-gradient(
                135deg,
                #a8d878 0%,
                #72ac4b 45%,
                #3f7835 100%
            );

        border:
            1px solid rgba(220,245,190,0.30);

        box-shadow:
            0 5px 12px rgba(0,0,0,0.30);

        transform:
            rotate(-28deg);

        animation:
            cardamomFloat 3s ease-in-out infinite;
    }

    .cardamom-pod::before {
        content: "";

        position: absolute;

        width: 5px;
        height: 7px;

        top: -5px;
        right: 1px;

        border-radius:
            60% 30% 60% 30%;

        background:
            #82b95d;
    }

    .cardamom-pod::after {
        content: "";

        position: absolute;

        width: 5px;
        height: 5px;

        bottom: -2px;
        left: 3px;

        border-radius: 50%;

        background:
            #315d2c;
    }

    .pod-line {
        position: absolute;

        left: 3px;

        width: 11px;
        height: 1px;

        border-radius: 50%;

        background:
            rgba(240,255,215,0.40);
    }

    .pod-line-1 {
        top: 7px;
    }

    .pod-line-2 {
        top: 12px;
    }

    .pod-line-3 {
        top: 17px;
    }

    .pod-line-4 {
        top: 22px;
    }

    @keyframes cardamomFloat {

        0%,
        100% {
            transform:
                translateY(0)
                rotate(-28deg);
        }

        50% {
            transform:
                translateY(-5px)
                rotate(-20deg);
        }
    }

    @keyframes cardamomGlow {

        0%,
        100% {
            transform: scale(0.9);
            opacity: 0.45;
        }

        50% {
            transform: scale(1.15);
            opacity: 0.85;
        }
    }


    /* =========================================================
       LOWER GRID
       ========================================================= */

    .cf-lower-grid {
        display: grid;

        grid-template-columns:
            1.35fr 1fr;

        gap: 18px;

        margin-top: 18px;
    }

    .cf-info-card {
        background:
            #10251b;

        border:
            1px solid var(--cf-line-strong);

        border-radius: 18px;

        padding: 25px;

        opacity: 0;

        transform:
            translateY(18px);

        animation:
            cfFadeUp .6s ease .55s forwards;
    }

    .cf-info-title {
        display: flex;
        align-items: center;
        gap: 10px;

        margin-bottom: 20px;

        font-family:
            'Fraunces',
            Georgia,
            serif;

        font-size: 19px;

        color:
            var(--cf-cream);
    }

    .cf-info-title i {
        color:
            var(--cf-gold);

        font-size: 17px;
    }


    /* =========================================================
       SYSTEM MODULES
       ========================================================= */

    .cf-status-list {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .cf-status-item {
        display: flex;

        align-items: center;
        justify-content: space-between;

        gap: 15px;

        padding: 13px 0;

        border-bottom:
            1px solid var(--cf-line);
    }

    .cf-status-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .cf-status-left {
        display: flex;

        align-items: center;

        gap: 12px;
    }

    .cf-status-icon {
        width: 35px;
        height: 35px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 10px;

        background:
            rgba(76,175,80,0.10);

        color:
            var(--cf-green-light);
    }

    .cf-status-name {
        color:
            var(--cf-cream);

        font-size: 13.5px;
        font-weight: 600;
    }

    .cf-status-desc {
        color:
            var(--cf-sage);

        font-size: 11px;

        margin-top: 2px;
    }

    .cf-status-badge {
        display: inline-flex;

        align-items: center;

        gap: 6px;

        padding: 5px 10px;

        border-radius: 999px;

        background:
            rgba(76,175,80,0.10);

        border:
            1px solid rgba(76,175,80,0.22);

        color:
            var(--cf-green-light);

        font-size: 10.5px;
        font-weight: 700;

        white-space: nowrap;
    }

    .cf-status-dot {
        width: 6px;
        height: 6px;

        border-radius: 50%;

        background:
            var(--cf-green);

        animation:
            cfPulse 2s ease-in-out infinite;
    }


    /* =========================================================
       TODAY'S FOCUS
       ========================================================= */

    .cf-tip-box {
        position: relative;

        overflow: hidden;

        min-height: 195px;

        display: flex;
        flex-direction: column;
        justify-content: center;

        background:
            linear-gradient(
                145deg,
                rgba(255,213,79,0.08),
                rgba(76,175,80,0.06)
            );

        border:
            1px solid rgba(255,213,79,0.16);

        border-radius: 16px;

        padding: 24px;
    }

    .cf-tip-icon {
        width: 44px;
        height: 44px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 12px;

        margin-bottom: 16px;

        background:
            rgba(255,213,79,0.10);

        color:
            var(--cf-gold);

        font-size: 18px;
    }

    .cf-tip-box h3 {
        margin:
            0 0 8px;

        color:
            var(--cf-cream);

        font-size: 16px;
    }

    .cf-tip-text {
        min-height: 62px;

        margin: 0;

        color:
            var(--cf-sage);

        font-size: 12.8px;

        line-height: 1.7;

        transition:
            opacity .25s ease,
            transform .25s ease;
    }

    .cf-tip-controls {
        display: flex;

        justify-content: space-between;
        align-items: center;

        margin-top: 15px;
    }

    .cf-tip-number {
        font-size: 10.5px;

        color:
            rgba(169,201,174,0.65);
    }

    .cf-tip-next {
        border: none;

        background:
            rgba(255,213,79,0.10);

        border:
            1px solid rgba(255,213,79,0.18);

        color:
            var(--cf-gold);

        width: 32px;
        height: 32px;

        border-radius: 10px;

        cursor: pointer;

        transition:
            transform .18s ease,
            background .18s ease;
    }

    .cf-tip-next:hover {
        transform:
            translateX(3px);

        background:
            rgba(255,213,79,0.17);
    }


    /* =========================================================
       FOOTER
       ========================================================= */

    .cf-dashboard-footer {
        display: flex;

        justify-content: space-between;
        align-items: center;

        gap: 15px;

        margin-top: 25px;

        padding:
            17px 4px 0;

        border-top:
            1px solid var(--cf-line);

        color:
            rgba(169,201,174,0.65);

        font-size: 11.5px;
    }

    .cf-dashboard-footer strong {
        color:
            var(--cf-green-light);
    }


    /* =========================================================
       ANIMATIONS
       ========================================================= */

    @keyframes cfFadeUp {

        from {
            opacity: 0;

            transform:
                translateY(18px);
        }

        to {
            opacity: 1;

            transform:
                translateY(0);
        }
    }

    @keyframes cfPulse {

        0%,
        100% {
            opacity: .65;

            transform:
                scale(1);
        }

        50% {
            opacity: 1;

            transform:
                scale(1.25);
        }
    }


    /* =========================================================
       RESPONSIVE
       ========================================================= */

    @media (max-width: 1100px) {

        .cf-action-grid {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }

        .cf-lower-grid {
            grid-template-columns: 1fr;
        }
    }


    @media (max-width: 768px) {

        .cf-dashboard {
            padding: 18px;
        }

        .cf-welcome {
            padding: 28px 22px;
        }

        .cf-action-grid {
            grid-template-columns: 1fr;
        }

        .cf-live-row {
            align-items:
                flex-start;

            flex-direction:
                column;

            gap: 9px;
        }

        .cf-dashboard-footer {
            flex-direction:
                column;

            align-items:
                flex-start;
        }
    }

</style>


<div class="cf-dashboard">


    {{-- =====================================================
         WELCOME HERO
    ====================================================== --}}

    <section class="cf-welcome">

        <div class="cf-welcome-content">

            <div class="cf-eyebrow">

                <span class="cf-eyebrow-dot"></span>

                CARDO-FER ADMIN CONTROL CENTER

            </div>


            <h1>

                <span id="cfGreeting">
                    Welcome back
                </span>,

                {{ auth()->user()->name ?? 'Administrator' }}

            </h1>


            <p class="cf-welcome-message">

                Welcome to your Cardo-Fer control center.

                Keep farmer records, field information, disease
                intelligence and fertilizer recommendations
                organized from one place.

            </p>


            <div class="cf-live-row">

                <div class="cf-live-item">

                    <i class="fa-solid fa-calendar-days"></i>

                    <span id="cfCurrentDate">
                        Loading date...
                    </span>

                </div>


                <div class="cf-live-item">

                    <i class="fa-solid fa-clock"></i>

                    <span id="cfClock">
                        --:--:--
                    </span>

                </div>

            </div>

        </div>

    </section>


    {{-- =====================================================
         QUICK ACCESS
    ====================================================== --}}

    <div class="cf-section-heading">

        <div>

            <h2>
                Quick Access
            </h2>

            <p>
                Frequently used administration tools
            </p>

        </div>

    </div>


    <div class="cf-action-grid">


        {{-- Disease Intelligence --}}

        <a
            href="{{ route('symptomsview') }}"
            class="cf-action-card"
        >

            <div class="cf-action-icon cardamom-action-icon">

                <span class="cardamom-pod">

                    <span class="pod-line pod-line-1"></span>
                    <span class="pod-line pod-line-2"></span>
                    <span class="pod-line pod-line-3"></span>
                    <span class="pod-line pod-line-4"></span>

                </span>

            </div>


            <div>

                <h3>
                    Disease Intelligence
                </h3>

                <p>
                    Review symptoms and maintain the disease
                    information used by Cardo-Fer.
                </p>

            </div>


            <i
                class="fa-solid fa-arrow-right cf-action-arrow"
            ></i>

        </a>


        {{-- Fertilizer --}}

        <a
            href="{{ route('fertilizer') }}"
            class="cf-action-card"
        >

            <div class="cf-action-icon">

                <i class="fa-solid fa-flask"></i>

            </div>


            <div>

                <h3>
                    Fertilizer Management
                </h3>

                <p>
                    Maintain fertilizer dosage information
                    and farmer recommendations.
                </p>

            </div>


            <i
                class="fa-solid fa-arrow-right cf-action-arrow"
            ></i>

        </a>


        {{-- Field Management --}}

        <a
            href="{{ route('admin.field-management') }}"
            class="cf-action-card"
        >

            <div class="cf-action-icon">

                <i class="fa-solid fa-tree"></i>

            </div>


            <div>

                <h3>
                    Field Management
                </h3>

                <p>
                    View farmer fields, plant counts,
                    area and plant density.
                </p>

            </div>


            <i
                class="fa-solid fa-arrow-right cf-action-arrow"
            ></i>

        </a>


        {{-- Users --}}

        <a
            href="{{ route('users') }}"
            class="cf-action-card"
        >

            <div class="cf-action-icon">

                <i class="fa-solid fa-users"></i>

            </div>


            <div>

                <h3>
                    User Management
                </h3>

                <p>
                    View and manage registered farmers
                    and administrators.
                </p>

            </div>


            <i
                class="fa-solid fa-arrow-right cf-action-arrow"
            ></i>

        </a>

    </div>


    {{-- =====================================================
         LOWER CONTENT
    ====================================================== --}}

    <div class="cf-lower-grid">


        {{-- System overview --}}

        <div class="cf-info-card">

            <div class="cf-info-title">

                <i class="fa-solid fa-layer-group"></i>

                Cardo-Fer Modules

            </div>


            <div class="cf-status-list">


                <div class="cf-status-item">

                    <div class="cf-status-left">

                        <div class="cf-status-icon">

                            <i class="fa-solid fa-users"></i>

                        </div>


                        <div>

                            <div class="cf-status-name">
                                Farmer Management
                            </div>

                            <div class="cf-status-desc">
                                Farmer registration and records
                            </div>

                        </div>

                    </div>


                    <span class="cf-status-badge">

                        <span class="cf-status-dot"></span>

                        Ready

                    </span>

                </div>


                <div class="cf-status-item">

                    <div class="cf-status-left">

                        <div class="cf-status-icon">

                            <i class="fa-solid fa-virus"></i>

                        </div>


                        <div>

                            <div class="cf-status-name">
                                Disease Intelligence
                            </div>

                            <div class="cf-status-desc">
                                Disease and symptom information
                            </div>

                        </div>

                    </div>


                    <span class="cf-status-badge">

                        <span class="cf-status-dot"></span>

                        Ready

                    </span>

                </div>


                <div class="cf-status-item">

                    <div class="cf-status-left">

                        <div class="cf-status-icon">

                            <i class="fa-solid fa-flask"></i>

                        </div>


                        <div>

                            <div class="cf-status-name">
                                Fertilizer Management
                            </div>

                            <div class="cf-status-desc">
                                Dosage and fertilizer records
                            </div>

                        </div>

                    </div>


                    <span class="cf-status-badge">

                        <span class="cf-status-dot"></span>

                        Ready

                    </span>

                </div>


                <div class="cf-status-item">

                    <div class="cf-status-left">

                        <div class="cf-status-icon">

                            <i class="fa-solid fa-tree"></i>

                        </div>


                        <div>

                            <div class="cf-status-name">
                                Field Management
                            </div>

                            <div class="cf-status-desc">
                                Area, plants and density
                            </div>

                        </div>

                    </div>


                    <span class="cf-status-badge">

                        <span class="cf-status-dot"></span>

                        Ready

                    </span>

                </div>

            </div>

        </div>


        {{-- Today's focus --}}

        <div class="cf-info-card">

            <div class="cf-info-title">

                <i class="fa-solid fa-lightbulb"></i>

                Today's Focus

            </div>


            <div class="cf-tip-box">

                <div class="cf-tip-icon">

                    <i
                        id="cfTipIcon"
                        class="fa-solid fa-seedling"
                    ></i>

                </div>


                <h3 id="cfTipTitle">

                    Keep your farming data accurate

                </h3>


                <p
                    id="cfTipText"
                    class="cf-tip-text"
                >

                    Accurate fertilizer, disease and field
                    information helps Cardo-Fer provide better
                    farming guidance.

                </p>


                <div class="cf-tip-controls">

                    <span
                        id="cfTipNumber"
                        class="cf-tip-number"
                    >
                        Tip 1 of 5
                    </span>


                    <button
                        type="button"
                        id="cfNextTip"
                        class="cf-tip-next"
                        title="Next tip"
                    >

                        <i class="fa-solid fa-arrow-right"></i>

                    </button>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         FOOTER
    ====================================================== --}}

    <div class="cf-dashboard-footer">

        <span>

            <strong>
                Cardo-Fer
            </strong>

            · Smart farming management system

        </span>


        <span>

            Western Ghats · Idukki · Wayanad

        </span>

    </div>


</div>


<script>

document.addEventListener('DOMContentLoaded', function () {


    /* =====================================================
       GREETING
    ===================================================== */

    const greeting =
        document.getElementById('cfGreeting');


    const hour =
        new Date().getHours();


    if (hour < 12) {

        greeting.textContent =
            'Good morning';

    }

    else if (hour < 17) {

        greeting.textContent =
            'Good afternoon';

    }

    else {

        greeting.textContent =
            'Good evening';

    }


    /* =====================================================
       DATE
    ===================================================== */

    const dateElement =
        document.getElementById('cfCurrentDate');


    function updateDate() {

        const now =
            new Date();


        dateElement.textContent =
            now.toLocaleDateString(
                'en-IN',
                {
                    weekday: 'long',
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                }
            );

    }


    updateDate();


    /* =====================================================
       LIVE CLOCK
    ===================================================== */

    const clock =
        document.getElementById('cfClock');


    function updateClock() {

        const now =
            new Date();


        clock.textContent =
            now.toLocaleTimeString(
                'en-IN',
                {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true
                }
            );

    }


    updateClock();


    setInterval(
        updateClock,
        1000
    );


    /* =====================================================
       ADMIN TIPS
    ===================================================== */

    const tips = [

        {
            title:
                'Keep your farming data accurate',

            text:
                'Accurate fertilizer, disease and field information helps Cardo-Fer provide better farming guidance.',

            icon:
                'fa-seedling'
        },


        {
            title:
                'Review fertilizer dosages',

            text:
                'Make sure fertilizer dosage values and application references are correct before farmers use them for planning.',

            icon:
                'fa-flask'
        },


        {
            title:
                'Keep disease information updated',

            text:
                'Well-maintained disease and symptom records help keep Cardo-Fer recommendations consistent.',

            icon:
                'fa-virus'
        },


        {
            title:
                'Check farmer field records',

            text:
                'Review field area and plant counts regularly so field-based calculations remain reliable.',

            icon:
                'fa-tree'
        },


        {
            title:
                'Maintain a clean admin system',

            text:
                'Keep outdated records and duplicate information under control so the platform remains easy to manage.',

            icon:
                'fa-shield-halved'
        }

    ];


    let currentTip =
        0;


    const tipTitle =
        document.getElementById('cfTipTitle');


    const tipText =
        document.getElementById('cfTipText');


    const tipNumber =
        document.getElementById('cfTipNumber');


    const tipIcon =
        document.getElementById('cfTipIcon');


    const nextTip =
        document.getElementById('cfNextTip');


    function showTip(index) {

        const tip =
            tips[index];


        tipText.style.opacity =
            '0';


        tipText.style.transform =
            'translateY(5px)';


        setTimeout(function () {

            tipTitle.textContent =
                tip.title;


            tipText.textContent =
                tip.text;


            tipIcon.className =
                'fa-solid ' + tip.icon;


            tipNumber.textContent =
                'Tip ' +
                (index + 1) +
                ' of ' +
                tips.length;


            tipText.style.opacity =
                '1';


            tipText.style.transform =
                'translateY(0)';

        }, 180);

    }


    nextTip.addEventListener(
        'click',
        function () {

            currentTip =
                (currentTip + 1) %
                tips.length;


            showTip(currentTip);

        }
    );


    /* =====================================================
       AUTOMATIC TIP ROTATION
    ===================================================== */

    setInterval(function () {

        currentTip =
            (currentTip + 1) %
            tips.length;


        showTip(currentTip);

    }, 10000);


    /* =====================================================
       QUICK ACCESS CLICK FEEDBACK
    ===================================================== */

    document
        .querySelectorAll('.cf-action-card')
        .forEach(function (card) {

            card.addEventListener(
                'click',
                function () {

                    card.style.transform =
                        'scale(.985)';


                    setTimeout(
                        function () {

                            card.style.transform =
                                '';

                        },
                        120
                    );

                }
            );

        });


});

</script>

@endsection