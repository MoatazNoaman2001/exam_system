@extends('layouts.app')

@section('content')
<div class="min-vh-100 bg-light d-flex align-items-center justify-content-center p-3 {{ app()->getLocale() === 'ar' ? 'direction-rtl' : 'direction-ltr' }}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <div class="row g-0">
                        <!-- Login Header -->
                        <div class="col-lg-4 bg-primary bg-gradient text-white p-5 d-flex flex-column justify-content-center">
                            <div class="d-flex align-items-center mb-4">
                                <i class="fas fa-graduation-cap {{ app()->getLocale() === 'ar' ? 'ms-3' : 'me-3' }} text-danger fs-3"></i>
                                <span class="fs-3 fw-bold">PMP Master</span>
                            </div>
                            <h1 class="h3 fw-bold mb-2">{{ __('lang.welcome_back') }}</h1>
                            <p class="opacity-75">{{ __('lang.sign_in_to_continue') }}</p>
                        </div>

                        <!-- Login Card -->
                        <div class="col-lg-5 p-5 d-flex flex-column justify-content-center">
                            @if ($errors->any())
                            <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                                <i class="fas fa-exclamation-circle {{ app()->getLocale() === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                <div>
                                    @foreach ($errors->all() as $error)
                                        <p class="mb-0">{{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
                                @csrf

                                <div class="mb-4">
                                    <label for="email" class="form-label fw-medium {{ app()->getLocale() === 'ar' ? 'text-end d-block' : '' }}">{{ __('lang.email_address') }}</label>
                                    <div class="input-group position-relative">
                                        <input id="email" type="email" class="form-control wider-input @error('email') is-invalid @enderror {{ app()->getLocale() === 'ar' ? 'text-end' : '' }}" 
                                               name="email" value="{{ old('email') }}" required autocomplete="email" 
                                               autofocus placeholder="{{ __('lang.enter_your_email') }}">
                                        <i class="fas fa-envelope field-icon"></i>
                                        @error('email')
                                            <div class="invalid-feedback d-flex align-items-center {{ app()->getLocale() === 'ar' ? 'justify-content-end' : '' }}">
                                                <i class="fas fa-exclamation-circle {{ app()->getLocale() === 'ar' ? 'ms-1' : 'me-1' }}"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="password" class="form-label fw-medium {{ app()->getLocale() === 'ar' ? 'text-end d-block' : '' }}">{{ __('lang.password') }}</label>
                                    <div class="input-group position-relative">
                                        <input id="password" type="password" class="form-control wider-input @error('password') is-invalid @enderror {{ app()->getLocale() === 'ar' ? 'text-end' : '' }}" 
                                               name="password" required autocomplete="current-password" 
                                               placeholder="{{ __('lang.enter_your_password') }}">
                                        <i class="fas fa-eye toggle-password" data-target="password"></i>
                                        @error('password')
                                            <div class="invalid-feedback d-flex align-items-center {{ app()->getLocale() === 'ar' ? 'justify-content-end' : '' }}">
                                                <i class="fas fa-exclamation-circle {{ app()->getLocale() === 'ar' ? 'ms-1' : 'me-1' }}"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
                                    <div class="form-check">
                                        <input type="checkbox" name="remember" id="remember" class="form-check-input" {{ old('remember') ? 'checked' : '' }}>
                                        <label for="remember" class="form-check-label">{{ __('lang.remember_me') }}</label>
                                    </div>
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="text-primary text-decoration-none small">
                                            {{ __('lang.forgot_password') }}
                                        </a>
                                    @endif
                                </div>

                                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                                    {{ __('lang.login') }}
                                </button>

                                <div class="text-center mt-4 text-muted">
                                    <p>{{ __('lang.dont_have_account') }} <a href="{{ route('register') }}" class="text-primary fw-medium text-decoration-none">{{ __('lang.sign_up') }}</a></p>
                                </div>
                            </form>
                        </div>

                        <!-- Login Image -->
                        <div class="col-lg-3 d-none d-lg-block position-relative">
                            <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="PMP Exam Preparation" class="w-100 h-100 object-fit-cover">
                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle password visibility
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toggle-password').forEach(icon => {
        icon.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            
            if (!input) {
                console.error(`Input with ID ${targetId} not found`);
                return;
            }
            
            if (input.type === 'password') {
                input.type = 'text';
                this.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                this.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });
});

// Form validation
(function () {
    'use strict';
    
    const forms = document.querySelectorAll('.needs-validation');
    
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>

<style>
:root {
    --bs-primary: #4a6bff;
    --bs-secondary: #ff6b6b;
}

body {
    font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
    letter-spacing: -0.01em;
}

.bg-light {
    background-color: #f8fafc !important;
}

.form-label {
    color: #374151;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.fw-medium {
    font-weight: 500;
}

.btn-primary {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
    transition: all 0.3s ease;
    font-weight: 600;
    letter-spacing: 0.025em;
}

.btn-primary:hover {
    background-color: #3a56d4;
    border-color: #3a56d4;
    transform: translateY(-1px);
    box-shadow: 0 8px 25px rgba(74, 107, 255, 0.25);
}

.form-control {
    border-color: #e2e8f0;
    padding: 0.75rem 2.5rem 0.75rem 1rem;
    font-size: 0.875rem;
    transition: all 0.2s ease;
}

.direction-rtl .form-control {
    padding: 0.75rem 1rem 0.75rem 2.5rem;
}

.form-control:focus {
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 0.25rem rgba(74, 107, 255, 0.1);
}

.form-control::placeholder {
    color: #9ca3af;
    font-style: normal;
}

.toggle-password, .field-icon {
    cursor: pointer;
    color: #64748b;
    font-size: 0.875rem;
    line-height: 1;
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    z-index: 10;
    background: transparent;
}

.direction-rtl .toggle-password, .direction-rtl .field-icon {
    right: auto;
    left: 1rem;
}

.toggle-password:hover, .field-icon:hover {
    color: #374151;
}

.form-check-label {
    font-size: 0.875rem;
    color: #4b5563;
    line-height: 1.5;
}

.text-muted {
    color: #6b7280 !important;
}

.text-primary {
    color: var(--bs-primary) !important;
}

.object-fit-cover {
    object-fit: cover;
}

.card {
    border-radius: 1rem;
}

.rounded-4 {
    border-radius: 1rem !important;
}

/* Wider inputs */
.wider-input {
    min-width: 300px;
    width: 100%;
}

/* Enhanced responsive design */
@media (max-width: 767.98px) {
    .min-vh-100 {
        min-height: auto !important;
    }
    
    .form-control {
        font-size: 1rem;
    }
    
    .wider-input {
        min-width: 100%;
    }
}
</style>