/* ==========================================================================
   CARDO-FER — MAIN.JS
   Site-wide behaviour shared across every page: loading screen, sticky
   navbar, dark/light mode, mobile menu, toast notifications, animated
   counters, language switcher and generic form validation.
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {
  initLoadingScreen();
  initNavbar();
  initThemeToggle();
  initMobileMenu();
  initLanguageSwitcher();
  initCounters();
  initFormValidation();
  initFooterYear();
});

/* ---------------------------------------------------------------------- */
/* Loading Screen — hides once the window has finished loading assets     */
/* ---------------------------------------------------------------------- */
function initLoadingScreen(){
  const screen = document.getElementById('loading-screen');
  if(!screen) return;
  const hide = () => screen.classList.add('hidden');
  // Give the loader a minimum visible time so it never just "flashes".
  window.addEventListener('load', () => setTimeout(hide, 500));
  setTimeout(hide, 2200); // safety fallback
}

/* ---------------------------------------------------------------------- */
/* Sticky Navbar — adds a shadow once the page is scrolled                */
/* ---------------------------------------------------------------------- */
function initNavbar(){
  const nav = document.querySelector('.navbar');
  if(!nav) return;
  const onScroll = () => nav.classList.toggle('scrolled', window.scrollY > 12);
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });

  // Highlight the current page in the nav links.
  const path = location.pathname.split('/').pop() || 'index.html';
  document.querySelectorAll('.nav-links a, .mobile-menu a').forEach(link => {
    const href = link.getAttribute('href');
    if(href === path) link.classList.add('active');
  });
}

/* ---------------------------------------------------------------------- */
/* Dark / Light Mode — persisted in localStorage                          */
/* ---------------------------------------------------------------------- */
function initThemeToggle(){
  const root = document.documentElement;
  const saved = localStorage.getItem('cardofer-theme');
  const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
  const theme = saved || (prefersDark ? 'dark' : 'light');
  root.setAttribute('data-theme', theme);

  document.querySelectorAll('.theme-toggle').forEach(btn => {
    updateToggleIcon(btn, theme);
    btn.addEventListener('click', () => {
      const next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
      root.setAttribute('data-theme', next);
      localStorage.setItem('cardofer-theme', next);
      document.querySelectorAll('.theme-toggle').forEach(b => updateToggleIcon(b, next));
    });
  });
}
function updateToggleIcon(btn, theme){
  btn.innerHTML = theme === 'dark'
    ? '<svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="4.5"/><path d="M12 2v2M12 20v2M4.2 4.2l1.4 1.4M18.4 18.4l1.4 1.4M2 12h2M20 12h2M4.2 19.8l1.4-1.4M18.4 5.6l1.4-1.4"/></svg>'
    : '<svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"><path d="M20 14.5A8.5 8.5 0 1 1 9.5 4a7 7 0 0 0 10.5 10.5Z"/></svg>';
}

/* ---------------------------------------------------------------------- */
/* Mobile Menu                                                            */
/* ---------------------------------------------------------------------- */
function initMobileMenu(){
  const burger = document.querySelector('.hamburger');
  const menu = document.querySelector('.mobile-menu');
  if(!burger || !menu) return;
  burger.addEventListener('click', () => {
    burger.classList.toggle('open');
    menu.classList.toggle('open');
    document.body.style.overflow = menu.classList.contains('open') ? 'hidden' : '';
  });
  menu.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
    burger.classList.remove('open');
    menu.classList.remove('open');
    document.body.style.overflow = '';
  }));
}

/* ---------------------------------------------------------------------- */
/* Language Switcher — swaps text of elements carrying [data-i18n]        */
/* A tiny in-memory dictionary is enough to demonstrate real switching    */
/* without pulling in an external i18n library.                          */
/* ---------------------------------------------------------------------- */
const I18N = {
  en: {
    navHome: 'Home', navDashboard: 'Dashboard', navMarket: 'Marketplace',
    navDisease: 'Disease Library', navWeather: 'Weather', navConsult: 'Consultation',
    navGrants: 'Grants', navForum: 'Forum', navContact: 'Contact',
    heroTitle: 'Grow the world\u2019s finest <em>cardamom</em>, smarter.',
    heroSubtitle: 'Cardo-Fer brings AI diagnostics, live market prices and weather intelligence to every cardamom farmer in the Western Ghats.'
  },
  ml: {
    navHome: 'ഹോം', navDashboard: 'ഡാഷ്ബോർഡ്', navMarket: 'മാർക്കറ്റ്',
    navDisease: 'രോഗ വിവരണം', navWeather: 'കാലാവസ്ഥ', navConsult: 'കൺസൾട്ടേഷൻ',
    navGrants: 'ഗ്രാന്റുകൾ', navForum: 'ഫോറം', navContact: 'ബന്ധപ്പെടുക',
    heroTitle: 'ലോകത്തിലെ ഏറ്റവും മികച്ച <em>ഏലം</em> കൂടുതൽ മിടുക്കോടെ വളർത്തുക.',
    heroSubtitle: 'AI രോഗനിർണയം, തത്സമയ വിപണി വിലകൾ, കാലാവസ്ഥാ വിവരങ്ങൾ Cardo-Fer എല്ലാ ഏലം കർഷകർക്കും നൽകുന്നു.'
  },
  hi: {
    navHome: 'होम', navDashboard: 'डैशबोर्ड', navMarket: 'बाज़ार',
    navDisease: 'रोग सूची', navWeather: 'मौसम', navConsult: 'परामर्श',
    navGrants: 'अनुदान', navForum: 'फोरम', navContact: 'संपर्क करें',
    heroTitle: 'दुनिया की बेहतरीन <em>इलायची</em> उगाएं, समझदारी से।',
    heroSubtitle: 'Cardo-Fer हर इलायची किसान तक एआई निदान, बाज़ार भाव और मौसम जानकारी पहुंचाता है।'
  }
};
function initLanguageSwitcher(){
  const select = document.querySelector('.lang-switch select');
  if(!select) return;
  const saved = localStorage.getItem('cardofer-lang') || 'en';
  select.value = saved;
  applyLanguage(saved);
  select.addEventListener('change', () => {
    localStorage.setItem('cardofer-lang', select.value);
    applyLanguage(select.value);
    showToast('Language updated', `Interface switched to ${select.options[select.selectedIndex].text}.`, 'info');
  });
}
function applyLanguage(lang){
  const dict = I18N[lang] || I18N.en;
  document.querySelectorAll('[data-i18n]').forEach(el => {
    const key = el.getAttribute('data-i18n');
    if(dict[key]) el.innerHTML = dict[key];
  });
}

/* ---------------------------------------------------------------------- */
/* Animated Counters — counts up when scrolled into view                  */
/* ---------------------------------------------------------------------- */
function initCounters(){
  const counters = document.querySelectorAll('[data-counter]');
  if(!counters.length) return;
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if(entry.isIntersecting){
        animateCounter(entry.target);
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.4 });
  counters.forEach(c => observer.observe(c));
}
function animateCounter(el){
  const target = parseFloat(el.getAttribute('data-counter'));
  const suffix = el.getAttribute('data-suffix') || '';
  const duration = 1600;
  const start = performance.now();
  function tick(now){
    const progress = Math.min((now - start) / duration, 1);
    const eased = 1 - Math.pow(1 - progress, 3); // ease-out cubic
    const value = target * eased;
    el.textContent = (Number.isInteger(target) ? Math.round(value) : value.toFixed(1)) + suffix;
    if(progress < 1) requestAnimationFrame(tick);
  }
  requestAnimationFrame(tick);
}

/* ---------------------------------------------------------------------- */
/* Toast Notification System                                              */
/* ---------------------------------------------------------------------- */
function showToast(title, message, type = 'success'){
  let container = document.getElementById('toast-container');
  if(!container){
    container = document.createElement('div');
    container.id = 'toast-container';
    document.body.appendChild(container);
  }
  const toast = document.createElement('div');
  toast.className = `toast ${type}`;
  const icon = type === 'success' ? '✓' : type === 'info' ? 'ℹ' : '!';
  toast.innerHTML = `
    <div class="toast-icon">${icon}</div>
    <div><strong>${title}</strong><p>${message}</p></div>
  `;
  container.appendChild(toast);
  requestAnimationFrame(() => toast.classList.add('show'));
  setTimeout(() => {
    toast.classList.remove('show');
    setTimeout(() => toast.remove(), 400);
  }, 4200);
}
window.showToast = showToast;

/* ---------------------------------------------------------------------- */
/* Generic Form Validation — applies to any <form data-validate>          */
/* ---------------------------------------------------------------------- */
function initFormValidation(){
  document.querySelectorAll('form[data-validate]').forEach(form => {
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      let valid = true;
      form.querySelectorAll('[required]').forEach(field => {
        const group = field.closest('.form-group') || field.parentElement;
        const isEmail = field.type === 'email';
        const isValidEmail = !isEmail || /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value);
        const filled = field.value.trim().length > 0;
        if(!filled || !isValidEmail){
          group.classList.add('invalid');
          valid = false;
        } else {
          group.classList.remove('invalid');
        }
      });
      if(valid){
        const successMsg = form.querySelector('.form-success-msg');
        if(successMsg){
          successMsg.classList.add('show');
          form.reset();
          setTimeout(() => successMsg.classList.remove('show'), 5000);
        }
        showToast('Message sent', 'We\u2019ll get back to you within one business day.', 'success');
      } else {
        showToast('Check the form', 'Some required fields need your attention.', 'info');
      }
    });
  });
}

/* ---------------------------------------------------------------------- */
/* Footer year auto-fill                                                  */
/* ---------------------------------------------------------------------- */
function initFooterYear(){
  document.querySelectorAll('.js-year').forEach(el => el.textContent = new Date().getFullYear());
}
