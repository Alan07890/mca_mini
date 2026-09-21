<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cardo-Fer · Smart Farming for Cardamom Growers</title>
<meta name="description" content="Cardo-Fer brings disease guidance, fertilizer recommendations and field management to cardamom growers in the Western Ghats.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;1,9..144,500&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root{
    --bg-deep:#0a1d15;
    --bg-deep-2:#081812;
    --bg-panel:#0d2318;
    --card-fill:rgba(245,241,230,0.035);
    --card-fill-hover:rgba(245,241,230,0.06);
    --card-border:rgba(245,241,230,0.12);
    --cream:#f5f1e6;
    --cream-soft:rgba(245,241,230,0.72);
    --cream-faint:rgba(245,241,230,0.45);
    --sage:#9fbfa0;
    --sage-bright:#b7d2ac;
    --gold:#d5893f;
    --gold-hover:#c37631;
    --focus-ring:rgba(213,137,63,0.4);
    --line:rgba(245,241,230,0.14);
    --font-serif:'Fraunces', Georgia, serif;
    --font-sans:'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
  }
  *{margin:0;padding:0;box-sizing:border-box;}
  html{scroll-behavior:smooth;}
  body{font-family:var(--font-sans);background:var(--bg-deep);color:var(--cream);-webkit-font-smoothing:antialiased;overflow-x:hidden;}
  a{color:inherit;text-decoration:none;}
  ul{list-style:none;}
  ::selection{background:var(--gold);color:#1a1109;}
  :focus-visible{outline:2px solid var(--gold);outline-offset:3px;border-radius:4px;}
  img,svg{display:block;max-width:100%;}
  @media (prefers-reduced-motion: reduce){*{animation-duration:0.01ms !important;transition-duration:0.01ms !important;scroll-behavior:auto !important;}}

  .wrap{max-width:1240px;margin:0 auto;padding:0 40px;}
  section{position:relative;}
  .eyebrow{display:flex;align-items:center;gap:8px;font-size:0.72rem;letter-spacing:0.16em;text-transform:uppercase;color:var(--sage);margin-bottom:18px;}
  .eyebrow .dot{width:5px;height:5px;border-radius:50%;background:var(--gold);flex-shrink:0;}

  /* ---------- reveal ---------- */
  .reveal{opacity:0;transform:translateY(22px);transition:opacity .8s cubic-bezier(.16,1,.3,1), transform .8s cubic-bezier(.16,1,.3,1);}
  .reveal.in{opacity:1;transform:translateY(0);}

  /* ---------- navbar ---------- */
  .navbar{position:fixed;top:0;left:0;right:0;z-index:100;display:flex;align-items:center;justify-content:space-between;padding:20px 40px;border-bottom:1px solid transparent;transition:background .3s ease, border-color .3s ease;}
  .navbar.scrolled{background:rgba(10,29,21,0.92);backdrop-filter:blur(10px);border-bottom-color:var(--line);}
  .brand{display:flex;align-items:center;gap:10px;font-family:var(--font-serif);font-size:1.15rem;font-weight:500;}
  .brand-mark{width:30px;height:30px;border:1px solid var(--cream-faint);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
  .brand-mark svg{width:14px;height:14px;stroke:var(--sage-bright);}
  .nav-links{display:flex;align-items:center;gap:34px;font-size:0.88rem;color:var(--cream-soft);}
  .nav-links a{position:relative;padding-bottom:4px;transition:color .2s ease;}
  .nav-links a:hover{color:var(--cream);}
  .nav-links a.active{color:var(--cream);}
  .nav-links a.active::after{content:"";position:absolute;left:0;right:0;bottom:0;height:1px;background:var(--gold);}
  .nav-right{display:flex;align-items:center;gap:16px;}
  .lang-select{display:flex;align-items:center;gap:6px;font-size:0.8rem;color:var(--cream-soft);border:1px solid var(--card-border);padding:7px 12px;border-radius:999px;background:var(--card-fill);}
  .lang-select svg{width:11px;height:11px;}
  .admin-pill{display:flex;align-items:center;gap:6px;font-size:0.78rem;color:var(--cream-faint);border:1px solid var(--card-border);padding:8px 14px;border-radius:999px;transition:border-color .2s ease, color .2s ease;}
  .admin-pill:hover{border-color:var(--sage-bright);color:var(--sage-bright);}
  .admin-pill svg{width:14px;height:14px;stroke:currentColor;flex-shrink:0;}
  .nav-login{font-size:0.85rem;color:var(--cream-soft);transition:color .2s ease;}
  .nav-login:hover{color:var(--cream);}
  .nav-register{background:var(--cream);color:var(--bg-deep);font-size:0.85rem;font-weight:600;padding:9px 20px;border-radius:999px;transition:background .2s ease, transform .15s ease;}
  .nav-register:hover{background:var(--sage-bright);}
  .hamburger{display:none;width:24px;height:18px;position:relative;cursor:pointer;background:none;border:none;}
  .hamburger span{position:absolute;left:0;right:0;height:1px;background:var(--cream);transition:transform .25s ease, opacity .25s ease;}
  .hamburger span:nth-child(1){top:0;}
  .hamburger span:nth-child(2){top:8px;}
  .hamburger span:nth-child(3){top:16px;}
  .hamburger.open span:nth-child(1){transform:translateY(8px) rotate(45deg);}
  .hamburger.open span:nth-child(2){opacity:0;}
  .hamburger.open span:nth-child(3){transform:translateY(-8px) rotate(-45deg);}
  .mobile-menu{display:none;position:fixed;inset:0;background:var(--bg-deep-2);z-index:99;padding:100px 40px 40px;flex-direction:column;gap:26px;font-size:1.3rem;font-family:var(--font-serif);transform:translateX(100%);transition:transform .35s cubic-bezier(.16,1,.3,1);}
  .mobile-menu.open{transform:translateX(0);}

  /* ---------- hero ---------- */
  .hero{min-height:100vh;display:flex;flex-direction:column;justify-content:center;padding:120px 0 60px;overflow:hidden;}
  .hero-decor{position:absolute;inset:0;pointer-events:none;z-index:0;}
  .hero-circle{position:absolute;border:1px solid rgba(245,241,230,0.14);border-radius:50%;}
  .hc1{width:120px;height:120px;top:22%;right:10%;}
  .hc2{width:56px;height:56px;top:64%;right:20%;border-color:rgba(183,210,172,0.2);}
  .hero-pod{position:absolute;right:-4%;top:8%;width:420px;opacity:0.35;}
  .hero-content{position:relative;z-index:1;max-width:660px;}
  .hero-heading{font-family:var(--font-serif);font-size:clamp(2.6rem, 5.4vw, 4.4rem);line-height:1.1;font-weight:500;color:var(--cream);margin-bottom:26px;}
  .hero-heading em{font-style:italic;color:var(--sage-bright);font-weight:500;}
  .hero-sub{font-size:1.05rem;line-height:1.7;color:var(--cream-soft);max-width:520px;margin-bottom:38px;}
  .hero-cta{display:flex;align-items:center;gap:16px;flex-wrap:wrap;}
  .btn-primary{background:var(--gold);color:#1a1109;font-size:0.92rem;font-weight:600;padding:15px 28px;border-radius:999px;transition:background .2s ease, transform .15s ease;display:inline-block;}
  .btn-primary:hover{background:var(--gold-hover);}
  .btn-secondary{border:1px solid var(--cream-faint);color:var(--cream);font-size:0.92rem;font-weight:500;padding:14px 26px;border-radius:999px;transition:border-color .2s ease, background .2s ease;display:inline-block;}
  .btn-secondary:hover{border-color:var(--cream-soft);background:rgba(245,241,230,0.05);}
  .scroll-indicator{position:absolute;bottom:36px;left:40px;display:flex;flex-direction:column;align-items:center;gap:10px;font-size:0.68rem;letter-spacing:0.14em;color:var(--cream-faint);}
  .scroll-line{width:1px;height:34px;background:linear-gradient(var(--cream-faint), transparent);}

  /* ---------- about ---------- */
  .about{padding:120px 0;border-top:1px solid var(--line);}
  .about-grid{display:grid;grid-template-columns:1fr 1.15fr;gap:80px;align-items:start;}
  .about-heading{font-family:var(--font-serif);font-size:2.4rem;line-height:1.2;color:var(--cream);}
  .about-heading em{font-style:italic;color:var(--sage-bright);}
  .about-body p{font-size:1rem;line-height:1.8;color:var(--cream-soft);margin-bottom:20px;}
  .about-tags{display:flex;flex-wrap:wrap;gap:10px;margin-top:24px;}
  .about-tag{font-size:0.78rem;color:var(--sage-bright);border:1px solid rgba(183,210,172,0.3);padding:7px 14px;border-radius:999px;}

  /* ---------- features ---------- */
  .features{padding:120px 0;border-top:1px solid var(--line);}
  .section-head{max-width:560px;margin-bottom:64px;}
  .section-title{font-family:var(--font-serif);font-size:2.4rem;line-height:1.2;color:var(--cream);}
  .section-title em{font-style:italic;color:var(--sage-bright);}
  .feature-grid{display:grid;grid-template-columns:repeat(3, 1fr);gap:1px;background:var(--line);border:1px solid var(--line);border-radius:16px;overflow:hidden;}
  .feature-card{background:var(--bg-deep);padding:38px 32px;transition:background .3s ease;}
  .feature-card:hover{background:var(--card-fill-hover);}
  .feature-num{font-family:var(--font-serif);font-size:0.85rem;color:var(--sage);margin-bottom:24px;}
  .feature-icon{width:40px;height:40px;border:1px solid var(--card-border);border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:20px;transition:border-color .3s ease;}
  .feature-card:hover .feature-icon{border-color:var(--sage-bright);}
  .feature-icon svg{width:19px;height:19px;stroke:var(--sage-bright);}
  .feature-title{font-family:var(--font-serif);font-size:1.2rem;color:var(--cream);margin-bottom:10px;}
  .feature-desc{font-size:0.9rem;line-height:1.65;color:var(--cream-soft);}

  /* ---------- how it works ---------- */
  .how{padding:120px 0;border-top:1px solid var(--line);}
  .how-steps{display:grid;grid-template-columns:repeat(4, 1fr);gap:32px;position:relative;margin-top:20px;}
  .how-steps::before{content:"";position:absolute;top:19px;left:8%;right:8%;height:1px;background:var(--line);z-index:0;}
  .how-step{position:relative;z-index:1;}
  .how-num{width:40px;height:40px;border:1px solid var(--card-border);background:var(--bg-deep);border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:var(--font-serif);font-size:0.85rem;color:var(--sage-bright);margin-bottom:22px;}
  .how-title{font-size:1.02rem;font-weight:500;color:var(--cream);margin-bottom:8px;}
  .how-desc{font-size:0.86rem;line-height:1.6;color:var(--cream-soft);}

  /* ---------- why ---------- */
  .why{padding:120px 0;border-top:1px solid var(--line);}
  .why-grid{display:grid;grid-template-columns:repeat(3, 1fr);gap:22px;margin-top:20px;}
  .why-card{border:1px solid var(--card-border);background:var(--card-fill);border-radius:14px;padding:28px 26px;transition:border-color .25s ease, background .25s ease;}
  .why-card:hover{border-color:rgba(183,210,172,0.35);background:var(--card-fill-hover);}
  .why-title{font-size:1rem;font-weight:500;color:var(--cream);margin-bottom:10px;}
  .why-desc{font-size:0.86rem;line-height:1.6;color:var(--cream-soft);}

  /* ---------- platform preview ---------- */
  .preview{padding:120px 0;border-top:1px solid var(--line);}
  .preview-grid{display:grid;grid-template-columns:repeat(2, 1fr);gap:22px;margin-top:20px;}
  .preview-card{border:1px solid var(--card-border);background:linear-gradient(160deg, rgba(245,241,230,0.04), rgba(245,241,230,0.01));border-radius:16px;overflow:hidden;transition:transform .3s ease, border-color .3s ease;}
  .preview-card:hover{transform:translateY(-4px);border-color:rgba(183,210,172,0.3);}
  .preview-top{padding:22px 24px;border-bottom:1px solid var(--line);display:flex;align-items:center;justify-content:space-between;}
  .preview-tag{font-size:0.72rem;letter-spacing:0.08em;text-transform:uppercase;color:var(--sage);}
  .preview-dots{display:flex;gap:5px;}
  .preview-dots span{width:6px;height:6px;border-radius:50%;background:var(--card-border);}
  .preview-body{padding:26px 24px 30px;}
  .preview-title{font-family:var(--font-serif);font-size:1.25rem;color:var(--cream);margin-bottom:10px;}
  .preview-desc{font-size:0.86rem;line-height:1.6;color:var(--cream-soft);margin-bottom:20px;}
  .preview-bar{height:7px;border-radius:4px;background:rgba(245,241,230,0.08);overflow:hidden;margin-bottom:8px;}
  .preview-bar span{display:block;height:100%;background:var(--sage-bright);border-radius:4px;}
  .preview-meta{display:flex;justify-content:space-between;font-size:0.74rem;color:var(--cream-faint);}

  /* ---------- cta ---------- */
  .cta{padding:130px 0;border-top:1px solid var(--line);text-align:center;position:relative;overflow:hidden;}
  .cta-circle{position:absolute;width:340px;height:340px;border:1px solid rgba(245,241,230,0.08);border-radius:50%;top:-100px;left:50%;transform:translateX(-50%);}
  .cta-heading{font-family:var(--font-serif);font-size:clamp(2.2rem, 4vw, 3.2rem);color:var(--cream);margin-bottom:20px;position:relative;}
  .cta-sub{font-size:1rem;line-height:1.7;color:var(--cream-soft);max-width:520px;margin:0 auto 38px;position:relative;}
  .cta-actions{display:flex;align-items:center;justify-content:center;gap:16px;flex-wrap:wrap;position:relative;}

  /* ---------- footer ---------- */
  footer{background:var(--bg-deep-2);border-top:1px solid var(--line);padding:70px 0 30px;}
  .footer-grid{display:grid;grid-template-columns:1.4fr 1fr 1fr 1fr;gap:40px;margin-bottom:50px;}
  .footer-brand{display:flex;align-items:center;gap:10px;font-family:var(--font-serif);font-size:1.1rem;margin-bottom:16px;}
  .footer-desc{font-size:0.88rem;line-height:1.6;color:var(--cream-soft);max-width:260px;}
  .footer-col-title{font-size:0.76rem;letter-spacing:0.1em;text-transform:uppercase;color:var(--sage);margin-bottom:18px;}
  .footer-col a{display:block;font-size:0.88rem;color:var(--cream-soft);margin-bottom:12px;transition:color .2s ease;}
  .footer-col a:hover{color:var(--cream);}
  .footer-bottom{border-top:1px solid var(--line);padding-top:26px;display:flex;justify-content:space-between;align-items:center;font-size:0.78rem;color:var(--cream-faint);flex-wrap:wrap;gap:12px;}

  /* ---------- responsive ---------- */
  @media (max-width: 980px){
    .wrap{padding:0 24px;}
    .nav-links{display:none;}
    .hamburger{display:block;}
    .mobile-menu{display:flex;}
    .about-grid{grid-template-columns:1fr;gap:40px;}
    .feature-grid{grid-template-columns:1fr;}
    .how-steps{grid-template-columns:1fr 1fr;gap:36px;}
    .how-steps::before{display:none;}
    .why-grid{grid-template-columns:1fr;}
    .preview-grid{grid-template-columns:1fr;}
    .footer-grid{grid-template-columns:1fr 1fr;gap:32px;}
    .hero-pod{display:none;}
  }
  @media (max-width: 560px){
    .navbar{padding:16px 20px;}
    .nav-right .lang-select, .nav-right .admin-pill{display:none;}
    .hero{padding:110px 0 50px;}
    .about, .features, .how, .why, .preview, .cta{padding:80px 0;}
    .how-steps{grid-template-columns:1fr;}
    .footer-grid{grid-template-columns:1fr;}
    .cta-actions{flex-direction:column;width:100%;}
    .cta-actions a{width:100%;text-align:center;}
  }
</style>
</head>
<body>

  <!-- ============ NAVBAR ============ -->
  <nav class="navbar" id="navbar">
    <a href="{{ url('/') }}" class="brand">
      <span class="brand-mark">
        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M12 2C12 2 6 7 6 13C6 17 8.5 20 12 20C15.5 20 18 17 18 13C18 7 12 2 12 2Z" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </span>
      Cardo-Fer
    </a>

    <div class="nav-links">
      <a href="#home" class="active">Home</a>
      <a href="#about">About</a>
      <a href="#features">Features</a>
      <a href="#how">How It Works</a>
      <a href="#contact">Contact</a>
    </div>

    <div class="nav-right">
      <div class="lang-select">
        English
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
      </div>
     <a href="{{ route('login') }}" class="admin-pill">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="8" r="3.2"/>
          <path d="M5.5 20c0-3.6 2.9-6 6.5-6s6.5 2.4 6.5 6"/>
          <path d="M12 11v6M9.3 14.2c0 1 1.2 1.8 2.7 1.8s2.7-.8 2.7-1.8"/>
        </svg>
        Admin
      </a>
      <a href="{{ route('login') }}" class="nav-login">Login</a>
      <a href="{{ route('register') }}" class="nav-register">Register</a>
      <button class="hamburger" id="hamburger" aria-label="Open menu">
        <span></span><span></span><span></span>
      </button>
    </div>
  </nav>

  <div class="mobile-menu" id="mobileMenu">
    <a href="#home">Home</a>
    <a href="#about">About</a>
    <a href="#features">Features</a>
    <a href="#how">How It Works</a>
    <a href="#contact">Contact</a>
    <a href="{{ route('login') }}">Login</a>
    <a href="{{ route('register') }}">Register</a>
    <a href="{{ route('login') }}" style="font-size:1rem;color:var(--cream-faint);"> Admin Login </a>
  </div>

  <!-- ============ HERO ============ -->
  <section class="hero" id="home">
    <div class="hero-decor">
      <span class="hero-circle hc1"></span>
      <span class="hero-circle hc2"></span>
      <svg class="hero-pod" viewBox="0 0 400 500" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M200 60C160 100 140 160 145 220C148 260 165 300 200 320C235 300 252 260 255 220C260 160 240 100 200 60Z" stroke="rgba(183,210,172,0.4)" stroke-width="1.2"/>
        <path d="M200 65C185 130 182 250 200 315" stroke="rgba(183,210,172,0.22)" stroke-width="1"/>
        <path d="M200 65C215 130 218 250 200 315" stroke="rgba(183,210,172,0.22)" stroke-width="1"/>
        <path d="M200 320C200 350 195 375 178 395" stroke="rgba(183,210,172,0.35)" stroke-width="1.2" stroke-linecap="round"/>
        <path d="M178 395C150 388 128 400 112 425C140 428 165 420 178 395Z" stroke="rgba(183,210,172,0.3)" stroke-width="1.1"/>
        <path d="M178 395C200 410 208 435 202 462C178 452 165 425 178 395Z" stroke="rgba(183,210,172,0.3)" stroke-width="1.1"/>
      </svg>
    </div>

    <div class="wrap hero-content">
      <div class="eyebrow"><span class="dot"></span> WESTERN GHATS · IDUKKI · WAYANAD</div>
      <h1 class="hero-heading">Grow the world's finest <em>cardamom</em>, smarter.</h1>
      <p class="hero-sub">Cardo-Fer brings AI-assisted disease insights, fertilizer guidance, smart field management and farming intelligence to cardamom growers.</p>
      <div class="hero-cta">
        <a href="{{ route('register') }}" class="btn-primary">Get Started</a>
        <a href="#features" class="btn-secondary">Explore Features</a>
      </div>
    </div>

    <div class="scroll-indicator"><span class="scroll-line"></span>SCROLL</div>
  </section>

  <!-- ============ ABOUT ============ -->
  <section class="about reveal" id="about">
    <div class="wrap about-grid">
      <div>
        <div class="eyebrow"><span class="dot"></span> ABOUT CARDO-FER</div>
        <h2 class="about-heading">Smart farming,<br>built for <em>cardamom.</em></h2>
      </div>
      <div class="about-body">
        <p>Cardo-Fer is a web-based smart farming platform designed specifically for cardamom cultivation in the Western Ghats. Rather than generic agricultural advice, every part of the platform is built around the way cardamom is actually grown.</p>
        <p>Farmers select the symptoms they observe in their crop and receive guidance on likely diseases and prevention measures, along with fertilizer recommendations suited to their field. Field details, fertilizer schedules and application history stay organized in one place, supported by AI-assisted search for farming questions.</p>
        <div class="about-tags">
          <span class="about-tag">Symptom-based guidance</span>
          <span class="about-tag">Fertilizer planning</span>
          <span class="about-tag">Field records</span>
          <span class="about-tag">AI-assisted search</span>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ FEATURES ============ -->
  <section class="features reveal" id="features">
    <div class="wrap">
      <div class="section-head">
        <div class="eyebrow"><span class="dot"></span> FEATURES</div>
        <h2 class="section-title">Everything your <em>farm</em> needs, organized.</h2>
      </div>

      <div class="feature-grid">
        <div class="feature-card">
          <div class="feature-num">01</div>
          <div class="feature-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2C12 2 6 8 6 14a6 6 0 0012 0c0-6-6-12-6-12Z"/><path d="M9 15c0 2 1.5 3 3 3"/></svg></div>
          <div class="feature-title">Disease Identification</div>
          <div class="feature-desc">Select the symptoms you observe in your crop and get guidance on the likely disease affecting your plants.</div>
        </div>
        <div class="feature-card">
          <div class="feature-num">02</div>
          <div class="feature-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l7 3v6c0 5-3 8-7 9-4-1-7-4-7-9V6l7-3Z"/><path d="M9 12l2 2 4-4"/></svg></div>
          <div class="feature-title">Disease Prevention</div>
          <div class="feature-desc">Access preventive measures and management information tailored to identified diseases.</div>
        </div>
        <div class="feature-card">
          <div class="feature-num">03</div>
          <div class="feature-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><path d="M4 21V10l8-6 8 6v11"/><path d="M9 21v-7h6v7"/></svg></div>
          <div class="feature-title">Fertilizer Recommendation</div>
          <div class="feature-desc">Get fertilizer suggestions suited to your field and cultivation details.</div>
        </div>
        <div class="feature-card">
          <div class="feature-num">04</div>
          <div class="feature-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg></div>
          <div class="feature-title">Field Management</div>
          <div class="feature-desc">Enter your field area and plant count, and keep field information organized in one place.</div>
        </div>
        <div class="feature-card">
          <div class="feature-num">05</div>
          <div class="feature-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></div>
          <div class="feature-title">Fertilizer Scheduling &amp; History</div>
          <div class="feature-desc">Track applications, upcoming schedules, validity periods and past fertilizer history.</div>
        </div>
        <div class="feature-card">
          <div class="feature-num">06</div>
          <div class="feature-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg></div>
          <div class="feature-title">AI Search &amp; Recommendation</div>
          <div class="feature-desc">Ask farming questions and get AI-assisted recommendations relevant to cardamom cultivation.</div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ HOW IT WORKS ============ -->
  <section class="how reveal" id="how">
    <div class="wrap">
      <div class="section-head">
        <div class="eyebrow"><span class="dot"></span> HOW IT WORKS</div>
        <h2 class="section-title">From sign-up to <em>insight.</em></h2>
      </div>

      <div class="how-steps">
        <div class="how-step">
          <div class="how-num">01</div>
          <div class="how-title">Create your account</div>
          <div class="how-desc">Register as a farmer to get access to the platform.</div>
        </div>
        <div class="how-step">
          <div class="how-num">02</div>
          <div class="how-title">Add your farm information</div>
          <div class="how-desc">Enter your field area, plant count and cultivation details.</div>
        </div>
        <div class="how-step">
          <div class="how-num">03</div>
          <div class="how-title">Explore guidance</div>
          <div class="how-desc">Check disease and fertilizer guidance suited to your field.</div>
        </div>
        <div class="how-step">
          <div class="how-num">04</div>
          <div class="how-title">Manage and track</div>
          <div class="how-desc">Keep your farm records and fertilizer schedule up to date.</div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ WHY CARDO-FER ============ -->
  <section class="why reveal">
    <div class="wrap">
      <div class="section-head">
        <div class="eyebrow"><span class="dot"></span> WHY CARDO-FER</div>
        <h2 class="section-title">Built around how you <em>actually</em> farm.</h2>
      </div>
      <div class="why-grid">
        <div class="why-card">
          <div class="why-title">Farmer-friendly interface</div>
          <div class="why-desc">Straightforward screens designed for everyday use in the field, not a technical dashboard.</div>
        </div>
        <div class="why-card">
          <div class="why-title">Organized farming information</div>
          <div class="why-desc">Field details, disease guidance and fertilizer records kept in one accessible place.</div>
        </div>
        <div class="why-card">
          <div class="why-title">Smarter fertilizer planning</div>
          <div class="why-desc">Recommendations and scheduling built around your specific field and crop.</div>
        </div>
        <div class="why-card">
          <div class="why-title">Disease-focused guidance</div>
          <div class="why-desc">Symptom-based identification with prevention and management guidance.</div>
        </div>
        <div class="why-card">
          <div class="why-title">Farm record management</div>
          <div class="why-desc">Track fertilizer history and application timing without losing paper records.</div>
        </div>
        <div class="why-card">
          <div class="why-title">AI-assisted agricultural search</div>
          <div class="why-desc">Ask questions about cardamom cultivation and get relevant, guided answers.</div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ PLATFORM PREVIEW ============ -->
  <section class="preview reveal">
    <div class="wrap">
      <div class="section-head">
        <div class="eyebrow"><span class="dot"></span> INSIDE CARDO-FER</div>
        <h2 class="section-title">A closer look at the <em>platform.</em></h2>
      </div>

      <div class="preview-grid">
        <div class="preview-card">
          <div class="preview-top"><span class="preview-tag">Disease Identification</span><span class="preview-dots"><span></span><span></span><span></span></span></div>
          <div class="preview-body">
            <div class="preview-title">Symptom check</div>
            <div class="preview-desc">Select observed symptoms to view likely diseases and prevention steps.</div>
            <div class="preview-bar"><span style="width:70%;"></span></div>
            <div class="preview-meta"><span>Capsule rot</span><span>Match: High</span></div>
          </div>
        </div>
        <div class="preview-card">
          <div class="preview-top"><span class="preview-tag">Fertilizer Recommendation</span><span class="preview-dots"><span></span><span></span><span></span></span></div>
          <div class="preview-body">
            <div class="preview-title">Recommended plan</div>
            <div class="preview-desc">Fertilizer suggestions based on your field size and crop stage.</div>
            <div class="preview-bar"><span style="width:55%;"></span></div>
            <div class="preview-meta"><span>NPK 10:5:20</span><span>Next: 12 days</span></div>
          </div>
        </div>
        <div class="preview-card">
          <div class="preview-top"><span class="preview-tag">Field Management</span><span class="preview-dots"><span></span><span></span><span></span></span></div>
          <div class="preview-body">
            <div class="preview-title">Field overview</div>
            <div class="preview-desc">Field area and plant count kept organized and easy to update.</div>
            <div class="preview-bar"><span style="width:85%;"></span></div>
            <div class="preview-meta"><span>2.4 acres</span><span>1,150 plants</span></div>
          </div>
        </div>
        <div class="preview-card">
          <div class="preview-top"><span class="preview-tag">Fertilizer Schedule</span><span class="preview-dots"><span></span><span></span><span></span></span></div>
          <div class="preview-body">
            <div class="preview-title">Application history</div>
            <div class="preview-desc">Past applications and upcoming schedule tracked automatically.</div>
            <div class="preview-bar"><span style="width:40%;"></span></div>
            <div class="preview-meta"><span>Last: 18 days ago</span><span>Valid: 30 days</span></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ CTA ============ -->
  <section class="cta reveal" id="contact">
    <span class="cta-circle"></span>
    <div class="wrap">
      <h2 class="cta-heading">Ready to farm smarter?</h2>
      <p class="cta-sub">Create your Cardo-Fer account and bring your cardamom farm information, disease guidance and fertilizer planning into one place.</p>
      <div class="cta-actions">
        <a href="{{ route('register') }}" class="btn-primary">Create Your Account</a>
        <a href="{{ route('login') }}" class="btn-secondary">Sign In</a>
      </div>
    </div>
  </section>

  <!-- ============ FOOTER ============ -->
  <footer>
    <div class="wrap">
      <div class="footer-grid">
        <div>
          <div class="footer-brand">
            <span class="brand-mark">
              <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2C12 2 6 7 6 13C6 17 8.5 20 12 20C15.5 20 18 17 18 13C18 7 12 2 12 2Z" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </span>
            Cardo-Fer
          </div>
          <div class="footer-desc">Smart farming intelligence for cardamom growers.</div>
        </div>
        <div class="footer-col">
          <div class="footer-col-title">Navigate</div>
          <a href="#home">Home</a>
          <a href="#about">About</a>
          <a href="#features">Features</a>
          <a href="#how">How It Works</a>
          <a href="#contact">Contact</a>
        </div>
        <div class="footer-col">
          <div class="footer-col-title">Account</div>
          <a href="{{ route('login') }}">Login</a>
          <a href="{{ route('register') }}">Register</a>
        </div>
        <div class="footer-col">
          <div class="footer-col-title">Admin</div>
          <a href="{{ route('login') }}">Admin Login</a>
        </div>
      </div>
      <div class="footer-bottom">
        <span>&copy; 2026 Cardo-Fer. All rights reserved.</span>
        <span>Western Ghats · Idukki · Wayanad</span>
      </div>
    </div>
  </footer>

  <script>
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
      navbar.classList.toggle('scrolled', window.scrollY > 20);
    });

    const hamburger = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobileMenu');
    hamburger.addEventListener('click', () => {
      hamburger.classList.toggle('open');
      mobileMenu.classList.toggle('open');
    });
    mobileMenu.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        hamburger.classList.remove('open');
        mobileMenu.classList.remove('open');
      });
    });

    const revealEls = document.querySelectorAll('.reveal');
    const io = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('in');
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15 });
    revealEls.forEach(el => io.observe(el));
  </script>

</body>
</html>
