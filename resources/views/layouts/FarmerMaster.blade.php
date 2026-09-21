```html
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
</head>
<body>

  <!-- Loading Screen -->
  <div id="loading-screen">
    <div class="pod-loader">
      <svg viewBox="0 0 60 60" fill="none"><path d="M30 4C42 4 52 16 52 30C52 44 42 56 30 56C18 56 8 44 8 30C8 16 18 4 30 4Z" stroke="#B8CDB0" stroke-width="2.5"/><path d="M30 10V50M14 30H46" stroke="#B8CDB0" stroke-width="1.5" opacity="0.5"/></svg>
    </div>
    <p class="loading-text">Cardo-Fer</p>
  </div>

  <!-- Navbar -->
  <header class="navbar">
    <div class="container">
      <a href="index.html" class="brand">
        <svg class="pod-icon" viewBox="0 0 60 60" fill="none"><path d="M30 4C42 4 52 16 52 30C52 44 42 56 30 56C18 56 8 44 8 30C8 16 18 4 30 4Z" stroke="currentColor" stroke-width="3"/><path d="M30 10V50M14 30H46" stroke="currentColor" stroke-width="1.5" opacity="0.5"/></svg>
        Cardo-Fer
      </a>
      <nav class="nav-links">
        <a href="index.html" data-i18n="navHome">Home</a>
        <a href="dashboard.html" data-i18n="navDashboard">Dashboard</a>
          <a href="{{ route('farmer.field') }}">Add Field Details</a>
        <a href="{{ route('disease.identification') }}" data-i18n="navDisease">Disease Library</a>
        <a href="weather.html" data-i18n="navWeather">Weather</a>
        <a href="consultation.html" data-i18n="navConsult">Consultation</a>
        <a href="grants.html" data-i18n="navGrants">Grants</a>
        <a href="forum.html" data-i18n="navForum">Forum</a>
      </nav>
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
        <button class="hamburger" aria-label="Open menu"><span></span><span></span><span></span></button>
      </div>
    </div>
  </header>

@yield('content')

<!-- ============================ FOOTER ============================ -->
<footer class="footer">
    <div class="container">
      <div class="footer-grid">
        <div class="footer-brand">
          <a href="index.html" class="brand">
            <svg class="pod-icon" viewBox="0 0 60 60" fill="none"><path d="M30 4C42 4 52 16 52 30C52 44 42 56 30 56C18 56 8 44 8 30C8 16 18 4 30 4Z" stroke="currentColor" stroke-width="3"/></svg>
            Cardo-Fer
          </a>
          <p>A smarter growing season for every cardamom estate in the Western Ghats.</p>
          <div class="footer-social">
            <a href="#" aria-label="Facebook">f</a>
            <a href="#" aria-label="Instagram">ig</a>
            <a href="#" aria-label="YouTube">yt</a>
          </div>
        </div>
        <div class="footer-col">
          <h4>Platform</h4>
          <a href="dashboard.html">Dashboard</a>
          <a href="marketplace.html">Marketplace</a>
          <a href="weather.html">Weather Alerts</a>
          <a href="{{ route('disease.identification') }}">Disease Library</a>
        </div>
        <div class="footer-col">
          <h4>Support</h4>
          <a href="consultation.html">Consultation</a>
          <a href="grants.html">Government Grants</a>
          <a href="forum.html">Community Forum</a>
          <a href="contact.html">Contact Us</a>
        </div>
        <div class="footer-col">
          <h4>Company</h4>
          <a href="#">About</a>
          <a href="#">Careers</a>
          <a href="#">Press</a>
        </div>
        <div class="footer-col">
          <h4>Legal</h4>
          <a href="#">Privacy Policy</a>
          <a href="#">Terms of Service</a>
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
</body>
</html>






