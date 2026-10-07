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
       SYMPTOM HEADER / COUNT
    ========================================================= */

    .did-selection-head-row {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 18px;
        margin-bottom: 22px;
    }

    .did-selection-head-copy .did-sub {
        margin-bottom: 0;
    }

    .did-result-count {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 999px;
        color: var(--did-sage);
        background: rgba(129, 199, 132, 0.07);
        border: 1px solid rgba(129, 199, 132, 0.14);
        font-size: 0.78rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .did-result-count i {
        color: var(--did-gold);
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
       AI IDENTIFICATION RESULT
    ========================================================= */

    .did-ai-warning {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        margin-bottom: 24px;
        padding: 16px 18px;
        border: 1px solid rgba(255,213,79,0.28);
        border-left: 4px solid var(--did-gold);
        border-radius: 14px;
        background: rgba(255,213,79,0.06);
    }

    .did-ai-warning-icon {
        width: 38px;
        height: 38px;
        flex: 0 0 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(255,213,79,0.12);
        color: var(--did-gold);
    }

    .did-ai-warning strong {
        display: block;
        margin-bottom: 3px;
        color: var(--did-gold);
        font-size: 0.92rem;
    }

    .did-ai-warning p {
        margin: 0;
        color: var(--did-sage);
        font-size: 0.82rem;
        line-height: 1.55;
    }

    .did-ai-source {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-top: 8px;
        padding: 5px 10px;
        border-radius: 999px;
        background: rgba(129,199,132,0.10);
        border: 1px solid rgba(129,199,132,0.24);
        color: var(--did-light-green);
        font-size: 0.78rem;
    }

    .did-ai-confidence {
        margin-top: 10px;
        color: var(--did-sage);
        font-size: 0.88rem;
    }

    .did-ai-confidence strong {
        color: var(--did-gold);
    }

    .did-ai-reason {
        margin-top: 22px;
        padding: 16px 18px;
        border-radius: 14px;
        background: rgba(129,199,132,0.06);
        border: 1px solid rgba(129,199,132,0.16);
    }

    .did-ai-reason-title {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
        color: var(--did-light-green);
        font-size: 0.92rem;
        font-weight: 600;
    }

    .did-ai-reason-title i {
        color: var(--did-gold);
    }

    .did-ai-reason p {
        margin: 0;
        color: var(--did-sage);
        font-size: 0.9rem;
        line-height: 1.6;
    }

    /* =========================================================
       AI IMAGE ANALYSIS — CREATIVE UPLOAD UI
    ========================================================= */

    .did-image-analysis-card {
        position: relative;
        overflow: hidden;
        margin-top: 28px;
        border: 1px solid rgba(129, 199, 132, 0.17);
        background:
            radial-gradient(circle at 88% 12%, rgba(255, 213, 79, 0.08), transparent 23%),
            radial-gradient(circle at 8% 90%, rgba(76, 175, 80, 0.10), transparent 28%),
            linear-gradient(145deg, rgba(255,255,255,0.035), rgba(255,255,255,0.012));
    }

    .did-image-analysis-card::before,
    .did-image-analysis-card::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
        opacity: .42;
    }

    .did-image-analysis-card::before {
        width: 190px;
        height: 190px;
        right: -88px;
        top: 48px;
        border: 1px solid rgba(255, 213, 79, 0.11);
    }

    .did-image-analysis-card::after {
        width: 135px;
        height: 135px;
        left: -70px;
        bottom: -62px;
        border: 1px solid rgba(129, 199, 132, 0.13);
    }

    .did-image-analysis-head {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 8px;
    }

    .did-image-analysis-title {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .did-image-analysis-icon {
        width: 44px;
        height: 44px;
        flex: 0 0 44px;
        display: grid;
        place-items: center;
        border-radius: 14px;
        color: var(--did-gold);
        background:
            linear-gradient(145deg, rgba(255,213,79,0.12), rgba(76,175,80,0.08));
        border: 1px solid rgba(255,213,79,0.18);
        box-shadow: 0 12px 28px rgba(0,0,0,0.16);
    }

    .did-eyebrow {
        margin-bottom: 2px;
        color: rgba(169,201,174,0.65);
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .13em;
    }

    .did-image-analysis-head h2 {
        margin: 0;
    }

    .did-image-analysis-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 10px;
        border-radius: 999px;
        color: var(--did-light-green);
        background: rgba(129,199,132,0.07);
        border: 1px solid rgba(129,199,132,0.14);
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: .05em;
        white-space: nowrap;
    }

    .did-image-analysis-sub {
        position: relative;
        z-index: 2;
        max-width: 780px;
        color: var(--did-sage);
        font-size: 0.88rem;
        line-height: 1.65;
        margin-bottom: 18px;
    }

    .did-image-upload-area {
        position: relative;
        z-index: 2;
        min-height: 245px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 26px;
        border: 1px solid rgba(129,199,132,0.17);
        background:
            radial-gradient(circle at 50% 35%, rgba(255,255,255,0.026), transparent 34%),
            linear-gradient(180deg, rgba(255,255,255,0.016), rgba(0,0,0,0.13));
        box-shadow:
            inset 0 0 0 1px rgba(255,255,255,0.012),
            0 18px 40px rgba(0,0,0,0.12);
        overflow: hidden;
        cursor: pointer;
        transition:
            transform .25s ease,
            border-color .25s ease,
            box-shadow .25s ease,
            background .25s ease;
    }

    .did-image-upload-area::before {
        content: '';
        position: absolute;
        inset: 14px;
        border-radius: 20px;
        border: 1px dashed rgba(129,199,132,0.13);
        pointer-events: none;
    }

    .did-image-upload-area:hover,
    .did-image-upload-area.dragover {
        transform: translateY(-2px);
        border-color: rgba(255,213,79,0.34);
        background:
            radial-gradient(circle at 50% 36%, rgba(255,213,79,0.055), transparent 34%),
            linear-gradient(180deg, rgba(255,255,255,0.02), rgba(0,0,0,0.10));
        box-shadow:
            inset 0 0 0 1px rgba(255,255,255,0.012),
            0 22px 48px rgba(0,0,0,0.20);
    }

    .did-image-glow {
        position: absolute;
        width: 140px;
        height: 140px;
        border-radius: 50%;
        filter: blur(25px);
        pointer-events: none;
        opacity: .10;
    }

    .did-image-glow.glow-one {
        top: 8px;
        left: 14%;
        background: #81C784;
    }

    .did-image-glow.glow-two {
        right: 10%;
        bottom: 10px;
        background: #FFD54F;
    }

    .did-image-upload-content {
        position: relative;
        z-index: 3;
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 34px 24px;
    }

    .did-image-camera-ring {
        position: relative;
        width: 96px;
        height: 96px;
        margin-bottom: 16px;
        display: grid;
        place-items: center;
    }

    .did-image-camera-ring::before,
    .did-image-camera-ring::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        border: 1px solid rgba(129,199,132,0.13);
    }

    .did-image-camera-ring::before {
        inset: 0;
    }

    .did-image-camera-ring::after {
        inset: 10px;
        border-color: rgba(255,213,79,0.17);
    }

    .did-image-camera-core {
        position: relative;
        z-index: 2;
        width: 58px;
        height: 58px;
        display: grid;
        place-items: center;
        border-radius: 18px;
        color: var(--did-light-green);
        background:
            linear-gradient(145deg, rgba(129,199,132,0.15), rgba(255,213,79,0.07));
        border: 1px solid rgba(129,199,132,0.21);
        box-shadow: 0 15px 30px rgba(0,0,0,0.22);
    }

    .did-image-upload-content h3 {
        margin: 0 0 7px;
        font-family: 'Poppins', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        color: var(--did-cream);
    }

    .did-image-upload-content > p {
        margin: 0 0 14px;
        color: var(--did-sage);
        font-size: 0.8rem;
    }

    .did-image-upload-tags {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 7px;
        margin-bottom: 17px;
    }

    .did-image-upload-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 9px;
        border-radius: 999px;
        color: rgba(241,248,233,0.70);
        background: rgba(0,0,0,0.15);
        border: 1px solid rgba(255,255,255,0.07);
        font-size: 0.68rem;
        font-weight: 600;
    }

    .did-image-upload-tag i {
        color: var(--did-gold);
        font-size: 0.62rem;
    }

    .did-image-select-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 17px;
        border-radius: 999px;
        border: 1px solid rgba(255,213,79,0.28);
        background: linear-gradient(135deg, rgba(255,213,79,0.13), rgba(255,213,79,0.045));
        color: var(--did-gold);
        font-size: 0.82rem;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 10px 24px rgba(0,0,0,0.18);
        transition: transform .2s ease, background .2s ease, box-shadow .2s ease;
    }

    .did-image-select-btn:hover {
        transform: translateY(-1px);
        background: linear-gradient(135deg, rgba(255,213,79,0.18), rgba(255,213,79,0.07));
        box-shadow: 0 14px 28px rgba(0,0,0,0.22);
    }

    .did-image-drop-hint {
        margin-top: 10px;
        color: rgba(169,201,174,0.48);
        font-size: 0.66rem;
    }

    #didImageInput {
        display: none;
    }

    .did-image-preview-wrap {
        position: relative;
        z-index: 2;
        display: none;
        grid-template-columns: minmax(190px, 330px) 1fr;
        gap: 18px;
        align-items: center;
        margin-top: 18px;
        padding: 14px;
        border-radius: 20px;
        border: 1px solid rgba(129,199,132,0.13);
        background: rgba(0,0,0,0.13);
    }

    .did-image-preview-wrap.show {
        display: grid;
    }

    .did-image-preview-frame {
        position: relative;
        overflow: hidden;
        min-height: 220px;
        border-radius: 16px;
        background: #091a12;
        border: 1px solid rgba(255,255,255,0.08);
    }

    .did-image-preview {
        display: block;
        width: 100%;
        height: 220px;
        object-fit: cover;
    }

    .did-image-ready-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 8px;
        border-radius: 999px;
        color: var(--did-light-green);
        background: rgba(4,17,11,0.74);
        border: 1px solid rgba(129,199,132,0.18);
        font-size: 0.62rem;
        font-weight: 700;
    }

    .did-image-preview-meta {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 11px;
        min-width: 0;
    }

    .did-image-preview-kicker {
        color: rgba(169,201,174,0.55);
        font-size: 0.66rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .11em;
    }

    .did-image-file-name {
        color: var(--did-cream);
        font-size: 0.9rem;
        font-weight: 600;
        word-break: break-word;
    }

    .did-image-note {
        max-width: 540px;
        color: var(--did-sage);
        font-size: 0.75rem;
        line-height: 1.55;
    }

    .did-image-remove-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 999px;
        padding: 8px 12px;
        background: rgba(255,255,255,0.025);
        color: var(--did-sage);
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: border-color .2s ease, background .2s ease, color .2s ease;
    }

    .did-image-remove-btn:hover {
        border-color: rgba(220,90,90,0.30);
        background: rgba(220,90,90,0.05);
        color: #efaaaa;
    }


    /* =========================================================
       IMAGE AI ANALYSIS / CONFIRMATION
    ========================================================= */

    .did-image-ai-actions {
        width: 100%;
        margin-top: 2px;
    }

    .did-image-ai-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 12px 16px;
        border: 1px solid rgba(255,213,79,0.28);
        border-radius: 12px;
        background: linear-gradient(
            135deg,
            rgba(255,213,79,0.14),
            rgba(76,175,80,0.08)
        );
        color: var(--did-gold);
        font-family: 'Poppins', sans-serif;
        font-size: 0.84rem;
        font-weight: 700;
        cursor: pointer;
        transition:
            transform .2s ease,
            background .2s ease,
            box-shadow .2s ease,
            opacity .2s ease;
    }

    .did-image-ai-btn:hover:not(:disabled) {
        transform: translateY(-1px);
        background: linear-gradient(
            135deg,
            rgba(255,213,79,0.19),
            rgba(76,175,80,0.11)
        );
        box-shadow: 0 12px 24px rgba(0,0,0,0.18);
    }

    .did-image-ai-btn:disabled {
        opacity: .55;
        cursor: not-allowed;
        transform: none;
    }

    .did-image-ai-btn .spinner {
        display: none;
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255,255,255,0.28);
        border-top-color: #fff;
        border-radius: 50%;
        animation: did-spin .8s linear infinite;
    }

    .did-image-ai-btn.loading .spinner {
        display: inline-block;
    }

    .did-image-ai-btn.loading .btn-icon {
        display: none;
    }

    .did-image-stage {
        display: grid;
        gap: 16px;
    }

    .did-image-observation {
        padding: 16px 18px;
        border-radius: 14px;
        background: rgba(129,199,132,0.055);
        border: 1px solid rgba(129,199,132,0.14);
    }

    .did-image-observation h4 {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0 0 8px;
        color: var(--did-light-green);
        font-size: .94rem;
        font-family: 'Poppins', sans-serif;
    }

    .did-image-observation h4 i {
        color: var(--did-gold);
    }

    .did-image-observation p {
        margin: 0;
        color: var(--did-sage);
        font-size: .88rem;
        line-height: 1.6;
    }

    .did-image-meta-row {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .did-image-meta-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 999px;
        background: rgba(255,255,255,0.035);
        border: 1px solid rgba(255,255,255,0.08);
        color: var(--did-sage);
        font-size: .70rem;
        font-weight: 600;
    }

    .did-image-meta-pill.good i {
        color: var(--did-light-green);
    }

    .did-image-meta-pill.warn i {
        color: var(--did-gold);
    }

    .did-image-symptoms {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .did-image-symptom-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 11px;
        border-radius: 999px;
        background: rgba(255,213,79,0.06);
        border: 1px solid rgba(255,213,79,0.18);
        color: var(--did-cream);
        font-size: .76rem;
    }

    .did-image-candidate {
        padding: 13px 15px;
        border-radius: 13px;
        background: rgba(255,255,255,0.025);
        border: 1px solid rgba(255,255,255,0.07);
    }

    .did-image-candidate + .did-image-candidate {
        margin-top: 9px;
    }

    .did-image-candidate-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 6px;
    }

    .did-image-candidate-name {
        color: var(--did-cream);
        font-size: .84rem;
        font-weight: 700;
    }

    .did-image-candidate-confidence {
        color: var(--did-light-green);
        font-size: .70rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .did-image-candidate p {
        margin: 0;
        color: var(--did-sage);
        font-size: .78rem;
        line-height: 1.55;
    }

    .did-image-question-list {
        display: grid;
        gap: 10px;
    }

    .did-image-question {
        padding: 14px;
        border-radius: 14px;
        background: rgba(255,255,255,0.022);
        border: 1px solid rgba(255,255,255,0.07);
    }

    .did-image-question-text {
        color: var(--did-cream);
        font-size: .83rem;
        line-height: 1.5;
        margin-bottom: 10px;
    }

    .did-image-question-options {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
    }

    .did-image-answer-btn {
        border: 1px solid rgba(255,255,255,0.10);
        border-radius: 999px;
        padding: 7px 11px;
        background: rgba(255,255,255,0.025);
        color: var(--did-sage);
        font-size: .70rem;
        font-weight: 700;
        cursor: pointer;
        transition: all .2s ease;
    }

    .did-image-answer-btn:hover {
        border-color: rgba(255,213,79,0.25);
        color: var(--did-cream);
        background: rgba(255,213,79,0.05);
    }

    .did-image-answer-btn.selected {
        border-color: rgba(255,213,79,0.40);
        background: rgba(255,213,79,0.10);
        color: var(--did-gold);
    }

    .did-image-final-btn {
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        padding: 14px 16px;
        margin-top: 16px;
        border: none;
        border-radius: 13px;
        background: linear-gradient(
            120deg,
            var(--did-primary),
            var(--did-secondary)
        );
        color: #fff;
        font-size: .92rem;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 12px 28px rgba(27,94,32,0.28);
        transition: transform .2s ease, box-shadow .2s ease, opacity .2s ease;
    }

    .did-image-final-btn:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 16px 34px rgba(27,94,32,0.38);
    }

    .did-image-final-btn:disabled {
        opacity: .50;
        cursor: not-allowed;
        transform: none;
    }

    .did-image-final-note {
        margin-top: 9px;
        color: rgba(169,201,174,.58);
        font-size: .70rem;
        line-height: 1.5;
    }

    @media (max-width: 575px) {
        .did-image-candidate-head {
            align-items: flex-start;
            flex-direction: column;
            gap: 4px;
        }

        .did-image-question-options {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
        }

        .did-image-answer-btn {
            width: 100%;
        }
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

        .did-selection-head-row {
            align-items: flex-start;
            flex-direction: column;
            gap: 10px;
        }

        .did-image-analysis-head {
            align-items: flex-start;
        }

        .did-image-analysis-badge {
            margin-top: 2px;
        }

        .did-image-upload-area {
            min-height: 225px;
        }

        .did-image-preview-wrap.show {
            grid-template-columns: 1fr;
        }

        .did-image-preview,
        .did-image-preview-frame {
            height: 230px;
            min-height: 230px;
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

            <div class="did-selection-head-row">

                <div class="did-selection-head-copy">
                    <h2>
                        What symptoms are you seeing?
                    </h2>

                    <div class="did-sub">
                        Tap a symptom image to select it.
                    </div>
                </div>

                <div
                    class="did-result-count"
                    id="didResultCount"
                >
                    <i class="fa-solid fa-layer-group"></i>
                    {{ $symptoms->count() }} symptoms available
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

        </div>


        <!-- =====================================================
             AI IMAGE ANALYSIS — CREATIVE UPLOAD
        ====================================================== -->

        <div
            class="did-card did-image-analysis-card"
            id="didImageAnalysisCard"
        >

            <div class="did-image-analysis-head">

                <div class="did-image-analysis-title">

                    <div class="did-image-analysis-icon">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                    </div>

                    <div>
                        <div class="did-eyebrow">
                            Optional visual check
                        </div>

                        <h2>
                            Let AI Inspect the Plant
                        </h2>
                    </div>

                </div>

                <div class="did-image-analysis-badge">
                    <i class="fa-solid fa-bolt"></i>
                    AI VISION
                </div>

            </div>


            <div class="did-image-analysis-sub">
                Cannot recognise the symptom from the cards? Show the AI the
                affected plant area. A clear image will be used later as visual
                evidence alongside the symptoms you confirm.
            </div>


            <div
                class="did-image-upload-area"
                id="didImageUploadArea"
                role="button"
                tabindex="0"
                aria-label="Upload a cardamom plant photo"
            >

                <div class="did-image-glow glow-one"></div>
                <div class="did-image-glow glow-two"></div>

                <div class="did-image-upload-content">

                    <div class="did-image-camera-ring">
                        <div class="did-image-camera-core">
                            <i class="fa-solid fa-camera-retro"></i>
                        </div>
                    </div>

                    <h3>
                        Show the affected area
                    </h3>

                    <p>
                        Drop a photo here or open your gallery
                    </p>

                    <div class="did-image-upload-tags">

                        <span class="did-image-upload-tag">
                            <i class="fa-solid fa-leaf"></i>
                            Leaf
                        </span>

                        <span class="did-image-upload-tag">
                            <i class="fa-solid fa-tree"></i>
                            Stem
                        </span>

                        <span class="did-image-upload-tag">
                            <i class="fa-solid fa-seedling"></i>
                            Capsule
                        </span>

                    </div>

                    <label
                        for="didImageInput"
                        class="did-image-select-btn"
                    >
                        <i class="fa-solid fa-arrow-up-from-bracket"></i>
                        Choose Photo
                    </label>

                    <div class="did-image-drop-hint">
                        JPG · PNG · WEBP&nbsp;&nbsp;•&nbsp;&nbsp;up to 10 MB
                    </div>

                    <input
                        type="file"
                        id="didImageInput"
                        accept="image/jpeg,image/png,image/webp"
                    >

                </div>

            </div>


            <div
                class="did-image-preview-wrap"
                id="didImagePreviewWrap"
            >

                <div class="did-image-preview-frame">

                    <img
                        src=""
                        alt="Selected cardamom plant"
                        class="did-image-preview"
                        id="didImagePreview"
                    >

                    <span class="did-image-ready-badge">
                        <i class="fa-solid fa-circle-check"></i>
                        Photo ready
                    </span>

                </div>

                <div class="did-image-preview-meta">

                    <div class="did-image-preview-kicker">
                        Visual evidence selected
                    </div>

                    <div
                        class="did-image-file-name"
                        id="didImageFileName"
                    ></div>

                    <div class="did-image-note">
                        Keep the affected leaf, stem, flower or capsule area sharp
                        and close enough for the AI to inspect visible patterns.
                    </div>

                    <div class="did-image-ai-actions">
                        <button
                            type="button"
                            class="did-image-ai-btn"
                            id="didAnalyzeImageBtn"
                            disabled
                        >
                            <span class="btn-icon">
                                <i class="fa-solid fa-wand-magic-sparkles"></i>
                            </span>

                            <span class="spinner"></span>

                            <span id="didAnalyzeImageLabel">
                                Analyze Photo with AI
                            </span>
                        </button>
                    </div>

                    <button
                        type="button"
                        class="did-image-remove-btn"
                        id="didImageRemoveBtn"
                    >
                        <i class="fa-solid fa-rotate"></i>
                        Change Photo
                    </button>

                </div>

            </div>

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
                    No symptoms selected yet — select one or more symptoms above.
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
       IMAGE PRELIMINARY ANALYSIS
    ========================================================= */

    const defaultImageScanMessages = [
        'Reading the plant image...',
        'Inspecting visible leaf and stem patterns...',
        'Preparing confirmation questions...'
    ];


    function startImageScanMessages(
        customMessages
    ) {

        const messages =
            Array.isArray(customMessages) &&
            customMessages.length > 0
                ? customMessages
                : defaultImageScanMessages;

        let index =
            0;

        clearInterval(
            imageScanInterval
        );

        if (scanLine) {
            scanLine.textContent =
                messages[0];
        }

        imageScanInterval =
            setInterval(
                function () {

                    index =
                        (index + 1) %
                        messages.length;

                    if (scanLine) {
                        scanLine.textContent =
                            messages[index];
                    }

                },
                700
            );

    }


    function stopImageScanMessages() {

        clearInterval(
            imageScanInterval
        );

        imageScanInterval =
            null;

        if (scanLine) {
            scanLine.textContent =
                '';
        }

    }


    async function analyzeSelectedImage() {

        if (
            isImageAnalyzing ||
            !selectedImageFile
        ) {
            return;
        }

        isImageAnalyzing =
            true;

        if (analyzeImageBtn) {
            analyzeImageBtn.disabled = true;
            analyzeImageBtn.classList.add('loading');
        }

        if (analyzeImageLabel) {
            analyzeImageLabel.textContent =
                'Inspecting Photo...';
        }

        setStep(3);
        startImageScanMessages();

        try {

            const formData =
                new FormData();

            formData.append(
                'image',
                selectedImageFile
            );

            Array.from(
                selected.keys()
            ).forEach(
                function (id) {

                    formData.append(
                        'symptoms[]',
                        id
                    );

                }
            );

            const response =
                await fetch(
                    analyzeImageUrl,
                    {
                        method: 'POST',

                        headers: {
                            'X-CSRF-TOKEN':
                                csrfToken,

                            'Accept':
                                'application/json'
                        },

                        body:
                            formData
                    }
                );

            let data;

            try {
                data =
                    await response.json();
            } catch (error) {
                throw new Error(
                    'The server returned an invalid response.'
                );
            }

            if (!response.ok) {
                throw new Error(
                    data?.message ||
                    `Image analysis failed with status ${response.status}.`
                );
            }

            if (
                !data ||
                !data.success ||
                !data.result
            ) {
                throw new Error(
                    data?.message ||
                    'Gemini could not analyze this image.'
                );
            }

            preliminaryImageAnalysis =
                data.result;

            imageQuestionAnswers =
                {};

            renderImagePreliminaryResult(
                data.result
            );

        } catch (error) {

            renderError(
                error.message ||
                'Could not analyze the selected image.'
            );

        } finally {

            stopImageScanMessages();

            if (analyzeImageBtn) {
                analyzeImageBtn.classList.remove('loading');
                analyzeImageBtn.disabled =
                    !selectedImageFile;
            }

            if (analyzeImageLabel) {
                analyzeImageLabel.textContent =
                    'Analyze Photo with AI';
            }

            isImageAnalyzing =
                false;

        }

    }


    function renderImagePreliminaryResult(
        result
    ) {

        const visibleSymptoms =
            Array.isArray(result.visible_symptoms)
                ? result.visible_symptoms
                : [];

        const possibleDiseases =
            Array.isArray(result.possible_diseases)
                ? result.possible_diseases
                : [];

        const questions =
            Array.isArray(result.confirmation_questions)
                ? result.confirmation_questions
                : [];

        const imageQuality =
            String(
                result.image_quality ||
                'Unknown'
            );

        const qualityIsGood =
            /good|fair/i.test(
                imageQuality
            );

        const visualSummary =
            String(
                result.visual_summary ||
                result.visual_observations ||
                ''
            );

        const questionHtml =
            questions.length > 0
                ? questions
                    .map(
                        function (
                            question,
                            index
                        ) {

                            return `
                                <div
                                    class="did-image-question"
                                    data-question-index="${index}"
                                >

                                    <div class="did-image-question-text">
                                        ${index + 1}. ${escapeHtml(question)}
                                    </div>

                                    <div class="did-image-question-options">

                                        <button
                                            type="button"
                                            class="did-image-answer-btn"
                                            data-answer="YES"
                                            data-question-index="${index}"
                                        >
                                            YES
                                        </button>

                                        <button
                                            type="button"
                                            class="did-image-answer-btn"
                                            data-answer="NO"
                                            data-question-index="${index}"
                                        >
                                            NO
                                        </button>

                                        <button
                                            type="button"
                                            class="did-image-answer-btn"
                                            data-answer="NOT SURE"
                                            data-question-index="${index}"
                                        >
                                            NOT SURE
                                        </button>

                                    </div>

                                </div>
                            `;

                        }
                    )
                    .join('')
                : `
                    <div class="did-detail-text">
                        No additional confirmation questions were returned.
                        The uploaded image and any symptoms you selected will
                        still be available for the final AI analysis.
                    </div>
                `;

        const candidatesHtml =
            possibleDiseases.length > 0
                ? possibleDiseases
                    .map(
                        function (
                            candidate
                        ) {

                            return `
                                <div class="did-image-candidate">

                                    <div class="did-image-candidate-head">

                                        <span class="did-image-candidate-name">
                                            ${escapeHtml(
                                                candidate?.disease_name ||
                                                'Possible condition'
                                            )}
                                        </span>

                                        <span class="did-image-candidate-confidence">
                                            ${escapeHtml(
                                                candidate?.confidence ||
                                                'Unclear'
                                            )}
                                        </span>

                                    </div>

                                    <p>
                                        ${escapeHtml(
                                            candidate?.reason ||
                                            ''
                                        )}
                                    </p>

                                </div>
                            `;

                        }
                    )
                    .join('')
                : `
                    <div class="did-detail-text">
                        No disease candidate was considered sufficiently
                        supported by the visible image.
                    </div>
                `;

        const visibleSymptomsHtml =
            visibleSymptoms.length > 0
                ? visibleSymptoms
                    .map(
                        function (
                            symptom
                        ) {

                            return `
                                <span class="did-image-symptom-pill">
                                    <i class="fa-solid fa-eye"></i>
                                    ${escapeHtml(symptom)}
                                </span>
                            `;

                        }
                    )
                    .join('')
                : `
                    <span class="did-unmatched-pill">
                        No clear visible symptom returned.
                    </span>
                `;

        resultsBox.innerHTML = `

            <div class="did-result-header">

                <span class="tag">
                    AI Vision — Preliminary Check
                </span>

            </div>

            <div class="did-image-stage">

                <div class="did-image-meta-row">

                    <span class="did-image-meta-pill ${qualityIsGood ? 'good' : 'warn'}">
                        <i class="fa-solid ${
                            qualityIsGood
                                ? 'fa-circle-check'
                                : 'fa-triangle-exclamation'
                        }"></i>

                        Image quality:
                        ${escapeHtml(imageQuality)}
                    </span>

                    <span class="did-image-meta-pill">
                        <i class="fa-solid fa-gauge-high"></i>

                        Confidence:
                        ${escapeHtml(
                            result.confidence ||
                            result.overall_confidence ||
                            'Unclear'
                        )}
                    </span>

                    ${
                        result.needs_better_image === true
                            ? `
                                <span class="did-image-meta-pill warn">
                                    <i class="fa-solid fa-camera"></i>
                                    A clearer photo may improve analysis
                                </span>
                              `
                            : ''
                    }

                </div>

                ${
                    visualSummary
                        ? `
                            <div class="did-image-observation">

                                <h4>
                                    <i class="fa-solid fa-eye"></i>
                                    What Gemini Sees
                                </h4>

                                <p>
                                    ${escapeHtml(visualSummary)}
                                </p>

                            </div>
                          `
                        : ''
                }

                <div class="did-detail-block">

                    <h4>
                        <i class="fa-solid fa-microscope"></i>
                        Visible Symptoms
                    </h4>

                    <div class="did-image-symptoms">
                        ${visibleSymptomsHtml}
                    </div>

                </div>

                <div class="did-detail-block">

                    <h4>
                        <i class="fa-solid fa-layer-group"></i>
                        Possible Conditions
                    </h4>

                    ${candidatesHtml}

                </div>

                <div class="did-detail-block">

                    <h4>
                        <i class="fa-solid fa-comments"></i>
                        Confirm What You Observe
                    </h4>

                    <div class="did-detail-text" style="margin-bottom:10px;">
                        Answer each question with YES, NO or NOT SURE.
                        These answers will be combined with the photo for
                        the final AI prediction.
                    </div>

                    <div
                        class="did-image-question-list"
                        id="didImageQuestionList"
                    >
                        ${questionHtml}
                    </div>

                    <button
                        type="button"
                        class="did-image-final-btn"
                        id="didFinalImagePredictBtn"
                        ${questions.length > 0 ? 'disabled' : ''}
                    >
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                        Final AI Disease Prediction
                    </button>

                    <div class="did-image-final-note">
                        This final result is an AI-assisted decision-support
                        suggestion, not a confirmed scientific diagnosis.
                    </div>

                </div>

            </div>
        `;

        bindImageQuestionEvents();

        showResults();

    }


    function bindImageQuestionEvents() {

        resultsBox
            .querySelectorAll(
                '.did-image-answer-btn'
            )
            .forEach(
                function (button) {

                    button.addEventListener(
                        'click',
                        function () {

                            const index =
                                String(
                                    this.dataset.questionIndex
                                );

                            const answer =
                                this.dataset.answer;

                            imageQuestionAnswers[index] =
                                answer;

                            resultsBox
                                .querySelectorAll(
                                    `.did-image-answer-btn[data-question-index="${CSS.escape(index)}"]`
                                )
                                .forEach(
                                    function (otherButton) {

                                        otherButton.classList.remove(
                                            'selected'
                                        );

                                    }
                                );

                            this.classList.add(
                                'selected'
                            );

                            updateFinalImageButtonState();

                        }
                    );

                }
            );


        const finalButton =
            document.getElementById(
                'didFinalImagePredictBtn'
            );

        if (finalButton) {

            finalButton.addEventListener(
                'click',
                predictFinalImageDisease
            );

        }

    }


    function updateFinalImageButtonState() {

        const button =
            document.getElementById(
                'didFinalImagePredictBtn'
            );

        if (!button) {
            return;
        }

        const totalQuestions =
            resultsBox.querySelectorAll(
                '.did-image-question'
            ).length;

        const answeredQuestions =
            Object.keys(
                imageQuestionAnswers
            ).length;

        button.disabled =
            totalQuestions > 0 &&
            answeredQuestions < totalQuestions;

    }


    async function predictFinalImageDisease() {

        if (
            isFinalImageSubmitting ||
            !selectedImageFile
        ) {
            return;
        }

        const questions =
            Array.isArray(
                preliminaryImageAnalysis?.confirmation_questions
            )
                ? preliminaryImageAnalysis.confirmation_questions
                : [];

        const confirmedEvidence =
            [];

        selected.forEach(
            function (data) {

                confirmedEvidence.push(
                    `Farmer selected symptom: ${data.name}`
                );

            }
        );

        questions.forEach(
            function (
                question,
                index
            ) {

                const answer =
                    imageQuestionAnswers[
                        String(index)
                    ];

                if (!answer) {
                    return;
                }

                confirmedEvidence.push(
                    `${answer}: ${question}`
                );

            }
        );

        isFinalImageSubmitting =
            true;

        setStep(3);

        startImageScanMessages(
            [
                'Combining the photo with your answers...',
                'Re-checking the visible plant evidence...',
                'Preparing the final AI disease suggestion...'
            ]
        );

        resultsBox.innerHTML = `
            <div class="did-empty-state">

                <i class="fa-solid fa-wand-magic-sparkles"></i>

                <h3>
                    Gemini is checking the complete case
                </h3>

                <p>
                    The image, selected symptoms and your confirmations
                    are being considered together.
                </p>

            </div>
        `;

        showResults();

        try {

            const formData =
                new FormData();

            formData.append(
                'image',
                selectedImageFile
            );

            confirmedEvidence.forEach(
                function (
                    item
                ) {

                    formData.append(
                        'confirmed_symptoms[]',
                        item
                    );

                }
            );

            if (preliminaryImageAnalysis) {

                formData.append(
                    'preliminary_analysis',
                    JSON.stringify(
                        preliminaryImageAnalysis
                    )
                );

            }

            const response =
                await fetch(
                    predictImageUrl,
                    {
                        method: 'POST',

                        headers: {
                            'X-CSRF-TOKEN':
                                csrfToken,

                            'Accept':
                                'application/json'
                        },

                        body:
                            formData
                    }
                );

            let data;

            try {

                data =
                    await response.json();

            } catch (error) {

                throw new Error(
                    'The final prediction response was invalid.'
                );

            }

            if (!response.ok) {

                throw new Error(
                    data?.message ||
                    `Final prediction failed with status ${response.status}.`
                );

            }

            renderFinalImageResult(
                data
            );

        } catch (error) {

            renderError(
                error.message ||
                'Could not complete the final image prediction.'
            );

        } finally {

            stopImageScanMessages();

            isFinalImageSubmitting =
                false;

        }

    }


    function renderFinalImageResult(data) {

        if (
            !data ||
            !data.success
        ) {

            renderError(
                data?.message ||
                'The final AI prediction could not be completed.'
            );

            return;

        }

        if (
            data.result &&
            (
                data.result.ai_identified === true ||
                typeof data.result.disease_name !== 'undefined'
            )
        ) {

            renderResult(
                {
                    success: true,
                    result: data.result,
                    alternatives:
                        Array.isArray(
                            data.alternatives
                        )
                            ? data.alternatives
                            : []
                }
            );

            return;

        }

        renderError(
            data.message ||
            'The final AI prediction returned an incomplete result.'
        );

    }



    /* =========================================================
       ROUTES / CSRF
    ========================================================= */

    const analyzeUrl =
        "{{ route('disease.identification.analyze') }}";

    const analyzeImageUrl =
        "{{ route('disease.identification.analyze-image') }}";

    const predictImageUrl =
        "{{ route('disease.identification.predict-image') }}";

    const csrfToken =
        "{{ csrf_token() }}";


    /* =========================================================
       ELEMENTS
    ========================================================= */

    const grid =
        document.getElementById(
            'didSymptomGrid'
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


    const imageInput =
        document.getElementById(
            'didImageInput'
        );

    const imageUploadArea =
        document.getElementById(
            'didImageUploadArea'
        );

    const imagePreviewWrap =
        document.getElementById(
            'didImagePreviewWrap'
        );

    const imagePreview =
        document.getElementById(
            'didImagePreview'
        );

    const imageFileName =
        document.getElementById(
            'didImageFileName'
        );

    const imageRemoveBtn =
        document.getElementById(
            'didImageRemoveBtn'
        );

    const analyzeImageBtn =
        document.getElementById(
            'didAnalyzeImageBtn'
        );

    const analyzeImageLabel =
        document.getElementById(
            'didAnalyzeImageLabel'
        );


    /* =========================================================
       SAFETY CHECK
    ========================================================= */

    if (
        !grid ||
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


    let selectedImageFile =
        null;

    let preliminaryImageAnalysis =
        null;

    let imageQuestionAnswers =
        {};

    let isImageAnalyzing =
        false;

    let isFinalImageSubmitting =
        false;

    let imageScanInterval =
        null;


    function handleImageFile(file) {

        if (!file) {
            return;
        }

        const allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];

        if (!allowedTypes.includes(file.type)) {

            if (imageInput) {
                imageInput.value = '';
            }

            selectedImageFile = null;

            alert(
                'Please select a JPG, JPEG, PNG or WEBP image.'
            );

            return;
        }

        const maxSize =
            10 * 1024 * 1024;

        if (file.size > maxSize) {

            if (imageInput) {
                imageInput.value = '';
            }

            selectedImageFile = null;

            alert(
                'Image size must be 10 MB or less.'
            );

            return;
        }

        selectedImageFile = file;

        preliminaryImageAnalysis =
            null;

        imageQuestionAnswers =
            {};

        const reader =
            new FileReader();

        reader.onload =
            function (event) {

                if (imagePreview) {
                    imagePreview.src =
                        event.target.result;
                }

                if (imageFileName) {
                    imageFileName.textContent =
                        file.name;
                }

                if (imagePreviewWrap) {
                    imagePreviewWrap.classList.add(
                        'show'
                    );
                }

                if (analyzeImageBtn) {
                    analyzeImageBtn.disabled = false;
                }

            };

        reader.readAsDataURL(file);
    }


    if (imageInput) {

        imageInput.addEventListener(
            'change',
            function () {

                const file =
                    this.files && this.files.length > 0
                        ? this.files[0]
                        : null;

                handleImageFile(file);

            }
        );

    }


    if (imageUploadArea) {

        imageUploadArea.addEventListener(
            'click',
            function (event) {

                if (
                    event.target.closest(
                        '.did-image-select-btn'
                    )
                ) {
                    return;
                }

                if (imageInput) {
                    imageInput.click();
                }

            }
        );


        imageUploadArea.addEventListener(
            'keydown',
            function (event) {

                if (
                    event.key === 'Enter' ||
                    event.key === ' '
                ) {

                    event.preventDefault();

                    if (imageInput) {
                        imageInput.click();
                    }

                }

            }
        );


        ['dragenter', 'dragover'].forEach(
            function (eventName) {

                imageUploadArea.addEventListener(
                    eventName,
                    function (event) {

                        event.preventDefault();

                        imageUploadArea.classList.add(
                            'dragover'
                        );

                    }
                );

            }
        );


        ['dragleave', 'drop'].forEach(
            function (eventName) {

                imageUploadArea.addEventListener(
                    eventName,
                    function (event) {

                        event.preventDefault();

                        imageUploadArea.classList.remove(
                            'dragover'
                        );

                    }
                );

            }
        );


        imageUploadArea.addEventListener(
            'drop',
            function (event) {

                const files =
                    event.dataTransfer &&
                    event.dataTransfer.files
                        ? event.dataTransfer.files
                        : null;

                const file =
                    files && files.length > 0
                        ? files[0]
                        : null;

                handleImageFile(file);

            }
        );

    }


    if (imageRemoveBtn) {

        imageRemoveBtn.addEventListener(
            'click',
            function (event) {

                event.preventDefault();
                event.stopPropagation();

                selectedImageFile = null;

                preliminaryImageAnalysis =
                    null;

                imageQuestionAnswers =
                    {};

                if (analyzeImageBtn) {
                    analyzeImageBtn.disabled = true;
                    analyzeImageBtn.classList.remove('loading');
                }

                if (analyzeImageLabel) {
                    analyzeImageLabel.textContent =
                        'Analyze Photo with AI';
                }

                if (imageInput) {
                    imageInput.value = '';
                }

                if (imagePreview) {
                    imagePreview.src = '';
                }

                if (imageFileName) {
                    imageFileName.textContent = '';
                }

                if (imagePreviewWrap) {
                    imagePreviewWrap.classList.remove(
                        'show'
                    );
                }

            }
        );

    }


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

    throw new Error(
        data?.message ||
        `Request failed with status ${response.status}.`
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
            error.message ||
            'Could not process the request. Please try again.'
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


        /*
        |--------------------------------------------------------------------------
        | No result object
        |--------------------------------------------------------------------------
        */

        if (!data.result) {

            resultsBox.innerHTML = `
                <div class="did-empty-state">

                    <i class="fa-solid fa-leaf"></i>

                    <h3>
                        No Disease Match Found
                    </h3>

                    <p>
                        The selected symptoms could not be matched
                        with a registered disease.
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


        /*
        |--------------------------------------------------------------------------
        | Determine whether this is the Gemini fallback result
        |--------------------------------------------------------------------------
        */

        const isAiIdentified =
            result.ai_identified === true;


        /*
        |--------------------------------------------------------------------------
        | AI confidence
        |--------------------------------------------------------------------------
        */

        let aiConfidenceNumber = 0;

        const confidenceText =
            String(
                result.ai_confidence || ''
            );

        /*
        |--------------------------------------------------------------------------
        | Prefer the numeric confidence score returned by Gemini.
        |
        | This is an AI-estimated display score, NOT the database
        | symptom-match percentage and NOT a scientific probability.
        |--------------------------------------------------------------------------
        */

        const rawConfidenceScore =
            Number(
                result.ai_confidence_score
            );

        if (
            Number.isFinite(rawConfidenceScore) &&
            rawConfidenceScore > 0
        ) {

            aiConfidenceNumber =
                rawConfidenceScore;

        } else {

            /*
            | Compatibility fallback for older responses that only
            | returned "High", "Moderate", "Low", or a percentage.
            */

            const confidenceMatch =
                confidenceText.match(
                    /(\d+(?:\.\d+)?)\s*%/
                );

            if (confidenceMatch) {

                aiConfidenceNumber =
                    Number(
                        confidenceMatch[1]
                    );

            } else {

                const normalizedConfidence =
                    confidenceText
                        .trim()
                        .toLowerCase();

                if (
                    normalizedConfidence.includes('high')
                ) {

                    aiConfidenceNumber = 85;

                } else if (
                    normalizedConfidence.includes('moderate') ||
                    normalizedConfidence.includes('medium')
                ) {

                    aiConfidenceNumber = 60;

                } else if (
                    normalizedConfidence.includes('low')
                ) {

                    aiConfidenceNumber = 35;

                }

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Main ring percentage
        |--------------------------------------------------------------------------
        */

        const percentage =
            isAiIdentified
                ? Math.max(
                    0,
                    Math.min(
                        100,
                        aiConfidenceNumber
                    )
                )
                : Math.max(
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


        /*
        |--------------------------------------------------------------------------
        | AI reason + Causes + Prevention + Fertilizer
        |--------------------------------------------------------------------------
        */

        let detailBlocks =
            '';


        if (
            isAiIdentified &&
            result.ai_reason
        ) {

            detailBlocks += `
                <div class="did-ai-reason">

                    <div class="did-ai-reason-title">

                        <i class="fa-solid fa-wand-magic-sparkles"></i>

                        Why Gemini Suggested This Disease

                    </div>

                    <p>
                        ${escapeHtml(result.ai_reason)}
                    </p>

                </div>
            `;

        }


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


        /*
        |--------------------------------------------------------------------------
        | AI warning
        |--------------------------------------------------------------------------
        */

        let aiWarningHtml =
            '';


        if (isAiIdentified) {

            aiWarningHtml = `
                <div class="did-ai-warning">

                    <div class="did-ai-warning-icon">

                        <i class="fa-solid fa-robot"></i>

                    </div>

                    <div>

                        <strong>
                            AI-Suggested Disease
                        </strong>

                        <p>
                            Gemini AI independently analyzed the selected
                            symptoms without using the database disease
                            match as its final decision. This is an AI-assisted
                            suggestion, not a confirmed scientific diagnosis.
                        </p>

                    </div>

                </div>
            `;

        }


        /*
        |--------------------------------------------------------------------------
        | Other symptoms of a registered disease
        |--------------------------------------------------------------------------
        */

        let unmatchedHtml =
            '';


        if (
            !isAiIdentified &&
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


        /*
        |--------------------------------------------------------------------------
        | Alternative database matches
        |--------------------------------------------------------------------------
        */

        let alternativesHtml =
            '';


        if (
            !isAiIdentified &&
            alternatives.length > 0
        ) {

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


        /*
        |--------------------------------------------------------------------------
        | Symptoms shown in the result
        |--------------------------------------------------------------------------
        */

        const matchedSymptoms =
            Array.isArray(
                result.matched_symptoms
            )
                ? result.matched_symptoms
                : [];


        const symptomsToShow =
            isAiIdentified
                ? (
                    Array.isArray(
                        result.unmatched_symptoms
                    ) &&
                    result.unmatched_symptoms.length > 0
                        ? result.unmatched_symptoms
                        : matchedSymptoms
                )
                : matchedSymptoms;


        const symptomsTitle =
            isAiIdentified
                ? 'Symptoms Considered by Gemini'
                : 'Matched Symptoms';


        const symptomsIcon =
            isAiIdentified
                ? 'fa-list-check'
                : 'fa-check-double';


        const symptomsHtml =
            symptomsToShow.length > 0
                ? symptomsToShow
                    .map(
                        function (symptom) {

                            return `
                                <span class="${
                                    isAiIdentified
                                        ? 'did-unmatched-pill'
                                        : 'did-matched-pill'
                                }">

                                    ${
                                        isAiIdentified
                                            ? ''
                                            : '✓ '
                                    }

                                    ${escapeHtml(symptom)}

                                </span>
                            `;

                        }
                    )
                    .join('')
                : `
                    <span class="did-unmatched-pill">
                        No symptoms returned.
                    </span>
                `;


        /*
        |--------------------------------------------------------------------------
        | Result information
        |--------------------------------------------------------------------------
        */

        let resultInfoHtml =
            '';


        if (isAiIdentified) {

            resultInfoHtml = `

                <div class="match-sub">
                    Identified with Gemini AI
                </div>

                <h3>
                    ${escapeHtml(
                        result.disease_name ||
                        'Unknown Disease'
                    )}
                </h3>


                <div class="did-ai-source">

                    <i class="fa-solid fa-robot"></i>

                    Identification Source:
                    Gemini AI

                </div>


                ${
                    result.ai_confidence
                        ? `
                            <div class="did-ai-confidence">

                                AI Confidence:

                                <strong>
                                    ${escapeHtml(
                                        result.ai_confidence
                                    )}
                                </strong>

                            </div>
                        `
                        : ''
                }


                <div class="match-sub mt-2">

                    The selected symptoms did not match
                    a disease registered in CARDO-FER.

                </div>

            `;

        } else {

            resultInfoHtml = `

                <div class="match-sub">
                    Most Likely Match
                </div>

                <h3>
                    ${escapeHtml(
                        result.disease_name ||
                        'Unknown Disease'
                    )}
                </h3>

                <div class="match-sub">

                    ${Number(
                        result.matched_count || 0
                    )}

                    of

                    ${Number(
                        result.selected_count || 0
                    )}

                    selected symptoms matched

                    (

                    ${Number(
                        result.total_disease_symptoms || 0
                    )}

                    known symptoms for this disease

                    )

                </div>

            `;

        }


        const disclaimer =
            isAiIdentified
                ? 'This is an AI-assisted symptom-based suggestion, not a confirmed scientific diagnosis.'
                : 'This is a symptom-pattern match, not a medical or scientific diagnosis.';


        /*
        |--------------------------------------------------------------------------
        | Render result
        |--------------------------------------------------------------------------
        */

        resultsBox.innerHTML = `

            <div class="did-result-header">

                <span class="tag">

                    ${
                        isAiIdentified
                            ? 'AI-Assisted Identification'
                            : 'Analysis Complete'
                    }

                </span>

            </div>


            ${aiWarningHtml}


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

                        ${
                            isAiIdentified
                                ? 'AI Confidence'
                                : 'Symptom Match'
                        }

                    </div>

                </div>


                <div class="did-best-info">

                    ${resultInfoHtml}

                    <div class="did-disclaimer">

                        ${escapeHtml(disclaimer)}

                    </div>

                </div>

            </div>


            <div class="did-detail-block">

                <h4>

                    <i
                        class="fa-solid ${symptomsIcon}"
                    ></i>

                    ${symptomsTitle}

                </h4>


                <div class="did-matched-list">

                    ${symptomsHtml}

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


        /*
        |--------------------------------------------------------------------------
        | Animate result ring
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | Reset button
        |--------------------------------------------------------------------------
        */

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


        selectedImageFile =
            null;

        preliminaryImageAnalysis =
            null;

        imageQuestionAnswers =
            {};

        if (analyzeImageBtn) {
            analyzeImageBtn.disabled = true;
            analyzeImageBtn.classList.remove('loading');
        }

        if (analyzeImageLabel) {
            analyzeImageLabel.textContent =
                'Analyze Photo with AI';
        }

        if (imageInput) {
            imageInput.value = '';
        }

        if (imagePreview) {
            imagePreview.src = '';
        }

        if (imageFileName) {
            imageFileName.textContent = '';
        }

        if (imagePreviewWrap) {
            imagePreviewWrap.classList.remove(
                'show'
            );
        }


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
       IMAGE ANALYZE BUTTON LISTENER
       Register this only after all image variables are initialized.
    ========================================================= */

    if (analyzeImageBtn) {

        analyzeImageBtn.addEventListener(
            'click',
            analyzeSelectedImage
        );

    }


    /* =========================================================
       INITIAL STATE
    ========================================================= */

    renderChips();

})();
</script>

@endpush

@endsection