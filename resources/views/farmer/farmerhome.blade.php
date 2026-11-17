@extends('layouts.FarmerMaster')

@section('content')

<!-- =========================================================
     MOBILE MENU
========================================================= -->

<div class="mobile-menu">

    <a href="{{ route('farmerhome') }}">
        <i class="fa-solid fa-house"></i>
        Home
    </a>

    <a href="{{ route('farmer.field') }}">
        <i class="fa-solid fa-seedling"></i>
        Add Field Details
    </a>

    <a href="{{ route('disease.identification') }}">
        <i class="fa-solid fa-stethoscope"></i>
        Disease Identification
    </a>

    <a href="{{ route('farmer.fertilizer') }}">
        <i class="fa-solid fa-leaf"></i>
        Fertilizer Management
    </a>

    <a href="{{ route('farmer.fertilizer') }}">
        <i class="fa-solid fa-bell"></i>
        Fertilizer Reminder
    </a>

    <a href="#disease-history">
        <i class="fa-solid fa-clock-rotate-left"></i>
        Disease History
    </a>

    <a href="#farm-reports">
        <i class="fa-solid fa-chart-column"></i>
        Farm Reports
    </a>

    <a href="weather.html">
        <i class="fa-solid fa-cloud-sun"></i>
        Weather Prediction
    </a>

    <a
        href="{{ route('disease.identification') }}"
        class="btn btn-primary"
    >
        <i class="fa-solid fa-magnifying-glass"></i>
        Identify Disease
    </a>

</div>


<!-- =========================================================
     HERO
========================================================= -->

<section class="hero">

    <div
        class="hero-floats"
        aria-hidden="true"
    >

        <svg
            class="float-pod"
            style="top:18%; left:8%; animation-delay:0s;"
            viewBox="0 0 60 60"
            fill="none"
        >
            <path
                d="M30 4C42 4 52 16 52 30C52 44 42 56 30 56C18 56 8 44 8 30C8 16 18 4 30 4Z"
                stroke="#B8CDB0"
                stroke-width="2"
            />
        </svg>


        <svg
            class="float-pod"
            style="top:64%; left:14%; animation-delay:2s; width:30px;"
            viewBox="0 0 60 60"
            fill="none"
        >
            <path
                d="M30 4C42 4 52 16 52 30C52 44 42 56 30 56C18 56 8 44 8 30C8 16 18 4 30 4Z"
                stroke="#C87F3A"
                stroke-width="2"
            />
        </svg>


        <svg
            class="float-pod"
            style="top:30%; right:10%; animation-delay:1s;"
            viewBox="0 0 60 60"
            fill="none"
        >
            <path
                d="M30 4C42 4 52 16 52 30C52 44 42 56 30 56C18 56 8 44 8 30C8 16 18 4 30 4Z"
                stroke="#B8CDB0"
                stroke-width="2"
            />
        </svg>


        <svg
            class="float-pod"
            style="bottom:14%; right:18%; animation-delay:3s; width:34px;"
            viewBox="0 0 60 60"
            fill="none"
        >
            <path
                d="M30 4C42 4 52 16 52 30C52 44 42 56 30 56C18 56 8 44 8 30C8 16 18 4 30 4Z"
                stroke="#B8CDB0"
                stroke-width="2"
            />
        </svg>

    </div>


    <div class="container">

        <div class="hero-content">

            <span class="eyebrow">
                Western Ghats · Idukki · Wayanad
            </span>


            <h1 data-i18n="heroTitle">
                Grow the world's finest
                <em>cardamom</em>, smarter.
            </h1>


            <p data-i18n="heroSubtitle">
                Cardo-Fer brings AI diagnostics, live market prices
                and weather intelligence to every cardamom farmer
                in the Western Ghats.
            </p>


            <div class="hero-actions">

                <!-- Disease Identification -->

                <a
                    href="{{ route('disease.identification') }}"
                    class="btn btn-spice"
                >
                    <i class="fa-solid fa-stethoscope"></i>
                    Identify Disease
                </a>


                <!-- Field Management -->

                <a
                    href="{{ route('farmer.field') }}"
                    class="btn btn-outline"
                    style="color:#fff; border-color:rgba(255,255,255,0.3);"
                >
                    <i class="fa-solid fa-seedling"></i>
                    Manage My Field
                </a>

                <a
                    href="{{ route('farmer.fertilizer') }}"
                    class="btn btn-outline"
                    style="color:#fff; border-color:rgba(255,255,255,0.3);"
                >
                    <i class="fa-solid fa-leaf"></i>
                    Fertilizer Management
                </a>

            </div>

        </div>

    </div>


    <div class="scroll-cue">
        <span></span>
        Scroll
    </div>

</section>


<!-- =========================================================
     FEATURES
========================================================= -->

<section id="features">

    <div class="container">

        <div class="section-head reveal">

            <span class="eyebrow">
                What Cardo-Fer Does
            </span>

            <h2>
                One platform, every stage of the harvest
            </h2>

            <p>
                From the nursery to the auction floor,
                Cardo-Fer keeps your crop healthy and your
                income predictable.
            </p>

        </div>


        <div class="features-grid stagger">


            <!-- =================================================
                 DISEASE DIAGNOSIS
            ================================================== -->

            <div
                class="feature-card reveal"
                style="--i:0"
            >

                <div class="feature-icon">
                    🩺
                </div>


                <h3>
                    AI Disease Diagnosis
                </h3>


                <p>
                    Select the symptoms you can see on your
                    cardamom plants from real symptom photographs
                    and get the closest disease match from the
                    Cardo-Fer disease database.
                </p>


                <a
                    href="{{ route('disease.identification') }}"
                    class="btn btn-primary"
                    style="margin-top:16px;"
                >

                    Identify Disease

                    <i class="fa-solid fa-arrow-right"></i>

                </a>

            </div>


            <!-- =================================================
                 WEATHER
            ================================================== -->

            <div
                class="feature-card reveal"
                style="--i:1"
            >

                <div class="feature-icon">
                    🌦️
                </div>

                <h3>
                    Hyperlocal Weather Alerts
                </h3>

                <p>
                    Rainfall, humidity and frost warnings tuned
                    to your estate's exact elevation — not the
                    nearest town.
                </p>

            </div>


            <!-- =================================================
                 MARKETPLACE
            ================================================== -->

            <div
                class="feature-card reveal"
                style="--i:2"
            >

                <div class="feature-icon">
                    🛒
                </div>

                <h3>
                    Direct Marketplace
                </h3>

                <p>
                    Sell cured cardamom straight to verified
                    buyers and exporters, skipping unnecessary
                    middle layers.
                </p>

            </div>


            <!-- =================================================
                 GRANTS
            ================================================== -->

            <div
                class="feature-card reveal"
                style="--i:3"
            >

                <div class="feature-icon">
                    📋
                </div>

                <h3>
                    Grant Navigator
                </h3>

                <p>
                    Discover subsidies and government schemes
                    you actually qualify for, with guided
                    applications.
                </p>

            </div>


            <!-- =================================================
                 CONSULTATION
            ================================================== -->

            <div
                class="feature-card reveal"
                style="--i:4"
            >

                <div class="feature-icon">
                    👨‍🌾
                </div>

                <h3>
                    Expert Consultation
                </h3>

                <p>
                    Book video or on-site visits with certified
                    spice-board agronomists when the AI needs
                    a second opinion.
                </p>

            </div>


            <!-- =================================================
                 COMMUNITY
            ================================================== -->

            <div
                class="feature-card reveal"
                style="--i:5"
            >

                <div class="feature-icon">
                    💬
                </div>

                <h3>
                    Farmer Community
                </h3>

                <p>
                    Trade tips, prices and warnings with growers
                    across every estate in the region, in your
                    own language.
                </p>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     FARMER TOOLS
========================================================= -->

<section id="farmer-tools">

    <div class="container">

        <div class="section-head reveal">

            <span class="eyebrow">
                Farmer Tools
            </span>

            <h2>
                Everything you need to manage your plantation
            </h2>

            <p>
                Manage fertilizer applications, reminders, disease
                records, farm reports and weather prediction from
                one simple platform.
            </p>

        </div>

        <div class="features-grid stagger">

            <!-- 1. FERTILIZER MANAGEMENT -->

            <div class="feature-card reveal" style="--i:0">

                <div class="feature-icon">🌿</div>

                <h3>Fertilizer Management</h3>

                <p>
                    Record fertilizers used in your plantation,
                    including quantity, unit, application date
                    and notes.
                </p>

                <a
                    href="{{ route('farmer.fertilizer') }}"
                    class="btn btn-primary"
                    style="margin-top:16px;"
                >
                    Manage Fertilizer
                    <i class="fa-solid fa-arrow-right"></i>
                </a>

            </div>


            <!-- 2. FERTILIZER REMINDER -->

            <div class="feature-card reveal" style="--i:1">

                <div class="feature-icon">🔔</div>

                <h3>Fertilizer Reminder</h3>

                <p>
                    Keep track of your next fertilizer application
                    date and manage upcoming fertilizer reminders.
                </p>

                <a
                    href="{{ route('farmer.fertilizer') }}"
                    class="btn btn-primary"
                    style="margin-top:16px;"
                >
                    View Reminders
                    <i class="fa-solid fa-arrow-right"></i>
                </a>

            </div>


            <!-- 3. DISEASE HISTORY -->

            <div
                id="disease-history"
                class="feature-card reveal"
                style="--i:2"
            >

                <div class="feature-icon">🩺</div>

                <h3>Disease History</h3>

                <p>
                    Review previous disease identification results,
                    selected symptoms and analysis dates.
                </p>

                <a
                    href="#disease-history"
                    class="btn btn-primary"
                    style="margin-top:16px;"
                >
                    View History
                    <i class="fa-solid fa-arrow-right"></i>
                </a>

            </div>


            <!-- 4. FARM REPORTS -->

            <div
                id="farm-reports"
                class="feature-card reveal"
                style="--i:3"
            >

                <div class="feature-icon">📊</div>

                <h3>Farm Reports</h3>

                <p>
                    Get an organized overview of field details,
                    fertilizer applications and disease records.
                </p>

                <a
                    href="#farm-reports"
                    class="btn btn-primary"
                    style="margin-top:16px;"
                >
                    View Reports
                    <i class="fa-solid fa-arrow-right"></i>
                </a>

            </div>


            <!-- 5. WEATHER PREDICTION -->

            <div class="feature-card reveal" style="--i:4">

                <div class="feature-icon">🌦️</div>

                <h3>Weather Prediction</h3>

                <p>
                    Check temperature, rainfall, humidity and other
                    weather conditions to help plan plantation
                    activities.
                </p>

                <a
                    href="weather.html"
                    class="btn btn-primary"
                    style="margin-top:16px;"
                >
                    Check Weather
                    <i class="fa-solid fa-arrow-right"></i>
                </a>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     STATS
========================================================= -->

<section class="stats-section">

    <div class="container">

        <div class="stats-grid">

            <div
                class="reveal"
                style="--i:0"
            >

                <div class="stat-num">
                    <span
                        data-counter="18400"
                        data-suffix="+"
                    >
                        0
                    </span>
                </div>

                <div class="stat-label">
                    Farmers onboarded
                </div>

            </div>


            <div
                class="reveal"
                style="--i:1"
            >

                <div class="stat-num">
                    <span
                        data-counter="96"
                        data-suffix="%"
                    >
                        0
                    </span>
                </div>

                <div class="stat-label">
                    Diagnosis accuracy
                </div>

            </div>


            <div
                class="reveal"
                style="--i:2"
            >

                <div class="stat-num">
                    ₹<span
                        data-counter="42"
                        data-suffix="Cr"
                    >
                        0
                    </span>
                </div>

                <div class="stat-label">
                    Produce sold on marketplace
                </div>

            </div>


            <div
                class="reveal"
                style="--i:3"
            >

                <div class="stat-num">
                    <span
                        data-counter="312"
                        data-suffix="+"
                    >
                        0
                    </span>
                </div>

                <div class="stat-label">
                    Grants successfully claimed
                </div>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     SUCCESS STORIES
========================================================= -->

<section id="stories">

    <div class="container">

        <div class="section-head reveal">

            <span class="eyebrow">
                Grower Stories
            </span>

            <h2>
                Estates already growing with Cardo-Fer
            </h2>

        </div>


        <div
            class="carousel reveal"
            data-carousel
            style="max-width:820px; margin:0 auto; position:relative;"
        >

            <div class="carousel-track">


                <div class="carousel-slide">

                    <div class="story-card">

                        <div class="story-avatar"></div>

                        <div>

                            <blockquote>
                                "The disease alert caught a Katte
                                outbreak two weeks before I would
                                have noticed it myself. Saved half
                                my plantation."
                            </blockquote>

                            <cite>
                                Thomas Varghese —
                                Vandanmedu, Idukki
                            </cite>

                        </div>

                    </div>

                </div>


                <div class="carousel-slide">

                    <div class="story-card">

                        <div class="story-avatar"></div>

                        <div>

                            <blockquote>
                                "I sold my entire cured harvest
                                through the marketplace at 14%
                                above the auction floor price."
                            </blockquote>

                            <cite>
                                Latha Menon — Kumily
                            </cite>

                        </div>

                    </div>

                </div>


                <div class="carousel-slide">

                    <div class="story-card">

                        <div class="story-avatar"></div>

                        <div>

                            <blockquote>
                                "The grant navigator found a
                                drip-irrigation subsidy I didn't
                                even know existed. Application
                                took ten minutes."
                            </blockquote>

                            <cite>
                                Rajan Pillai — Wayanad
                            </cite>

                        </div>

                    </div>

                </div>


            </div>


            <button
                class="carousel-arrow prev"
                aria-label="Previous story"
            >
                ‹
            </button>


            <button
                class="carousel-arrow next"
                aria-label="Next story"
            >
                ›
            </button>


            <div class="carousel-nav"></div>

        </div>

    </div>

</section>


<!-- =========================================================
     MARKETPLACE PREVIEW
========================================================= -->

<section id="marketplace-preview">

    <div class="container split-preview">

        <div class="reveal">

            <span class="eyebrow">
                Add Field Details
            </span>


            <h2
                style="
                    font-size:2rem;
                    color:var(--forest);
                    margin-bottom:14px;
                "
            >
                Sell direct. Buy trusted inputs.
            </h2>


            <p
                style="
                    color:var(--text-muted);
                    margin-bottom:28px;
                "
            >
                Verified buyers bid on your cured cardamom
                while you shop fertilizer, saplings and
                equipment from the same dashboard.
            </p>


            <a
                href="{{ route('farmer.field') }}"
                class="btn btn-primary"
            >
                Add Field Details
            </a>

        </div>


        <div
            class="mini-product-grid reveal split-media"
        >

            <div class="mini-product card">

                <span style="font-size:1.6rem;">
                    🌿
                </span>

                <h4
                    style="
                        margin:10px 0 4px;
                        font-size:0.9rem;
                    "
                >
                    Green Cardamom 1kg
                </h4>

                <span class="price">
                    ₹2,450
                </span>

            </div>


            <div class="mini-product card">

                <span style="font-size:1.6rem;">
                    🌱
                </span>

                <h4
                    style="
                        margin:10px 0 4px;
                        font-size:0.9rem;
                    "
                >
                    Seedlings x10
                </h4>

                <span class="price">
                    ₹650
                </span>

            </div>


            <div class="mini-product card">

                <span style="font-size:1.6rem;">
                    💧
                </span>

                <h4
                    style="
                        margin:10px 0 4px;
                        font-size:0.9rem;
                    "
                >
                    Drip Irrigation Kit
                </h4>

                <span class="price">
                    ₹3,200
                </span>

            </div>


            <div class="mini-product card">

                <span style="font-size:1.6rem;">
                    🧪
                </span>

                <h4
                    style="
                        margin:10px 0 4px;
                        font-size:0.9rem;
                    "
                >
                    NPK Fertilizer 25kg
                </h4>

                <span class="price">
                    ₹1,450
                </span>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     WEATHER + AI PREVIEW
========================================================= -->

<section id="tools-preview">

    <div class="container split-preview reverse">


        <div
            class="weather-widget reveal split-media"
        >

            <span
                class="eyebrow"
                style="color:var(--pod);"
            >
                Idukki District
            </span>


            <div class="temp">
                27°C
            </div>


            <div class="desc">
                Light rain expected in 4 hours
            </div>


            <div class="weather-mini-grid">

                <div>
                    Humidity
                    <strong>
                        84%
                    </strong>
                </div>


                <div>
                    Wind
                    <strong>
                        11 km/h
                    </strong>
                </div>


                <div>
                    UV Index
                    <strong>
                        Moderate
                    </strong>
                </div>

            </div>

        </div>


        <div class="reveal">

            <span class="eyebrow">
                Weather Intelligence + AI Assistant
            </span>


            <h2
                style="
                    font-size:2rem;
                    color:var(--forest);
                    margin-bottom:14px;
                "
            >
                Ask Cardo-Fer anything about your crop
            </h2>


            <p
                style="
                    color:var(--text-muted);
                    margin-bottom:20px;
                "
            >
                The assistant reads your local weather
                and disease history to answer questions
                in plain language.
            </p>


            <div class="ai-preview glass">

                <div class="ai-chat-bubble user">
                    Should I spray fungicide before
                    Thursday's rain?
                </div>


                <div class="ai-chat-bubble bot">
                    Rain probability is 78% Thursday
                    afternoon — spray by Wednesday evening
                    for the fungicide to set.
                </div>


                <div class="ai-chat-bubble bot">

                    <span class="ai-typing">

                        <span></span>
                        <span></span>
                        <span></span>

                    </span>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
     CTA
========================================================= -->

<div class="cta-section reveal">

    <h2>
        Bring your estate onto Cardo-Fer today
    </h2>


    <p>
        Free for individual farmers. Set up your
        dashboard in under five minutes — no credit
        card required.
    </p>


    <div class="cta-actions">

        <a
            href="{{ route('disease.identification') }}"
            class="btn btn-spice"
        >
            Identify Disease
        </a>


        <a
            href="{{ route('farmer.field') }}"
            class="btn btn-outline"
            style="
                color:#fff;
                border-color:rgba(255,255,255,0.35);
            "
        >
            Manage Field
        </a>

        <a
            href="{{ route('farmer.fertilizer') }}"
            class="btn btn-outline"
            style="
                color:#fff;
                border-color:rgba(255,255,255,0.35);
            "
        >
            Manage Fertilizer
        </a>

    </div>

</div>



<!-- =========================================================
     FOOTER
========================================================= -->

<footer
    class="farmer-footer"
    style="
        margin-top:80px;
        padding:55px 0 25px;
        background:var(--forest);
        color:#fff;
    "
>

    <div class="container">

        <div
            style="
                display:grid;
                grid-template-columns:2fr 1fr 1fr 1fr;
                gap:40px;
                align-items:start;
            "
        >

            <div>

                <h3 style="margin-bottom:12px; color:#fff;">
                    Cardo-Fer
                </h3>

                <p
                    style="
                        max-width:420px;
                        color:rgba(255,255,255,0.70);
                        line-height:1.7;
                        margin:0;
                    "
                >
                    AI-powered tools for smarter cardamom farming,
                    disease identification, field management,
                    fertilizer planning and weather prediction.
                </p>

            </div>


            <!-- FARMER -->

            <div>

                <h4 style="color:#fff; margin-bottom:16px;">
                    Farmer
                </h4>

                <a href="{{ route('farmerhome') }}"
                   style="display:block; color:rgba(255,255,255,0.72); margin-bottom:10px;">
                    Home
                </a>

                <a href="{{ route('farmer.field') }}"
                   style="display:block; color:rgba(255,255,255,0.72); margin-bottom:10px;">
                    Add Field Details
                </a>

                <a href="{{ route('disease.identification') }}"
                   style="display:block; color:rgba(255,255,255,0.72); margin-bottom:10px;">
                    Disease Identification
                </a>

            </div>


            <!-- FARMER FEATURES -->

            <div>

                <h4 style="color:#fff; margin-bottom:16px;">
                    Farmer Features
                </h4>

                <a href="{{ route('farmer.fertilizer') }}"
                   style="display:block; color:rgba(255,255,255,0.72); margin-bottom:10px;">
                    Fertilizer Management
                </a>

                <a href="{{ route('farmer.fertilizer') }}"
                   style="display:block; color:rgba(255,255,255,0.72); margin-bottom:10px;">
                    Fertilizer Reminder
                </a>

                <a href="#disease-history"
                   style="display:block; color:rgba(255,255,255,0.72); margin-bottom:10px;">
                    Disease History
                </a>

                <a href="#farm-reports"
                   style="display:block; color:rgba(255,255,255,0.72); margin-bottom:10px;">
                    Farm Reports
                </a>

                <a href="weather.html"
                   style="display:block; color:rgba(255,255,255,0.72); margin-bottom:10px;">
                    Weather Prediction
                </a>

            </div>


            <!-- QUICK ACTION -->

            <div>

                <h4 style="color:#fff; margin-bottom:16px;">
                    Quick Action
                </h4>

                <a href="{{ route('disease.identification') }}"
                   style="display:block; color:rgba(255,255,255,0.72); margin-bottom:10px;">
                    Identify Disease
                </a>

                <a href="{{ route('farmer.field') }}"
                   style="display:block; color:rgba(255,255,255,0.72); margin-bottom:10px;">
                    Manage Field
                </a>

                <a href="{{ route('farmer.fertilizer') }}"
                   style="display:block; color:rgba(255,255,255,0.72); margin-bottom:10px;">
                    Manage Fertilizer
                </a>

            </div>

        </div>


        <div
            style="
                margin-top:40px;
                padding-top:20px;
                border-top:1px solid rgba(255,255,255,0.12);
                display:flex;
                justify-content:space-between;
                gap:20px;
                flex-wrap:wrap;
                color:rgba(255,255,255,0.55);
                font-size:0.85rem;
            "
        >

            <span>
                © {{ date('Y') }} Cardo-Fer. All rights reserved.
            </span>

            <span>
                Smarter farming. Healthier cardamom.
            </span>

        </div>

    </div>

</footer>


<style>
    @media (max-width: 900px) {
        .farmer-footer > .container > div:first-child {
            grid-template-columns:1fr 1fr !important;
        }
    }

    @media (max-width: 576px) {
        .farmer-footer > .container > div:first-child {
            grid-template-columns:1fr !important;
        }
    }
</style>


@endsection