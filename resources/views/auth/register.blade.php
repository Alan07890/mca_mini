<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cardo-Fer · Create Account</title>

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
            --sage-bright: #b7d2ac;
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

            background:
                linear-gradient(
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

            max-width: 540px;
        }

        .promo h1 em {
            font-style: italic;
            color: var(--sage-bright);
        }

        .desc {
            margin-top: 24px;
            max-width: 440px;

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
            max-width: 460px;

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

            font-size: 28px;
            font-weight: 500;
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
            gap: 15px;
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

        /* Role select — reuses the same visual language as .field input */
        .field select {
            width: 100%;

            background: rgba(6,16,10,.45);

            border: 1px solid var(--line-strong);

            border-radius: 12px;

            padding: 13px 15px;

            font-size: 14.5px;

            color: var(--cream);

            font-family: var(--sans);

            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;

            background-image:
                url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%238fac96' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><polyline points='6 9 12 15 18 9'></polyline></svg>");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 14px;

            cursor: pointer;

            transition:
                border-color .2s ease,
                box-shadow .2s ease;
        }

        .field select:focus {
            outline: none;

            border-color: var(--gold);

            box-shadow:
                0 0 0 3px rgba(217,154,78,.16);
        }

        .field select option {
            background: var(--bg-1);
            color: var(--cream);
        }

        .error-box {
            margin-top: 3px;

            padding: 9px 11px;

            border-radius: 10px;

            border: 1px solid rgba(226,131,107,.25);

            background: rgba(226,131,107,.08);

            color: var(--danger);

            font-size: 12px;
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
            Grow smarter,
            <br>
            farm with <em>Cardo-Fer.</em>
        </h1>


        <p class="desc">
            Create your Cardo-Fer account and bring disease
            guidance, fertilizer planning and field management
            into one place.
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

            <h2>
                Create your Cardo-Fer account
            </h2>

            <p class="sub">
                Join Cardo-Fer and manage your cardamom farm smarter.
            </p>


            <form
                method="POST"
                action="{{ route('register') }}"
                class="auth-form"
            >

                @csrf


                <!-- NAME -->

                <div class="field">

                    <label for="name">
                        Full Name
                    </label>

                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Your full name"
                        required
                        autofocus
                        autocomplete="name"
                    >

                    @error('name')
                        <div class="error-box">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


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
                        autocomplete="username"
                    >

                    @error('email')
                        <div class="error-box">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <!-- ROLE -->

                <div class="field">

                    <label for="role">
                        Register As
                    </label>

                    <select
                        id="role"
                        name="role"
                        required
                    >
                        <option value="" disabled {{ old('role') ? '' : 'selected' }}>
                            Select a role
                        </option>

                        <option value="farmer" {{ old('role') === 'farmer' ? 'selected' : '' }}>
                            Farmer
                        </option>

                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                            Admin
                        </option>
                    </select>

                    @error('role')
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
                        placeholder="Create a password"
                        required
                        autocomplete="new-password"
                    >

                    @error('password')
                        <div class="error-box">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <!-- CONFIRM PASSWORD -->

                <div class="field">

                    <label for="password_confirmation">
                        Confirm Password
                    </label>

                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        placeholder="Re-enter your password"
                        required
                        autocomplete="new-password"
                    >

                    @error('password_confirmation')
                        <div class="error-box">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <button
                    type="submit"
                    class="btn-primary"
                >
                    Create Account
                </button>

            </form>


            <p class="foot-switch">

                Already have an account?

                <a
                    href="{{ route('login') }}"
                    class="link"
                >
                    Sign in
                </a>

            </p>

        </div>

    </section>

</main>

</body>
</html>