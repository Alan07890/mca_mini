<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8" />

    <meta

        name="viewport"

        content="width=device-width, initial-scale=1.0"

    />

    <link

        rel="apple-touch-icon"

        sizes="76x76"

        href="{{ asset('admin/assets/img/apple-icon.png') }}"

    />

    <link

        rel="icon"

        type="image/png"

        href="{{ asset('admin/assets/img/favicon.png') }}"

    />

    <title>

        CARDO-FER | AI Smart Farming System

    </title>



    <!-- Google Font: Poppins -->

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link

        rel="preconnect"

        href="https://fonts.gstatic.com"

        crossorigin

    >

    <link

        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"

        rel="stylesheet"

    >



    <!-- Bootstrap 5 -->

    <link

        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"

        rel="stylesheet"

    >



    <!-- Font Awesome 6 -->

    <link

        rel="stylesheet"

        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"

    >



    <!-- Existing assets -->

    <link

        href="{{ asset('admin/assets/css/nucleo-icons.css') }}"

        rel="stylesheet"

    />

    <link

        href="{{ asset('admin/assets/css/nucleo-svg.css') }}"

        rel="stylesheet"

    />



    <style>

        /* =====================================================

           CARDO-FER GLOBAL THEME

        ===================================================== */

        :root {

            --cf-forest: #123c17;

            --cf-primary: #1B5E20;

            --cf-secondary: #2E7D32;

            --cf-leaf: #4CAF50;

            --cf-lightleaf: #81C784;

            --cf-cream: #F1F8E9;

            --cf-gold: #FFD54F;

            --cf-brown: #6D4C41;

            --cf-dark: #0a1d15;

            --cf-dark-deep: #07150f;

            --cf-card: #10251b;

            --cf-line:

                rgba(241,248,233,0.10);

            --cf-line-strong:

                rgba(129,199,132,0.18);

            --cf-sidebar-w: 280px;

        }



        * {

            box-sizing: border-box;

        }



        html,

        body {

            margin: 0;

            padding: 0;

        }



        body {

            font-family:

                'Poppins',

                sans-serif;

            background:

                #0a1d15;

            color:

                var(--cf-cream);

            min-height:

                100vh;

        }



        /* =====================================================

           BACKGROUND

        ===================================================== */

        .cardofer-bg {

            position: fixed;

            inset: 0;

            z-index: -1;

            background:

                radial-gradient(

                    circle at 8% 12%,

                    rgba(76,175,80,0.10) 0%,

                    transparent 40%

                ),

                radial-gradient(

                    circle at 92% 85%,

                    rgba(255,213,79,0.06) 0%,

                    transparent 45%

                ),

                #0a1d15;

        }



        .cardofer-bg svg {

            position:

                absolute;

            opacity:

                0.035;

        }



        .cardofer-bg .leaf-a {

            top:

                2.5rem;

            left:

                -4rem;

        }



        .cardofer-bg .leaf-b {

            bottom:

                0;

            right:

                -6rem;

        }



        .cardofer-bg .hills {

            bottom:

                0;

            left:

                0;

        }



        /* =====================================================

           SIDEBAR

        ===================================================== */

        .cf-sidebar {

            position:

                fixed;

            top: 0;

            bottom: 0;

            left: 0;

            width:

                var(--cf-sidebar-w);

            background:

                linear-gradient(

                    180deg,

                    #0e3515,

                    #155c1e

                );

            box-shadow:

                0 10px 30px -12px

                rgba(0,0,0,0.45);

            overflow-y:

                auto;

            transform:

                translateX(-100%);

            transition:

                transform .2s ease-in-out;

            z-index:

                1050;

            padding:

                1.5rem 0;

        }



        .cf-sidebar.show {

            transform:

                translateX(0);

        }



        @media (min-width: 1200px) {

            .cf-sidebar {

                transform:

                    translateX(0);

            }



            .cf-main {

                margin-left:

                    var(--cf-sidebar-w);

            }

        }



        .cf-brand {

            padding:

                0 1.5rem 1.5rem;

            border-bottom:

                1px solid rgba(255,255,255,0.10);

            margin:

                0 1rem 1rem;

        }



        .cf-brand-icon {

            width: 44px;

            height: 44px;

            border-radius:

                1rem;

            background:

                rgba(255,255,255,0.10);

            display:

                flex;

            align-items:

                center;

            justify-content:

                center;

            font-size:

                1.4rem;

        }



        .cf-nav-section-label {

            font-size:

                .68rem;

            font-weight:

                600;

            text-transform:

                uppercase;

            letter-spacing:

                .06em;

            color:

                rgba(255,255,255,0.40);

            padding:

                1rem 1.5rem .25rem;

        }



        .cf-sidebar .nav-link {

            display:

                flex;

            align-items:

                center;

            gap:

                .75rem;

            padding:

                .65rem 1.25rem;

            margin:

                0 .75rem;

            border-radius:

                .75rem;

            font-size:

                .875rem;

            font-weight:

                500;

            color:

                rgba(255,255,255,0.85);

            text-decoration:

                none;

            transition:

                background .15s ease,

                color .15s ease;

        }



        .cf-sidebar .nav-link i {

            width:

                1.1rem;

            text-align:

                center;

            color:

                var(--cf-lightleaf);

        }



        .cf-sidebar .nav-link:hover {

            background:

                rgba(255,255,255,0.07);

            color:

                #fff;

        }



        .cf-sidebar .nav-link.active {

            background:

                linear-gradient(

                    90deg,

                    rgba(76,175,80,0.25),

                    rgba(76,175,80,0.02)

                );

            border-left:

                3px solid var(--cf-leaf);

            color:

                #fff;

        }



        .cf-sidebar .nav-link.active i {

            color:

                var(--cf-gold);

        }



        .cf-sidebar .nav-link.text-logout i {

            color:

                #f8a8a8;

        }



        .cf-sidebar-footer {

            margin:

                1.5rem 1rem 0;

            padding:

                1rem;

            border-radius:

                1rem;

            background:

                rgba(255,255,255,0.05);

            text-align:

                center;

        }



        .cf-sidebar-footer p {

            margin-bottom:

                0;

            font-size:

                .85rem;

            font-weight:

                600;

        }



        .cf-sidebar-footer .line-1 {

            color:

                var(--cf-lightleaf);

        }



        .cf-sidebar-footer .line-2 {

            color:

                #fff;

        }



        /* =====================================================

           TOP NAVBAR

        ===================================================== */

        .cf-topnav {

            position:

                sticky;

            top:

                1rem;

            z-index:

                1030;

            margin:

                1rem 1rem 0;

            background:

                rgba(241,248,233,0.96);

            backdrop-filter:

                blur(8px);

            border-radius:

                1rem;

            box-shadow:

                0 8px 25px rgba(0,0,0,0.18);

            padding:

                .75rem 1.25rem;

        }



        @media (min-width: 1200px) {

            .cf-topnav {

                margin:

                    1rem 1.5rem 0;

            }

        }



        .cf-search {

            border-radius:

                999px;

            border:

                1px solid

                rgba(76,175,80,0.30);

            background:

                rgba(255,255,255,0.55);

            color:

                #263629;

            padding-left:

                2.5rem;

        }



        .cf-search:focus {

            border-color:

                var(--cf-leaf);

            box-shadow:

                0 0 0 .2rem

                rgba(76,175,80,0.20);

        }



        .cf-search-wrap {

            position:

                relative;

        }



        .cf-search-wrap i {

            position:

                absolute;

            left:

                1rem;

            top:

                50%;

            transform:

                translateY(-50%);

            color:

                #64748b;

            font-size:

                .85rem;

        }



        .cf-notif-badge {

            position:

                absolute;

            top:

                -4px;

            right:

                -4px;

            width:

                16px;

            height:

                16px;

            border-radius:

                50%;

            background:

                var(--cf-gold);

            color:

                var(--cf-brown);

            font-size:

                .6rem;

            font-weight:

                700;

            display:

                flex;

            align-items:

                center;

            justify-content:

                center;

        }



        .cf-avatar {

            width:

                36px;

            height:

                36px;

            border-radius:

                50%;

            background:

                rgba(76,175,80,0.15);

            color:

                var(--cf-secondary);

            display:

                flex;

            align-items:

                center;

            justify-content:

                center;

        }



        /* =====================================================

           MOBILE DASHBOARD MENU BUTTON

        ===================================================== */

        .cf-dashboard-menu {

            display: none;

            position: fixed;

            top: 18px;

            right: 18px;

            z-index: 1040;

            width: 46px;

            height: 46px;

            border-radius: 14px;

            border:

                1px solid

                rgba(129,199,132,0.25);

            background:

                #10251b;

            color:

                var(--cf-gold);

            box-shadow:

                0 10px 25px rgba(0,0,0,0.30);

            align-items: center;

            justify-content: center;

            cursor: pointer;

        }



        /* Only show this on the dashboard */

        body.cf-dashboard-page .cf-dashboard-menu {

            display: flex;

        }



        @media (min-width: 1200px) {

            body.cf-dashboard-page .cf-dashboard-menu {

                display: none;

            }

        }



        /* =====================================================

           MAIN

        ===================================================== */

        .cf-main {

            background:

                #0a1d15;

            min-height:

                100vh;

        }



        .cf-page-content {

            min-height:

                calc(100vh - 100px);

            background:

                #0a1d15;

        }



        /* =====================================================

           DARK PAGE SUPPORT

        ===================================================== */

        .cf-main:has(.fert-wrap),

        .cf-main:has(.field-page) {

            background:

                #0a1d15;

            min-height:

                100vh;

        }



        .cf-main:has(.fert-wrap)

        .cf-page-content,

        .cf-main:has(.field-page)

        .cf-page-content {

            background:

                #0a1d15;

        }



        /* =====================================================

           GENERAL

        ===================================================== */

        .card-lift {

            transition:

                transform .25s ease,

                box-shadow .25s ease;

        }



        .card-lift:hover {

            transform:

                translateY(-4px);

            box-shadow:

                0 16px 34px -14px

                rgba(0,0,0,0.30);

        }



        .fade-in {

            animation:

                cardoferFadeIn .5s ease both;

        }



        @keyframes cardoferFadeIn {

            from {

                opacity:

                    0;

                transform:

                    translateY(8px);

            }

            to {

                opacity:

                    1;

                transform:

                    translateY(0);

            }

        }



        .rounded-xl2 {

            border-radius:

                1rem !important;

        }



        .shadow-card {

            box-shadow:

                0 4px 20px

                rgba(0,0,0,0.18) !important;

        }



        ::-webkit-scrollbar {

            width:

                8px;

        }



        ::-webkit-scrollbar-track {

            background:

                #07150f;

        }



        ::-webkit-scrollbar-thumb {

            background:

                var(--cf-lightleaf);

            border-radius:

                8px;

        }



        /* =====================================================

           MOBILE SIDEBAR BACKDROP

        ===================================================== */

        .cf-sidebar-backdrop {

            position:

                fixed;

            inset:

                0;

            background:

                rgba(0,0,0,.45);

            z-index:

                1040;

            display:

                none;

        }



        .cf-sidebar-backdrop.show {

            display:

                block;

        }



        /* =====================================================

           FOOTER

        ===================================================== */

        .cf-admin-footer {

            padding:

                0 1rem 1rem;

        }



        @media (min-width: 1200px) {

            .cf-admin-footer {

                padding:

                    0 1.5rem 1rem;

            }

        }



        .cf-admin-footer-inner {

            display:

                flex;

            flex-wrap:

                wrap;

            align-items:

                center;

            justify-content:

                space-between;

            gap:

                12px;

            padding:

                14px 18px;

            border-radius:

                14px;

            background:

                #10251b;

            border:

                1px solid

                var(--cf-line-strong);

            color:

                rgba(169,201,174,0.70);

            font-size:

                11px;

        }



        .cf-admin-footer-inner strong {

            color:

                var(--cf-lightleaf);

        }



        /* =====================================================

           DASHBOARD-SPECIFIC FULL DARK BACKGROUND

        ===================================================== */

        body.cf-dashboard-page {

            background:

                #0a1d15;

        }



        body.cf-dashboard-page .cardofer-bg {

            background:

                radial-gradient(

                    circle at 15% 15%,

                    rgba(76,175,80,0.08),

                    transparent 32%

                ),

                radial-gradient(

                    circle at 90% 85%,

                    rgba(255,213,79,0.05),

                    transparent 34%

                ),

                #0a1d15;

        }



        body.cf-dashboard-page .cf-page-content {

            padding-top:

                28px !important;

        }



        /* =====================================================

           RESPONSIVE

        ===================================================== */

        @media (max-width: 1199px) {

            .cf-main {

                margin-left:

                    0 !important;

            }

        }



        @media (max-width: 768px) {

            .cf-page-content {

                padding:

                    18px !important;

            }

            .cf-admin-footer {

                padding:

                    0 18px 18px;

            }

            .cf-admin-footer-inner {

                flex-direction:

                    column;

                align-items:

                    flex-start;

            }

        }

    </style>



    @stack('styles')

</head>



<body

    class="{{ request()->is('admin') ? 'cf-dashboard-page' : '' }}"

>



    <!-- =====================================================

         BACKGROUND

    ====================================================== -->

    <div class="cardofer-bg">



        <svg

            class="leaf-a"

            width="420"

            height="420"

            viewBox="0 0 200 200"

            xmlns="http://www.w3.org/2000/svg"

        >

            <path

                fill="#1B5E20"

                d="M100 10 C40 40 20 100 100 190 C180 100 160 40 100 10 Z"

            />

        </svg>



        <svg

            class="leaf-b"

            width="520"

            height="520"

            viewBox="0 0 200 200"

            xmlns="http://www.w3.org/2000/svg"

        >

            <path

                fill="#2E7D32"

                d="M100 10 C40 40 20 100 100 190 C180 100 160 40 100 10 Z"

            />

        </svg>



        <svg

            class="hills"

            width="900"

            height="220"

            viewBox="0 0 900 220"

            xmlns="http://www.w3.org/2000/svg"

        >

            <path

                fill="#6D4C41"

                d="M0 220 L0 160 L120 90 L220 150 L340 60 L460 140 L600 40 L740 130 L860 70 L900 120 L900 220 Z"

            />

        </svg>



    </div>



    <!-- =====================================================

         MOBILE SIDEBAR BACKDROP

    ====================================================== -->

    <div

        class="cf-sidebar-backdrop"

        id="cfSidebarBackdrop"

    ></div>



    <!-- =====================================================

         DASHBOARD MOBILE MENU

    ====================================================== -->

    <button

        type="button"

        class="cf-dashboard-menu"

        id="cfDashboardMenu"

        aria-label="Open menu"

    >

        <i class="fa-solid fa-bars"></i>

    </button>



    <!-- =====================================================

         SIDEBAR

    ====================================================== -->

    <aside

        class="cf-sidebar"

        id="cfSidebar"

    >



        <button

            type="button"

            class="btn-close btn-close-white d-xl-none position-absolute top-0 end-0 m-3"

            id="cfSidebarClose"

            aria-label="Close"

        ></button>



        <!-- Brand -->

        <a

            href="{{ url('/') }}"

            class="cf-brand d-flex align-items-center gap-3 text-decoration-none"

        >

            <span class="cf-brand-icon">

                🌿

            </span>



            <span class="d-flex flex-column lh-sm">

                <span

                    class="fw-bold text-white fs-6"

                >

                    CARDO-FER

                </span>



                <span

                    class="text-uppercase"

                    style="

                        font-size:.65rem;

                        letter-spacing:.08em;

                        color:var(--cf-lightleaf);

                    "

                >

                    AI Smart Farming System

                </span>

            </span>

        </a>



        <!-- =================================================

             NAVIGATION

        ================================================== -->

        <nav class="nav flex-column">



            <!-- Dashboard -->

            {{-- <a

                href="{{ url('/admin') }}"

                class="nav-link {{ request()->is('admin') ? 'active' : '' }}"

            >

                <i

                    class="fa-solid fa-chart-line"

                ></i>

                <span>

                    Dashboard

                </span>

            </a> --}}



            <!-- Disease Intelligence -->

            <p

                class="cf-nav-section-label mb-0"

            >

                Disease Intelligence

            </p>



            <a

                href="{{ route('category') }}"

                class="nav-link"

            >

                <i

                    class="fa-solid fa-seedling"

                ></i>

                <span>

                    ADD Disease

                </span>

            </a>



            <a

                href="{{ route('symptom_reg') }}"

                class="nav-link"

            >

                <i

                    class="fa-solid fa-bug"

                ></i>

                <span>

                    Diseases Symptoms Registration

                </span>

            </a>



            <a

                href="{{ route('symptoms') }}"

                class="nav-link"

            >

                <i

                    class="fa-solid fa-stethoscope"

                ></i>

                <span>

                    Symptoms

                </span>

            </a>



            {{-- <a

                href="#"

                class="nav-link"

            >

                <i

                    class="fa-solid fa-microscope"

                ></i>

                <span>

                    AI Disease Prediction

                </span>

            </a>



            <a

                href="#"

                class="nav-link"

            >

                <i

                    class="fa-solid fa-shield-heart"

                ></i>

                <span>

                    Disease Prevention

                </span>

            </a> --}}



            <!-- Fertilizer & Field -->

            <p

                class="cf-nav-section-label mb-0"

            >

                Fertilizer &amp; Field

            </p>



            <a

                href="{{ route('fertilizer') }}"

                class="nav-link"

            >

                <i

                    class="fa-solid fa-flask"

                ></i>

                <span>

                    Fertilizer Management

                </span>

            </a>



            {{-- <a

                href="#"

                class="nav-link"

            >

                <i

                    class="fa-solid fa-calculator"

                ></i>

                <span>

                    Fertilizer Calculator

                </span>

            </a>



            <a

                href="#"

                class="nav-link"

            >

                <i

                    class="fa-solid fa-book"

                ></i>

                <span>

                    Fertilizer Diary

                </span>

            </a> --}}



            <a

                href="{{ route('admin.field-management') }}"

                class="nav-link {{ request()->routeIs('admin.field-management') ? 'active' : '' }}"

            >

                <i

                    class="fa-solid fa-tree"

                ></i>

                <span>

                    Field Management

                </span>

            </a>



            {{-- <!-- Planning -->

            <p

                class="cf-nav-section-label mb-0"

            >

                Planning

            </p>



            <a

                href="#"

                class="nav-link"

            >

                <i

                    class="fa-solid fa-calendar-days"

                ></i>

                <span>

                    Schedules

                </span>

            </a>



            <a

                href="#"

                class="nav-link"

            >

                <i

                    class="fa-solid fa-bell"

                ></i>

                <span>

                    Reminder Notifications

                </span>

            </a>



            <a

                href="#"

                class="nav-link"

            >

                <i

                    class="fa-solid fa-clock-rotate-left"

                ></i>

                <span>

                    History

                </span>

            </a>



            {{-- Reports --}}
<p class="cf-nav-section-label mb-0">Reports</p>

<a href="{{ route('admin.activity-report') }}"
   class="nav-link {{ request()->routeIs('admin.activity-report') ? 'active' : '' }}">

    <i class="fa-solid fa-chart-pie"></i>

    <span>Activity Report</span>
</a>

            <!-- Account -->

            <p

                class="cf-nav-section-label mb-0"

            >

                Account

            </p>



            <a

                href="{{ route('users') }}"

                class="nav-link"

            >

                <i

                    class="fa-solid fa-users"

                    style="color:rgba(255,255,255,0.7);"

                ></i>

                <span>

                    Users

                </span>

            </a>



            {{-- <a

                href="#"

                class="nav-link"

            >

                <i

                    class="fa-solid fa-user"

                    style="color:rgba(255,255,255,0.7);"

                ></i>

                <span>

                    Profile

                </span>

            </a> --}}



            {{-- <a

                href="#"

                class="nav-link"

            >

                <i

                    class="fa-solid fa-gear"

                    style="color:rgba(255,255,255,0.7);"

                ></i>

                <span>

                    Settings

                </span>

            </a> --}}



            <!-- Logout -->

            <form

                method="POST"

                action="{{ route('logout') }}"

                style="margin:0;"

            >

                @csrf

                <button

                    type="submit"

                    class="nav-link text-logout"

                    style="

                        border:none;

                        background:none;

                        width:100%;

                        text-align:left;

                        cursor:pointer;

                    "

                >

                    <i

                        class="fa-solid fa-right-from-bracket"

                    ></i>

                    <span>

                        Logout

                    </span>

                </button>

            </form>



        </nav>



        <!-- Sidebar footer -->

        <div

            class="cf-sidebar-footer"

        >

            <p class="line-1">

                🌱 Healthy Plants,

            </p>

            <p class="line-2">

                Healthy Harvests.

            </p>

        </div>



    </aside>



    <!-- =====================================================

         MAIN

    ====================================================== -->

    <main

        class="cf-main"

    >



        <!-- =================================================

             TOP NAVBAR

             Hidden ONLY on /admin dashboard

        ================================================== -->

        @if (!request()->is('admin'))

            <nav

                class="cf-topnav d-flex flex-wrap align-items-center justify-content-between gap-3"

            >



                <!-- Left -->

                <div

                    class="d-flex align-items-center gap-3"

                >

                    <button

                        type="button"

                        class="btn btn-link text-secondary p-0 d-xl-none"

                        id="cfSidebarTrigger"

                    >

                        <i

                            class="fa-solid fa-bars fs-5"

                        ></i>

                    </button>



                    <div>

                        <p

                            class="mb-0 text-uppercase fw-medium"

                            style="

                                font-size:.7rem;

                                letter-spacing:.05em;

                                color:var(--cf-secondary);

                            "

                        >

                            Welcome Back,

                        </p>



                        <h6

                            class="mb-0 fw-semibold text-dark"

                        >

                            Administrator

                        </h6>

                    </div>

                </div>



                <!-- Search -->

                <div

                    class="cf-search-wrap order-last order-sm-0 w-100"

                    style="max-width:320px;"

                >

                    <i

                        class="fa-solid fa-magnifying-glass"

                    ></i>



                    <input

                        type="text"

                        placeholder="Search..."

                        class="form-control cf-search"

                    >

                </div>



                <!-- Right -->

                <div

                    class="d-flex align-items-center gap-3"

                >



                    <div

                        class="d-none d-sm-flex align-items-center gap-2 rounded-pill px-3 py-1"

                        style="

                            background-color:var(--cf-cream);

                            font-size:.75rem;

                            font-weight:500;

                            color:var(--cf-secondary);

                        "

                    >

                        <i

                            class="fa-solid fa-cloud-sun"

                            style="color:var(--cf-gold);"

                        ></i>

                        <span

                            id="cardoferToday"

                        ></span>

                    </div>



                    <div class="dropdown">

                        <a

                            href="#"

                            class="position-relative text-secondary d-block"

                            data-bs-toggle="dropdown"

                            aria-expanded="false"

                        >

                            <i

                                class="fa-regular fa-bell fs-5"

                            ></i>

                            <span

                                class="cf-notif-badge"

                            >

                                3

                            </span>

                        </a>



                        <ul

                            class="dropdown-menu dropdown-menu-end shadow-card rounded-xl2 p-2"

                            style="width:300px;"

                        >

                            <li>

                                <a

                                    class="dropdown-item d-flex align-items-start gap-2 rounded-xl2 py-2"

                                    href="#"

                                >

                                    <i

                                        class="fa-solid fa-bell mt-1"

                                        style="color:var(--cf-gold);"

                                    ></i>



                                    <span>

                                        <span

                                            class="d-block fw-medium text-dark small"

                                        >

                                            Fertilizer schedule due tomorrow

                                        </span>

                                        <span

                                            class="d-block text-muted"

                                            style="font-size:.72rem;"

                                        >

                                            13 minutes ago

                                        </span>

                                    </span>

                                </a>

                            </li>



                            <li>

                                <a

                                    class="dropdown-item d-flex align-items-start gap-2 rounded-xl2 py-2"

                                    href="#"

                                >

                                    <i

                                        class="fa-solid fa-microscope mt-1"

                                        style="color:var(--cf-leaf);"

                                    ></i>



                                    <span>

                                        <span

                                            class="d-block fw-medium text-dark small"

                                        >

                                            New AI prediction ready for review

                                        </span>

                                        <span

                                            class="d-block text-muted"

                                            style="font-size:.72rem;"

                                        >

                                            1 day ago

                                        </span>

                                    </span>

                                </a>

                            </li>

                        </ul>

                    </div>



                    <a

                        href="#"

                        class="d-flex align-items-center gap-2 text-decoration-none text-dark small fw-medium"

                    >

                        <span class="cf-avatar">

                            <i

                                class="fa-solid fa-user"

                            ></i>

                        </span>

                        <span

                            class="d-none d-sm-inline"

                        >

                            Administrator

                        </span>

                    </a>



                </div>

            </nav>

        @endif



        <!-- =================================================

             PAGE CONTENT

        ================================================== -->

        <div

            class="cf-page-content px-3 px-xl-4 py-4"

        >

            @yield('content')

        </div>



        <!-- =================================================

             FOOTER

        ================================================== -->

        <footer

            class="cf-admin-footer"

        >

            <div

                class="cf-admin-footer-inner"

            >

                <span>

                    ©

                    <span id="cardoferYear"></span>

                    Cardo-Fer &mdash;

                    AI-Based Cardamom Disease Identification

                    &amp; Fertilizer Recommendation System

                </span>



                <span>

                    <strong>

                        🌿 Built for smarter, healthier harvests

                    </strong>

                </span>

            </div>

        </footer>



    </main>



    <!-- =====================================================

         BOOTSTRAP

    ====================================================== -->

    <script

        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"

    ></script>



    <script>

        /* =====================================================

           YEAR

        ===================================================== */

        const yearElement =

            document.getElementById(

                'cardoferYear'

            );



        if (yearElement) {

            yearElement.textContent =

                new Date().getFullYear();

        }



        /* =====================================================

           DATE

        ===================================================== */

        const todayElement =

            document.getElementById(

                'cardoferToday'

            );



        if (todayElement) {

            todayElement.textContent =

                new Date().toLocaleDateString(

                    'en-US',

                    {

                        weekday: 'long',

                        month: 'short',

                        day: 'numeric'

                    }

                );

        }



        /* =====================================================

           SIDEBAR

        ===================================================== */

        const sidebar =

            document.getElementById(

                'cfSidebar'

            );



        const backdrop =

            document.getElementById(

                'cfSidebarBackdrop'

            );



        const sidebarTrigger =

            document.getElementById(

                'cfSidebarTrigger'

            );



        const sidebarClose =

            document.getElementById(

                'cfSidebarClose'

            );



        const dashboardMenu =

            document.getElementById(

                'cfDashboardMenu'

            );



        function openSidebar() {

            if (!sidebar || !backdrop) {

                return;

            }

            sidebar.classList.add(

                'show'

            );

            backdrop.classList.add(

                'show'

            );

        }



        function closeSidebar() {

            if (!sidebar || !backdrop) {

                return;

            }

            sidebar.classList.remove(

                'show'

            );

            backdrop.classList.remove(

                'show'

            );

        }



        if (sidebarTrigger) {

            sidebarTrigger.addEventListener(

                'click',

                openSidebar

            );

        }



        if (dashboardMenu) {

            dashboardMenu.addEventListener(

                'click',

                openSidebar

            );

        }



        if (sidebarClose) {

            sidebarClose.addEventListener(

                'click',

                closeSidebar

            );

        }



        if (backdrop) {

            backdrop.addEventListener(

                'click',

                closeSidebar

            );

        }

    </script>



    <!-- Chart plugin -->

    <script

        src="{{ asset('admin/assets/js/plugins/chartjs.min.js') }}"

        async

    ></script>



    @stack('scripts')



</body>

</html>
