/* ==========================================================================
   CARDO-FER — MARKETPLACE.JS
   Product catalogue, live search, category filtering, and a fully working
   shopping cart drawer persisted to localStorage.
   ========================================================================== */

/* ---------------------------------------------------------------------- */
/* Product Catalogue (would normally come from an API)                    */
/* ---------------------------------------------------------------------- */
const PRODUCTS = [
  { id: 'p1', name: 'AAA Grade Green Cardamom (1kg)', cat: 'produce', price: 2450, icon: '🌿', rating: '4.9' },
  { id: 'p2', name: 'Bleached Cardamom Pods (500g)', cat: 'produce', price: 980, icon: '🫘', rating: '4.6' },
  { id: 'p3', name: 'Organic Cardamom Seedlings (x10)', cat: 'saplings', price: 650, icon: '🌱', rating: '4.8' },
  { id: 'p4', name: 'NPK 19:19:19 Fertilizer (25kg)', cat: 'inputs', price: 1450, icon: '🧪', rating: '4.5' },
  { id: 'p5', name: 'Neem-Based Bio Pesticide (1L)', cat: 'inputs', price: 420, icon: '🧴', rating: '4.7' },
  { id: 'p6', name: 'Drip Irrigation Starter Kit', cat: 'equipment', price: 3200, icon: '💧', rating: '4.6' },
  { id: 'p7', name: 'Solar Farm Weather Station', cat: 'equipment', price: 8900, icon: '📡', rating: '4.9' },
  { id: 'p8', name: 'Cardamom Curing Chamber Rental', cat: 'equipment', price: 5400, icon: '🏭', rating: '4.4' },
  { id: 'p9', name: 'Shade Net (50% - 20x10ft)', cat: 'equipment', price: 1750, icon: '🕸️', rating: '4.5' },
  { id: 'p10', name: 'Vermicompost (50kg)', cat: 'inputs', price: 890, icon: '🪱', rating: '4.7' },
  { id: 'p11', name: 'Certified Mother Rhizomes (x5)', cat: 'saplings', price: 1100, icon: '🌾', rating: '4.8' },
  { id: 'p12', name: 'Export-Grade Cardamom (5kg bulk)', cat: 'produce', price: 11500, icon: '📦', rating: '5.0' },
];

let cart = JSON.parse(localStorage.getItem('cardofer-cart') || '[]');
let activeCategory = 'all';
let searchTerm = '';

document.addEventListener('DOMContentLoaded', () => {
  if(!document.getElementById('product-grid')) return; // only run on marketplace page
  renderProducts();
  initSearch();
  initCategoryFilters();
  initCartDrawer();
  updateCartUI();
});

/* ---------------------------------------------------------------------- */
/* Render product grid based on current search + category                */
/* ---------------------------------------------------------------------- */
function renderProducts(){
  const grid = document.getElementById('product-grid');
  const empty = document.getElementById('no-results');
  const filtered = PRODUCTS.filter(p => {
    const matchesCat = activeCategory === 'all' || p.cat === activeCategory;
    const matchesSearch = p.name.toLowerCase().includes(searchTerm.toLowerCase());
    return matchesCat && matchesSearch;
  });

  grid.innerHTML = filtered.map(p => `
    <article class="product-card card reveal visible">
      <div class="product-thumb">
        <span>${p.icon}</span>
        <button class="fav" aria-label="Save to favorites">♡</button>
      </div>
      <div class="product-body">
        <span class="product-cat">${capitalize(p.cat)}</span>
        <h3>${p.name}</h3>
        <span class="product-rating">★ ${p.rating}</span>
        <div class="product-foot">
          <span class="product-price">₹${p.price.toLocaleString('en-IN')}</span>
          <button class="add-cart-btn" aria-label="Add to cart" onclick="addToCart('${p.id}')">+</button>
        </div>
      </div>
    </article>
  `).join('');

  empty.classList.toggle('show', filtered.length === 0);
}
function capitalize(s){ return s.charAt(0).toUpperCase() + s.slice(1); }

/* ---------------------------------------------------------------------- */
/* Search Bar                                                              */
/* ---------------------------------------------------------------------- */
function initSearch(){
  const input = document.getElementById('product-search');
  if(!input) return;
  input.addEventListener('input', () => {
    searchTerm = input.value;
    renderProducts();
  });
}

/* ---------------------------------------------------------------------- */
/* Category Filters                                                        */
/* ---------------------------------------------------------------------- */
function initCategoryFilters(){
  const chips = document.querySelectorAll('.cat-chip');
  chips.forEach(chip => {
    chip.addEventListener('click', () => {
      chips.forEach(c => c.classList.remove('active'));
      chip.classList.add('active');
      activeCategory = chip.getAttribute('data-cat');
      renderProducts();
    });
  });
}

/* ---------------------------------------------------------------------- */
/* Cart — add / remove / quantity / persistence                           */
/* ---------------------------------------------------------------------- */
function addToCart(id){
  const product = PRODUCTS.find(p => p.id === id);
  if(!product) return;
  const existing = cart.find(i => i.id === id);
  if(existing){ existing.qty += 1; }
  else{ cart.push({ id, qty: 1 }); }
  persistCart();
  updateCartUI();
  if(window.showToast) showToast('Added to cart', `${product.name} added successfully.`, 'success');
  openCart();
}
function changeQty(id, delta){
  const item = cart.find(i => i.id === id);
  if(!item) return;
  item.qty += delta;
  if(item.qty <= 0) cart = cart.filter(i => i.id !== id);
  persistCart();
  updateCartUI();
}
function removeFromCart(id){
  cart = cart.filter(i => i.id !== id);
  persistCart();
  updateCartUI();
}
function persistCart(){ localStorage.setItem('cardofer-cart', JSON.stringify(cart)); }

function updateCartUI(){
  const countEls = document.querySelectorAll('.cart-count');
  const totalQty = cart.reduce((sum, i) => sum + i.qty, 0);
  countEls.forEach(el => { el.textContent = totalQty; el.style.display = totalQty ? 'flex' : 'none'; });

  const itemsWrap = document.getElementById('cart-items');
  const subtotalEl = document.getElementById('cart-subtotal-value');
  if(!itemsWrap) return;

  if(!cart.length){
    itemsWrap.innerHTML = '<div class="cart-empty">Your cart is empty.<br>Browse the marketplace to add farm supplies.</div>';
    if(subtotalEl) subtotalEl.textContent = '₹0';
    return;
  }

  let subtotal = 0;
  itemsWrap.innerHTML = cart.map(i => {
    const product = PRODUCTS.find(p => p.id === i.id);
    if(!product) return '';
    subtotal += product.price * i.qty;
    return `
      <div class="cart-item">
        <div class="thumb">${product.icon}</div>
        <div class="info">
          <h4>${product.name}</h4>
          <span class="price">₹${product.price.toLocaleString('en-IN')}</span>
          <div class="qty-control">
            <button onclick="changeQty('${i.id}', -1)" aria-label="Decrease quantity">−</button>
            <span>${i.qty}</span>
            <button onclick="changeQty('${i.id}', 1)" aria-label="Increase quantity">+</button>
          </div>
        </div>
        <button class="remove" onclick="removeFromCart('${i.id}')">Remove</button>
      </div>
    `;
  }).join('');

  if(subtotalEl) subtotalEl.textContent = '₹' + subtotal.toLocaleString('en-IN');
}

/* ---------------------------------------------------------------------- */
/* Cart Drawer open/close                                                  */
/* ---------------------------------------------------------------------- */
function initCartDrawer(){
  const openBtns = document.querySelectorAll('.cart-btn');
  const closeBtn = document.getElementById('cart-close');
  const overlay = document.getElementById('cart-overlay');
  const checkoutBtn = document.getElementById('checkout-btn');

  openBtns.forEach(btn => btn.addEventListener('click', openCart));
  closeBtn && closeBtn.addEventListener('click', closeCart);
  overlay && overlay.addEventListener('click', closeCart);
  checkoutBtn && checkoutBtn.addEventListener('click', () => {
    if(!cart.length) return;
    if(window.showToast) showToast('Order placed', 'This is a UI demo — connect a payment API to go live.', 'info');
    cart = [];
    persistCart();
    updateCartUI();
    closeCart();
  });
}
function openCart(){
  document.getElementById('cart-drawer')?.classList.add('open');
  document.getElementById('cart-overlay')?.classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closeCart(){
  document.getElementById('cart-drawer')?.classList.remove('open');
  document.getElementById('cart-overlay')?.classList.remove('open');
  document.body.style.overflow = '';
}

// Expose functions used via inline onclick handlers.
window.addToCart = addToCart;
window.changeQty = changeQty;
window.removeFromCart = removeFromCart;
