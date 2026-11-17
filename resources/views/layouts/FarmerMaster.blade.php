<!DOCTYPE html>

<html lang="en">

<head>

  <meta charset="UTF-8">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Cardo-Fer — Smart Platform for Cardamom Farmers</title>

  <meta name="description" content="Cardo-Fer helps cardamom farmers diagnose crop disease, track weather, sell produce and access government grants — all from one premium dashboard.">

  <meta name="theme-color" content="#1B3B2F">

  <!-- Fonts -->

  <link rel="preconnect" href="https://fonts.googleapis.com">

  <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,500&family=Manrope:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

  <!-- Styles -->

  <link rel="stylesheet" href="{{ asset('farmer/assets/css/style.css') }}">

  <link rel="stylesheet" href="{{ asset('farmer/assets/css/responsive.css') }}">

  <style>

    /* =========================================================

       CARDO-FER NAVBAR REFINEMENT

       Keeps the new farmer features accessible without

       overcrowding the desktop header.

    ========================================================= */

    .navbar .container {

      display: flex;

      align-items: center;

      min-width: 0;

    }

    .navbar .brand {

      flex: 0 0 auto;

      white-space: nowrap;

      margin-right: 28px;

    }

    .cardo-main-nav {

      flex: 1 1 auto;

      justify-content: center;

      gap: 10px !important;

      min-width: 0;

    }

    .cardo-main-nav > a,

    .cardo-nav-dropdown-btn {

      display: inline-flex;

      align-items: center;

      justify-content: center;

      min-height: 40px;

      padding: 8px 11px !important;

      border-radius: 10px;

      font-size: 0.82rem;

      white-space: nowrap;

      color: rgba(235, 244, 236, 0.72);

      transition:

        background 0.2s ease,

        color 0.2s ease,

        transform 0.2s ease;

    }

    .cardo-main-nav > a:hover,

    .cardo-main-nav > a:focus-visible,

    .cardo-nav-dropdown:hover > .cardo-nav-dropdown-btn,

    .cardo-nav-dropdown:focus-within > .cardo-nav-dropdown-btn {

      background: rgba(255, 255, 255, 0.055);

      color: #f4f7f3;

      transform: translateY(-1px);

    }

    .cardo-nav-dropdown {

      position: relative;

      flex: 0 0 auto;

    }

    .cardo-nav-dropdown-btn {

      border: 1px solid transparent;

      background: transparent;

      font-family: inherit;

      cursor: pointer;

    }

    .cardo-nav-dropdown-btn i {

      margin-left: 7px;

      font-size: 0.62rem;

      transition: transform 0.2s ease;

    }

    .cardo-nav-dropdown:hover .cardo-nav-dropdown-btn i,

    .cardo-nav-dropdown:focus-within .cardo-nav-dropdown-btn i {

      transform: rotate(180deg);

    }

    .cardo-nav-dropdown-menu {

      position: absolute;

      top: calc(100% + 10px);

      left: 50%;

      transform: translate(-50%, -6px);

      width: 270px;

      padding: 8px;

      border: 1px solid rgba(190, 211, 194, 0.14);

      border-radius: 16px;

      background: rgba(12, 39, 28, 0.98);

      box-shadow: 0 18px 45px rgba(0, 0, 0, 0.28);

      backdrop-filter: blur(16px);

      opacity: 0;

      visibility: hidden;

      pointer-events: none;

      transition:

        opacity 0.18s ease,

        transform 0.18s ease,

        visibility 0.18s ease;

      z-index: 120;

    }

    .cardo-nav-dropdown-menu::before {

      content: "";

      position: absolute;

      top: -5px;

      left: 50%;

      width: 10px;

      height: 10px;

      transform: translateX(-50%) rotate(45deg);

      background: rgba(12, 39, 28, 0.98);

      border-left: 1px solid rgba(190, 211, 194, 0.14);

      border-top: 1px solid rgba(190, 211, 194, 0.14);

    }

    .cardo-nav-dropdown:hover .cardo-nav-dropdown-menu,

    .cardo-nav-dropdown:focus-within .cardo-nav-dropdown-menu {

      opacity: 1;

      visibility: visible;

      pointer-events: auto;

      transform: translate(-50%, 0);

    }

    .cardo-nav-dropdown-menu a {

      display: flex;

      align-items: flex-start;

      gap: 11px;

      padding: 11px 12px;

      border-radius: 11px;

      color: rgba(239, 247, 241, 0.78);

      transition: background 0.18s ease, color 0.18s ease;

    }

    .cardo-nav-dropdown-menu a:hover,

    .cardo-nav-dropdown-menu a:focus-visible {

      background: rgba(255, 255, 255, 0.06);

      color: #fff;

    }

    .cardo-nav-dropdown-menu a > i {

      width: 18px;

      margin-top: 3px;

      color: #c88a42;

      flex: 0 0 18px;

    }

    .cardo-nav-dropdown-menu a span {

      display: flex;

      flex-direction: column;

      gap: 2px;

      min-width: 0;

    }

    .cardo-nav-dropdown-menu strong {

      font-size: 0.82rem;

      font-weight: 600;

    }

    .cardo-nav-dropdown-menu small {

      font-size: 0.70rem;

      line-height: 1.35;

      color: rgba(215, 229, 218, 0.52);

    }

    .navbar .nav-actions {

      flex: 0 0 auto;

      margin-left: 18px;

    }

    @media (max-width: 1250px) {

      .navbar .brand {

        margin-right: 14px;

      }

      .cardo-main-nav {

        gap: 4px !important;

      }

      .cardo-main-nav > a,

      .cardo-nav-dropdown-btn {

        padding-left: 8px !important;

        padding-right: 8px !important;

        font-size: 0.78rem;

      }

      .navbar .nav-actions {

        margin-left: 10px;

      }

    }

    @media (max-width: 1080px) {

      .cardo-main-nav {

        display: none !important;

      }

      .navbar .nav-actions {

        margin-left: auto;

      }

    }

  </style>

</head>

<body>

  <!-- Loading Screen -->

  <div id="loading-screen">

    <div class="pod-loader">

      <svg viewBox="0 0 60 60" fill="none">

        <path d="M30 4C42 4 52 16 52 30C52 44 42 56 30 56C18 56 8 44 8 30C8 16 18 4 30 4Z" stroke="#B8CDB0" stroke-width="2.5"/>

        <path d="M30 10V50M14 30H46" stroke="#B8CDB0" stroke-width="1.5" opacity="0.5"/>

      </svg>

    </div>

    <p class="loading-text">Cardo-Fer</p>

  </div>

  <!-- Navbar -->

  <header class="navbar">

    <div class="container">

      <!-- Brand -->

      <a href="{{ route('farmerhome') }}" class="brand">

        <svg class="pod-icon" viewBox="0 0 60 60" fill="none">

          <path d="M30 4C42 4 52 16 52 30C52 44 42 56 30 56C18 56 8 44 8 30C8 16 18 4 30 4Z" stroke="currentColor" stroke-width="3"/>

          <path d="M30 10V50M14 30H46" stroke="currentColor" stroke-width="1.5" opacity="0.5"/>

        </svg>

        Cardo-Fer

      </a>

      <!-- Desktop Navigation -->

      <nav class="nav-links cardo-main-nav">

        <a href="{{ route('farmerhome') }}" data-i18n="navHome">

          Home

        </a>

        <a href="{{ route('farmer.field') }}">

          Add Field Details

        </a>

        <a href="{{ route('disease.identification') }}" data-i18n="navDisease">

          Disease Identification

        </a>

        <!-- Fertilizer -->

        <div class="cardo-nav-dropdown">

          <button

            type="button"

            class="cardo-nav-dropdown-btn"

            aria-haspopup="true"

          >

            Fertilizer

            <i class="fa-solid fa-chevron-down"></i>

          </button>

          <div class="cardo-nav-dropdown-menu">

            <a href="{{ route('farmer.fertilizer') }}">

              <i class="fa-solid fa-leaf"></i>

              <span>

                <strong>Fertilizer Management</strong>

                <small>View admin-approved fertilizers by disease</small>

              </span>

            </a>

            <a href="{{ route('farmer.fertilizer.reminder') }}">

              <i class="fa-solid fa-bell"></i>

              <span>

                <strong>Fertilizer Reminder</strong>

                <small>Track upcoming applications</small>

              </span>

            </a>

          </div>

        </div>

        <!-- Records -->

        <div class="cardo-nav-dropdown">

          <button

            type="button"

            class="cardo-nav-dropdown-btn"

            aria-haspopup="true"

            aria-expanded="false"

          >

            Records

            <i class="fa-solid fa-chevron-down"></i>

          </button>

          <div class="cardo-nav-dropdown-menu">

            <a href="{{ route('farmer.analysis-history') }}">

              <i class="fa-solid fa-clock-rotate-left"></i>

              <span>

                <strong>Disease Analysis History</strong>

                <small>Review your previous disease analyses</small>

              </span>

            </a>

          </div>

        </div>

        <a href="{{ route('farmer.weather') }}"

           class="nav-link {{ request()->routeIs('farmer.weather') ? 'active' : '' }}">

          <i class="fa-solid fa-cloud-sun"></i>

          <span>Weather Forecast</span>

        </a>

      </nav>

      <!-- Navigation Actions -->

      <div class="nav-actions">

        <div class="lang-switch">

          <select aria-label="Choose language">

            <option value="en">English</option>

            <option value="ml">മലയാളം</option>

            <option value="hi">हिन्दी</option>

          </select>

        </div> 

        <button class="theme-toggle" aria-label="Toggle dark mode"></button>

        <form method="POST" action="{{ route('logout') }}" style="display: inline;">

          @csrf

          <button type="submit" class="btn btn-primary">

            Log Out

          </button>

        </form>

        <button class="hamburger" aria-label="Open menu">

          <span></span>

          <span></span>

          <span></span>

        </button>

      </div>

    </div>

  </header>

  @yield('content')

  <!-- ============================ FOOTER ============================ -->

  <footer class="footer">

    <div class="container">

      <div class="footer-grid">

        <div class="footer-brand">

          <a href="{{ route('farmerhome') }}" class="brand">

            <svg class="pod-icon" viewBox="0 0 60 60" fill="none">

              <path d="M30 4C42 4 52 16 52 30C52 44 42 56 30 56C18 56 8 44 8 30C8 16 18 4 30 4Z"

                    stroke="currentColor" stroke-width="3"/>

            </svg>

            Cardo-Fer

          </a>

          <p>

            A smarter growing season for every cardamom estate

            in the Western Ghats.

          </p>

          <div class="footer-social">

            <a href="#" aria-label="Facebook">f</a>

            <a href="#" aria-label="Instagram">ig</a>

            <a href="#" aria-label="YouTube">yt</a>

          </div>

        </div>

        <div class="footer-col">

          <h4>Farmer</h4>

          <a href="{{ route('farmerhome') }}">

            Home

          </a>

          <a href="{{ route('farmer.field') }}">

            Add Field Details

          </a>

          <a href="{{ route('disease.identification') }}">

            Disease Identification

          </a>

        </div>

        <div class="footer-col">

          <h4>Farming Tools</h4>

          <a href="{{ route('farmer.fertilizer') }}">

            Fertilizer Management

          </a>

          <a href="{{ route('farmer.fertilizer.reminder') }}">

            Fertilizer Reminder

          </a>

          <a href="{{ route('farmer.analysis-history') }}">

            Disease Analysis History

          </a>

          <a href="#farm-reports">

            Farm Reports

          </a>

          {{-- Weather Forecast --}}

        </div>

        <div class="footer-col">

          <h4>Quick Actions</h4>

          <a href="{{ route('disease.identification') }}">

            Identify Disease

          </a>

          <a href="{{ route('farmer.field') }}">

            Manage Field

          </a>

          <a href="{{ route('farmer.fertilizer') }}">

            Manage Fertilizer

          </a>

        </div>

      </div>

      <div class="footer-bottom">

        <span>© <span class="js-year">2026</span> Cardo-Fer. All rights reserved.</span>

        <span>Built for the growers of Idukki, Wayanad &amp; Kodagu.</span>

      </div>

    </div>

  </footer>

  <div id="toast-container"></div>

  <script src="{{ asset('farmer/assets/js/main.js') }}"></script>

  <script src="{{ asset('farmer/assets/js/animations.js') }}"></script>

  @stack('scripts')

</body>

</html>