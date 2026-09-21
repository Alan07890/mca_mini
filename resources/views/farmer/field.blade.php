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

      <div class="ff-submit-row">
        <button type="submit" class="ff-submit-btn" id="ff-submit">Save Field Details <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
        <span class="ff-submit-hint">Used to personalize your recommendations</span>
      </div>
    </form>

    <aside class="ff-card ff-summary">
      <div class="ff-summary-label">Your field</div>
      <div class="ff-summary-primary"><span class="ff-summary-num" id="ff-sum-area">{{ $field->area ?? '—' }}</span><span class="ff-summary-unit" id="ff-sum-area-unit">{{ ($field->area_unit ?? 'acre') === 'cent' ? 'Cents' : 'Acres' }}</span></div>
      <div class="ff-summary-convert" id="ff-sum-convert">Add your field area</div>
      <div class="ff-summary-row"><span class="ff-summary-row-label">Cardamom plants</span><span class="ff-summary-row-val" id="ff-sum-plants">{{ isset($field->plant_count) ? number_format($field->plant_count) : '—' }}</span></div>
      <div class="ff-summary-row"><span class="ff-summary-row-label">Plant density</span><span class="ff-summary-row-val" id="ff-sum-density">— <span class="ff-unit-suffix">plants / acre</span></span></div>
      <div class="ff-viz"><div class="ff-viz-grid" id="ff-viz-grid"></div><div class="ff-viz-caption">A rough sense of how densely your field is planted</div></div>
    </aside>
  </div>

  <div class="ff-toast" id="ff-toast" role="status"><div class="ff-toast-icon">&check;</div><div><div class="ff-toast-title">Field details saved</div><div class="ff-toast-body">Your farm profile is ready for personalized recommendations.</div></div></div>
</div>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@400;500;600&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
.ff-page{--ff-bg:#0a1d15;--ff-bg-panel:#102a20;--ff-bg-panel-2:#143523;--ff-cream:#f1f8e9;--ff-sage:#9db8a3;--ff-sage-dim:#6f8a76;--ff-gold:#d5893f;--ff-gold-bright:#ffd54f;--ff-border:rgba(241,248,233,.12);--ff-border-strong:rgba(241,248,233,.2);--ff-radius:18px;--ff-serif:"Fraunces",Georgia,serif;--ff-sans:"Inter","Poppins",-apple-system,BlinkMacSystemFont,sans-serif;background:var(--ff-bg);color:var(--ff-cream);font-family:var(--ff-sans);min-height:100vh;position:relative;overflow-x:hidden;padding:64px 24px 96px}.ff-page *{box-sizing:border-box}@media(prefers-reduced-motion:reduce){.ff-page *{animation-duration:.001ms!important;transition-duration:.001ms!important}}.ff-deco{position:absolute;inset:0;pointer-events:none;z-index:0;overflow:hidden}.ff-deco svg{position:absolute;opacity:.5}.ff-deco-ring-1{top:-120px;right:-140px}.ff-deco-ring-2{bottom:60px;left:-160px}.ff-deco-leaf{top:40%;right:6%;opacity:.14}.ff-header{position:relative;z-index:1;max-width:1180px;margin:0 auto 48px}.ff-eyebrow{font-family:var(--ff-sans);font-size:13px;letter-spacing:.06em;color:var(--ff-gold);margin-bottom:14px;display:flex;align-items:center;gap:10px}.ff-eyebrow svg{flex-shrink:0}.ff-header h1{font-family:var(--ff-serif);font-weight:500;font-size:clamp(30px,4.2vw,46px);line-height:1.15;margin:0 0 14px;color:var(--ff-cream);max-width:14ch}.ff-header p{font-size:16px;line-height:1.6;color:var(--ff-sage);max-width:52ch;margin:0}.ff-grid{position:relative;z-index:1;max-width:1180px;margin:0 auto;display:grid;grid-template-columns:1.15fr .85fr;gap:28px;align-items:start}@media(max-width:900px){.ff-grid{grid-template-columns:1fr}}.ff-card{background:linear-gradient(160deg,rgba(241,248,233,.045),rgba(241,248,233,.015));border:1px solid var(--ff-border);border-radius:var(--ff-radius);backdrop-filter:blur(6px);padding:34px}@media(max-width:560px){.ff-card{padding:24px}}.ff-section+.ff-section{margin-top:34px;padding-top:34px;border-top:1px solid var(--ff-border)}.ff-section-label{font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;color:var(--ff-sage-dim);margin-bottom:16px;display:flex;align-items:center;gap:8px}.ff-area-row{display:flex;align-items:stretch;gap:10px;flex-wrap:wrap}.ff-area-input-wrap{position:relative;flex:1 1 180px}.ff-area-input{width:100%;background:rgba(241,248,233,.04);border:1px solid var(--ff-border);border-radius:12px;color:var(--ff-cream);font-family:var(--ff-serif);font-size:40px;font-weight:500;padding:14px 18px;outline:none;transition:border-color .2s ease,background .2s ease;-moz-appearance:textfield}.ff-area-input::-webkit-outer-spin-button,.ff-area-input::-webkit-inner-spin-button{-webkit-appearance:none;margin:0}.ff-area-input:focus{border-color:var(--ff-gold);background:rgba(241,248,233,.06)}.ff-area-input.ff-invalid{border-color:#d97757}.ff-unit-select{position:relative;flex:0 0 150px}.ff-unit-btn{width:100%;height:100%;min-height:60px;background:var(--ff-bg-panel-2);border:1px solid var(--ff-border);border-radius:12px;color:var(--ff-cream);font-family:var(--ff-sans);font-size:15px;font-weight:600;padding:0 16px;display:flex;align-items:center;justify-content:space-between;cursor:pointer;transition:border-color .2s ease}.ff-unit-btn:hover,.ff-unit-btn:focus-visible{border-color:var(--ff-border-strong)}.ff-unit-btn svg{transition:transform .2s ease}.ff-unit-select.ff-open .ff-unit-btn svg{transform:rotate(180deg)}.ff-unit-menu{position:absolute;top:calc(100% + 8px);left:0;right:0;background:var(--ff-bg-panel-2);border:1px solid var(--ff-border-strong);border-radius:12px;overflow:hidden;z-index:20;opacity:0;transform:translateY(-6px);pointer-events:none;transition:opacity .15s ease,transform .15s ease;box-shadow:0 12px 30px rgba(0,0,0,.35)}.ff-unit-select.ff-open .ff-unit-menu{opacity:1;transform:translateY(0);pointer-events:auto}.ff-unit-option{padding:12px 16px;font-size:14px;color:var(--ff-sage);cursor:pointer;transition:background .15s ease,color .15s ease}.ff-unit-option:hover{background:rgba(241,248,233,.06);color:var(--ff-cream)}.ff-unit-option.ff-selected{color:var(--ff-gold);font-weight:600}.ff-helper{margin-top:12px;font-size:13.5px;color:var(--ff-sage-dim)}.ff-helper strong{color:var(--ff-sage);font-weight:600}.ff-error{margin-top:10px;font-size:13px;color:#e0a382;display:none}.ff-error.ff-show{display:block}.ff-stepper{display:flex;align-items:center;gap:18px}.ff-step-btn{width:52px;height:52px;border-radius:50%;border:1px solid var(--ff-border-strong);background:rgba(241,248,233,.04);color:var(--ff-cream);font-size:22px;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:border-color .15s ease,background .15s ease,transform .1s ease;flex-shrink:0;user-select:none}.ff-step-btn:hover{border-color:var(--ff-gold);background:rgba(213,137,63,.1)}.ff-step-btn:active{transform:scale(.94)}.ff-step-btn:disabled{opacity:.35;cursor:not-allowed}.ff-plant-input-wrap{flex:1;text-align:center}.ff-plant-input{width:100%;background:transparent;border:none;color:var(--ff-cream);font-family:var(--ff-serif);font-size:44px;font-weight:500;text-align:center;outline:none;-moz-appearance:textfield}.ff-plant-input::-webkit-outer-spin-button,.ff-plant-input::-webkit-inner-spin-button{-webkit-appearance:none;margin:0}.ff-plant-label{text-align:center;font-size:12.5px;letter-spacing:.06em;text-transform:uppercase;color:var(--ff-sage-dim);margin-top:4px}.ff-submit-row{margin-top:36px;display:flex;align-items:center;gap:16px;flex-wrap:wrap}.ff-submit-btn{background:var(--ff-gold);color:#1a1006;border:none;border-radius:12px;font-family:var(--ff-sans);font-weight:700;font-size:15.5px;padding:15px 28px;cursor:pointer;display:inline-flex;align-items:center;gap:10px;transition:transform .15s ease,box-shadow .15s ease,background .15s ease}.ff-submit-btn:hover{transform:translateY(-2px) scale(1.015);box-shadow:0 10px 24px rgba(213,137,63,.28);background:var(--ff-gold-bright)}.ff-submit-btn:active{transform:translateY(0) scale(.99)}.ff-submit-btn svg{transition:transform .15s ease}.ff-submit-btn:hover svg{transform:translateX(3px)}.ff-submit-hint{font-size:13px;color:var(--ff-sage-dim)}.ff-toast{position:fixed;bottom:28px;left:50%;transform:translateX(-50%) translateY(20px);background:var(--ff-bg-panel-2);border:1px solid var(--ff-border-strong);border-left:3px solid var(--ff-gold);border-radius:12px;padding:16px 20px;display:flex;align-items:flex-start;gap:12px;max-width:380px;opacity:0;pointer-events:none;transition:opacity .25s ease,transform .25s ease;z-index:50;box-shadow:0 16px 40px rgba(0,0,0,.4)}.ff-toast.ff-show{opacity:1;transform:translateX(-50%) translateY(0);pointer-events:auto}.ff-toast-icon{width:26px;height:26px;border-radius:50%;background:rgba(213,137,63,.16);color:var(--ff-gold);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:14px}.ff-toast-title{font-weight:700;font-size:14.5px;color:var(--ff-cream);margin-bottom:2px}.ff-toast-body{font-size:13px;color:var(--ff-sage);line-height:1.5}.ff-summary{position:sticky;top:32px}.ff-summary-label{font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;color:var(--ff-sage-dim);margin-bottom:22px}.ff-summary-primary{display:flex;align-items:baseline;gap:10px;margin-bottom:4px}.ff-summary-num{font-family:var(--ff-serif);font-size:52px;font-weight:500;line-height:1;color:var(--ff-cream)}.ff-summary-unit{font-size:13px;letter-spacing:.06em;text-transform:uppercase;color:var(--ff-sage)}.ff-summary-convert{font-size:13.5px;color:var(--ff-sage-dim);margin-bottom:26px}.ff-summary-convert strong{color:var(--ff-gold);font-weight:600}.ff-summary-row{display:flex;align-items:baseline;justify-content:space-between;padding:14px 0;border-top:1px solid var(--ff-border)}.ff-summary-row-label{font-size:13.5px;color:var(--ff-sage)}.ff-summary-row-val{font-family:var(--ff-serif);font-size:20px;color:var(--ff-cream)}.ff-summary-row-val .ff-unit-suffix{font-family:var(--ff-sans);font-size:12px;color:var(--ff-sage-dim);margin-left:4px}.ff-viz{margin-top:26px;padding:20px;background:rgba(0,0,0,.15);border:1px solid var(--ff-border);border-radius:14px}.ff-viz-grid{display:grid;grid-template-columns:repeat(12,1fr);gap:6px;aspect-ratio:12/5}.ff-viz-dot{width:100%;aspect-ratio:1;border-radius:50%;background:var(--ff-sage-dim);opacity:.25;transition:opacity .4s ease,background .4s ease,transform .3s ease}.ff-viz-dot.ff-active{background:var(--ff-gold);opacity:.85}.ff-viz-caption{margin-top:12px;font-size:12px;color:var(--ff-sage-dim);text-align:center}.ff-badge{display:inline-flex;align-items:center;gap:8px;background:rgba(213,137,63,.12);border:1px solid rgba(213,137,63,.3);color:var(--ff-gold);font-size:12.5px;font-weight:600;padding:6px 14px;border-radius:100px;margin-bottom:18px}
@media(max-width:560px){.ff-page{padding:40px 16px 72px}.ff-area-input{font-size:32px}.ff-unit-select{flex:1 1 120px}.ff-summary-num{font-size:42px}.ff-stepper{gap:10px}.ff-step-btn{width:46px;height:46px}.ff-plant-input{font-size:36px}.ff-submit-btn{width:100%;justify-content:center}.ff-submit-hint{width:100%;text-align:center}}
</style>

<script>
(function(){"use strict";
const ACRE_TO_CENT=100;
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
function showToast(){if(!toast)return;toast.classList.add("ff-show");setTimeout(()=>toast.classList.remove("ff-show"),4200)}window.ffShowToast=showToast;buildVizGrid();updateSummary();if(form&&form.dataset.showSuccess==="1")showToast();
})();
</script>
@endsection
