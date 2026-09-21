/* ==========================================================================
   CARDO-FER — DASHBOARD.JS
   Powers the Farmer Dashboard: builds the current-month calendar widget,
   animates the analytics bar chart into view, and lets notifications be
   marked as read.
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {
  buildCalendar();
  animateChartBars();
  initNotificationDismiss();
  initSidebarActive();
});

/* ---------------------------------------------------------------------- */
/* Calendar Widget — renders the real current month                       */
/* ---------------------------------------------------------------------- */
function buildCalendar(){
  const grid = document.getElementById('cal-grid');
  const monthLabel = document.getElementById('cal-month-label');
  if(!grid) return;

  // Farm events keyed by day-of-month for this demo (would come from an API).
  const events = { 5: 'Fertilizer application', 12: 'Irrigation check', 18: 'Pest inspection', 24: 'Harvest planning' };

  const now = new Date();
  const year = now.getFullYear();
  const month = now.getMonth();
  const firstDay = new Date(year, month, 1).getDay();
  const daysInMonth = new Date(year, month + 1, 0).getDate();
  const prevMonthDays = new Date(year, month, 0).getDate();

  if(monthLabel){
    monthLabel.textContent = now.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
  }

  ['S','M','T','W','T','F','S'].forEach(d => {
    const el = document.createElement('div');
    el.className = 'dow';
    el.textContent = d;
    grid.appendChild(el);
  });

  // Leading days from previous month (muted, for grid alignment).
  for(let i = firstDay - 1; i >= 0; i--){
    const el = document.createElement('div');
    el.className = 'day muted';
    el.textContent = prevMonthDays - i;
    grid.appendChild(el);
  }

  for(let d = 1; d <= daysInMonth; d++){
    const el = document.createElement('div');
    el.className = 'day';
    if(d === now.getDate()) el.classList.add('today');
    if(events[d]) el.classList.add('event');
    el.textContent = d;
    grid.appendChild(el);
  }

  // Populate the "upcoming events" list beneath the grid.
  const list = document.getElementById('cal-events-list');
  if(list){
    Object.entries(events).forEach(([day, label]) => {
      const item = document.createElement('div');
      item.className = 'cal-event';
      item.innerHTML = `<span class="bar"></span><span>${monthLabel ? monthLabel.textContent.split(' ')[0] : ''} ${day} — ${label}</span>`;
      list.appendChild(item);
    });
  }
}

/* ---------------------------------------------------------------------- */
/* Analytics Chart — grows bars from 0 to their data height on load       */
/* ---------------------------------------------------------------------- */
function animateChartBars(){
  const bars = document.querySelectorAll('.chart-bars .bar');
  if(!bars.length) return;
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if(entry.isIntersecting){
        const bar = entry.target;
        const height = bar.getAttribute('data-height');
        requestAnimationFrame(() => { bar.style.height = height + '%'; });
        observer.unobserve(bar);
      }
    });
  }, { threshold: 0.3 });
  bars.forEach(bar => { bar.style.height = '0%'; observer.observe(bar); });
}

/* ---------------------------------------------------------------------- */
/* Notifications — click to mark as read                                  */
/* ---------------------------------------------------------------------- */
function initNotificationDismiss(){
  document.querySelectorAll('.notif-item').forEach(item => {
    item.addEventListener('click', () => item.classList.add('read'));
  });
  const clearBtn = document.getElementById('clear-notifs');
  if(clearBtn){
    clearBtn.addEventListener('click', () => {
      document.querySelectorAll('.notif-item').forEach(i => i.classList.add('read'));
      if(window.showToast) showToast('All caught up', 'Notifications marked as read.', 'success');
    });
  }
}

/* ---------------------------------------------------------------------- */
/* Sidebar — highlight active section based on filename                   */
/* ---------------------------------------------------------------------- */
function initSidebarActive(){
  const path = location.pathname.split('/').pop() || 'dashboard.html';
  document.querySelectorAll('.sidebar a').forEach(link => {
    if(link.getAttribute('href') === path) link.classList.add('active');
  });
}
