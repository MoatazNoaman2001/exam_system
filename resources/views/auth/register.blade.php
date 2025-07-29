@extends('layouts.app')

@section('content')
<div class="{{ app()->getLocale() === 'ar' ? 'direction-rtl' : 'direction-ltr' }}">
    <div class="row">
        <!-- Right Column - Image -->
        <div class="col-lg-6 d-lg-block">
            <div class="position-relative h-100">
                <img src="https://images.unsplash.com/photo-1546410531-bb4caa6b424d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80" 
                     alt="PMP Professional Certification" class="w-100 h-100 object-fit-cover">
                <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50"></div>
                <div class="position-absolute bottom-0 {{ app()->getLocale() === 'ar' ? 'end-0 text-end' : 'start-0 text-start' }} p-5 text-white">
                    <h2 class="fw-bold">{{ __('lang.advance_career') }}</h2>
                    <p class="mb-0">{{ __('lang.access_resources') }}</p>
                </div>
            </div>
        </div>
        <!-- Left Column - Form -->
        <div class="col-lg-6 d-flex align-items-center justify-content-center register-container">
            <a class="backcontainer" href="{{route('welcome')}}">
                <p>{{__('lang.home')}}</p>
            </a>
            <div class="w-100 px-4 px-md-5" style="max-width: 700px;">
                <!-- Header -->
                <div class="text-center mb-5">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <i class="fas fa-graduation-cap fa-2x text-primary {{ app()->getLocale() === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        <span class="h3 fw-bold text-primary mb-0">Sprint Skills</span>
                    </div>
                    <h1 class="h2 fw-bold">{{ __('lang.create_professional_account') }}</h1>
                    <p class="text-muted">{{ __('lang.join_community') }}</p>
                </div>

                <!-- Error Messages -->
                @if ($errors->any())
                <div class="alert alert-danger d-flex align-items-center mb-4">
                    <i class="fas fa-exclamation-circle {{ app()->getLocale() === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Registration Form -->
                <form method="POST" action="{{ route('register') }}" class="needs-validation" novalidate dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
                    @csrf

                    <div class="row">
                        <!-- Username -->
                        <div class="col-12 mb-4">
                            <label for="username" class="form-label fw-medium {{ app()->getLocale() === 'ar' ? 'text-end d-block' : '' }}">{{ __('lang.username') }}</label>
                            <div class="input-group position-relative">
                                <input type="text" class="form-control wider-input @error('username') is-invalid @enderror {{ app()->getLocale() === 'ar' ? 'text-end' : '' }}" 
                                       id="username" name="username" value="{{ old('username') }}" 
                                       placeholder="{{ __('lang.enter_username') }}" required autocomplete="username" autofocus>
                                <i class="fas fa-user field-icon {{ app()->getLocale() === 'ar' ? 'order-0' : '' }}"></i>
                                @error('username')
                                    <div class="invalid-feedback d-flex align-items-center {{ app()->getLocale() === 'ar' ? 'justify-content-end' : '' }}">
                                        <i class="fas fa-exclamation-circle {{ app()->getLocale() === 'ar' ? 'ms-1' : 'me-1' }}"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-12 mb-4">
                            <label for="email" class="form-label fw-medium {{ app()->getLocale() === 'ar' ? 'text-end d-block' : '' }}">{{ __('lang.professional_email') }}</label>
                            <div class="input-group position-relative">
                                <input type="email" class="form-control wider-input @error('email') is-invalid @enderror {{ app()->getLocale() === 'ar' ? 'text-end' : '' }}" 
                                       id="email" name="email" value="{{ old('email') }}" 
                                       placeholder="{{ __('lang.enter_email') }}" required autocomplete="email">
                                <i class="fas fa-envelope field-icon {{ app()->getLocale() === 'ar' ? 'order-0' : '' }}"></i>
                                @error('email')
                                    <div class="invalid-feedback d-flex align-items-center {{ app()->getLocale() === 'ar' ? 'justify-content-end' : '' }}">
                                        <i class="fas fa-exclamation-circle {{ app()->getLocale() === 'ar' ? 'ms-1' : 'me-1' }}"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Passwords -->
                        <div class="col-md-6 mb-4">
                            <label for="password" class="form-label fw-medium {{ app()->getLocale() === 'ar' ? 'text-end d-block' : '' }}">{{ __('lang.secure_password') }}</label>
                            <div class="input-group position-relative">
                                <input type="password" class="form-control wider-input @error('password') is-invalid @enderror {{ app()->getLocale() === 'ar' ? 'text-end' : '' }}" 
                                       id="password" name="password" placeholder="{{ __('lang.create_password') }}" required autocomplete="new-password">
                                <i class="fas fa-eye toggle-password {{ app()->getLocale() === 'ar' ? 'order-0' : '' }}" data-target="password"></i>
                                @error('password')
                                    <div class="invalid-feedback d-flex align-items-center {{ app()->getLocale() === 'ar' ? 'justify-content-end' : '' }}">
                                        <i class="fas fa-exclamation-circle {{ app()->getLocale() === 'ar' ? 'ms-1' : 'me-1' }}"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="password_confirmation" class="form-label fw-medium {{ app()->getLocale() === 'ar' ? 'text-end d-block' : '' }}">{{ __('lang.confirm_password') }}</label>
                            <div class="input-group position-relative">
                                <input type="password" class="form-control wider-input {{ app()->getLocale() === 'ar' ? 'text-end' : '' }}" 
                                       id="password_confirmation" name="password_confirmation" 
                                       placeholder="{{ __('lang.reenter_password') }}" required autocomplete="new-password">
                                <i class="fas fa-eye toggle-password {{ app()->getLocale() === 'ar' ? 'order-0' : '' }}" data-target="password_confirmation"></i>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="col-12 mb-4">
                            <label for="phone" class="form-label fw-medium {{ app()->getLocale() === 'ar' ? 'text-end d-block' : '' }}">{{ __('lang.contact_number') }}</label>
                            <div class="input-group position-relative">
                                <input type="tel" class="form-control wider-input @error('phone') is-invalid @enderror {{ app()->getLocale() === 'ar' ? 'text-end' : '' }}" 
                                       id="phone" name="phone" value="{{ old('phone') }}" 
                                       placeholder="{{ __('lang.enter_phone') }}" required autocomplete="tel">
                                <i class="fas fa-phone field-icon {{ app()->getLocale() === 'ar' ? 'order-0' : '' }}"></i>
                                @error('phone')
                                    <div class="invalid-feedback d-flex align-items-center {{ app()->getLocale() === 'ar' ? 'justify-content-end' : '' }}">
                                        <i class="fas fa-exclamation-circle {{ app()->getLocale() === 'ar' ? 'ms-1' : 'me-1' }}"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Role & Language -->
                        <div class="col-md-6 d-none mb-4">
                            <label for="role" class="form-label fw-medium {{ app()->getLocale() === 'ar' ? 'text-end d-block' : '' }}">{{ __('lang.professional_role') }}</label>
                            <div class="input-group position-relative">
                                <select class="form-select @error('role') is-invalid @enderror {{ app()->getLocale() === 'ar' ? 'text-end' : '' }}" 
                                        id="role" name="role" required>
                                    <option value="" disabled>{{ __('lang.select_role') }}</option>
                                    <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>{{ __('lang.pmp_candidate') }}</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>{{ __('lang.administrator') }}</option>
                                </select>
                                <i class="fas fa-user-tag field-icon {{ app()->getLocale() === 'ar' ? 'order-0' : '' }}"></i>
                                @error('role')
                                    <div class="invalid-feedback d-flex align-items-center {{ app()->getLocale() === 'ar' ? 'justify-content-end' : '' }}">
                                        <i class="fas fa-exclamation-circle {{ app()->getLocale() === 'ar' ? 'ms-1' : 'me-1' }}"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="preferred_language" class="form-label fw-medium {{ app()->getLocale() === 'ar' ? 'text-end d-block' : '' }}">{{ __('lang.preferred_language') }}</label>
                            <div class="input-group position-relative">
                                <select class="form-select @error('preferred_language') is-invalid @enderror {{ app()->getLocale() === 'ar' ? 'text-end' : '' }}" 
                                        id="preferred_language" name="preferred_language" required>
                                    <option value="" disabled selected>{{ __('lang.choose_language') }}</option>
                                    <option value="ar" {{ old('preferred_language') == 'ar' ? 'selected' : '' }}>{{ __('lang.arabic') }}</option>
                                    <option value="en" {{ old('preferred_language') == 'en' ? 'selected' : '' }}>{{ __('lang.english') }}</option>
                                </select>
                                {{-- <i class="fas fa-language field-icon {{ app()->getLocale() === 'ar' ? 'order-0' : '' }}"></i> --}}
                                @error('preferred_language')
                                    <div class="invalid-feedback d-flex align-items-center {{ app()->getLocale() === 'ar' ? 'justify-content-end' : '' }}">
                                        <i class="fas fa-exclamation-circle {{ app()->getLocale() === 'ar' ? 'ms-1' : 'me-1' }}"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Terms Checkbox -->
                        <div class="col-12 mb-4">
                            <div class="form-check">
                                <input class="form-check-input @error('is_agree') is-invalid @enderror" 
                                       type="checkbox" name="is_agree" id="is_agree" required 
                                       {{ old('is_agree') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_agree">
                                    {{ __('lang.agree_to_terms') }} <a href="{{ route('terms') }}" class="text-decoration-none fw-medium text-primary">{{ __('lang.terms_of_service') }}</a> {{ __('lang.and') }} {{ __('lang.privacy_policy') }}
                                </label>
                                @error('is_agree')
                                    <div class="invalid-feedback d-flex align-items-center {{ app()->getLocale() === 'ar' ? 'justify-content-end' : '' }}">
                                        <i class="fas fa-exclamation-circle {{ app()->getLocale() === 'ar' ? 'ms-1' : 'me-1' }}"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-12 mt-4">
                            <button class="btn btn-primary w-100 py-3" type="submit">
                                <i class="fas fa-user-plus {{ app()->getLocale() === 'ar' ? 'ms-2' : 'me-2' }}"></i> {{ __('lang.create_account') }}
                            </button>
                        </div>

                        <!-- Login Link -->
                        <div class="col-12 text-center mt-4">
                            <p class="text-muted mb-0">{{ __('lang.already_registered') }} 
                                <a href="{{ route('login') }}" class="text-decoration-none fw-medium text-primary">{{ __('lang.sign_in') }}</a>
                            </p>
                        </div>
                    </div>
                </form>
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

.form-control, .form-select {
    border-color: #e2e8f0;
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    transition: all 0.2s ease;
}

.form-control:focus, .form-select:focus {
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
    padding: 0.75rem;
    transition: color 0.2s ease;
    position: absolute;
    {{ app()->getLocale() === 'ar' ? 'left: 0.75rem' : 'right: 0.75rem' }};
    top: 50%;
    transform: translateY(-50%);
    z-index: 10;
    background: transparent;
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

/* Professional spacing and typography */
.h2 {
    color: #111827;
    font-weight: 700;
}

.h3 {
    color: var(--bs-primary);
}

/* Wider inputs */
.wider-input {
    min-width: 300px;
    width: 100%;
    padding-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }}: 2.5rem;
}

/* RTL adjustments for dropdown */
.direction-rtl .form-select {
    background-position: left 0.75rem center;
    padding-left: 2.5rem;
    padding-right: 1rem;
}

/* Enhanced responsive design */
@media (max-width: 767.98px) {
    .min-vh-90 {
        min-height: auto !important;
    }
    
    .form-control, .form-select {
        font-size: 1rem; /* Better mobile usability */
    }
    
    .wider-input {
        min-width: 100%;
    }
}

.register-container {
    padding: 80px 0 !important;
}

[dir='rtl'] .backcontainer {
        position: absolute; left: 15px; top: 15px; display: flex; gap: 8px;
        color: gray;
        cursor: pointer;
        text-decoration: none;
        background-color: transparent;
        border: 0px;
    }       
    [dir='ltr'] .backcontainer {
        position: absolute; right: 15px; top: 15px; display: flex; gap: 8px;
        color: rgb(70, 70, 70);
        cursor: pointer;
        text-decoration: none;
        background-color: transparent;
        border: 0px;
    }
    .backcontainer:hover{
        color: rgb(17, 17, 111)
    }
</style>