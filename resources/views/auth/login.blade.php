<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cardo-Fer · Sign in</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;1,0,9..144,500&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-0: #0a1913;
            --bg-1: #0f2419;
            --cream: #f4efe3;
            --cream-dim: rgba(244,239,227,0.62);
            --sage: #8fac96;
            --sage-dim: rgba(143,172,150,0.55);
            --gold: #d99a4e;
            --gold-hi: #e8ac66;
            --gold-ink: #241708;
            --line: rgba(244,239,227,0.10);
            --line-strong: rgba(244,239,227,0.18);
            --danger: #e2836b;

            --serif: 'Fraunces', Georgia, serif;
            --sans: 'Inter', Arial, sans-serif;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--sans);
            background:
                radial-gradient(
                    900px 500px at 88% -8%,
                    rgba(217,154,78,0.10),
                    transparent 60%
                ),
                radial-gradient(
                    700px 500px at -5% 100%,
                    rgba(143,172,150,0.08),
                    transparent 55%
                ),
                linear-gradient(
                    160deg,
                    var(--bg-1) 0%,
                    var(--bg-0) 55%,
                    #081209 100%
                );

            color: var(--cream);
            min-height: 100vh;
            overflow-x: hidden;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .topnav {
            position: relative;
            z-index: 2;

            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 22px clamp(20px, 5vw, 64px);

            border-bottom: 1px solid var(--line);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;

            font-family: var(--serif);
            font-size: 19px;
            font-weight: 600;
        }

        .brand-mark {
            width: 26px;
            height: 26px;

            border-radius: 50%;
            border: 1px solid var(--sage-dim);

            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-mark span {
            width: 11px;
            height: 11px;
            border-radius: 50%;
            border: 1px solid var(--sage-dim);
        }

        .back-link {
            font-size: 13.5px;
            color: var(--cream-dim);
            transition: color .2s ease;
        }

        .back-link:hover {
            color: var(--cream);
        }

        .auth-shell {
            min-height: calc(100vh - 78px);

            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
        }

        .promo {
            position: relative;

            display: flex;
            flex-direction: column;
            justify-content: center;

            padding: clamp(36px, 6vw, 88px);

            overflow: hidden;
        }

        .promo::after {
            content: "";
            position: absolute;
            inset: 0;

            background: linear-gradient(
                115deg,
                transparent 60%,
                rgba(0,0,0,0.18) 100%
            );

            pointer-events: none;
        }

        .eyebrow {
            display: flex;
            align-items: center;
            gap: 9px;

            font-size: 11.5px;
            letter-spacing: 2.2px;
            text-transform: uppercase;

            color: var(--sage);

            margin-bottom: 26px;
        }

        .eyebrow .dot {
            width: 6px;
            height: 6px;

            border-radius: 50%;
            background: var(--gold);

            box-shadow:
                0 0 10px 2px rgba(217,154,78,0.55);
        }

        .promo h1 {
            font-family: var(--serif);
            font-weight: 500;

            font-size: clamp(38px, 4.6vw, 58px);
            line-height: 1.08;

            max-width: 520px;
        }

        .promo h1 em {
            font-style: italic;
            color: var(--sage);
        }

        .desc {
            margin-top: 24px;

            max-width: 420px;

            font-size: 15.5px;
            line-height: 1.7;

            color: var(--cream-dim);
        }

        .stat-row {
            display: flex;
            gap: 34px;

            margin-top: 44px;

            flex-wrap: wrap;
        }

        .stat-row b {
            display: block;

            font-family: var(--serif);
            font-size: 22px;
            font-weight: 500;
        }

        .stat-row span {
            font-size: 12px;
            color: var(--sage-dim);

            letter-spacing: .4px;
            text-transform: uppercase;
        }

        .deco-circle {
            position: absolute;

            border-radius: 50%;
            border: 1px solid var(--line-strong);

            opacity: .7;
        }

        .c1 {
            width: 64px;
            height: 64px;

            top: 12%;
            right: 14%;
        }

        .c2 {
            width: 26px;
            height: 26px;

            bottom: 16%;
            right: 26%;
        }

        .c3 {
            width: 120px;
            height: 120px;

            bottom: -40px;
            left: -40px;

            opacity: .35;
        }

        .leaf-svg {
            position: absolute;

            right: 6%;
            top: 50%;

            transform: translateY(-50%);

            width: 280px;

            opacity: .16;
        }

        .form-panel {
            display: flex;
            align-items: center;
            justify-content: center;

            padding: clamp(28px, 5vw, 64px);
        }

        .auth-card {
            width: 100%;
            max-width: 420px;

            background:
                linear-gradient(
                    180deg,
                    rgba(244,239,227,0.045),
                    rgba(244,239,227,0.015)
                );

            border: 1px solid var(--line-strong);
            border-radius: 26px;

            padding: clamp(30px, 4vw, 42px);

            backdrop-filter: blur(18px);

            box-shadow:
                0 30px 60px -25px rgba(0,0,0,0.55);
        }

        .auth-card h2 {
            font-family: var(--serif);

            font-weight: 500;

            font-size: 28px;
        }

        .sub {
            margin-top: 9px;

            font-size: 13.8px;
            line-height: 1.6;

            color: var(--cream-dim);
        }

        .auth-form {
            margin-top: 28px;

            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .field {
            display: flex;
            flex-direction: column;

            gap: 7px;
        }

        .field label {
            font-size: 12.5px;

            letter-spacing: .4px;

            color: var(--sage);

            text-transform: uppercase;

            font-weight: 600;
        }

        .field input {
            width: 100%;

            background: rgba(6,16,10,.45);

            border: 1px solid var(--line-strong);

            border-radius: 12px;

            padding: 13px 15px;

            font-size: 14.5px;

            color: var(--cream);

            font-family: var(--sans);

            transition:
                border-color .2s ease,
                box-shadow .2s ease;
        }

        .field input::placeholder {
            color: rgba(244,239,227,.32);
        }

        .field input:focus {
            outline: none;

            border-color: var(--gold);

            box-shadow:
                0 0 0 3px rgba(217,154,78,.16);
        }

    

        .link {
            color: var(--gold-hi);
            font-weight: 600;
        }

        .link:hover {
            text-decoration: underline;
        }

        .btn-primary {
            margin-top: 6px;

            border: none;

            background:
                linear-gradient(
                    180deg,
                    var(--gold-hi),
                    var(--gold)
                );

            color: var(--gold-ink);

            border-radius: 999px;

            padding: 14px 20px;

            font-family: var(--sans);

            font-weight: 700;

            font-size: 14.5px;

            cursor: pointer;

            transition:
                transform .18s ease,
                box-shadow .18s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);

            box-shadow:
                0 18px 32px -12px rgba(217,154,78,.65);
        }

        .error-box {
            margin-top: 14px;

            padding: 10px 12px;

            border-radius: 10px;

            border: 1px solid rgba(226,131,107,.25);

            background: rgba(226,131,107,.08);

            color: var(--danger);

            font-size: 12.5px;
        }

        .foot-switch {
            text-align: center;

            margin-top: 20px;

            font-size: 13.5px;

            color: var(--cream-dim);
        }

        @media (max-width: 900px) {
            .auth-shell {
                grid-template-columns: 1fr;
            }

            .promo {
                padding: 44px 24px 20px;
            }

            .form-panel {
                padding: 28px 24px 50px;
            }

            .leaf-svg {
                display: none;
            }
        }

        @media (max-width: 560px) {
            .topnav {
                padding: 18px 20px;
            }

            .promo h1 {
                font-size: 38px;
            }

            .stat-row {
                gap: 20px;
            }
        }
    </style>
</head>

<body>

<header class="topnav">

    <a href="{{ route('guest') }}" class="brand">

        <span class="brand-mark">
            <span></span>
        </span>

        Cardo-Fer

    </a>

    <a href="{{ route('guest') }}" class="back-link">
        ← Back to home
    </a>

</header>


<main class="auth-shell">

    <!-- LEFT SIDE -->
    <section class="promo">

        <div class="deco-circle c1"></div>
        <div class="deco-circle c2"></div>
        <div class="deco-circle c3"></div>

        <svg
            class="leaf-svg"
            viewBox="0 0 200 320"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                d="M100 10C60 60 30 130 40 200C48 255 75 295 100 310C125 295 152 255 160 200C170 130 140 60 100 10Z"
                stroke="#f4efe3"
                stroke-width="1"
            />

            <path
                d="M100 20V300"
                stroke="#f4efe3"
                stroke-width="1"
            />

            <path
                d="M100 70C80 90 65 110 60 135"
                stroke="#f4efe3"
                stroke-width=".7"
            />

            <path
                d="M100 120C80 140 65 160 58 185"
                stroke="#f4efe3"
                stroke-width=".7"
            />

            <path
                d="M100 170C80 188 68 208 62 230"
                stroke="#f4efe3"
                stroke-width=".7"
            />

            <path
                d="M100 70C120 90 135 110 140 135"
                stroke="#f4efe3"
                stroke-width=".7"
            />

            <path
                d="M100 120C120 140 135 160 142 185"
                stroke="#f4efe3"
                stroke-width=".7"
            />

            <path
                d="M100 170C120 188 132 208 138 230"
                stroke="#f4efe3"
                stroke-width=".7"
            />
        </svg>


        <div class="eyebrow">
            <span class="dot"></span>
            WESTERN GHATS · IDUKKI · WAYANAD
        </div>


        <h1>
            Smarter farming
            <br>
            <em>starts here.</em>
        </h1>


        <p class="desc">
            AI-assisted insights, disease intelligence,
            fertilizer guidance and smarter farm management
            for cardamom growers.
        </p>


        <div class="stat-row">

            <div>
                <b>01</b>
                <span>Smart Farming</span>
            </div>

            <div>
                <b>AI</b>
                <span>Assisted Insights</span>
            </div>

            <div>
                <b>24/7</b>
                <span>Access</span>
            </div>

        </div>

    </section>


    <!-- RIGHT SIDE -->
    <section class="form-panel">

        <div class="auth-card">

            <h2>Welcome back</h2>

            <p class="sub">
                Sign in to continue managing your cardamom farm with Cardo-Fer.
            </p>


            <form
                method="POST"
                action="{{ route('login') }}"
                class="auth-form"
            >

                @csrf


                <!-- EMAIL -->
                <div class="field">

                    <label for="email">
                        Email Address
                    </label>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="you@farmstead.com"
                        required
                        autofocus
                        autocomplete="username"
                    >

                    @error('email')
                        <div class="error-box">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <!-- PASSWORD -->
                <div class="field">

                    <label for="password">
                        Password
                    </label>

                    <input
                        id="password"
                        type="password"
                        name="password"
                        placeholder="Enter your password"
                        required
                        autocomplete="current-password"
                    >

                    @error('password')
                        <div class="error-box">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <!-- REMEMBER + FORGOT -->
                <div class="row-between">

                


                    @if (Route::has('password.request'))

                        <a
                            href="{{ route('password.request') }}"
                            class="link"
                        >
                            Forgot password?
                        </a>

                    @endif

                </div>


                <button
                    type="submit"
                    class="btn-primary"
                >
                    Sign in to Cardo-Fer
                </button>

            </form>


            @if (session('status'))
                <div class="error-box"
                     style="border-color:rgba(159,191,160,.25); background:rgba(159,191,160,.08); color:var(--sage-bright);">
                    {{ session('status') }}
                </div>
            @endif


            <p class="foot-switch">

                Don't have an account?

                <a
                    href="{{ route('register') }}"
                    class="link"
                >
                    Create an account
                </a>

            </p>

        </div>

    </section>

</main>

</body>
</html>