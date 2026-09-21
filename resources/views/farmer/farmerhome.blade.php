@extends('layouts.FarmerMaster')

@section('content')

<!-- =========================================================
     MOBILE MENU
========================================================= -->

<div class="mobile-menu">

    <a href="{{ route('farmerhome') }}">
        Home
    </a>

    <a href="{{ route('dashboard') }}">
        Dashboard
    </a>

    <a href="{{ route('disease.identification') }}">
        Disease Identification
    </a>

    <a href="{{ route('farmer.field') }}">
        Add Field Details
    </a>

  <a href="{{ route('disease.identification') }}">
    Disease Identification
</a>

    <a href="weather.html">
        Weather
    </a>

    <a href="consultation.html">
        Consultation
    </a>

    <a href="grants.html">
        Grants
    </a>

    <a href="forum.html">
        Forum
    </a>

    <a href="contact.html">
        Contact
    </a>

    <a
        href="{{ route('disease.identification') }}"
        class="btn btn-primary"
    >
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

    </div>

</div>


@endsection