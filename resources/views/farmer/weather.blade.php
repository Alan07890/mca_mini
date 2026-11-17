@extends('layouts.FarmerMaster')

@section('title', 'Weather Forecast — CARDO-FER')

@section('content')
@php $fmt = fn($v, $s = '') => $v !== null ? $v.$s : '—'; @endphp
<style>
    .cw-page{--gold:#d5893f;--gold2:#efb16f;--ink:#f1f7e9;--mute:#9eb6a3;--line:rgba(190,211,194,.15);position:relative;isolation:isolate;overflow:hidden;max-width:1240px;margin:0 auto;padding:44px 28px 72px;color:#edf4e8}
    .cw-page:before{content:"";position:absolute;z-index:-1;top:-190px;right:-160px;width:480px;height:480px;border:1px solid rgba(213,137,63,.10);border-radius:50%;box-shadow:0 0 0 60px rgba(241,248,233,.018),0 0 0 120px rgba(213,137,63,.018)}
    .cw-page button{font-family:inherit}
    .cw-rv{opacity:0;transform:translateY(16px);transition:opacity .6s ease,transform .6s ease}.cw-rv.in{opacity:1;transform:none}
    .cw-eyebrow{color:var(--gold);font-size:.8rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase}
    .cw-title{margin:12px 0 10px;font-family:Fraunces,Georgia,serif;font-size:clamp(2rem,4vw,3.4rem);line-height:1.1;letter-spacing:-.03em;color:var(--ink)}
    .cw-subtitle{max-width:640px;color:var(--mute);line-height:1.75;margin:0}
    .cw-location-line{display:flex;align-items:center;flex-wrap:wrap;gap:10px;margin-top:18px;color:#a8bfae;font-size:.88rem}
    .cw-pill{display:inline-flex;align-items:center;gap:7px;border:1px solid rgba(213,137,63,.3);background:rgba(213,137,63,.09);border-radius:999px;padding:7px 13px;color:var(--gold2);font-weight:700}
    .cw-card{border:1px solid var(--line);border-radius:24px;background:linear-gradient(145deg,rgba(24,54,39,.84),rgba(12,34,25,.9));box-shadow:0 20px 60px rgba(0,0,0,.14)}
    /* toolbar */
    .cw-bar{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-top:30px}
    .cw-hero-label{color:#8fa995;font-size:.76rem;text-transform:uppercase;letter-spacing:.13em;font-weight:700}
    .cw-seg{display:inline-flex;padding:3px;border-radius:12px;background:rgba(241,248,233,.06);border:1px solid var(--line)}
    .cw-seg button{border:0;background:none;color:var(--mute);font-weight:700;font-size:.82rem;padding:6px 14px;border-radius:9px;transition:.2s}
    .cw-seg button.on{background:var(--gold);color:#142317}
    /* hero */
    .cw-hero{display:grid;grid-template-columns:minmax(0,1.2fr) minmax(0,.8fr);gap:26px;margin-top:12px;padding:30px 34px}
    .cw-condition{display:flex;align-items:center;gap:20px;margin-top:14px}
    .cw-weather-icon{display:grid;place-items:center;width:92px;height:92px;border:1px solid rgba(241,248,233,.1);border-radius:26px;background:rgba(241,248,233,.045);font-size:3.1rem;flex:none;animation:cwBob 5s ease-in-out infinite}
    @keyframes cwBob{50%{transform:translateY(-6px)}}
    .cw-condition h2{font-family:Fraunces,Georgia,serif;font-size:clamp(1.5rem,2.5vw,2.3rem);margin:0 0 4px;color:var(--ink)}
    .cw-date{color:#9cb4a1;font-size:.92rem}
    .cw-temp{display:flex;align-items:baseline;gap:12px;flex-wrap:wrap;margin-top:14px}
    .cw-temp-max{font-family:Fraunces,Georgia,serif;font-size:clamp(3rem,5vw,4.6rem);line-height:1;color:var(--ink);letter-spacing:-.05em}
    .cw-temp-min{font-size:1.15rem;color:#9db3a0}
    .cw-temp-caption{font-size:.8rem;color:#76917d;margin-top:6px}
    .cw-stats{display:grid;grid-template-columns:1fr 1fr;gap:12px}
    .cw-stat{padding:16px;border:1px solid rgba(190,211,194,.12);border-radius:17px;background:rgba(241,248,233,.035);min-width:0}
    .cw-stat.wide{grid-column:1/-1;display:flex;align-items:center;gap:16px}
    .cw-stat-label{font-size:.72rem;text-transform:uppercase;letter-spacing:.09em;color:#8fa995;margin-bottom:6px}
    .cw-stat-value{font-size:1.4rem;font-weight:800;letter-spacing:-.03em;color:var(--ink)}
    .cw-stat-note{margin-top:3px;font-size:.74rem;color:#849e8a}
    .cw-ring{width:74px;height:74px;flex:none;transform:rotate(-90deg)}
    .cw-ring circle{fill:none;stroke-width:9}.cw-ring .bg{stroke:rgba(241,248,233,.09)}
    .cw-ring .fg{stroke:var(--gold);stroke-linecap:round;stroke-dasharray:100;stroke-dashoffset:100;transition:stroke-dashoffset 1s ease}
    .cw-mini{height:5px;border-radius:9px;background:rgba(241,248,233,.09);margin-top:10px;overflow:hidden}.cw-mini i{display:block;height:100%;width:0;background:var(--gold);border-radius:9px;transition:width .8s ease}
    /* sections */
    .cw-section{margin-top:40px}
    .cw-section h2{font-family:Fraunces,Georgia,serif;font-size:clamp(1.45rem,2.3vw,2rem);margin:0;color:var(--ink)}
    .cw-section-intro{font-size:.85rem;line-height:1.7;color:#8fa995;margin:6px 0 16px;max-width:660px}
    .cw-days{display:grid;grid-template-columns:repeat(6,minmax(0,1fr));gap:10px}
    .cw-day{border:1px solid rgba(190,211,194,.13);border-radius:18px;padding:16px 10px;text-align:center;background:rgba(241,248,233,.03);color:inherit;min-width:0;cursor:pointer;transition:transform .2s,border-color .2s,background .2s}
    .cw-day:hover{transform:translateY(-4px);border-color:rgba(213,137,63,.5)}
    .cw-day:focus-visible{outline:2px solid var(--gold2);outline-offset:2px}
    .cw-day.is-active{border-color:var(--gold);background:linear-gradient(160deg,rgba(213,137,63,.16),rgba(241,248,233,.04));box-shadow:0 10px 30px rgba(213,137,63,.12)}
    .cw-day-date{color:#9cb4a1;font-size:.75rem;font-weight:700;text-transform:uppercase}.cw-day.is-tomorrow .cw-day-date{color:var(--gold2)}
    .cw-day-icon{font-size:1.9rem;margin:10px 0 6px}
    .cw-day-condition{font-size:.75rem;line-height:1.4;color:#a5b9a8;min-height:32px}
    .cw-day-temps{margin-top:8px;font-weight:800;color:var(--ink)}.cw-day-temps small{color:#819987;font-weight:600;margin-left:4px}
    .cw-day-rain{font-size:.72rem;color:#9cb4a1;margin-top:8px}
    .cw-chart{margin-top:14px;padding:14px 10px 6px}.cw-chart svg{width:100%;height:auto;display:block}
    .cw-chart text{fill:#9cb4a1;font-size:11px;text-anchor:middle;font-family:Inter,sans-serif}.cw-chart .v{fill:var(--ink);font-weight:700}
    .cw-chart .hit{fill:transparent;cursor:pointer}.cw-chart .hit:hover,.cw-chart .sel{fill:rgba(213,137,63,.1)}
    .cw-legend{display:flex;gap:16px;font-size:.75rem;color:#8fa995;padding:0 8px 8px}.cw-legend i{display:inline-block;width:10px;height:10px;border-radius:3px;margin-right:6px}
    /* advisories */
    .cw-chips{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:14px}
    .cw-chip{border:1px solid var(--line);background:rgba(241,248,233,.04);color:var(--mute);border-radius:99px;padding:6px 14px;font-size:.8rem;font-weight:600;transition:.2s}
    .cw-chip b{margin-left:5px;color:var(--gold2)}.cw-chip.on,.cw-chip:hover{background:rgba(213,137,63,.15);border-color:rgba(213,137,63,.5);color:var(--ink)}
    .cw-advisory-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,280px),1fr));gap:14px}
    .cw-advisory{position:relative;display:flex;gap:14px;text-align:left;padding:20px;border-radius:19px;border:1px solid rgba(190,211,194,.14);background:rgba(241,248,233,.035);color:inherit;transition:.25s;cursor:pointer}
    .cw-advisory:hover{transform:translateY(-3px);background:rgba(241,248,233,.06)}
    .cw-advisory-icon{width:42px;height:42px;border-radius:14px;display:grid;place-items:center;flex:none;background:rgba(129,199,132,.12);color:#9cdaa0}
    .cw-advisory--warning{border-color:rgba(213,137,63,.35);background:rgba(213,137,63,.055)}.cw-advisory--warning .cw-advisory-icon{background:rgba(213,137,63,.14);color:#e8aa65}
    .cw-advisory--watch .cw-advisory-icon{background:rgba(238,199,94,.12);color:#edca75}
    .cw-advisory h3{font-size:1rem;margin:2px 0 6px;color:var(--ink);font-weight:800}.cw-advisory p{font-size:.86rem;line-height:1.7;color:#a3b8a8;margin:0}
    .cw-tick{position:absolute;top:14px;right:14px;width:22px;height:22px;border-radius:50%;border:1.5px solid rgba(190,211,194,.3);display:grid;place-items:center;font-size:.65rem;color:transparent;transition:.2s}
    .cw-advisory.done{opacity:.55}.cw-advisory.done .cw-tick{background:#66bb6a;border-color:#66bb6a;color:#0a1d15}.cw-advisory.done h3{text-decoration:line-through}
    .cw-note{font-size:.8rem;line-height:1.7;color:#78927e;margin-top:26px;padding:14px 16px;border-left:2px solid rgba(213,137,63,.7);background:rgba(241,248,233,.025);border-radius:0 12px 12px 0}
    .cw-source{margin-top:24px;text-align:right;font-size:.76rem;color:#718c78}.cw-source a{color:#b8cdb0;text-decoration:underline;text-underline-offset:3px}
    .cw-empty,.cw-error{margin-top:32px;padding:34px;border-radius:22px;border:1px solid var(--line);background:rgba(241,248,233,.04);text-align:center}
    .cw-error{border-color:rgba(213,137,63,.34)}.cw-state-icon{font-size:2.6rem;margin-bottom:10px}
    .cw-empty h2,.cw-error h2{font-family:Fraunces,Georgia,serif;font-size:1.6rem;margin:0 0 10px;color:var(--ink)}
    .cw-empty p,.cw-error p{color:#a1b7a5;line-height:1.8;margin:0 auto 18px;max-width:560px}
    .cw-btn{display:inline-flex;align-items:center;gap:9px;border:0;border-radius:12px;background:var(--gold);color:#142317;text-decoration:none;font-weight:800;padding:12px 18px;margin:0 4px;transition:transform .18s,filter .18s}
    .cw-btn:hover{color:#142317;filter:brightness(1.07);transform:translateY(-2px)}.cw-btn.ghost{background:transparent;color:var(--gold2);border:1px solid rgba(213,137,63,.45)}.cw-btn.ghost:hover{color:var(--gold2)}
    @media(max-width:1000px){.cw-hero{grid-template-columns:1fr}.cw-days{grid-template-columns:repeat(3,minmax(0,1fr))}}
    @media(max-width:600px){.cw-page{padding:30px 14px 50px}.cw-hero{padding:22px 18px}.cw-weather-icon{width:68px;height:68px;font-size:2.3rem}.cw-days{grid-template-columns:repeat(2,minmax(0,1fr))}.cw-stats{grid-template-columns:1fr}}
</style>

<main class="cw-page">
    <header class="cw-rv">
        <div class="cw-eyebrow">☼ Weather desk</div>
        <h1 class="cw-title">Plan for the weather.<br>Care for your cardamom.</h1>
        <p class="cw-subtitle">A local forecast for your saved farm location, plus practical weather-based reminders for planning field work.</p>
        @if($field && filled($field->place))
            <div class="cw-location-line"><span class="cw-pill"><i class="fa-solid fa-location-dot"></i> {{ $field->place }}</span><span>{{ $matchedLocation ? 'Forecast matched to '.$matchedLocation : '' }}</span></div>
        @endif
    </header>

    @if(!$field || blank($field->place))
        <section class="cw-empty cw-rv">
            <div class="cw-state-icon">📍</div>
            <h2>Add your farm location first</h2>
            <p>Weather forecasts use the place saved in your Field Profile. Add a village or nearby town (for example, Kattappana, Kerala), save it, and return here.</p>
            <a class="cw-btn" href="{{ route('farmer.field') }}">Update Field Details <span aria-hidden="true">→</span></a>
        </section>
    @elseif($error)
        <section class="cw-error cw-rv" role="alert">
            <div class="cw-state-icon">⛅</div>
            <h2>Forecast unavailable right now</h2>
            <p>{{ $error }}</p>
            <button type="button" class="cw-btn" onclick="location.reload()"><i class="fa-solid fa-rotate-right"></i> Try again</button>
            <a class="cw-btn ghost" href="{{ route('farmer.field') }}">Check Saved Location</a>
        </section>
    @elseif($weather && $tomorrow)
        <div class="cw-bar cw-rv">
            <div class="cw-hero-label" data-b="label">Tomorrow's forecast</div>
            <div class="cw-seg" role="group" aria-label="Temperature unit"><button type="button" class="on" data-unit="C">°C</button><button type="button" data-unit="F">°F</button></div>
        </div>
        <section class="cw-card cw-hero cw-rv" aria-live="polite" data-place="{{ $weather['requested_place'] }}">
            <div>
                <div class="cw-condition">
                    <div class="cw-weather-icon" aria-hidden="true" data-b="icon">{{ $tomorrow['icon'] }}</div>
                    <div>
                        <h2 data-b="cond">{{ $tomorrow['condition'] }}</h2>
                        <div class="cw-date" data-b="date">{{ $tomorrow['date_label'] }} · {{ $weather['requested_place'] }}</div>
                    </div>
                </div>
                <div class="cw-temp">
                    <span class="cw-temp-max" data-b="max" data-t="{{ $tomorrow['max_temp'] }}">{{ $fmt($tomorrow['max_temp'], '°') }}</span>
                    <span class="cw-temp-min">Low <span data-b="min" data-t="{{ $tomorrow['min_temp'] }}">{{ $fmt($tomorrow['min_temp'], '°') }}</span></span>
                </div>
                <div class="cw-temp-caption" data-cap>Temperature in Celsius</div>
            </div>
            <div class="cw-stats">
                <div class="cw-stat wide">
                    <svg class="cw-ring" viewBox="0 0 100 100" aria-hidden="true"><circle class="bg" cx="50" cy="50" r="42" pathLength="100"/><circle class="fg" cx="50" cy="50" r="42" pathLength="100" data-b="ring" data-v="{{ $tomorrow['rain_probability'] }}"/></svg>
                    <div><div class="cw-stat-label">Rain chance</div><div class="cw-stat-value" data-b="rp">{{ $fmt($tomorrow['rain_probability'], '%') }}</div><div class="cw-stat-note">Highest chance on this day</div></div>
                </div>
                <div class="cw-stat"><div class="cw-stat-label">Expected rain</div><div class="cw-stat-value" data-b="mm">{{ $fmt($tomorrow['rain_mm'], ' mm') }}</div><div class="cw-mini"><i data-b="mmbar" data-v="{{ $tomorrow['rain_mm'] }}"></i></div></div>
                <div class="cw-stat"><div class="cw-stat-label">Max wind</div><div class="cw-stat-value" data-b="wind">{{ $fmt($tomorrow['wind_kmh'], ' km/h') }}</div><div class="cw-mini"><i data-b="windbar" data-v="{{ $tomorrow['wind_kmh'] }}"></i></div></div>
            </div>
        </section>

        <section class="cw-section cw-rv" aria-labelledby="cw-outlook-heading">
            <h2 id="cw-outlook-heading">Coming days</h2>
            <p class="cw-section-intro">Tap any day to see its details above. Forecasts can change, especially for rainfall in hilly areas.</p>
            <div class="cw-days">
                @foreach($forecastDays as $day)
                    <button type="button" class="cw-day {{ $day['date'] === $tomorrow['date'] ? 'is-tomorrow is-active' : '' }}" aria-pressed="{{ $day['date'] === $tomorrow['date'] ? 'true' : 'false' }}"
                        data-label="{{ $day['date_label'] }}" data-icon="{{ $day['icon'] }}" data-cond="{{ $day['condition'] }}" data-max="{{ $day['max_temp'] }}" data-min="{{ $day['min_temp'] }}" data-rp="{{ $day['rain_probability'] }}" data-mm="{{ $day['rain_mm'] }}" data-wind="{{ $day['wind_kmh'] ?? '' }}">
                        <div class="cw-day-date">{{ $day['date'] === $tomorrow['date'] ? 'Tomorrow' : $day['date_label'] }}</div>
                        <div class="cw-day-icon" aria-hidden="true">{{ $day['icon'] }}</div>
                        <div class="cw-day-condition">{{ $day['condition'] }}</div>
                        <div class="cw-day-temps"><span data-t="{{ $day['max_temp'] }}">{{ $fmt($day['max_temp'], '°') }}</span><small data-t="{{ $day['min_temp'] }}">{{ $fmt($day['min_temp'], '°') }}</small></div>
                        <div class="cw-day-rain">☂ {{ $fmt($day['rain_mm'], ' mm') }} · {{ $fmt($day['rain_probability'], '%') }}</div>
                    </button>
                @endforeach
            </div>
            <div class="cw-card cw-chart"><div class="cw-legend"><span><i style="background:#d5893f"></i>High</span><span><i style="background:#9cb4a1"></i>Low</span><span><i style="background:rgba(129,199,132,.55)"></i>Rain</span></div><div id="cw-chart"></div></div>
        </section>

        <section class="cw-section cw-rv" aria-labelledby="cw-advisory-heading">
            <h2 id="cw-advisory-heading">Farm planning notes</h2>
            <p class="cw-section-intro">Rule-based suggestions from forecast rainfall and temperature, not an AI diagnosis or a fertilizer dosage recommendation. Tap a note to tick it off.</p>
            <div class="cw-chips" id="cw-chips"></div>
            <div class="cw-advisory-grid">
                @foreach($advisories as $advisory)
                    <button type="button" class="cw-advisory cw-advisory--{{ $advisory['type'] }}" data-type="{{ $advisory['type'] }}">
                        <span class="cw-tick"><i class="fa-solid fa-check"></i></span>
                        <span class="cw-advisory-icon" aria-hidden="true"><i class="fa-solid {{ ['warning'=>'fa-triangle-exclamation','watch'=>'fa-clock'][$advisory['type']] ?? 'fa-circle-check' }}"></i></span>
                        <span><h3>{{ $advisory['title'] }}</h3><p>{{ $advisory['message'] }}</p></span>
                    </button>
                @endforeach
            </div>
        </section>

        <div class="cw-note cw-rv">Weather is a planning aid, not a guarantee. Check local conditions before entering the field. For fertilizer timing and quantities, follow the product label and advice from your local agricultural officer.</div>
        <div class="cw-source">Weather data by <a href="https://open-meteo.com/" target="_blank" rel="noopener noreferrer">Open-Meteo</a>.</div>
    @endif
</main>

<script>
(() => {
 const $ = (s, r = document) => r.querySelector(s), $$ = (s, r = document) => [...r.querySelectorAll(s)];
 const io = new IntersectionObserver(es => es.forEach(e => e.isIntersecting && (e.target.classList.add('in'), io.unobserve(e.target))), {threshold:.08});
 $$('.cw-rv').forEach(e => io.observe(e));
 const hero = $('.cw-hero'); if (!hero) return;
 let unit = 'C'; const num = v => (v === '' || v == null) ? null : +v, conv = c => unit === 'F' ? Math.round(c * 9 / 5 + 32) : c;
 const B = k => $(`[data-b="${k}"]`);

 function temps() {
  $$('[data-t]').forEach(e => { const v = num(e.dataset.t); e.textContent = v === null ? '—' : conv(v) + '°'; });
  $('[data-cap]').textContent = 'Temperature in ' + (unit === 'F' ? 'Fahrenheit' : 'Celsius'); chart();
 }
 function bars() {
  const r = num(B('ring').dataset.v); B('ring').style.strokeDashoffset = 100 - (r ?? 0);
  B('mmbar').style.width = Math.min((num(B('mmbar').dataset.v) ?? 0) * 3, 100) + '%';
  B('windbar').style.width = Math.min((num(B('windbar').dataset.v) ?? 0) * 2.5, 100) + '%';
 }
 function pick(d) {
  $$('.cw-day').forEach(x => { x.classList.toggle('is-active', x === d); x.setAttribute('aria-pressed', x === d); });
  const s = d.dataset, t = d.classList.contains('is-tomorrow'), f = (v, u) => v === '' ? '—' : v + u;
  B('label').textContent = t ? "Tomorrow's forecast" : 'Forecast · ' + s.label;
  B('icon').textContent = s.icon; B('cond').textContent = s.cond; B('date').textContent = s.label + ' · ' + hero.dataset.place;
  B('max').dataset.t = s.max; B('min').dataset.t = s.min;
  B('rp').textContent = f(s.rp, '%'); B('mm').textContent = f(s.mm, ' mm'); B('wind').textContent = f(s.wind, ' km/h');
  B('ring').dataset.v = s.rp; B('mmbar').dataset.v = s.mm; B('windbar').dataset.v = s.wind;
  bars(); temps();
 }
 function chart() {
  const ds = $$('.cw-day'), n = ds.length, host = $('#cw-chart'); if (n < 2) { host.innerHTML = ''; return; }
  const W = 640, H = 190, px = 40, top = 30, bot = 60, x = i => px + i * (W - 2 * px) / (n - 1);
  const hi = ds.map(d => num(d.dataset.max)), lo = ds.map(d => num(d.dataset.min)), all = hi.concat(lo).filter(v => v !== null);
  if (!all.length) { host.innerHTML = ''; return; }
  const mx = Math.max(...all), mn = Math.min(...all), y = v => top + (mx - v) / ((mx - mn) || 1) * (H - top - bot);
  const mm = ds.map(d => num(d.dataset.mm) ?? 0), mmax = Math.max(...mm, 1), act = ds.findIndex(d => d.classList.contains('is-active'));
  const pl = a => a.map((v, i) => v === null ? '' : x(i) + ',' + y(v)).filter(Boolean).join(' ');
  let s = `<svg viewBox="0 0 ${W} ${H}" role="img" aria-label="Temperature and rainfall trend">`;
  ds.forEach((d, i) => {
   const bw = (W - 2 * px) / (n - 1);
   s += `<rect class="hit ${i === act ? 'sel' : ''}" data-i="${i}" x="${x(i) - bw / 2}" y="0" width="${bw}" height="${H}" rx="10"/>`;
   const h = mm[i] / mmax * 34; s += `<rect x="${x(i) - 11}" y="${H - 22 - h}" width="22" height="${Math.max(h, 2)}" rx="4" fill="rgba(129,199,132,.55)" pointer-events="none"/>`;
   s += `<text x="${x(i)}" y="${H - 6}">${d.dataset.label.split(',')[0]}</text>`;
  });
  s += `<polyline fill="none" stroke="#9cb4a1" stroke-width="2" points="${pl(lo)}"/><polyline fill="none" stroke="#d5893f" stroke-width="3" points="${pl(hi)}"/>`;
  hi.forEach((v, i) => { if (v === null) return; s += `<circle cx="${x(i)}" cy="${y(v)}" r="${i === act ? 6 : 4}" fill="#d5893f" pointer-events="none"/><text class="v" x="${x(i)}" y="${y(v) - 11}">${conv(v)}°</text>`; });
  host.innerHTML = s + '</svg>';
 }
 $('#cw-chart').onclick = e => { const r = e.target.closest('[data-i]'); if (r) pick($$('.cw-day')[r.dataset.i]); };
 $$('.cw-day').forEach(d => d.onclick = () => pick(d));
 $$('[data-unit]').forEach(b => b.onclick = () => { unit = b.dataset.unit; $$('[data-unit]').forEach(x => x.classList.toggle('on', x === b)); temps(); });

 // advisory filters + tick-off
 const cards = $$('.cw-advisory'), names = {all:'All', warning:'Warnings', watch:'Watch', good:'Good to go'};
 const chips = $('#cw-chips');
 chips.innerHTML = Object.keys(names).map(k => { const c = k === 'all' ? cards.length : cards.filter(a => a.dataset.type === k).length; return c || k === 'all' ? `<button type="button" class="cw-chip ${k === 'all' ? 'on' : ''}" data-f="${k}">${names[k]}<b>${c}</b></button>` : ''; }).join('');
 chips.onclick = e => { const c = e.target.closest('[data-f]'); if (!c) return; $$('.cw-chip').forEach(x => x.classList.toggle('on', x === c)); cards.forEach(a => a.hidden = c.dataset.f !== 'all' && a.dataset.type !== c.dataset.f); };
 cards.forEach(a => a.onclick = () => a.classList.toggle('done'));
 setTimeout(() => { bars(); temps(); }, 250);
})();
</script>
@endsection