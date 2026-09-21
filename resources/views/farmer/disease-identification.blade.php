@extends('layouts.FarmerMaster')

@section('content')

<style>
    /* =========================================================
       CARDO-FER — DISEASE IDENTIFICATION
    ========================================================= */

    .did-wrap {
        --did-bg-deep: #07150f;
        --did-bg: #0a1d15;
        --did-card: #10251b;
        --did-primary: #1B5E20;
        --did-secondary: #2E7D32;
        --did-light-green: #81C784;
        --did-cream: #F1F8E9;
        --did-gold: #FFD54F;
        --did-gold-deep: #D5893F;
        --did-sage: #a9c9ae;
        --did-danger: #e2836b;

        background:
            radial-gradient(
                circle at 15% 0%,
                #0f2a1c 0%,
                var(--did-bg) 45%,
                var(--did-bg-deep) 100%
            );

        color: var(--did-cream);

        font-family:
            'Poppins',
            'Inter',
            sans-serif;

        padding:
            48px 20px 96px;

        min-height: 100vh;

        position: relative;

        overflow: hidden;
    }

    .did-wrap h1,
    .did-wrap h2,
    .did-wrap h3 {
        font-family:
            'Fraunces',
            Georgia,
            serif;
    }

    .did-container {
        max-width: 1180px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    /* =========================================================
       DECORATIVE CARDAMOM PODS
    ========================================================= */

    .did-pod {
        position: absolute;
        opacity: 0.12;
        animation:
            did-float 7s ease-in-out infinite;
        pointer-events: none;
    }

    .did-pod.p1 {
        top: 40px;
        right: 6%;
        width: 90px;
    }

    .did-pod.p2 {
        bottom: 60px;
        left: 4%;
        width: 60px;
        animation-delay: 2s;
    }

    @keyframes did-float {
        0%,
        100% {
            transform:
                translateY(0)
                rotate(0deg);
        }

        50% {
            transform:
                translateY(-18px)
                rotate(8deg);
        }
    }

    /* =========================================================
       HERO
    ========================================================= */

    .did-hero {
        text-align: center;
        margin-bottom: 36px;
    }

    .did-hero h1 {
        font-size:
            clamp(1.9rem, 4vw, 2.75rem);

        font-weight: 600;

        margin-bottom: 12px;
    }

    .did-hero h1 span {
        color: var(--did-gold);
    }

    .did-hero p {
        color: var(--did-sage);

        max-width: 600px;

        margin: 0 auto;

        font-size: 1.02rem;

        line-height: 1.6;
    }

    /* =========================================================
       STEP INDICATOR
    ========================================================= */

    .did-steps {
        display: flex;

        justify-content: center;

        gap: 8px;

        margin:
            32px auto 40px;

        max-width: 680px;

        flex-wrap: wrap;
    }

    .did-step {
        display: flex;

        align-items: center;

        gap: 10px;

        padding:
            8px 16px 8px 8px;

        border-radius: 999px;

        background:
            rgba(255, 255, 255, 0.03);

        border:
            1px solid rgba(255, 255, 255, 0.06);

        transition:
            all .4s ease;
    }

    .did-step .num {
        width: 28px;
        height: 28px;

        border-radius: 50%;

        display: flex;

        align-items: center;
        justify-content: center;

        background:
            rgba(255, 255, 255, 0.06);

        color: var(--did-sage);

        font-size: 0.8rem;

        font-weight: 700;

        transition:
            all .4s ease;
    }

    .did-step .label {
        font-size: 0.8rem;

        color: var(--did-sage);

        white-space: nowrap;
    }

    .did-step.active {
        background:
            rgba(255, 213, 79, 0.08);

        border-color:
            rgba(255, 213, 79, 0.35);

        box-shadow:
            0 0 18px
            rgba(255, 213, 79, 0.15);
    }

    .did-step.active .num {
        background:
            var(--did-gold);

        color: #1a1305;
    }

    .did-step.active .label {
        color: var(--did-gold);

        font-weight: 600;
    }

    .did-step.done .num {
        background:
            var(--did-secondary);

        color: #fff;
    }

    .did-step.done .label {
        color:
            var(--did-light-green);
    }

    /* =========================================================
       COMMON CARD
    ========================================================= */

    .did-card {
        background:
            linear-gradient(
                160deg,
                rgba(255,255,255,0.035),
                rgba(255,255,255,0.01)
            );

        border:
            1px solid rgba(255,255,255,0.07);

        border-radius: 20px;

        padding: 32px;

        backdrop-filter: blur(6px);

        box-shadow:
            0 20px 50px
            rgba(0,0,0,0.35);

        margin-bottom: 28px;
    }

    .did-card h2 {
        font-size: 1.4rem;

        margin-bottom: 6px;
    }

    .did-card .did-sub {
        color: var(--did-sage);

        font-size: 0.92rem;

        margin-bottom: 22px;
    }

    #didSelectionCard {
        padding: 26px;
    }

    @media (max-width: 575px) {
        #didSelectionCard {
            padding: 18px 14px;
        }
    }

    /* =========================================================
       TOOLBAR
    ========================================================= */

    .did-toolbar {
        display: flex;

        gap: 16px;

        flex-wrap: wrap;

        align-items: center;

        margin-bottom: 22px;
    }

    .did-search {
        position: relative;

        flex: 1;

        min-width: 220px;
    }

    .did-search input {
        width: 100%;

        padding:
            13px 18px 13px 44px;

        border-radius: 12px;

        border:
            1px solid rgba(255,255,255,0.1);

        background:
            rgba(0,0,0,0.25);

        color: var(--did-cream);

        font-size: 0.95rem;

        outline: none;

        transition:
            border-color .3s ease,
            box-shadow .3s ease;
    }

    .did-search input:focus {
        border-color:
            var(--did-gold);

        box-shadow:
            0 0 0 3px
            rgba(255,213,79,0.15);
    }

    .did-search i {
        position: absolute;

        left: 16px;

        top: 50%;

        transform:
            translateY(-50%);

        color:
            var(--did-sage);
    }

    .did-result-count {
        color:
            var(--did-sage);

        font-size: 0.85rem;

        white-space: nowrap;
    }

    /* =========================================================
       SYMPTOM GRID — COMPACT / CLEAN
    ========================================================= */

.did-symptom-grid {
    display: grid;
    grid-template-columns: repeat(6, minmax(0, 1fr));
    gap: 12px;
}

@media (max-width: 1250px) {
    .did-symptom-grid {
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 11px;
    }
}

@media (max-width: 1050px) {
    .did-symptom-grid {
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 11px;
    }
}

@media (max-width: 800px) {
    .did-symptom-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
    }
}

@media (max-width: 600px) {
    .did-symptom-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 9px;
    }
}

/* =========================================================
   SYMPTOM CARD — SMALLER
========================================================= */

.did-symptom-card {
    width: 100%;
    min-width: 0;

    background:
        linear-gradient(
            145deg,
            rgba(255,255,255,0.045),
            rgba(255,255,255,0.012)
        );

    border: 1px solid rgba(129,199,132,0.14);
    border-radius: 12px;
    overflow: hidden;

    cursor: pointer;
    text-align: left;
    padding: 0;
    color: var(--did-cream);

    appearance: none;
    -webkit-appearance: none;

    transition:
        transform .18s ease,
        border-color .18s ease,
        box-shadow .18s ease,
        background .18s ease;
}

.did-symptom-card:hover {
    transform: translateY(-2px);
    border-color: rgba(255,213,79,0.34);
    box-shadow: 0 9px 18px rgba(0,0,0,0.20);
}

.did-symptom-card:focus-visible {
    outline: 2px solid var(--did-gold);
    outline-offset: 2px;
}

/* =========================================================
   PHOTO AREA — FULL IMAGE / NO CROPPING
========================================================= */

.did-image-wrapper {
    position: relative;
    width: 100%;
    height: 112px;

    overflow: hidden;

    display: flex;
    align-items: center;
    justify-content: center;

    background:
        linear-gradient(
            145deg,
            #132d1f,
            #0b2015
        );
}

.did-image-wrapper img {
    position: relative;
    z-index: 2;

    display: block;

    width: auto;
    height: 100%;
    max-width: 100%;
    max-height: 100%;

    object-fit: contain;
    object-position: center;

    padding: 4px;

    background: transparent;

    transition:
        transform .20s ease,
        filter .20s ease;
}

.did-symptom-card:hover .did-image-wrapper img {
    transform: scale(1.015);
    filter: brightness(1.03) saturate(1.04);
}

.did-image-wrapper::before {
    content: "";
    position: absolute;
    inset: 5px;

    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 8px;

    pointer-events: none;
    z-index: 4;
}

.did-image-wrapper::after {
    content: "";
    position: absolute;
    inset: 0;

    background:
        linear-gradient(
            180deg,
            transparent 62%,
            rgba(7,21,15,0.16) 100%
        );

    pointer-events: none;
    z-index: 3;
}

.did-image-backdrop {
    display: none;
}

/* =========================================================
   FALLBACK IMAGE
========================================================= */

.did-image-fallback {
    position: relative;
    z-index: 3;

    width: 100%;
    height: 100%;

    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;

    gap: 5px;

    color: var(--did-sage);
    font-size: 0.68rem;
    line-height: 1.25;
    text-align: center;
    padding: 10px;
}

.did-image-fallback i {
    font-size: 1.15rem;
    color: var(--did-gold-deep);
}

/* =========================================================
   SELECTED OVERLAY
========================================================= */

.did-selected-overlay {
    position: absolute;
    inset: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    background: rgba(27,94,32,0.30);

    opacity: 0;
    transition: opacity .20s ease;

    z-index: 5;
}

.did-selected-overlay span {
    width: 38px;
    height: 38px;

    border-radius: 50%;

    background: var(--did-gold);

    display: flex;
    align-items: center;
    justify-content: center;

    color: #1a1305;
    font-size: 0.95rem;

    transform: scale(.75);
    transition:
        transform .22s cubic-bezier(.34,1.56,.64,1);

    box-shadow: 0 5px 14px rgba(0,0,0,0.35);
}

/* =========================================================
   CARD TEXT — SMALLER
========================================================= */

.did-symptom-card-body {
    padding: 8px 9px 10px;
    background: rgba(255,255,255,0.018);
}

.did-symptom-card-body h3 {
    font-family: 'Poppins', sans-serif;

    font-size: 0.78rem;
    line-height: 1.25;
    font-weight: 600;

    margin: 0;
    color: var(--did-cream);

    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
    overflow: hidden;

    min-height: 1.95em;
}

.did-symptom-card-body .helper {
    margin-top: 3px;

    font-size: 0.60rem;
    line-height: 1.2;

    color: var(--did-sage);
    opacity: 0.78;
}

/* =========================================================
   SELECTED CARD
========================================================= */

.did-symptom-card[aria-pressed="true"] {
    border-color: var(--did-gold);

    box-shadow:
        0 0 0 1px rgba(255,213,79,0.15),
        0 8px 20px rgba(255,213,79,0.09);

    transform: translateY(-1px);
}

.did-symptom-card[aria-pressed="true"] .did-selected-overlay {
    opacity: 1;
}

.did-symptom-card[aria-pressed="true"] .did-selected-overlay span {
    transform: scale(1);
}

.did-symptom-card[aria-pressed="true"] .did-symptom-card-body {
    background: rgba(255,213,79,0.045);
}

.did-symptom-card[aria-pressed="true"] .did-symptom-card-body h3 {
    color: var(--did-gold);
}

    /* =========================================================
       SELECTED PANEL
    ========================================================= */

    .did-selected-head {
        display: flex;

        align-items: center;

        gap: 12px;

        margin-bottom: 16px;
    }

    .did-count-badge {
        background:
            var(--did-gold);

        color:
            #1a1305;

        font-weight: 700;

        font-size: 0.85rem;

        padding:
            3px 12px;

        border-radius:
            999px;
    }

    .did-chips {
        display: flex;

        flex-wrap: wrap;

        gap: 10px;

        min-height: 48px;
    }

    .did-chip {
        display: inline-flex;

        align-items: center;

        gap: 9px;

        background:
            rgba(129,199,132,0.12);

        border:
            1px solid rgba(129,199,132,0.4);

        color:
            var(--did-light-green);

        padding:
            5px 10px 5px 5px;

        border-radius:
            999px;

        font-size:
            0.85rem;

        animation:
            did-chip-in .25s ease;
    }

    @keyframes did-chip-in {
        from {
            opacity: 0;

            transform:
                scale(0.8);
        }

        to {
            opacity: 1;

            transform:
                scale(1);
        }
    }

    .did-chip img {
        width: 30px;
        height: 30px;

        border-radius: 50%;

        object-fit: cover;

        border:
            1px solid rgba(255,255,255,0.2);
    }

    .did-chip .no-thumb {
        width: 30px;
        height: 30px;

        border-radius: 50%;

        background:
            rgba(255,255,255,0.08);

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 0.7rem;

        color:
            var(--did-sage);
    }

    .did-chip button {
        background:
            rgba(255,255,255,0.08);

        border: none;

        color:
            var(--did-cream);

        width: 20px;
        height: 20px;

        border-radius: 50%;

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 0.7rem;

        cursor: pointer;

        transition:
            background .2s ease;
    }

    .did-chip button:hover {
        background:
            var(--did-danger);
    }

    .did-empty-chip-hint {
        color:
            var(--did-sage);

        font-size:
            0.88rem;

        font-style:
            italic;
    }

    /* =========================================================
       STATUS
    ========================================================= */

    .did-status-msg {
        margin-top: 14px;

        font-size: 0.88rem;

        color:
            var(--did-sage);

        padding:
            10px 14px;

        background:
            rgba(255,255,255,0.03);

        border-left:
            3px solid var(--did-gold-deep);

        border-radius:
            6px;
    }

    /* =========================================================
       ANALYZE BUTTON
    ========================================================= */

    .did-analyze-btn {
        width: 100%;

        margin-top: 26px;

        padding: 16px;

        border: none;

        border-radius: 14px;

        background:
            linear-gradient(
                120deg,
                var(--did-primary),
                var(--did-secondary)
            );

        color: #fff;

        font-size: 1.05rem;

        font-weight: 600;

        display: flex;

        align-items: center;

        justify-content: center;

        gap: 10px;

        cursor: pointer;

        box-shadow:
            0 12px 28px
            rgba(27,94,32,0.4);

        transition:
            transform .2s ease,
            box-shadow .2s ease,
            opacity .2s ease;
    }

    .did-analyze-btn:hover:not(:disabled) {
        transform:
            translateY(-2px);

        box-shadow:
            0 16px 34px
            rgba(27,94,32,0.5);
    }

    .did-analyze-btn:disabled {
        opacity: 0.65;

        cursor: not-allowed;

        transform: none;
    }

    .did-analyze-btn .spinner {
        width: 18px;
        height: 18px;

        border:
            2.5px solid rgba(255,255,255,0.35);

        border-top-color:
            #fff;

        border-radius: 50%;

        animation:
            did-spin 0.8s linear infinite;

        display: none;
    }

    .did-analyze-btn.loading .spinner {
        display: inline-block;
    }

    .did-analyze-btn.loading .btn-icon {
        display: none;
    }

    @keyframes did-spin {
        to {
            transform:
                rotate(360deg);
        }
    }

    .did-scan-line {
        text-align: center;

        color:
            var(--did-sage);

        font-size:
            0.85rem;

        margin-top:
            12px;

        min-height:
            20px;
    }

    /* =========================================================
       RESULTS
    ========================================================= */

    .did-results {
        display: none;
    }

    .did-results.show {
        display: block;

        animation:
            did-fade-up .5s ease;
    }

    @keyframes did-fade-up {
        from {
            opacity: 0;

            transform:
                translateY(16px);
        }

        to {
            opacity: 1;

            transform:
                translateY(0);
        }
    }

    .did-result-header {
        text-align:
            center;

        margin-bottom:
            26px;
    }

    .did-result-header .tag {
        display:
            inline-block;

        background:
            rgba(129,199,132,0.12);

        color:
            var(--did-light-green);

        border:
            1px solid rgba(129,199,132,0.4);

        padding:
            4px 14px;

        border-radius:
            999px;

        font-size:
            0.78rem;

        letter-spacing:
            0.06em;

        text-transform:
            uppercase;

        margin-bottom:
            14px;
    }

    .did-best-match {
        display:
            flex;

        gap:
            28px;

        align-items:
            center;

        flex-wrap:
            wrap;
    }

    /* =========================================================
       MATCH RING
    ========================================================= */

    .did-ring-wrap {
        position:
            relative;

        width:
            130px;

        height:
            130px;

        flex-shrink:
            0;
    }

    .did-ring-wrap svg {
        transform:
            rotate(-90deg);
    }

    .did-ring-bg {
        fill:
            none;

        stroke:
            rgba(255,255,255,0.08);

        stroke-width:
            10;
    }

    .did-ring-fg {
        fill:
            none;

        stroke:
            var(--did-gold);

        stroke-width:
            10;

        stroke-linecap:
            round;

        stroke-dasharray:
            345.6;

        stroke-dashoffset:
            345.6;

        transition:
            stroke-dashoffset 1.1s
            cubic-bezier(.22,.9,.35,1);
    }

    .did-ring-pct {
        position:
            absolute;

        inset:
            0;

        display:
            flex;

        align-items:
            center;

        justify-content:
            center;

        font-size:
            1.4rem;

        font-weight:
            700;

        color:
            var(--did-cream);

        font-family:
            'Fraunces',
            Georgia,
            serif;
    }

    .did-ring-label {
        position:
            absolute;

        bottom:
            -22px;

        left:
            0;

        right:
            0;

        text-align:
            center;

        font-size:
            0.68rem;

        color:
            var(--did-sage);

        text-transform:
            uppercase;

        letter-spacing:
            0.05em;
    }

    .did-best-info {
        flex:
            1;

        min-width:
            220px;
    }

    .did-best-info .match-sub {
        color:
            var(--did-sage);

        font-size:
            0.9rem;
    }

    .did-best-info h3 {
        font-size:
            1.6rem;

        color:
            var(--did-gold);

        margin:
            4px 0 6px;
    }

    /* =========================================================
       RESULT DETAILS
    ========================================================= */

    .did-detail-block {
        margin-top:
            22px;
    }

    .did-detail-block h4 {
        font-size:
            0.95rem;

        margin-bottom:
            10px;

        display:
            flex;

        align-items:
            center;

        gap:
            8px;
    }

    .did-detail-block h4 i {
        color:
            var(--did-gold);
    }

    .did-matched-list,
    .did-unmatched-list {
        display:
            flex;

        flex-wrap:
            wrap;

        gap:
            8px;
    }

    .did-matched-pill {
        background:
            rgba(129,199,132,0.1);

        border:
            1px solid rgba(129,199,132,0.3);

        color:
            var(--did-light-green);

        padding:
            5px 12px;

        border-radius:
            999px;

        font-size:
            0.82rem;
    }

    .did-unmatched-pill {
        background:
            rgba(255,255,255,0.03);

        border:
            1px solid rgba(255,255,255,0.1);

        color:
            var(--did-sage);

        padding:
            5px 12px;

        border-radius:
            999px;

        font-size:
            0.82rem;
    }

    .did-detail-text {
        color:
            var(--did-sage);

        font-size:
            0.92rem;

        line-height:
            1.6;
    }

    .did-disclaimer {
        color:
            var(--did-sage);

        font-size:
            0.78rem;

        font-style:
            italic;

        margin-top:
            6px;
    }

    /* =========================================================
       ALTERNATIVE MATCHES
    ========================================================= */

    .did-alt-list {
        margin-top:
            8px;
    }

    .did-alt-card {
        display:
            flex;

        align-items:
            center;

        gap:
            14px;

        background:
            rgba(255,255,255,0.02);

        border:
            1px solid rgba(255,255,255,0.06);

        padding:
            12px 16px;

        border-radius:
            12px;

        margin-bottom:
            10px;

        opacity:
            0;

        animation:
            did-fade-up .4s ease forwards;
    }

    .did-alt-card .name {
        flex:
            1;

        font-size:
            0.9rem;
    }

    .did-alt-bar-track {
        flex:
            1.4;

        height:
            7px;

        border-radius:
            999px;

        background:
            rgba(255,255,255,0.08);

        overflow:
            hidden;
    }

    .did-alt-bar-fill {
        height:
            100%;

        border-radius:
            999px;

        background:
            linear-gradient(
                90deg,
                var(--did-secondary),
                var(--did-gold-deep)
            );

        width:
            0%;

        transition:
            width 0.8s ease;
    }

    .did-alt-pct {
        font-size:
            0.85rem;

        color:
            var(--did-sage);

        width:
            42px;

        text-align:
            right;
    }

    /* =========================================================
       ACTIONS
    ========================================================= */

    .did-actions {
        display:
            flex;

        gap:
            12px;

        margin-top:
            26px;

        flex-wrap:
            wrap;
    }

    .did-btn-secondary {
        flex:
            1;

        min-width:
            180px;

        padding:
            13px;

        border-radius:
            12px;

        border:
            1px solid rgba(255,255,255,0.15);

        background:
            rgba(255,255,255,0.03);

        color:
            var(--did-cream);

        font-weight:
            600;

        cursor:
            pointer;

        transition:
            all .2s ease;
    }

    .did-btn-secondary:hover {
        background:
            rgba(255,255,255,0.08);
    }

    /* =========================================================
       EMPTY / ERROR
    ========================================================= */

    .did-empty-state,
    .did-error-state {
        text-align:
            center;

        padding:
            40px 20px;
    }

    .did-empty-state i,
    .did-error-state i {
        font-size:
            2.4rem;

        color:
            var(--did-gold-deep);

        margin-bottom:
            14px;
    }

    .did-error-state i {
        color:
            var(--did-danger);
    }

    .did-empty-state h3,
    .did-error-state h3 {
        color:
            var(--did-cream);

        margin-bottom:
            8px;
    }

    .did-empty-state p,
    .did-error-state p {
        color:
            var(--did-sage);

        max-width:
            420px;

        margin:
            0 auto 18px;
    }

    .did-empty-state .did-actions,
    .did-error-state .did-actions {
        justify-content:
            center;
    }

    .did-empty-state .did-btn-secondary,
    .did-error-state .did-btn-secondary {
        flex:
            none;

        min-width:
            200px;
    }

    /* =========================================================
       MOBILE
    ========================================================= */

    @media (max-width: 575px) {

        .did-wrap {
            padding:
                30px 12px 70px;
        }

        .did-card {
            padding:
                22px 16px;
        }

        .did-actions {
            flex-direction:
                column;
        }

        .did-toolbar {
            flex-direction:
                column;

            align-items:
                stretch;
        }

        .did-search {
            width:
                100%;
        }

        .did-alt-card {
            flex-wrap:
                wrap;
        }

        .did-alt-bar-track {
            width:
                100%;

            flex-basis:
                100%;
        }
    }


    /* =========================================================
       FINAL COMPACT CARD REDESIGN
       - Smaller cards
       - Six-column desktop layout
       - Photo completely fills the image area
       - No padding / black side bars
       - Clean symptom typography
    ========================================================= */

    .did-symptom-grid {
        display: grid !important;
        grid-template-columns: repeat(6, minmax(0, 1fr)) !important;
        gap: 10px !important;
        align-items: stretch;
    }

    .did-symptom-card {
        width: 100% !important;
        min-width: 0 !important;
        padding: 0 !important;
        margin: 0 !important;
        border-radius: 11px !important;
        overflow: hidden !important;
        background: #10251b !important;
        border: 1px solid rgba(129,199,132,0.13) !important;
        box-shadow: 0 5px 14px rgba(0,0,0,0.14) !important;
        transform: none;
        display: block;
        appearance: none;
        -webkit-appearance: none;
    }

    .did-symptom-card:hover {
        transform: translateY(-2px) !important;
        border-color: rgba(255,213,79,0.38) !important;
        box-shadow: 0 9px 18px rgba(0,0,0,0.22) !important;
    }

    .did-image-wrapper {
        position: relative !important;
        width: 100% !important;
        height: 108px !important;
        padding: 0 !important;
        margin: 0 !important;
        overflow: hidden !important;
        display: block !important;
        background: #0a1d15 !important;
    }

    .did-image-wrapper img {
        position: absolute !important;
        inset: 0 !important;
        z-index: 1 !important;
        width: 100% !important;
        height: 100% !important;
        max-width: none !important;
        max-height: none !important;
        padding: 0 !important;
        margin: 0 !important;
        display: block !important;
        object-fit: cover !important;
        object-position: center center !important;
        background: transparent !important;
        border-radius: 0 !important;
        transform: none !important;
        filter: none !important;
    }

    .did-symptom-card:hover .did-image-wrapper img {
        transform: scale(1.035) !important;
        filter: brightness(1.04) saturate(1.05) !important;
    }

    .did-image-wrapper::before,
    .did-image-wrapper::after {
        content: none !important;
        display: none !important;
    }

    .did-image-fallback {
        position: absolute !important;
        inset: 0 !important;
        z-index: 1 !important;
        width: 100% !important;
        height: 100% !important;
        padding: 8px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        flex-direction: column !important;
        gap: 4px !important;
        background: linear-gradient(145deg, #10251b, #091a12) !important;
        color: var(--did-sage) !important;
        font-size: 0.65rem !important;
        line-height: 1.2 !important;
        text-align: center !important;
    }

    .did-image-fallback i {
        font-size: 1rem !important;
        color: var(--did-gold-deep) !important;
    }

    .did-selected-overlay {
        z-index: 5 !important;
    }

    .did-symptom-card-body {
        min-height: 58px !important;
        padding: 7px 9px 8px !important;
        background: #10251b !important;
    }

    .did-symptom-card-body h3 {
        font-family: 'Poppins', sans-serif !important;
        font-size: 0.74rem !important;
        font-weight: 600 !important;
        line-height: 1.25 !important;
        margin: 0 !important;
        color: var(--did-cream) !important;
        display: -webkit-box !important;
        -webkit-box-orient: vertical !important;
        -webkit-line-clamp: 2 !important;
        overflow: hidden !important;
        min-height: 1.86em !important;
    }

    .did-symptom-card-body .helper {
        margin-top: 2px !important;
        font-size: 0.57rem !important;
        line-height: 1.15 !important;
        color: var(--did-sage) !important;
        opacity: 0.78 !important;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .did-symptom-card[aria-pressed="true"] {
        border-color: var(--did-gold) !important;
        box-shadow:
            0 0 0 1px rgba(255,213,79,0.18),
            0 8px 18px rgba(255,213,79,0.10) !important;
        transform: translateY(-1px) !important;
    }

    .did-symptom-card[aria-pressed="true"] .did-symptom-card-body {
        background: linear-gradient(180deg, rgba(255,213,79,0.055), #10251b) !important;
    }

    .did-symptom-card[aria-pressed="true"] .did-symptom-card-body h3 {
        color: var(--did-gold) !important;
    }

    @media (max-width: 1250px) {
        .did-symptom-grid {
            grid-template-columns: repeat(5, minmax(0, 1fr)) !important;
        }
    }

    @media (max-width: 1050px) {
        .did-symptom-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
        }
    }

    @media (max-width: 800px) {
        .did-symptom-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
            gap: 9px !important;
        }

        .did-image-wrapper {
            height: 105px !important;
        }
    }

    @media (max-width: 600px) {
        .did-symptom-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 8px !important;
        }

        .did-image-wrapper {
            height: 112px !important;
        }

        .did-symptom-card-body {
            padding: 7px 8px 8px !important;
        }
    }

</style>


<div class="did-wrap">

    <!-- Decorative cardamom pods -->

    <svg
        class="did-pod p1"
        viewBox="0 0 60 100"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
        aria-hidden="true"
    >
        <ellipse
            cx="30"
            cy="50"
            rx="22"
            ry="42"
            fill="#81C784"
        />
    </svg>


    <svg
        class="did-pod p2"
        viewBox="0 0 60 100"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
        aria-hidden="true"
    >
        <ellipse
            cx="30"
            cy="50"
            rx="22"
            ry="42"
            fill="#FFD54F"
        />
    </svg>


    <div class="did-container">

        <!-- =====================================================
             HERO
        ====================================================== -->

        <div class="did-hero">

            <h1>
                Identify Your
                <span>Cardamom Disease</span>
            </h1>

            <p>
                Select the symptoms you can see on your cardamom
                plants. Cardo-Fer will compare your selections
                with the disease patterns recorded by the system.
            </p>

        </div>


        <!-- =====================================================
             STEP INDICATOR
        ====================================================== -->

        <div
            class="did-steps"
            id="didSteps"
        >

            <div
                class="did-step active"
                data-step="1"
            >
                <div class="num">01</div>
                <div class="label">Observe</div>
            </div>


            <div
                class="did-step"
                data-step="2"
            >
                <div class="num">02</div>
                <div class="label">Select Symptoms</div>
            </div>


            <div
                class="did-step"
                data-step="3"
            >
                <div class="num">03</div>
                <div class="label">Analyze</div>
            </div>


            <div
                class="did-step"
                data-step="4"
            >
                <div class="num">04</div>
                <div class="label">Result</div>
            </div>

        </div>


        <!-- =====================================================
             SYMPTOM SELECTION
        ====================================================== -->

        <div
            class="did-card"
            id="didSelectionCard"
        >

            <h2>
                What symptoms are you seeing?
            </h2>

            <div class="did-sub">
                Tap a symptom image to select it.
            </div>


            <!-- Search -->

            <div class="did-toolbar">

                <div class="did-search">

                    <i
                        class="fa-solid fa-magnifying-glass"
                        aria-hidden="true"
                    ></i>

                    <input
                        type="text"
                        id="didSearchInput"
                        placeholder="Search symptoms..."
                        aria-label="Search symptoms"
                        autocomplete="off"
                    >

                </div>


                <div
                    class="did-result-count"
                    id="didResultCount"
                >
                    {{ $symptoms->count() }} symptoms
                </div>

            </div>


            <!-- Symptom cards -->

            <div
                class="did-symptom-grid"
                id="didSymptomGrid"
            >

                @forelse($symptoms as $symptom)

                    <button
                        type="button"
                        class="did-symptom-card"
                        data-id="{{ $symptom->symptom_id }}"
                        data-name="{{ $symptom->symptom_name }}"
                        tabindex="0"
                        aria-pressed="false"
                    >

                        <div class="did-image-wrapper">

                            @if($symptom->symptom_image)

                                <img
                                    src="{{ asset('storage/' . $symptom->symptom_image) }}"
                                    alt="Cardamom symptom: {{ $symptom->symptom_name }}"
                                    loading="lazy"
                                >

                            @else

                                <div class="did-image-fallback">

                                    <i class="fa-solid fa-image"></i>

                                    <span>
                                        Symptom image unavailable
                                    </span>

                                </div>

                            @endif


                            <div class="did-selected-overlay">

                                <span>
                                    <i class="fa-solid fa-check"></i>
                                </span>

                            </div>

                        </div>


                        <div class="did-symptom-card-body">

                            <h3>
                                {{ $symptom->symptom_name }}
                            </h3>

                            <div class="helper">
                                Select this symptom
                            </div>

                        </div>

                    </button>

                @empty

                    <div
                        class="did-detail-text"
                        style="grid-column: 1 / -1;"
                    >
                        No symptoms have been registered by the admin yet.
                    </div>

                @endforelse

            </div>


            <p
                class="did-detail-text"
                id="didNoResults"
                style="display: none;"
            >
                No symptoms match your search.
            </p>

        </div>


        <!-- =====================================================
             SELECTED SYMPTOMS
        ====================================================== -->

        <div class="did-card">

            <div class="did-selected-head">

                <h2 style="margin-bottom: 0;">
                    Selected Symptoms
                </h2>

                <span
                    class="did-count-badge"
                    id="didCountBadge"
                >
                    0
                </span>

            </div>


            <div
                class="did-chips"
                id="didChips"
            >

                <span class="did-empty-chip-hint">
                    No symptoms selected yet — tap a photo above to begin.
                </span>

            </div>


            <div
                class="did-status-msg"
                id="didStatusMsg"
            >
                Select the symptoms you observe.
            </div>


            <button
                type="button"
                class="did-analyze-btn"
                id="didAnalyzeBtn"
                disabled
            >

                <span class="btn-icon">
                    <i class="fa-solid fa-magnifying-glass-chart"></i>
                </span>

                <span class="spinner"></span>

                <span id="didAnalyzeLabel">
                    Analyze Symptoms
                </span>

            </button>


            <div
                class="did-scan-line"
                id="didScanLine"
            ></div>

        </div>


        <!-- =====================================================
             RESULTS
        ====================================================== -->

        <div
            class="did-card did-results"
            id="didResults"
        ></div>

    </div>

</div>


@push('scripts')

<script>
(function () {

    'use strict';


    /* =========================================================
       ROUTES / CSRF
    ========================================================= */

    const analyzeUrl =
        "{{ route('disease.identification.analyze') }}";

    const csrfToken =
        "{{ csrf_token() }}";


    /* =========================================================
       ELEMENTS
    ========================================================= */

    const grid =
        document.getElementById(
            'didSymptomGrid'
        );

    const searchInput =
        document.getElementById(
            'didSearchInput'
        );

    const noResults =
        document.getElementById(
            'didNoResults'
        );

    const resultCount =
        document.getElementById(
            'didResultCount'
        );

    const chipsWrap =
        document.getElementById(
            'didChips'
        );

    const countBadge =
        document.getElementById(
            'didCountBadge'
        );

    const statusMsg =
        document.getElementById(
            'didStatusMsg'
        );

    const analyzeBtn =
        document.getElementById(
            'didAnalyzeBtn'
        );

    const analyzeLabel =
        document.getElementById(
            'didAnalyzeLabel'
        );

    const scanLine =
        document.getElementById(
            'didScanLine'
        );

    const resultsBox =
        document.getElementById(
            'didResults'
        );

    const steps =
        document.querySelectorAll(
            '.did-step'
        );


    /* =========================================================
       SAFETY CHECK
    ========================================================= */

    if (
        !grid ||
        !searchInput ||
        !chipsWrap ||
        !countBadge ||
        !statusMsg ||
        !analyzeBtn ||
        !resultsBox
    ) {
        return;
    }


    const totalSymptomCount =
        grid.querySelectorAll(
            '.did-symptom-card'
        ).length;


    /* =========================================================
       STATE
    ========================================================= */

    // id -> { name, image }

    let selected =
        new Map();

    let isSubmitting =
        false;

    let scanInterval =
        null;


    /* =========================================================
       STEP INDICATOR
    ========================================================= */

    function setStep(number) {

        steps.forEach(function (step) {

            const stepNumber =
                parseInt(
                    step.dataset.step,
                    10
                );

            step.classList.remove(
                'active',
                'done'
            );


            if (stepNumber < number) {

                step.classList.add(
                    'done'
                );

            }


            if (stepNumber === number) {

                step.classList.add(
                    'active'
                );

            }

        });

    }


    /* =========================================================
       STATUS
    ========================================================= */

    function updateStatusMessage() {

        const count =
            selected.size;


        if (count === 0) {

            statusMsg.textContent =
                'Select the symptoms you observe.';

        } else if (count === 1) {

            statusMsg.textContent =
                'Add more symptoms for a stronger comparison.';

        } else if (count === 2) {

            statusMsg.textContent =
                'Good start. Add another symptom for a better match.';

        } else {

            statusMsg.textContent =
                'Enough symptoms selected. You can analyze now.';

        }

    }


    /* =========================================================
       HTML ESCAPE
    ========================================================= */

    function escapeHtml(value) {

        const div =
            document.createElement('div');

        div.textContent =
            value ?? '';

        return div.innerHTML;

    }


    /* =========================================================
       RENDER SELECTED CHIPS
    ========================================================= */

    function renderChips() {

        countBadge.textContent =
            selected.size;

        chipsWrap.innerHTML =
            '';


        if (selected.size === 0) {

            chipsWrap.innerHTML = `
                <span class="did-empty-chip-hint">
                    No symptoms selected yet —
                    tap a photo above to begin.
                </span>
            `;

            analyzeBtn.disabled =
                true;

            setStep(1);

        } else {

            selected.forEach(
                function (data, id) {

                    const chip =
                        document.createElement('span');

                    chip.className =
                        'did-chip';


                    const thumb =
                        data.image
                            ? `
                                <img
                                    src="${escapeHtml(data.image)}"
                                    alt=""
                                >
                              `
                            : `
                                <span class="no-thumb">
                                    <i class="fa-solid fa-leaf"></i>
                                </span>
                              `;


                    chip.innerHTML = `
                        ${thumb}

                        <span>
                            ${escapeHtml(data.name)}
                        </span>
                    `;


                    const removeBtn =
                        document.createElement('button');

                    removeBtn.type =
                        'button';

                    removeBtn.setAttribute(
                        'aria-label',
                        `Remove ${data.name}`
                    );

                    removeBtn.innerHTML =
                        '<i class="fa-solid fa-xmark"></i>';


                    removeBtn.addEventListener(
                        'click',
                        function () {

                            toggleSymptom(
                                id,
                                data.name,
                                data.image,
                                false
                            );

                        }
                    );


                    chip.appendChild(
                        removeBtn
                    );

                    chipsWrap.appendChild(
                        chip
                    );

                }
            );


            analyzeBtn.disabled =
                false;

            setStep(2);

        }


        updateStatusMessage();

    }


    /* =========================================================
       SELECT / DESELECT SYMPTOM
    ========================================================= */

    function toggleSymptom(
        id,
        name,
        image,
        forceState
    ) {

        const card =
            grid.querySelector(
                `.did-symptom-card[data-id="${CSS.escape(String(id))}"]`
            );


        const shouldSelect =
            typeof forceState === 'boolean'
                ? forceState
                : !selected.has(String(id));


        if (shouldSelect) {

            selected.set(
                String(id),
                {
                    name: name,
                    image: image || null
                }
            );


            if (card) {

                card.setAttribute(
                    'aria-pressed',
                    'true'
                );

            }

        } else {

            selected.delete(
                String(id)
            );


            if (card) {

                card.setAttribute(
                    'aria-pressed',
                    'false'
                );

            }

        }


        renderChips();

    }


    /* =========================================================
       SYMPTOM CARD CLICK
    ========================================================= */

    grid.addEventListener(
        'click',
        function (event) {

            const card =
                event.target.closest(
                    '.did-symptom-card'
                );


            if (!card) {
                return;
            }


            const imageElement =
                card.querySelector(
                    '.did-image-wrapper img'
                );


            toggleSymptom(
                card.dataset.id,
                card.dataset.name,
                imageElement
                    ? imageElement.currentSrc ||
                      imageElement.src
                    : null
            );

        }
    );


    /* =========================================================
       SEARCH
    ========================================================= */

    searchInput.addEventListener(
        'input',
        function () {

            const query =
                this.value
                    .trim()
                    .toLowerCase();


            let visibleCount =
                0;


            grid.querySelectorAll(
                '.did-symptom-card'
            ).forEach(
                function (card) {

                    const name =
                        (
                            card.dataset.name ||
                            ''
                        ).toLowerCase();


                    const match =
                        name.includes(
                            query
                        );


                    card.classList.toggle(
                        'did-hidden',
                        !match
                    );


                    if (match) {
                        visibleCount++;
                    }

                }
            );


            if (noResults) {

                noResults.style.display =
                    visibleCount === 0 &&
                    totalSymptomCount > 0
                        ? 'block'
                        : 'none';

            }


            if (resultCount) {

                resultCount.textContent =
                    query === ''
                        ? `${totalSymptomCount} symptoms`
                        : `${visibleCount} matching`;

            }

        }
    );


    /* =========================================================
       SCAN MESSAGES
    ========================================================= */

    const scanMessages = [
        'Reading symptoms...',
        'Comparing disease patterns...',
        'Finding closest match...'
    ];


    function startScanMessages() {

        let index =
            0;


        if (scanLine) {

            scanLine.textContent =
                scanMessages[0];

        }


        scanInterval =
            setInterval(
                function () {

                    index =
                        (index + 1) %
                        scanMessages.length;


                    if (scanLine) {

                        scanLine.textContent =
                            scanMessages[index];

                    }

                },
                500
            );

    }


    function stopScanMessages() {

        clearInterval(
            scanInterval
        );

        scanInterval =
            null;


        if (scanLine) {

            scanLine.textContent =
                '';

        }

    }


    /* =========================================================
       ANALYZE
    ========================================================= */

    analyzeBtn.addEventListener(
        'click',
        function () {

            if (
                isSubmitting ||
                selected.size === 0
            ) {
                return;
            }


            isSubmitting =
                true;


            analyzeBtn.disabled =
                true;

            analyzeBtn.classList.add(
                'loading'
            );


            analyzeLabel.textContent =
                'Analyzing...';


            setStep(3);

            startScanMessages();


            fetch(
                analyzeUrl,
                {
                    method: 'POST',

                    headers: {
                        'Content-Type':
                            'application/json',

                        'X-CSRF-TOKEN':
                            csrfToken,

                        'Accept':
                            'application/json'
                    },

                    body:
                        JSON.stringify({
                            symptoms:
                                Array.from(
                                    selected.keys()
                                )
                        })
                }
            )
            .then(
                async function (response) {

                    let data;


                    try {

                        data =
                            await response.json();

                    } catch (error) {

                        throw new Error(
                            'bad_response'
                        );

                    }


                    if (!response.ok) {

                        if (
                            response.status === 422
                        ) {

                            return data;

                        }


                        throw new Error(
                            'server_error'
                        );

                    }


                    return data;

                }
            )
            .then(
                renderResult
            )
            .catch(
                function (error) {

                    renderError(
                        error.message ===
                            'server_error'
                            ? 'The server had trouble processing your request. Please try again.'
                            : 'Could not reach the server. Check your connection and try again.'
                    );

                }
            )
            .finally(
                function () {

                    stopScanMessages();


                    analyzeBtn.classList.remove(
                        'loading'
                    );


                    analyzeLabel.textContent =
                        'Analyze Symptoms';


                    analyzeBtn.disabled =
                        selected.size === 0;


                    isSubmitting =
                        false;

                }
            );

        }
    );


    /* =========================================================
       RENDER RESULT
    ========================================================= */

    function renderResult(data) {

        if (!data || !data.success) {

            renderError(
                data?.message ||
                'Something went wrong. Please try again.'
            );

            return;

        }


        if (!data.result) {

            resultsBox.innerHTML = `
                <div class="did-empty-state">

                    <i class="fa-solid fa-leaf"></i>

                    <h3>
                        No Strong Symptom Match Found
                    </h3>

                    <p>
                        Try selecting a few more symptoms.
                    </p>

                    <div class="did-actions">

                        <button
                            type="button"
                            class="did-btn-secondary"
                            id="didSelectMoreBtn"
                        >
                            Select More Symptoms
                        </button>

                        <button
                            type="button"
                            class="did-btn-secondary"
                            id="didResetEmptyBtn"
                        >
                            Reset Analysis
                        </button>

                    </div>

                </div>
            `;


            const selectMoreBtn =
                document.getElementById(
                    'didSelectMoreBtn'
                );

            const resetEmptyBtn =
                document.getElementById(
                    'didResetEmptyBtn'
                );


            if (selectMoreBtn) {

                selectMoreBtn.addEventListener(
                    'click',
                    scrollToSelection
                );

            }


            if (resetEmptyBtn) {

                resetEmptyBtn.addEventListener(
                    'click',
                    resetAll
                );

            }


            showResults();

            setStep(4);

            return;

        }


        const result =
            data.result;

        const alternatives =
            Array.isArray(
                data.alternatives
            )
                ? data.alternatives
                : [];


        const percentage =
            Math.max(
                0,
                Math.min(
                    100,
                    Number(
                        result.match_percentage || 0
                    )
                )
            );


        const circumference =
            345.6;


        const offset =
            circumference -
            (
                circumference *
                percentage /
                100
            );


        let detailBlocks =
            '';


        if (result.causes) {

            detailBlocks += `
                <div class="did-detail-block">

                    <h4>
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        Possible Causes
                    </h4>

                    <p class="did-detail-text">
                        ${escapeHtml(result.causes)}
                    </p>

                </div>
            `;

        }


        if (result.prevention) {

            detailBlocks += `
                <div class="did-detail-block">

                    <h4>
                        <i class="fa-solid fa-shield-heart"></i>
                        Prevention & Management
                    </h4>

                    <p class="did-detail-text">
                        ${escapeHtml(result.prevention)}
                    </p>

                </div>
            `;

        }


        if (result.fertilizer) {

            detailBlocks += `
                <div class="did-detail-block">

                    <h4>
                        <i class="fa-solid fa-seedling"></i>
                        Recommended Fertilizer
                    </h4>

                    <p class="did-detail-text">
                        ${escapeHtml(result.fertilizer)}
                    </p>

                </div>
            `;

        }


        let unmatchedHtml =
            '';


        if (
            Array.isArray(
                result.unmatched_symptoms
            ) &&
            result.unmatched_symptoms.length > 0
        ) {

            unmatchedHtml = `
                <div class="did-detail-block">

                    <h4>
                        <i class="fa-solid fa-circle-question"></i>
                        Other Symptoms of This Disease
                    </h4>

                    <div class="did-unmatched-list">

                        ${
                            result.unmatched_symptoms
                                .map(
                                    function (symptom) {

                                        return `
                                            <span class="did-unmatched-pill">
                                                ${escapeHtml(symptom)}
                                            </span>
                                        `;

                                    }
                                )
                                .join('')
                        }

                    </div>

                </div>
            `;

        }


        let alternativesHtml =
            '';


        if (alternatives.length > 0) {

            alternativesHtml = `
                <div class="did-detail-block did-alt-list">

                    <h4>
                        <i class="fa-solid fa-layer-group"></i>
                        Other Possible Matches
                    </h4>

                    ${
                        alternatives
                            .map(
                                function (alternative, index) {

                                    const altPercentage =
                                        Math.max(
                                            0,
                                            Math.min(
                                                100,
                                                Number(
                                                    alternative.match_percentage || 0
                                                )
                                            )
                                        );


                                    return `
                                        <div
                                            class="did-alt-card"
                                            style="animation-delay:${index * 0.1}s"
                                        >

                                            <span class="name">
                                                ${escapeHtml(
                                                    alternative.disease_name || ''
                                                )}
                                            </span>

                                            <div class="did-alt-bar-track">

                                                <div
                                                    class="did-alt-bar-fill"
                                                    data-pct="${altPercentage}"
                                                ></div>

                                            </div>

                                            <span class="did-alt-pct">
                                                ${altPercentage}%
                                            </span>

                                        </div>
                                    `;

                                }
                            )
                            .join('')
                    }

                </div>
            `;

        }


        const matchedSymptoms =
            Array.isArray(
                result.matched_symptoms
            )
                ? result.matched_symptoms
                : [];


        resultsBox.innerHTML = `

            <div class="did-result-header">

                <span class="tag">
                    Analysis Complete
                </span>

            </div>


            <div class="did-best-match">

                <div class="did-ring-wrap">

                    <svg
                        viewBox="0 0 120 120"
                        width="130"
                        height="130"
                    >

                        <circle
                            class="did-ring-bg"
                            cx="60"
                            cy="60"
                            r="55"
                        ></circle>


                        <circle
                            class="did-ring-fg"
                            id="didRingFg"
                            cx="60"
                            cy="60"
                            r="55"
                        ></circle>

                    </svg>


                    <div
                        class="did-ring-pct"
                        id="didRingPct"
                    >
                        0%
                    </div>


                    <div class="did-ring-label">
                        Symptom Match
                    </div>

                </div>


                <div class="did-best-info">

                    <div class="match-sub">
                        Most Likely Match
                    </div>

                    <h3>
                        ${escapeHtml(
                            result.disease_name || 'Unknown Disease'
                        )}
                    </h3>

                    <div class="match-sub">

                        ${Number(result.matched_count || 0)}
                        of
                        ${Number(result.selected_count || 0)}
                        selected symptoms matched

                        (
                        ${Number(result.total_disease_symptoms || 0)}
                        known symptoms for this disease
                        )

                    </div>


                    <div class="did-disclaimer">
                        This is a symptom-pattern match,
                        not a medical or scientific diagnosis.
                    </div>

                </div>

            </div>


            <div class="did-detail-block">

                <h4>
                    <i class="fa-solid fa-check-double"></i>
                    Matched Symptoms
                </h4>

                <div class="did-matched-list">

                    ${
                        matchedSymptoms.length > 0
                            ? matchedSymptoms
                                .map(
                                    function (symptom) {

                                        return `
                                            <span class="did-matched-pill">
                                                ✓ ${escapeHtml(symptom)}
                                            </span>
                                        `;

                                    }
                                )
                                .join('')
                            : `
                                <span class="did-unmatched-pill">
                                    No exact symptom names returned.
                                </span>
                              `
                    }

                </div>

            </div>


            ${unmatchedHtml}

            ${detailBlocks}

            ${alternativesHtml}


            <div class="did-actions">

                <button
                    type="button"
                    class="did-btn-secondary"
                    id="didResetBtn"
                >

                    <i class="fa-solid fa-rotate-left"></i>

                    Analyze Another Case

                </button>

            </div>

        `;


        showResults();

        setStep(4);


        requestAnimationFrame(
            function () {

                const ringFg =
                    document.getElementById(
                        'didRingFg'
                    );

                const ringPct =
                    document.getElementById(
                        'didRingPct'
                    );


                if (ringFg) {

                    ringFg.style.strokeDashoffset =
                        offset;

                }


                if (ringPct) {

                    animateCount(
                        ringPct,
                        percentage
                    );

                }


                document
                    .querySelectorAll(
                        '.did-alt-bar-fill'
                    )
                    .forEach(
                        function (bar) {

                            requestAnimationFrame(
                                function () {

                                    bar.style.width =
                                        `${bar.dataset.pct}%`;

                                }
                            );

                        }
                    );

            }
        );


        const resetButton =
            document.getElementById(
                'didResetBtn'
            );


        if (resetButton) {

            resetButton.addEventListener(
                'click',
                resetAll
            );

        }

    }


    /* =========================================================
       ANIMATED PERCENTAGE
    ========================================================= */

    function animateCount(
        element,
        target
    ) {

        let current =
            0;


        const numericTarget =
            Number(target) || 0;


        const step =
            Math.max(
                1,
                Math.round(
                    numericTarget / 30
                )
            );


        const timer =
            setInterval(
                function () {

                    current += step;


                    if (
                        current >=
                        numericTarget
                    ) {

                        current =
                            numericTarget;

                        clearInterval(
                            timer
                        );

                    }


                    element.textContent =
                        `${current}%`;

                },
                20
            );

    }


    /* =========================================================
       ERROR
    ========================================================= */

    function renderError(message) {

        resultsBox.innerHTML = `

            <div class="did-error-state">

                <i class="fa-solid fa-circle-exclamation"></i>

                <h3>
                    Something Went Wrong
                </h3>

                <p>
                    ${escapeHtml(message)}
                </p>

                <button
                    type="button"
                    class="did-btn-secondary"
                    id="didRetryBtn"
                >
                    Try Again
                </button>

            </div>

        `;


        const retryButton =
            document.getElementById(
                'didRetryBtn'
            );


        if (retryButton) {

            retryButton.addEventListener(
                'click',
                function () {

                    resultsBox.classList.remove(
                        'show'
                    );

                }
            );

        }


        showResults();

    }


    /* =========================================================
       SHOW RESULTS
    ========================================================= */

    function showResults() {

        resultsBox.classList.add(
            'show'
        );


        resultsBox.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });

    }


    /* =========================================================
       SCROLL TO SELECTION
    ========================================================= */

    function scrollToSelection() {

        resultsBox.classList.remove(
            'show'
        );


        const selectionCard =
            document.getElementById(
                'didSelectionCard'
            );


        if (selectionCard) {

            selectionCard.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });

        }

    }


    /* =========================================================
       RESET
    ========================================================= */

    function resetAll() {

        selected.clear();


        grid.querySelectorAll(
            '.did-symptom-card'
        ).forEach(
            function (card) {

                card.setAttribute(
                    'aria-pressed',
                    'false'
                );

            }
        );


        renderChips();


        resultsBox.classList.remove(
            'show'
        );


        resultsBox.innerHTML =
            '';


        searchInput.value =
            '';


        grid.querySelectorAll(
            '.did-symptom-card'
        ).forEach(
            function (card) {

                card.classList.remove(
                    'did-hidden'
                );

            }
        );


        if (noResults) {

            noResults.style.display =
                'none';

        }


        if (resultCount) {

            resultCount.textContent =
                `${totalSymptomCount} symptoms`;

        }


        const selectionCard =
            document.getElementById(
                'didSelectionCard'
            );


        if (selectionCard) {

            selectionCard.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });

        }

    }


    /* =========================================================
       INITIAL STATE
    ========================================================= */

    renderChips();

})();
</script>

@endpush

@endsection