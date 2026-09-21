/* ==========================================================================
   CARDO-FER — ANIMATIONS.JS
   Scroll-triggered reveal animations and a lightweight, dependency-free
   carousel used by the Success Stories section (and reusable elsewhere).
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {
  initScrollReveal();
  initCarousels();
  initLazyImages();
});

/* ---------------------------------------------------------------------- */
/* Scroll Reveal — fades/slides elements with .reveal or .reveal-fade in  */
/* ---------------------------------------------------------------------- */
function initScrollReveal(){
  const targets = document.querySelectorAll('.reveal, .reveal-fade');
  if(!targets.length) return;
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if(entry.isIntersecting){
        entry.target.classList.add('visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.15, rootMargin: '0px 0px -60px 0px' });
  targets.forEach((el, i) => {
    el.style.setProperty('--i', i % 8);
    observer.observe(el);
  });
}

/* ---------------------------------------------------------------------- */
/* Carousel — works on any [data-carousel] wrapper                        */
/* Expected structure:
   <div class="carousel" data-carousel>
     <div class="carousel-track"> .carousel-slide ... </div>
     <button class="carousel-arrow prev">‹</button>
     <button class="carousel-arrow next">›</button>
     <div class="carousel-nav"></div>
   </div>
   ---------------------------------------------------------------------- */
function initCarousels(){
  document.querySelectorAll('[data-carousel]').forEach(setupCarousel);
}
function setupCarousel(root){
  const track = root.querySelector('.carousel-track');
  const slides = Array.from(root.querySelectorAll('.carousel-slide'));
  const nav = root.querySelector('.carousel-nav');
  const prevBtn = root.querySelector('.carousel-arrow.prev');
  const nextBtn = root.querySelector('.carousel-arrow.next');
  if(!track || !slides.length) return;

  let index = 0;
  let autoplayTimer;

  if(nav){
    slides.forEach((_, i) => {
      const dot = document.createElement('button');
      dot.className = 'carousel-dot' + (i === 0 ? ' active' : '');
      dot.setAttribute('aria-label', `Go to slide ${i + 1}`);
      dot.addEventListener('click', () => goTo(i));
      nav.appendChild(dot);
    });
  }

  function goTo(i){
    index = (i + slides.length) % slides.length;
    track.style.transform = `translateX(-${index * 100}%)`;
    if(nav){
      nav.querySelectorAll('.carousel-dot').forEach((d, di) => d.classList.toggle('active', di === index));
    }
  }

  prevBtn && prevBtn.addEventListener('click', () => { goTo(index - 1); resetAutoplay(); });
  nextBtn && nextBtn.addEventListener('click', () => { goTo(index + 1); resetAutoplay(); });

  function resetAutoplay(){
    clearInterval(autoplayTimer);
    autoplayTimer = setInterval(() => goTo(index + 1), 6000);
  }
  resetAutoplay();

  // Pause on hover for accessibility / usability.
  root.addEventListener('mouseenter', () => clearInterval(autoplayTimer));
  root.addEventListener('mouseleave', resetAutoplay);
}

/* ---------------------------------------------------------------------- */
/* Lazy Image Loading — swaps data-src to src when near the viewport      */
/* ---------------------------------------------------------------------- */
function initLazyImages(){
  const lazyImgs = document.querySelectorAll('img[data-src]');
  if(!lazyImgs.length) return;
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if(entry.isIntersecting){
        const img = entry.target;
        img.src = img.getAttribute('data-src');
        img.removeAttribute('data-src');
        observer.unobserve(img);
      }
    });
  }, { rootMargin: '200px' });
  lazyImgs.forEach(img => observer.observe(img));
}
