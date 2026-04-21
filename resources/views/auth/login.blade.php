@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;600;700&family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
/* ─── Variables ─────────────────────────────────────────────────────────────── */
:root {
    --primary:       #3b82f6;
    --primary-dark:  #2563eb;
    --primary-light: #eff6ff;
    --danger:        #ef4444;
    --danger-bg:     #fef2f2;
    --gray-50:  #f8fafc;
    --gray-100: #f1f5f9;
    --gray-200: #e2e8f0;
    --gray-300: #cbd5e1;
    --gray-400: #94a3b8;
    --gray-500: #64748b;
    --gray-600: #475569;
    --gray-700: #334155;
    --gray-800: #1e293b;
    --gray-900: #0f172a;
    --radius-sm: 8px;
    --radius-md: 12px;
}

* { box-sizing: border-box; }

body {
    font-family: 'Tajawal', 'Cairo', sans-serif;
    background: var(--gray-50);
    margin: 0;
    padding: 0;
    min-height: 100vh;
}

/* ─── Layout ─────────────────────────────────────────────────────────────────── */
.login-container { min-height: 100vh; width: 100%; display: flex; }

.login-wrapper {
    width: 100%;
    display: flex;
    min-height: 100vh;
}

/* ─── Branded Left Panel ─────────────────────────────────────────────────────── */
.design-half {
    width: 44%;
    flex-shrink: 0;
    background: linear-gradient(150deg, #1d4ed8 0%, #3b82f6 55%, #60a5fa 100%);
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: white;
    text-align: center;
    padding: 3rem 2.5rem;
}

.design-half::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(circle at 20% 30%, rgba(255,255,255,0.12) 0%, transparent 50%),
        radial-gradient(circle at 80% 70%, rgba(255,255,255,0.08) 0%, transparent 50%);
    pointer-events: none;
}

.floating-shapes {
    position: absolute;
    inset: 0;
    overflow: hidden;
    pointer-events: none;
}

.shape {
    position: absolute;
    border-radius: 50%;
    background: rgba(255,255,255,0.06);
    animation: floatShape 18s infinite ease-in-out;
}
.shape:nth-child(1) { width: 180px; height: 180px; top: 10%;  left: -5%;  animation-delay: 0s; }
.shape:nth-child(2) { width: 120px; height: 120px; bottom: 15%; right: -2%; animation-delay: -6s; }
.shape:nth-child(3) { width: 80px;  height: 80px;  top: 55%;  left: 15%;  animation-delay: -12s; }
.shape:nth-child(4) { width: 60px;  height: 60px;  top: 20%;  right: 10%; animation-delay: -4s; }

@keyframes floatShape {
    0%, 100% { transform: translateY(0) scale(1); }
    50%       { transform: translateY(-24px) scale(1.04); }
}

.brand-section {
    position: relative;
    z-index: 2;
    margin-bottom: 2.5rem;
}

.logo-img {
    width: 140px !important;
    height: 140px !important;
    object-fit: contain;
    user-select: none;
    -webkit-user-drag: none;
    filter: drop-shadow(0 8px 24px rgba(0,0,0,0.2));
}

.brand-name {
    font-size: 2rem;
    font-weight: 800;
    font-family: 'Cairo', 'Tajawal', sans-serif;
    color: #fff;
    margin-top: 0.75rem;
    letter-spacing: -0.5px;
}

.welcome-content {
    position: relative;
    z-index: 2;
    margin-bottom: 2.5rem;
}

.welcome-title {
    font-size: 1.75rem;
    font-weight: 700;
    font-family: 'Cairo', 'Tajawal', sans-serif;
    color: #fff;
    margin-bottom: 0.75rem;
    line-height: 1.3;
}

.welcome-subtitle {
    font-size: 1rem;
    color: rgba(255,255,255,0.82);
    line-height: 1.6;
}

.design-features {
    position: relative;
    z-index: 2;
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    justify-content: center;
}

.feature-badge {
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 24px;
    padding: 0.5rem 1.1rem;
    font-size: 0.85rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #fff;
    transition: background 0.2s ease;
}
.feature-badge:hover { background: rgba(255,255,255,0.25); }

/* ─── Form Right Panel ────────────────────────────────────────────────────────── */
.form-half {
    flex: 1;
    background: #fff;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    padding: 0 2rem 2.5rem;
    overflow-y: auto;
    min-height: 100vh;
}

.form-container {
    width: 100%;
    max-width: 420px;
    padding-top: 1rem;
}

/* ─── Top Navigation Bar ──────────────────────────────────────────────────────── */
.top-bar {
    position: sticky;
    top: 0;
    z-index: 100;
    background: #fff;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 8px;
    padding: 1rem 0;
    margin-bottom: 2rem;
    border-bottom: 1px solid var(--gray-100);
    width: 100%;
    max-width: 420px;
    align-self: center;
}

.backcontainer {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    padding: 0.4rem 0.9rem;
    border-radius: var(--radius-sm);
    background: var(--gray-100);
    color: var(--gray-600);
    border: 1px solid var(--gray-200);
    font-size: 0.85rem;
    font-weight: 600;
    white-space: nowrap;
    transition: background 0.15s, color 0.15s, border-color 0.15s;
}
.backcontainer p { margin: 0; }
.backcontainer:hover {
    background: var(--primary-light);
    border-color: rgba(59,130,246,0.3);
    color: var(--primary-dark);
}
.backcontainer.active {
    background: var(--primary);
    border-color: var(--primary);
    color: #fff;
}

/* ─── Form Header ─────────────────────────────────────────────────────────────── */
.form-header {
    text-align: center;
    margin-bottom: 2rem;
}

.form-title {
    font-size: 1.65rem;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 0.35rem;
    font-family: 'Cairo', 'Tajawal', sans-serif;
    letter-spacing: -0.3px;
}

.form-subtitle {
    color: var(--gray-400);
    font-size: 0.9rem;
}

/* ─── Alert ───────────────────────────────────────────────────────────────────── */
.alert-modern {
    border-radius: var(--radius-md);
    padding: 0.875rem 1.125rem;
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
    font-weight: 500;
    display: flex;
    align-items: flex-start;
    gap: 0.625rem;
}
.alert-danger-modern {
    background: var(--danger-bg);
    color: var(--danger);
    border: 1px solid rgba(239,68,68,0.3);
}

/* ─── Input Fields ────────────────────────────────────────────────────────────── */
.input-group-modern { margin-bottom: 1.125rem; }

.form-label-modern {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 0.375rem;
}

.input-container {
    position: relative;
    display: flex;
    align-items: center;
}

.input-icon {
    position: absolute;
    left: 13px;
    color: var(--gray-400);
    font-size: 0.9rem;
    z-index: 2;
    pointer-events: none;
    transition: color 0.15s ease;
}

.form-control-modern {
    width: 100%;
    height: 46px;
    padding: 0 12px 0 38px;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--radius-sm);
    font-size: 0.925rem;
    color: var(--gray-800);
    background: var(--gray-50);
    font-family: 'Tajawal', 'Cairo', sans-serif;
    transition: border-color 0.15s ease, background 0.15s ease, box-shadow 0.15s ease;
    outline: none;
}
.form-control-modern::placeholder { color: var(--gray-400); }

.form-control-modern:focus {
    border-color: var(--primary);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
}
.form-control-modern:focus ~ .input-icon { color: var(--primary); }

.form-control-modern.is-invalid {
    border-color: var(--danger);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(239,68,68,0.08);
}

.password-toggle {
    position: absolute;
    right: 12px;
    color: var(--gray-400);
    cursor: pointer;
    font-size: 0.9rem;
    z-index: 2;
    padding: 4px;
    transition: color 0.15s ease;
}
.password-toggle:hover { color: var(--gray-600); }

/* ─── Validation Feedback ─────────────────────────────────────────────────────── */
.invalid-feedback-modern {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    color: var(--danger);
    font-size: 0.8rem;
    margin-top: 0.375rem;
    font-weight: 500;
}

/* ─── Form Options Row ────────────────────────────────────────────────────────── */
.form-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.form-check-modern {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-check-input-modern {
    width: 18px;
    height: 18px;
    min-width: 18px;
    border: 1.5px solid var(--gray-300);
    border-radius: 4px;
    background: #fff;
    cursor: pointer;
    appearance: none;
    -webkit-appearance: none;
    transition: background 0.15s, border-color 0.15s;
    position: relative;
}
.form-check-input-modern:checked {
    background: var(--primary);
    border-color: var(--primary);
}
.form-check-input-modern:checked::after {
    content: '';
    position: absolute;
    top: 2px;
    left: 5px;
    width: 5px;
    height: 9px;
    border: 2px solid #fff;
    border-top: none;
    border-left: none;
    transform: rotate(45deg);
}

.form-check-label-modern {
    color: var(--gray-600);
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
}

.forgot-link {
    color: var(--primary);
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 600;
    transition: color 0.15s ease;
}
.forgot-link:hover { color: var(--primary-dark); text-decoration: underline; }

/* ─── Submit Button ───────────────────────────────────────────────────────────── */
.btn-login {
    width: 100%;
    height: 48px;
    padding: 0 1.5rem;
    background: var(--primary);
    border: none;
    border-radius: var(--radius-sm);
    color: #fff;
    font-weight: 700;
    font-size: 0.95rem;
    font-family: 'Cairo', 'Tajawal', sans-serif;
    cursor: pointer;
    margin-bottom: 1.25rem;
    position: relative;
    overflow: hidden;
    transition: background 0.15s ease, transform 0.1s ease, box-shadow 0.15s ease;
}
.btn-login:hover {
    background: var(--primary-dark);
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(59,130,246,0.3);
}
.btn-login:active { transform: translateY(0); }
.btn-login.loading { pointer-events: none; opacity: 0.75; }
.btn-login.loading::after {
    content: '';
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    width: 18px; height: 18px;
    border: 2px solid rgba(255,255,255,0.4);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

.signup-link {
    text-align: center;
    color: var(--gray-500);
    font-size: 0.9rem;
}
.signup-link a {
    color: var(--primary);
    text-decoration: none;
    font-weight: 600;
    margin-inline-start: 4px;
}
.signup-link a:hover { color: var(--primary-dark); text-decoration: underline; }

/* ─── Animations ──────────────────────────────────────────────────────────────── */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    20%       { transform: translateX(-5px); }
    60%       { transform: translateX(5px); }
}
@keyframes spin {
    to { transform: translate(-50%, -50%) rotate(360deg); }
}

/* ─── RTL Support ─────────────────────────────────────────────────────────────── */
[dir="rtl"] .input-icon    { left: auto; right: 13px; }
[dir="rtl"] .password-toggle { right: auto; left: 12px; }
[dir="rtl"] .form-control-modern { padding: 0 38px 0 12px; }
[dir="rtl"] .top-bar { justify-content: flex-start; }
[dir="rtl"] .backcontainer { font-family: 'Tajawal', 'Cairo', sans-serif; }
[dir="rtl"] .form-options { flex-direction: row-reverse; }

/* ─── Responsive ──────────────────────────────────────────────────────────────── */
@media (max-width: 900px) {
    .login-wrapper { flex-direction: column; }

    .design-half {
        width: 100%;
        padding: 2rem 1.5rem 1.5rem;
        min-height: auto;
    }
    .design-half .floating-shapes { display: none; }
    .design-half::before { display: none; }

    .logo-img      { width: 90px !important; height: 90px !important; }
    .brand-name    { font-size: 1.5rem; }
    .welcome-title { font-size: 1.25rem; }
    .welcome-subtitle { font-size: 0.9rem; }
    .design-features { display: none; }

    .form-half {
        min-height: auto;
        flex: 1;
        padding: 0 1.5rem 2rem;
    }
    .form-container { max-width: 100%; padding-top: 0.5rem; }
    .top-bar        { max-width: 100%; }
}

@media (max-width: 600px) {
    .design-half {
        padding: 1.25rem 1rem 1rem;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        gap: 1rem;
        text-align: left;
    }
    [dir="rtl"] .design-half { text-align: right; }

    .brand-section {
        margin-bottom: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .logo-img  { width: 48px !important; height: 48px !important; }
    .brand-name { font-size: 1.2rem; margin-top: 0; }
    .welcome-content { display: none; }

    .form-half   { padding: 0 1rem 1.5rem; }
    .form-title  { font-size: 1.4rem; }

    .top-bar {
        gap: 6px;
        padding: 0.75rem 0;
        margin-bottom: 1.25rem;
    }
    .backcontainer { padding: 0.35rem 0.75rem; font-size: 0.8rem; }

    .form-options { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
}

@media (max-width: 380px) {
    .form-title  { font-size: 1.25rem; }
    .form-half   { padding: 0 0.75rem 1.25rem; }
    .form-control-modern { font-size: 0.875rem; }
}

@media (prefers-reduced-motion: reduce) {
    *, *::before, *::after {
        animation-duration: 0.01ms !important;
        transition-duration: 0.01ms !important;
    }
}
</style>

<div class="login-container">
    <div class="login-wrapper" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

        {{-- Branded Left Panel --}}
        <div class="design-half">
            <div class="floating-shapes">
                <div class="shape"></div>
                <div class="shape"></div>
                <div class="shape"></div>
                <div class="shape"></div>
            </div>

            <div class="brand-section">
                <img class="logo-img" src="{{ asset('images/Sprint_Skills_logo_White.png') }}" alt="logo">
                <h1 class="brand-name">Sprint Skills</h1>
            </div>

            <div class="welcome-content">
                <h2 class="welcome-title">{{ __('lang.welcome_back') }}</h2>
                <p class="welcome-subtitle">{{ __('lang.sign_in_to_continue') }}</p>
            </div>

            <div class="design-features">
                <div class="feature-badge">
                    <i class="fas fa-shield-alt"></i>
                    {{ __('lang.secure_login') }}
                </div>
                <div class="feature-badge">
                    <i class="fas fa-rocket"></i>
                    {{ __('lang.fast_access') }}
                </div>
                <div class="feature-badge">
                    <i class="fas fa-trophy"></i>
                    {{ __('lang.achieve_goals') }}
                </div>
            </div>
        </div>

        {{-- Form Right Panel --}}
        <div class="form-half">

            <div class="top-bar">
                <a href="{{ route('welcome') }}"
                   class="backcontainer {{ Route::currentRouteName() == 'welcome' ? 'active' : '' }}">
                    <p>{{ __('lang.home') }}</p>
                </a>
                <a href="{{ route('locale.set', 'en') }}"
                   class="backcontainer {{ app()->getLocale() == 'en' ? 'active' : '' }}">
                    <p>EN</p>
                </a>
                <a href="{{ route('locale.set', 'ar') }}"
                   class="backcontainer {{ app()->getLocale() == 'ar' ? 'active' : '' }}">
                    <p>AR</p>
                </a>
            </div>

            <div class="form-container">
                <div class="form-header">
                    <h2 class="form-title">{{ __('lang.sign_in') }}</h2>
                    <p class="form-subtitle">{{ __('lang.enter_credentials_to_access') }}</p>
                </div>

                @if ($errors->any())
                    <div class="alert-modern alert-danger-modern">
                        <i class="fas fa-exclamation-circle" style="margin-top:2px;flex-shrink:0"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" novalidate>
                    @csrf

                    {{-- Email --}}
                    <div class="input-group-modern">
                        <label for="email" class="form-label-modern">{{ __('lang.email_address') }}</label>
                        <div class="input-container">
                            <input id="email"
                                   type="email"
                                   name="email"
                                   class="form-control-modern @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}"
                                   required
                                   autofocus
                                   autocomplete="email"
                                   placeholder="{{ __('lang.enter_your_email') }}">
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                        @error('email')
                            <div class="invalid-feedback-modern">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="input-group-modern">
                        <label for="password" class="form-label-modern">{{ __('lang.password') }}</label>
                        <div class="input-container">
                            <input id="password"
                                   type="password"
                                   name="password"
                                   class="form-control-modern @error('password') is-invalid @enderror"
                                   required
                                   autocomplete="current-password"
                                   placeholder="{{ __('lang.enter_your_password') }}">
                            <i class="fas fa-lock input-icon"></i>
                            <i class="fas fa-eye password-toggle" id="pwToggle"></i>
                        </div>
                        @error('password')
                            <div class="invalid-feedback-modern">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Remember / Forgot --}}
                    <div class="form-options">
                        <div class="form-check-modern">
                            <input type="checkbox"
                                   name="remember"
                                   id="remember"
                                   class="form-check-input-modern"
                                   {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember" class="form-check-label-modern">
                                {{ __('lang.remember_me') }}
                            </label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">
                                {{ __('lang.forgot_password') }}
                            </a>
                        @endif
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn-login" id="loginBtn">
                        <span class="btn-text">{{ __('lang.login') }}</span>
                    </button>

                    {{-- Sign Up Link --}}
                    <div class="signup-link">
                        {{ __('lang.dont_have_account') }}
                        <a href="{{ route('register') }}">{{ __('lang.sign_up') }}</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Password toggle
    const pwField  = document.getElementById('password');
    const pwToggle = document.getElementById('pwToggle');
    if (pwToggle) {
        pwToggle.addEventListener('click', function () {
            const isPassword = pwField.type === 'password';
            pwField.type = isPassword ? 'text' : 'password';
            this.classList.toggle('fa-eye',       !isPassword);
            this.classList.toggle('fa-eye-slash',  isPassword);
        });
    }

    // Loading state on submit
    const form     = document.querySelector('form');
    const loginBtn = document.getElementById('loginBtn');
    form.addEventListener('submit', function () {
        loginBtn.classList.add('loading');
        loginBtn.querySelector('.btn-text').textContent = '{{ __("lang.signing_in") }}';
    });

    // Clear invalid state on input
    document.querySelectorAll('.form-control-modern').forEach(function (input) {
        input.addEventListener('input', function () {
            this.classList.remove('is-invalid');
        });
    });
});
</script>
@endsection
