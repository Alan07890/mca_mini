@extends('layouts.FarmerMaster')

@section('title', 'Your Field — CARDO-FER')

@section('content')

<div class="ff-page">

  <div class="ff-deco" aria-hidden="true">

    <svg class="ff-deco-ring-1" width="420" height="420" viewBox="0 0 420 420" fill="none">

      <circle cx="210" cy="210" r="209" stroke="#F1F8E9" stroke-opacity="0.06"/>

      <circle cx="210" cy="210" r="150" stroke="#D5893F" stroke-opacity="0.08"/>

    </svg>

    <svg class="ff-deco-ring-2" width="360" height="360" viewBox="0 0 360 360" fill="none">

      <circle cx="180" cy="180" r="179" stroke="#F1F8E9" stroke-opacity="0.05"/>

    </svg>

    <svg class="ff-deco-leaf" width="140" height="220" viewBox="0 0 140 220" fill="none">

      <path d="M70 4C110 40 132 90 120 150C110 195 85 216 70 216C55 216 30 195 20 150C8 90 30 40 70 4Z" stroke="#F1F8E9" stroke-opacity="0.5"/>

      <path d="M70 10V210" stroke="#F1F8E9" stroke-opacity="0.4"/>

    </svg>

  </div>

  <header class="ff-header">

    <div class="ff-eyebrow">

      <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M8 1C8 1 3 5.5 3 9.5C3 12 5.2 14 8 14C10.8 14 13 12 13 9.5C13 5.5 8 1 8 1Z" stroke="#D5893F" stroke-width="1.2"/></svg>

      Field Profile

    </div>

    @if($field)

      <div class="ff-badge">Your field profile</div>

    @endif

    <h1>{{ $field ? 'Your field profile.' : 'Tell us about your field.' }}</h1>

    <p>Add a few details about your cardamom farm so CARDO-FER can personalize your fertilizer recommendations.</p>

  </header>

  <div class="ff-grid">

    <form class="ff-card" id="ff-form" method="POST" action="{{ route('farmer.field.save') }}" data-show-success="{{ session('ff_success') ? '1' : '0' }}">

      @csrf

      <div class="ff-section">

        <div class="ff-section-label">Field area</div>

        <div class="ff-area-row">

          <div class="ff-area-input-wrap">

            <input type="number" step="0.01" min="0.01" inputmode="decimal" id="ff-area" name="area" class="ff-area-input @error('area') ff-invalid @enderror" placeholder="0.00" value="{{ old('area', $field->area ?? '') }}" aria-label="Field area">

            <div class="ff-error @error('area') ff-show @enderror" id="ff-area-error">{{ $errors->first('area') ?: 'Enter a field area greater than 0.' }}</div>

          </div>

          <div class="ff-unit-select" id="ff-unit-select">

            <button type="button" class="ff-unit-btn" id="ff-unit-btn">

              <span id="ff-unit-label">{{ old('area_unit', $field->area_unit ?? 'acre') === 'cent' ? 'Cents' : 'Acres' }}</span>

              <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M3 5L7 9L11 5" stroke="#F1F8E9" stroke-width="1.4" stroke-linecap="round"/></svg>

            </button>

            <div class="ff-unit-menu" role="listbox">

              <div class="ff-unit-option {{ old('area_unit', $field->area_unit ?? 'acre') === 'acre' ? 'ff-selected' : '' }}" data-unit="acre">Acres</div>

              <div class="ff-unit-option {{ old('area_unit', $field->area_unit ?? 'acre') === 'cent' ? 'ff-selected' : '' }}" data-unit="cent">Cents</div>

            </div>

            <input type="hidden" id="ff-unit-value" name="area_unit" value="{{ old('area_unit', $field->area_unit ?? 'acre') }}">

          </div>

        </div>

        <p class="ff-helper" id="ff-area-helper">Enter your field area to see the conversion.</p>

        @error('area_unit') <div class="ff-error ff-show">{{ $message }}</div> @enderror

      </div>

      <div class="ff-section">

        <div class="ff-section-label">Cardamom plants</div>

        <div class="ff-stepper">

          <button type="button" class="ff-step-btn" id="ff-plant-minus" aria-label="Decrease">&minus;</button>

          <div class="ff-plant-input-wrap">

            <input type="number" step="1" min="0" inputmode="numeric" id="ff-plants" name="plant_count" class="ff-plant-input" value="{{ old('plant_count', $field->plant_count ?? '') }}" aria-label="Number of plants">

            <div class="ff-plant-label">Plants</div>

          </div>

          <button type="button" class="ff-step-btn" id="ff-plant-plus" aria-label="Increase">&plus;</button>

        </div>

        <div class="ff-error @error('plant_count') ff-show @enderror" id="ff-plant-error">{{ $errors->first('plant_count') ?: 'Enter a whole number greater than 0.' }}</div>

      </div>

      <div class="ff-section">
        <label class="ff-section-label" for="ff-place">Field location</label>
        <div class="ff-location-wrap">
          <input
            type="text"
            id="ff-place"
            name="place"
            class="ff-location-input @error('place') ff-invalid @enderror"
            placeholder="Search village, town, or PIN code..."
            value="{{ old('place', $field->place ?? '') }}"
            maxlength="255"
            autocomplete="off"
            role="combobox"
            aria-autocomplete="list"
            aria-expanded="false"
            aria-controls="ff-place-suggestions"
            aria-describedby="ff-place-helper ff-place-status"
          >
          <input type="hidden" id="ff-latitude" name="latitude" value="{{ old('latitude', $field->latitude ?? '') }}">
          <input type="hidden" id="ff-longitude" name="longitude" value="{{ old('longitude', $field->longitude ?? '') }}">
          <div id="ff-place-suggestions" class="ff-location-suggestions" role="listbox" hidden></div>
        </div>
        <p class="ff-helper" id="ff-place-helper">Search for your village or type its local name. If it does not appear, you can pin your farm on the map.</p>
        <p class="ff-location-status" id="ff-place-status" aria-live="polite">
          @if(is_numeric($field->latitude ?? null) && is_numeric($field->longitude ?? null))
            Saved weather location is ready.
          @else
            Select a suggested place to enable the local weather forecast.
          @endif
        </p>
        @error('place') <div class="ff-error ff-show">{{ $message }}</div> @enderror
        @error('latitude') <div class="ff-error ff-show">Please select a location from the suggestions.</div> @enderror
        @error('longitude') <div class="ff-error ff-show">Please select a location from suggestions or pin it on the map.</div> @enderror
        <button type="button" class="ff-map-open-btn" id="ff-open-map">
          <span aria-hidden="true">⌖</span> Can't find your place? Select on map
        </button>
        <p class="ff-location-attribution">Location search and map data © <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap contributors</a>.</p>
      </div>
      <div class="ff-submit-row">

        <button type="submit" class="ff-submit-btn" id="ff-submit">Save Field Details <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg></button>

        <span class="ff-submit-hint">Used to personalize your recommendations</span>

      </div>

    </form>

    <aside class="ff-card ff-summary">

      <div class="ff-summary-label">Your field</div>

      <div class="ff-summary-primary"><span class="ff-summary-num" id="ff-sum-area">{{ $field->area ?? '—' }}</span><span class="ff-summary-unit" id="ff-sum-area-unit">{{ ($field->area_unit ?? 'acre') === 'cent' ? 'Cents' : 'Acres' }}</span></div>

      <div class="ff-summary-convert" id="ff-sum-convert">Add your field area</div>
      <div class="ff-summary-row"><span class="ff-summary-row-label">Local place</span><span class="ff-summary-row-val" id="ff-sum-place" style="font-family:var(--ff-sans);font-size:14px;max-width:60%;text-align:right;overflow-wrap:anywhere">{{ $field->place ?? 'Not set' }}</span></div>

      <div class="ff-summary-row"><span class="ff-summary-row-label">Cardamom plants</span><span class="ff-summary-row-val" id="ff-sum-plants">{{ isset($field->plant_count) ? number_format($field->plant_count) : '—' }}</span></div>

      <div class="ff-summary-row"><span class="ff-summary-row-label">Plant density</span><span class="ff-summary-row-val" id="ff-sum-density">— <span class="ff-unit-suffix">plants / acre</span></span></div>

      <div class="ff-viz"><div class="ff-viz-grid" id="ff-viz-grid"></div><div class="ff-viz-caption">A rough sense of how densely your field is planted</div></div>

    </aside>

  </div>

  <div class="ff-map-overlay" id="ff-map-overlay" hidden aria-hidden="true">
  <section class="ff-map-dialog" role="dialog" aria-modal="true" aria-labelledby="ff-map-title">
    <div class="ff-map-dialog-head">
      <div>
        <div class="ff-map-kicker">Farm coordinates</div>
        <h2 id="ff-map-title">Pin your farm location</h2>
        <p>Click the map at your farm. You can type the local name above even when search has no suggestion.</p>
      </div>
      <button type="button" class="ff-map-close" id="ff-map-close" aria-label="Close map">&times;</button>
    </div>
    <div id="ff-location-map" class="ff-location-map" aria-label="Map for selecting farm location"></div>
    <div class="ff-map-bottom-row">
      <p id="ff-map-status" class="ff-map-status" aria-live="polite">Click the map to place a pin inside the Idukki search area.</p>
      <div class="ff-map-coordinates" id="ff-map-coordinates">No point selected</div>
    </div>
    <div class="ff-map-dialog-actions">
      <button type="button" class="ff-map-cancel-btn" id="ff-map-cancel">Cancel</button>
      <button type="button" class="ff-map-use-btn" id="ff-map-use" disabled>Use selected location</button>
    </div>
    <div class="ff-map-attribution">Map tiles © <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap contributors</a> · Map library <a href="https://leafletjs.com/" target="_blank" rel="noopener">Leaflet</a></div>
  </section>
</div>

<div class="ff-toast" id="ff-toast" role="status"><div class="ff-toast-icon">&check;</div><div><div class="ff-toast-title">Field details saved</div><div class="ff-toast-body">Your farm profile is ready for personalized recommendations.</div></div></div>

</div>

<link rel="preconnect" href="https://fonts.googleapis.com">

<link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@400;500;600&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<style>

.ff-page{--ff-bg:#0a1d15;--ff-bg-panel:#102a20;--ff-bg-panel-2:#143523;--ff-cream:#f1f8e9;--ff-sage:#9db8a3;--ff-sage-dim:#6f8a76;--ff-gold:#d5893f;--ff-gold-bright:#ffd54f;--ff-border:rgba(241,248,233,.12);--ff-border-strong:rgba(241,248,233,.2);--ff-radius:18px;--ff-serif:"Fraunces",Georgia,serif;--ff-sans:"Inter","Poppins",-apple-system,BlinkMacSystemFont,sans-serif;background:var(--ff-bg);color:var(--ff-cream);font-family:var(--ff-sans);min-height:100vh;position:relative;overflow-x:hidden;padding:64px 24px 96px}.ff-page *{box-sizing:border-box}@media(prefers-reduced-motion:reduce){.ff-page *{animation-duration:.001ms!important;transition-duration:.001ms!important}}.ff-deco{position:absolute;inset:0;pointer-events:none;z-index:0;overflow:hidden}.ff-deco svg{position:absolute;opacity:.5}.ff-deco-ring-1{top:-120px;right:-140px}.ff-deco-ring-2{bottom:60px;left:-160px}.ff-deco-leaf{top:40%;right:6%;opacity:.14}.ff-header{position:relative;z-index:1;max-width:1180px;margin:0 auto 48px}.ff-eyebrow{font-family:var(--ff-sans);font-size:13px;letter-spacing:.06em;color:var(--ff-gold);margin-bottom:14px;display:flex;align-items:center;gap:10px}.ff-eyebrow svg{flex-shrink:0}.ff-header h1{font-family:var(--ff-serif);font-weight:500;font-size:clamp(30px,4.2vw,46px);line-height:1.15;margin:0 0 14px;color:var(--ff-cream);max-width:14ch}.ff-header p{font-size:16px;line-height:1.6;color:var(--ff-sage);max-width:52ch;margin:0}.ff-grid{position:relative;z-index:1;max-width:1180px;margin:0 auto;display:grid;grid-template-columns:1.15fr .85fr;gap:28px;align-items:start}@media(max-width:900px){.ff-grid{grid-template-columns:1fr}}.ff-card{background:linear-gradient(160deg,rgba(241,248,233,.045),rgba(241,248,233,.015));border:1px solid var(--ff-border);border-radius:var(--ff-radius);backdrop-filter:blur(6px);padding:34px}@media(max-width:560px){.ff-card{padding:24px}}.ff-section+.ff-section{margin-top:34px;padding-top:34px;border-top:1px solid var(--ff-border)}.ff-section-label{font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;color:var(--ff-sage-dim);margin-bottom:16px;display:flex;align-items:center;gap:8px}.ff-area-row{display:flex;align-items:stretch;gap:10px;flex-wrap:wrap}.ff-area-input-wrap{position:relative;flex:1 1 180px}.ff-area-input{width:100%;background:rgba(241,248,233,.04);border:1px solid var(--ff-border);border-radius:12px;color:var(--ff-cream);font-family:var(--ff-serif);font-size:40px;font-weight:500;padding:14px 18px;outline:none;transition:border-color .2s ease,background .2s ease;-moz-appearance:textfield}.ff-area-input::-webkit-outer-spin-button,.ff-area-input::-webkit-inner-spin-button{-webkit-appearance:none;margin:0}.ff-area-input:focus{border-color:var(--ff-gold);background:rgba(241,248,233,.06)}.ff-area-input.ff-invalid{border-color:#d97757}.ff-unit-select{position:relative;flex:0 0 150px}.ff-unit-btn{width:100%;height:100%;min-height:60px;background:var(--ff-bg-panel-2);border:1px solid var(--ff-border);border-radius:12px;color:var(--ff-cream);font-family:var(--ff-sans);font-size:15px;font-weight:600;padding:0 16px;display:flex;align-items:center;justify-content:space-between;cursor:pointer;transition:border-color .2s ease}.ff-unit-btn:hover,.ff-unit-btn:focus-visible{border-color:var(--ff-border-strong)}.ff-unit-btn svg{transition:transform .2s ease}.ff-unit-select.ff-open .ff-unit-btn svg{transform:rotate(180deg)}.ff-unit-menu{position:absolute;top:calc(100% + 8px);left:0;right:0;background:var(--ff-bg-panel-2);border:1px solid var(--ff-border-strong);border-radius:12px;overflow:hidden;z-index:20;opacity:0;transform:translateY(-6px);pointer-events:none;transition:opacity .15s ease,transform .15s ease;box-shadow:0 12px 30px rgba(0,0,0,.35)}.ff-unit-select.ff-open .ff-unit-menu{opacity:1;transform:translateY(0);pointer-events:auto}.ff-unit-option{padding:12px 16px;font-size:14px;color:var(--ff-sage);cursor:pointer;transition:background .15s ease,color .15s ease}.ff-unit-option:hover{background:rgba(241,248,233,.06);color:var(--ff-cream)}.ff-unit-option.ff-selected{color:var(--ff-gold);font-weight:600}.ff-helper{margin-top:12px;font-size:13.5px;color:var(--ff-sage-dim)}.ff-helper strong{color:var(--ff-sage);font-weight:600}.ff-error{margin-top:10px;font-size:13px;color:#e0a382;display:none}.ff-error.ff-show{display:block}.ff-stepper{display:flex;align-items:center;gap:18px}.ff-step-btn{width:52px;height:52px;border-radius:50%;border:1px solid var(--ff-border-strong);background:rgba(241,248,233,.04);color:var(--ff-cream);font-size:22px;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:border-color .15s ease,background .15s ease,transform .1s ease;flex-shrink:0;user-select:none}.ff-step-btn:hover{border-color:var(--ff-gold);background:rgba(213,137,63,.1)}.ff-step-btn:active{transform:scale(.94)}.ff-step-btn:disabled{opacity:.35;cursor:not-allowed}.ff-plant-input-wrap{flex:1;text-align:center}.ff-plant-input{width:100%;background:transparent;border:none;color:var(--ff-cream);font-family:var(--ff-serif);font-size:44px;font-weight:500;text-align:center;outline:none;-moz-appearance:textfield}.ff-plant-input::-webkit-outer-spin-button,.ff-plant-input::-webkit-inner-spin-button{-webkit-appearance:none;margin:0}.ff-plant-label{text-align:center;font-size:12.5px;letter-spacing:.06em;text-transform:uppercase;color:var(--ff-sage-dim);margin-top:4px}.ff-submit-row{margin-top:36px;display:flex;align-items:center;gap:16px;flex-wrap:wrap}.ff-submit-btn{background:var(--ff-gold);color:#1a1006;border:none;border-radius:12px;font-family:var(--ff-sans);font-weight:700;font-size:15.5px;padding:15px 28px;cursor:pointer;display:inline-flex;align-items:center;gap:10px;transition:transform .15s ease,box-shadow .15s ease,background .15s ease}.ff-submit-btn:hover{transform:translateY(-2px) scale(1.015);box-shadow:0 10px 24px rgba(213,137,63,.28);background:var(--ff-gold-bright)}.ff-submit-btn:active{transform:translateY(0) scale(.99)}.ff-submit-btn svg{transition:transform .15s ease}.ff-submit-btn:hover svg{transform:translateX(3px)}.ff-submit-hint{font-size:13px;color:var(--ff-sage-dim)}.ff-toast{position:fixed;bottom:28px;left:50%;transform:translateX(-50%) translateY(20px);background:var(--ff-bg-panel-2);border:1px solid var(--ff-border-strong);border-left:3px solid var(--ff-gold);border-radius:12px;padding:16px 20px;display:flex;align-items:flex-start;gap:12px;max-width:380px;opacity:0;pointer-events:none;transition:opacity .25s ease,transform .25s ease;z-index:50;box-shadow:0 16px 40px rgba(0,0,0,.4)}.ff-toast.ff-show{opacity:1;transform:translateX(-50%) translateY(0);pointer-events:auto}.ff-toast-icon{width:26px;height:26px;border-radius:50%;background:rgba(213,137,63,.16);color:var(--ff-gold);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:14px}.ff-toast-title{font-weight:700;font-size:14.5px;color:var(--ff-cream);margin-bottom:2px}.ff-toast-body{font-size:13px;color:var(--ff-sage);line-height:1.5}.ff-summary{position:sticky;top:32px}.ff-summary-label{font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;color:var(--ff-sage-dim);margin-bottom:22px}.ff-summary-primary{display:flex;align-items:baseline;gap:10px;margin-bottom:4px}.ff-summary-num{font-family:var(--ff-serif);font-size:52px;font-weight:500;line-height:1;color:var(--ff-cream)}.ff-summary-unit{font-size:13px;letter-spacing:.06em;text-transform:uppercase;color:var(--ff-sage)}.ff-summary-convert{font-size:13.5px;color:var(--ff-sage-dim);margin-bottom:26px}.ff-summary-convert strong{color:var(--ff-gold);font-weight:600}.ff-summary-row{display:flex;align-items:baseline;justify-content:space-between;padding:14px 0;border-top:1px solid var(--ff-border)}.ff-summary-row-label{font-size:13.5px;color:var(--ff-sage)}.ff-summary-row-val{font-family:var(--ff-serif);font-size:20px;color:var(--ff-cream)}.ff-summary-row-val .ff-unit-suffix{font-family:var(--ff-sans);font-size:12px;color:var(--ff-sage-dim);margin-left:4px}.ff-viz{margin-top:26px;padding:20px;background:rgba(0,0,0,.15);border:1px solid var(--ff-border);border-radius:14px}.ff-viz-grid{display:grid;grid-template-columns:repeat(12,1fr);gap:6px;aspect-ratio:12/5}.ff-viz-dot{width:100%;aspect-ratio:1;border-radius:50%;background:var(--ff-sage-dim);opacity:.25;transition:opacity .4s ease,background .4s ease,transform .3s ease}.ff-viz-dot.ff-active{background:var(--ff-gold);opacity:.85}.ff-viz-caption{margin-top:12px;font-size:12px;color:var(--ff-sage-dim);text-align:center}.ff-badge{display:inline-flex;align-items:center;gap:8px;background:rgba(213,137,63,.12);border:1px solid rgba(213,137,63,.3);color:var(--ff-gold);font-size:12.5px;font-weight:600;padding:6px 14px;border-radius:100px;margin-bottom:18px}

.ff-location-input{width:100%;background:rgba(241,248,233,.04);border:1px solid var(--ff-border);border-radius:12px;color:var(--ff-cream);font-family:var(--ff-sans);font-size:15px;padding:16px 18px;outline:none;transition:border-color .2s ease,background .2s ease}.ff-location-input::placeholder{color:var(--ff-sage-dim);opacity:.9}.ff-location-input:focus{border-color:var(--ff-gold);background:rgba(241,248,233,.06)}.ff-location-input.ff-invalid{border-color:#d97757}

@media(max-width:560px){.ff-page{padding:40px 16px 72px}.ff-area-input{font-size:32px}.ff-unit-select{flex:1 1 120px}.ff-summary-num{font-size:42px}.ff-stepper{gap:10px}.ff-step-btn{width:46px;height:46px}.ff-plant-input{font-size:36px}.ff-submit-btn{width:100%;justify-content:center}.ff-submit-hint{width:100%;text-align:center}}



/* =========================================================
   FIELD PAGE LAYOUT FIX
   Break out of any inherited Bootstrap/container wrapper,
   then center the header and keep the form on the left and
   the live summary on the right on desktop.
========================================================= */
.ff-page {
    box-sizing: border-box !important;
    display: block !important;
    width: 100vw !important;
    max-width: 100vw !important;
    margin-left: calc(50% - 50vw) !important;
    margin-right: 0 !important;
    min-width: 0 !important;
    overflow-x: clip;
}

.ff-page > .ff-header,
.ff-page > .ff-grid {
    width: 100% !important;
    max-width: 1180px !important;
    margin-left: auto !important;
    margin-right: auto !important;
}

.ff-page > .ff-header {
    margin-bottom: 32px !important;
}

.ff-page > .ff-grid {
    display: grid !important;
    grid-template-columns: minmax(0, 1.15fr) minmax(300px, 0.85fr) !important;
    gap: 28px !important;
    align-items: start !important;
    justify-items: stretch !important;
}

#ff-form {
    grid-column: 1 !important;
    grid-row: 1 !important;
    width: 100% !important;
    min-width: 0 !important;
    max-width: none !important;
    margin: 0 !important;
    float: none !important;
    align-self: start !important;
}

.ff-page > .ff-grid > .ff-summary {
    grid-column: 2 !important;
    grid-row: 1 !important;
    width: 100% !important;
    min-width: 0 !important;
    max-width: none !important;
    margin: 0 !important;
    float: none !important;
    position: sticky !important;
    top: 24px !important;
}

.ff-page .ff-section,
.ff-page .ff-card,
.ff-page input,
.ff-page button {
    min-width: 0;
}

@media (max-width: 900px) {
    .ff-page {
        padding-left: 18px !important;
        padding-right: 18px !important;
    }

    .ff-page > .ff-grid {
        grid-template-columns: minmax(0, 1fr) !important;
    }

    #ff-form,
    .ff-page > .ff-grid > .ff-summary {
        grid-column: 1 !important;
        grid-row: auto !important;
    }

    .ff-page > .ff-grid > .ff-summary {
        position: relative !important;
        top: auto !important;
    }
}

@media (max-width: 560px) {
    .ff-page {
        padding-left: 12px !important;
        padding-right: 12px !important;
    }
}


/* Smart location autocomplete */
.ff-location-wrap { position: relative; width: 100%; }
.ff-location-suggestions { position: absolute; top: calc(100% + 8px); left: 0; right: 0; z-index: 60; max-height: 280px; overflow-y: auto; padding: 6px; background: #143523; border: 1px solid rgba(241,248,233,.2); border-radius: 13px; box-shadow: 0 18px 40px rgba(0,0,0,.42); }
.ff-location-suggestions[hidden] { display: none !important; }
.ff-location-result { display: block; width: 100%; padding: 12px 14px; border: 0; border-radius: 9px; background: transparent; color: var(--ff-cream); text-align: left; cursor: pointer; font-family: var(--ff-sans); }
.ff-location-result:hover, .ff-location-result:focus-visible { outline: none; background: rgba(241,248,233,.08); }
.ff-location-result-main { display: block; font-size: 14px; font-weight: 650; }
.ff-location-result-meta { display: block; margin-top: 3px; color: var(--ff-sage); font-size: 12px; line-height: 1.45; }
.ff-location-status { margin: 8px 0 0; color: var(--ff-sage-dim); font-size: 12.5px; line-height: 1.5; }
.ff-location-status.ff-location-status-error { color: #e0a382; }
.ff-location-status.ff-location-status-success { color: #a7d6ad; }


/* Free map fallback for small/unlisted Idukki localities */
.ff-map-open-btn{display:inline-flex;align-items:center;gap:9px;margin-top:13px;padding:11px 14px;border:1px solid rgba(213,137,63,.6);border-radius:11px;background:rgba(213,137,63,.08);color:var(--ff-cream);font:600 13px var(--ff-sans);cursor:pointer;transition:background .2s,border-color .2s}
.ff-map-open-btn:hover{background:rgba(213,137,63,.17);border-color:var(--ff-gold)}
.ff-map-open-btn span{font-size:21px;line-height:1;color:var(--ff-gold)}
.ff-location-attribution{font:11px/1.5 var(--ff-sans);color:var(--ff-sage-dim);margin:7px 0 0}
.ff-location-attribution a,.ff-map-attribution a{color:var(--ff-sage);text-decoration:underline}
.ff-map-overlay{position:fixed;inset:0;z-index:99999;background:rgba(1,10,6,.82);backdrop-filter:blur(8px);display:flex;align-items:center;justify-content:center;padding:18px}
.ff-map-overlay[hidden]{display:none!important}
.ff-map-dialog{width:min(920px,100%);max-height:calc(100vh - 36px);overflow:auto;background:#0d241a;border:1px solid rgba(241,248,233,.19);border-radius:20px;box-shadow:0 28px 90px rgba(0,0,0,.55);padding:22px;color:var(--ff-cream)}
.ff-map-dialog-head{display:flex;align-items:flex-start;justify-content:space-between;gap:20px;margin-bottom:16px}
.ff-map-kicker{font:600 11px var(--ff-sans);letter-spacing:.13em;text-transform:uppercase;color:var(--ff-gold);margin-bottom:7px}
.ff-map-dialog-head h2{font:500 clamp(24px,3vw,32px)/1.15 var(--ff-serif);margin:0 0 8px;color:var(--ff-cream)}
.ff-map-dialog-head p{font:13px/1.55 var(--ff-sans);color:var(--ff-sage);margin:0;max-width:680px}
.ff-map-close{border:1px solid var(--ff-border-strong);border-radius:50%;width:38px;height:38px;flex:0 0 38px;background:rgba(241,248,233,.05);color:var(--ff-cream);font-size:27px;line-height:1;cursor:pointer}
.ff-location-map{height:min(54vh,470px);min-height:300px;width:100%;border:1px solid rgba(241,248,233,.2);border-radius:14px;overflow:hidden;background:#dce4d9}
.ff-map-bottom-row{display:flex;justify-content:space-between;align-items:center;gap:15px;flex-wrap:wrap;margin:12px 0}
.ff-map-status{font:13px/1.5 var(--ff-sans);color:var(--ff-sage);margin:0}
.ff-map-coordinates{font:600 12px/1.4 ui-monospace,SFMono-Regular,Consolas,monospace;color:var(--ff-cream);padding:8px 10px;border:1px solid var(--ff-border);border-radius:9px;background:rgba(241,248,233,.04)}
.ff-map-dialog-actions{display:flex;justify-content:flex-end;gap:10px;flex-wrap:wrap;margin-top:12px}
.ff-map-cancel-btn,.ff-map-use-btn{border-radius:11px;padding:12px 16px;font:650 13px var(--ff-sans);cursor:pointer}
.ff-map-cancel-btn{background:transparent;border:1px solid var(--ff-border-strong);color:var(--ff-cream)}
.ff-map-use-btn{background:var(--ff-gold);border:1px solid var(--ff-gold);color:#111a13}
.ff-map-use-btn:disabled{opacity:.45;cursor:not-allowed}
.ff-map-attribution{font:10px/1.5 var(--ff-sans);color:var(--ff-sage-dim);margin-top:12px}
@media(max-width:600px){.ff-map-overlay{padding:8px}.ff-map-dialog{padding:14px;border-radius:15px;max-height:calc(100vh - 16px)}.ff-location-map{min-height:320px;height:48vh}.ff-map-coordinates{width:100%;overflow-wrap:anywhere}}

</style>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>

(function(){"use strict";

const ACRE_TO_CENT=100;
const placeInput=document.getElementById("ff-place");
const sumPlace=document.getElementById("ff-sum-place");
const latitudeInput=document.getElementById("ff-latitude");
const longitudeInput=document.getElementById("ff-longitude");
const placeSuggestions=document.getElementById("ff-place-suggestions");
const placeStatus=document.getElementById("ff-place-status");
const locationSearchUrl=@json(route('farmer.weather.locations'));
let selectedPlace=(latitudeInput&&longitudeInput&&latitudeInput.value!==""&&longitudeInput.value!=="")?(placeInput?.value.trim()||""):"";
let locationSearchTimer=null;
let latestLocationRequest=0;

function setLocationStatus(message,type=""){
    if(!placeStatus)return;
    placeStatus.textContent=message;
    placeStatus.classList.toggle("ff-location-status-error",type==="error");
    placeStatus.classList.toggle("ff-location-status-success",type==="success");
}
function closeLocationSuggestions(){
    if(!placeSuggestions)return;
    placeSuggestions.hidden=true;
    placeSuggestions.innerHTML="";
    if(placeInput)placeInput.setAttribute("aria-expanded","false");
}
function selectLocation(item){
    if(!placeInput||!latitudeInput||!longitudeInput)return;
    placeInput.value=item.label;
    latitudeInput.value=String(item.latitude);
    longitudeInput.value=String(item.longitude);
    selectedPlace=item.label;
    if(sumPlace)sumPlace.textContent=item.label;
    placeInput.classList.remove("ff-invalid");
    closeLocationSuggestions();
    setLocationStatus("Location selected. Save field details to use this location for weather forecasts.","success");
}
function renderLocationSuggestions(items){
    if(!placeSuggestions)return;
    placeSuggestions.innerHTML="";
    if(!Array.isArray(items)||items.length===0){
        closeLocationSuggestions();
        setLocationStatus("No matching settlement found. Keep your local place name and use the map button to pin the farm.","error");
        return;
    }
    items.forEach(item=>{
        const option=document.createElement("button");
        option.type="button";
        option.className="ff-location-result";
        option.setAttribute("role","option");
        const title=document.createElement("span");
        title.className="ff-location-result-main";
        title.textContent=item.name||item.label;
        const detail=document.createElement("span");
        detail.className="ff-location-result-meta";
        detail.textContent=item.label;
        option.appendChild(title);
        if(item.label&&item.label!==item.name)option.appendChild(detail);
        option.addEventListener("click",()=>selectLocation(item));
        placeSuggestions.appendChild(option);
    });
    placeSuggestions.hidden=false;
    if(placeInput)placeInput.setAttribute("aria-expanded","true");
    setLocationStatus("Choose the matching place from the list.");
}
async function searchLocations(query){
    const requestId=++latestLocationRequest;
    try{
        setLocationStatus("Searching locations…");
        const response=await fetch(locationSearchUrl+"?q="+encodeURIComponent(query),{
            headers:{"Accept":"application/json"},
            credentials:"same-origin"
        });
        if(!response.ok)throw new Error("Location search failed");
        const data=await response.json();
        if(requestId!==latestLocationRequest)return;
        if(data.search_available===false && (!Array.isArray(data.results)||data.results.length===0)){
            closeLocationSuggestions();
            setLocationStatus(data.message||"Place search is temporarily unavailable. You can still pin your farm on the map.","error");
            return;
        }
        renderLocationSuggestions(data.results||[]);
    }catch(error){
        if(requestId!==latestLocationRequest)return;
        closeLocationSuggestions();
        setLocationStatus("Place search is temporarily unavailable. You can still pin your farm on the map.","error");
    }
}
if(placeInput){
    if(selectedPlace){
        setLocationStatus("Saved location coordinates are ready.","success");
    }else if(placeInput.value.trim().length>=2){
        setLocationStatus("Choose a matching result to enable weather forecasts.");
        window.setTimeout(()=>searchLocations(placeInput.value.trim()),250);
    }
    placeInput.addEventListener("input",()=>{
        const typed=placeInput.value.trim();
        if(sumPlace)sumPlace.textContent=typed||"Not set";
        if(typed!==selectedPlace){
            if(latitudeInput)latitudeInput.value="";
            if(longitudeInput)longitudeInput.value="";
            selectedPlace="";
        }
        placeInput.classList.remove("ff-invalid");
        closeLocationSuggestions();
        window.clearTimeout(locationSearchTimer);
        latestLocationRequest++;
        if(typed.length<2){
            setLocationStatus("Type at least 2 letters to search, or pin the farm on the map.");
            return;
        }
        locationSearchTimer=window.setTimeout(()=>searchLocations(typed),350);
    });
    placeInput.addEventListener("keydown",event=>{
        if(event.key==="Escape")closeLocationSuggestions();
    });
    document.addEventListener("click",event=>{
        if(placeSuggestions&&!placeSuggestions.contains(event.target)&&event.target!==placeInput)closeLocationSuggestions();
    });
}


/* Leaflet/OpenStreetMap fallback: coordinates are selected on the map, not inferred from a text result. */
const openMapButton=document.getElementById("ff-open-map");
const mapOverlay=document.getElementById("ff-map-overlay");
const mapCloseButton=document.getElementById("ff-map-close");
const mapCancelButton=document.getElementById("ff-map-cancel");
const mapUseButton=document.getElementById("ff-map-use");
const mapStatus=document.getElementById("ff-map-status");
const mapCoordinates=document.getElementById("ff-map-coordinates");
const IDUKKI_BOUNDS_ARRAY=[[9.30,76.50],[10.50,77.55]];
let IDUKKI_SEARCH_BOUNDS=null;
let farmMap=null;
let farmMapMarker=null;
let mapCandidate=null;

function setMapStatus(message,error=false){
    if(!mapStatus)return;
    mapStatus.textContent=message;
    mapStatus.style.color=error?"#e0a382":"var(--ff-sage)";
}
function formatCoordinates(lat,lng){return Number(lat).toFixed(6)+", "+Number(lng).toFixed(6);}
function setMapCandidate(lat,lng){
    const point=L.latLng(Number(lat),Number(lng));
    if(!IDUKKI_SEARCH_BOUNDS.contains(point)){
        mapCandidate=null;
        if(mapUseButton)mapUseButton.disabled=true;
        setMapStatus("Please choose a point inside the Idukki search area.",true);
        return;
    }
    mapCandidate={latitude:point.lat,longitude:point.lng};
    if(farmMapMarker)farmMapMarker.setLatLng(point);
    else farmMapMarker=L.marker(point).addTo(farmMap);
    if(mapCoordinates)mapCoordinates.textContent=formatCoordinates(point.lat,point.lng);
    if(mapUseButton)mapUseButton.disabled=false;
    setMapStatus("Pin selected. Click “Use selected location” to attach these coordinates to your farm.");
}
function initialiseFarmMap(){
    if(!window.L){setMapStatus("Map library could not load. Check your internet connection and refresh.",true);return;}
    if(!IDUKKI_SEARCH_BOUNDS)IDUKKI_SEARCH_BOUNDS=L.latLngBounds(IDUKKI_BOUNDS_ARRAY);
    if(!farmMap){
        farmMap=L.map("ff-location-map",{
            maxBounds:IDUKKI_SEARCH_BOUNDS,
            maxBoundsViscosity:0.9,
            minZoom:8,
            maxZoom:19,
            zoomControl:true
        }).setView([9.75,77.13],10);
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",{
            maxZoom:19,
            attribution:'&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap contributors</a>'
        }).addTo(farmMap);
        farmMap.on("click",event=>setMapCandidate(event.latlng.lat,event.latlng.lng));
    }
    window.setTimeout(()=>farmMap.invalidateSize(),120);
    const savedLat=latitudeInput?parseFloat(latitudeInput.value):NaN;
    const savedLng=longitudeInput?parseFloat(longitudeInput.value):NaN;
    if(Number.isFinite(savedLat)&&Number.isFinite(savedLng)&&IDUKKI_SEARCH_BOUNDS.contains([savedLat,savedLng])){
        farmMap.setView([savedLat,savedLng],Math.max(farmMap.getZoom(),14));
        setMapCandidate(savedLat,savedLng);
    }else{
        mapCandidate=null;
        if(farmMapMarker){farmMap.removeLayer(farmMapMarker);farmMapMarker=null;}
        if(mapUseButton)mapUseButton.disabled=true;
        if(mapCoordinates)mapCoordinates.textContent="No point selected";
        setMapStatus("Click the map close to your farm. The map is centred on Idukki; zoom in for more precision.");
        farmMap.setView([9.75,77.13],10);
    }
}
function openFarmMap(){
    if(!mapOverlay)return;
    mapOverlay.hidden=false;
    mapOverlay.setAttribute("aria-hidden","false");
    document.body.style.overflow="hidden";
    initialiseFarmMap();
    if(mapCloseButton)mapCloseButton.focus();
}
function closeFarmMap(){
    if(!mapOverlay)return;
    mapOverlay.hidden=true;
    mapOverlay.setAttribute("aria-hidden","true");
    document.body.style.overflow="";
    if(openMapButton)openMapButton.focus();
}
if(openMapButton)openMapButton.addEventListener("click",openFarmMap);
if(mapCloseButton)mapCloseButton.addEventListener("click",closeFarmMap);
if(mapCancelButton)mapCancelButton.addEventListener("click",closeFarmMap);
if(mapOverlay)mapOverlay.addEventListener("click",event=>{if(event.target===mapOverlay)closeFarmMap();});
document.addEventListener("keydown",event=>{if(event.key==="Escape"&&mapOverlay&&!mapOverlay.hidden)closeFarmMap();});
if(mapUseButton)mapUseButton.addEventListener("click",()=>{
    if(!mapCandidate||!placeInput||!latitudeInput||!longitudeInput)return;
    const lat=Number(mapCandidate.latitude);
    const lng=Number(mapCandidate.longitude);
    latitudeInput.value=lat.toFixed(7);
    longitudeInput.value=lng.toFixed(7);
    if(!placeInput.value.trim())placeInput.value="Farm location ("+lat.toFixed(5)+", "+lng.toFixed(5)+")";
    selectedPlace=placeInput.value.trim();
    if(sumPlace)sumPlace.textContent=selectedPlace;
    placeInput.classList.remove("ff-invalid");
    closeLocationSuggestions();
    setLocationStatus("Map coordinates selected. Save field details to use this exact point for weather forecasts.","success");
    closeFarmMap();
});

const areaInput=document.getElementById("ff-area"),unitSelect=document.getElementById("ff-unit-select"),unitBtn=document.getElementById("ff-unit-btn"),unitLabel=document.getElementById("ff-unit-label"),unitOptions=document.querySelectorAll(".ff-unit-option"),unitHiddenInput=document.getElementById("ff-unit-value"),helperText=document.getElementById("ff-area-helper"),areaError=document.getElementById("ff-area-error"),plantInput=document.getElementById("ff-plants"),plantMinus=document.getElementById("ff-plant-minus"),plantPlus=document.getElementById("ff-plant-plus"),plantError=document.getElementById("ff-plant-error"),sumArea=document.getElementById("ff-sum-area"),sumAreaUnit=document.getElementById("ff-sum-area-unit"),sumConvert=document.getElementById("ff-sum-convert"),sumPlants=document.getElementById("ff-sum-plants"),sumDensity=document.getElementById("ff-sum-density"),vizGrid=document.getElementById("ff-viz-grid"),form=document.getElementById("ff-form"),toast=document.getElementById("ff-toast");

let currentUnit=unitHiddenInput?unitHiddenInput.value||"acre":"acre";const TOTAL_DOTS=60;

function buildVizGrid(){if(!vizGrid)return;vizGrid.innerHTML="";for(let i=0;i<TOTAL_DOTS;i++){const dot=document.createElement("div");dot.className="ff-viz-dot";vizGrid.appendChild(dot)}}

function toAcres(value,unit){if(isNaN(value))return 0;return unit==="cent"?value/ACRE_TO_CENT:value}

function formatNum(n,decimals){if(!isFinite(n))return"0";const rounded=Number(n.toFixed(decimals));return rounded.toLocaleString(undefined,{maximumFractionDigits:decimals})}

function updateHelper(areaVal){if(!helperText)return;if(isNaN(areaVal)||areaVal<=0){helperText.innerHTML="Enter your field area to see the conversion.";return}if(currentUnit==="acre")helperText.innerHTML=`${formatNum(areaVal,2)} acres &#8776; <strong>${formatNum(areaVal*ACRE_TO_CENT,0)} cents</strong>`;else helperText.innerHTML=`${formatNum(areaVal,0)} cents &#8776; <strong>${formatNum(areaVal/ACRE_TO_CENT,2)} acres</strong>`}

function updateSummary(){const areaVal=parseFloat(areaInput.value),plantVal=parseInt(plantInput.value,10);updateHelper(areaVal);const hasArea=!isNaN(areaVal)&&areaVal>0,hasPlants=!isNaN(plantVal)&&plantVal>0;sumArea.textContent=hasArea?formatNum(areaVal,2):"—";sumAreaUnit.textContent=currentUnit==="acre"?"Acres":"Cents";if(hasArea){if(currentUnit==="acre")sumConvert.innerHTML=`&#8776; <strong>${formatNum(areaVal*ACRE_TO_CENT,0)}</strong> cents`;else sumConvert.innerHTML=`&#8776; <strong>${formatNum(areaVal/ACRE_TO_CENT,2)}</strong> acres`}else sumConvert.textContent="Add your field area";sumPlants.textContent=hasPlants?plantVal.toLocaleString():"—";if(hasArea&&hasPlants){const acres=toAcres(areaVal,currentUnit),density=acres>0?plantVal/acres:0;sumDensity.innerHTML=`${formatNum(density,0)} <span class="ff-unit-suffix">plants / acre</span>`}else sumDensity.innerHTML=`— <span class="ff-unit-suffix">plants / acre</span>`;updateViz(plantVal)}

function updateViz(plantVal){if(!vizGrid)return;const dots=vizGrid.querySelectorAll(".ff-viz-dot");if(isNaN(plantVal)||plantVal<=0){dots.forEach(d=>d.classList.remove("ff-active"));return}const ratio=Math.min(1,Math.log10(plantVal+1)/Math.log10(5000)),activeCount=Math.max(1,Math.round(ratio*TOTAL_DOTS));dots.forEach((d,i)=>d.classList.toggle("ff-active",i<activeCount))}

function setUnit(unit,label){currentUnit=unit;unitHiddenInput.value=unit;unitLabel.textContent=label;unitOptions.forEach(opt=>opt.classList.toggle("ff-selected",opt.dataset.unit===unit));unitSelect.classList.remove("ff-open");updateSummary()}

if(unitBtn)unitBtn.addEventListener("click",()=>unitSelect.classList.toggle("ff-open"));unitOptions.forEach(opt=>opt.addEventListener("click",()=>setUnit(opt.dataset.unit,opt.textContent.trim())));document.addEventListener("click",e=>{if(unitSelect&&!unitSelect.contains(e.target))unitSelect.classList.remove("ff-open")});

if(areaInput)areaInput.addEventListener("input",()=>{areaInput.classList.remove("ff-invalid");if(areaError)areaError.classList.remove("ff-show");updateSummary()});

function clampPlants(val){if(isNaN(val)||val<0)return 0;return Math.round(val)}function stepPlants(delta){const current=parseInt(plantInput.value,10)||0,next=clampPlants(current+delta);plantInput.value=next;plantInput.classList.add("ff-pulse");setTimeout(()=>plantInput.classList.remove("ff-pulse"),150);updateSummary()}

if(plantMinus)plantMinus.addEventListener("click",()=>stepPlants(-10));if(plantPlus)plantPlus.addEventListener("click",()=>stepPlants(10));if(plantInput)plantInput.addEventListener("input",()=>{if(plantInput.value!==""&&parseInt(plantInput.value,10)<0)plantInput.value=0;if(plantError)plantError.classList.remove("ff-show");updateSummary()});

if(form){form.addEventListener("submit",event=>{
    const hasCoordinates=latitudeInput&&longitudeInput&&latitudeInput.value!==""&&longitudeInput.value!=="";
    if(!hasCoordinates){
        event.preventDefault();
        if(placeInput){placeInput.classList.add("ff-invalid");placeInput.focus();}
        setLocationStatus("Choose a search suggestion or use the map button to pin the farm before saving.","error");
    }
});}
function showToast(){if(!toast)return;toast.classList.add("ff-show");setTimeout(()=>toast.classList.remove("ff-show"),4200)}window.ffShowToast=showToast;buildVizGrid();updateSummary();if(form&&form.dataset.showSuccess==="1")showToast();

})();

</script>

@endsection