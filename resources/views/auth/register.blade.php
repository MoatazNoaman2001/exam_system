@extends('layouts.app')

@section('content')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite('resources/sass/register.scss')
@endpush


<div class="register-container">
    <div class="register-wrapper" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
        
        <!-- Beautiful Design Half -->
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
                <h2 class="welcome-title">{{ __('lang.join_our_community') }}</h2>
                <p class="welcome-subtitle">{{ __('lang.create_account_advance_career') }}</p>
            </div>
            
            <div class="design-features">
                <div class="feature-badge">
                    <i class="fas fa-graduation-cap"></i>
                    {{ __('lang.professional_training') }}
                </div>
                <div class="feature-badge">
                    <i class="fas fa-certificate"></i>
                    {{ __('lang.industry_certifications') }}
                </div>
                <div class="feature-badge">
                    <i class="fas fa-users"></i>
                    {{ __('lang.expert_community') }}
                </div>
            </div>
        </div>

        <!-- Form Half -->
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
                    <h2 class="form-title">{{ __('lang.create_professional_account') }}</h2>
                    <p class="form-subtitle">{{ __('lang.join_community') }}</p>
                </div>

                @if ($errors->any())
                    <div class="alert-modern alert-danger-modern">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="needs-validation" novalidate>
                    @csrf

                    <!-- Username -->
                    <div class="input-group-modern">
                        <label for="username" class="form-label-modern">{{ __('lang.username') }}</label>
                        <div class="input-container">
                            <input id="username" 
                                   type="text" 
                                   class="form-control-modern @error('username') is-invalid @enderror" 
                                   name="username" 
                                   value="{{ old('username') }}" 
                                   required 
                                   autocomplete="username" 
                                   autofocus 
                                   placeholder="{{ __('lang.enter_username') }}">
                            <i class="fas fa-user input-icon"></i>
                        </div>
                        @error('username')
                            <div class="invalid-feedback-modern">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="input-group-modern">
                        <label for="email" class="form-label-modern">{{ __('lang.professional_email') }}</label>
                        <div class="input-container">
                            <input id="email" 
                                   type="email" 
                                   class="form-control-modern @error('email') is-invalid @enderror" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autocomplete="email" 
                                   placeholder="{{ __('lang.enter_email') }}">
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                        
                        <!-- Real-time feedback -->
                        <div id="email-feedback" class="feedback" style="display: none;"></div>
                        
                        @error('email')
                            <div class="invalid-feedback-modern">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password Fields -->
                    <div class="row-modern">
                        <div class="col-modern">
                            <div class="input-group-modern">
                                <label for="password" class="form-label-modern">{{ __('lang.secure_password') }}</label>
                                <div class="input-container">
                                    <input id="password" 
                                           type="password" 
                                           class="form-control-modern @error('password') is-invalid @enderror" 
                                           name="password" 
                                           required 
                                           autocomplete="new-password" 
                                           placeholder="{{ __('lang.create_password') }}">
                                    <i class="fas fa-lock input-icon"></i>
                                    <i class="fas fa-eye password-toggle" data-target="password"></i>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback-modern">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-modern">
                            <div class="input-group-modern">
                                <label for="password_confirmation" class="form-label-modern">{{ __('lang.confirm_password') }}</label>
                                <div class="input-container">
                                    <input id="password_confirmation" 
                                           type="password" 
                                           class="form-control-modern" 
                                           name="password_confirmation" 
                                           required 
                                           autocomplete="new-password" 
                                           placeholder="{{ __('lang.reenter_password') }}">
                                    <i class="fas fa-lock input-icon"></i>
                                    <i class="fas fa-eye password-toggle" data-target="password_confirmation"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="input-group-modern">
                        <label for="phone" class="form-label-modern">{{ __('lang.contact_number') }}</label>
                        <div class="input-container">
                            <input id="phone" 
                                   type="tel" 
                                   class="form-control-modern @error('phone') is-invalid @enderror" 
                                   name="phone" 
                                   value="{{ old('phone') }}" 
                                   required 
                                   autocomplete="tel" 
                                   placeholder="{{ __('lang.enter_phone') }}">
                            <i class="fas fa-phone input-icon"></i>
                        </div>
                        @error('phone')
                            <div class="invalid-feedback-modern">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Language Selection -->
                    <div class="input-group-modern">
                        <label for="preferred_language" class="form-label-modern">{{ __('lang.preferred_language') }}</label>
                        <div class="input-container">
                            <select id="preferred_language" 
                                    name="preferred_language" 
                                    class="form-select-modern @error('preferred_language') is-invalid @enderror" 
                                    required>
                                <option value="" disabled selected>{{ __('lang.choose_language') }}</option>
                                <option value="ar" {{ old('preferred_language') == 'ar' ? 'selected' : '' }}>{{ __('lang.arabic') }}</option>
                                <option value="en" {{ old('preferred_language') == 'en' ? 'selected' : '' }}>{{ __('lang.english') }}</option>
                            </select>
                            <i class="fas fa-language input-icon"></i>
                        </div>
                        @error('preferred_language')
                            <div class="invalid-feedback-modern">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Hidden Role Field -->
                    <input type="hidden" name="role" value="student">

                    <!-- Terms Agreement -->
                    <div class="form-check-modern">
                        <input type="checkbox" 
                               name="is_agree" 
                               id="is_agree" 
                               class="form-check-input-modern @error('is_agree') is-invalid @enderror" 
                               required 
                               {{ old('is_agree') ? 'checked' : '' }}>
                        <label for="is_agree" class="form-check-label-modern">
                            {{ __('lang.agree_to_terms') }} 
                            <a href="{{ route('terms') }}" target="_blank">{{ __('lang.terms_of_service') }}</a>
                        </label>
                        @error('is_agree')
                            <div class="invalid-feedback-modern">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-register" id="registerBtn">
                        <span class="btn-text">
                            <i class="fas fa-user-plus me-2"></i>
                            {{ __('lang.create_account') }}
                        </span>
                    </button>

                    <!-- Sign In Link -->
                    <div class="signin-link">
                        {{ __('lang.already_registered') }}
                        <a href="{{ route('login') }}">{{ __('lang.sign_in') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    window.translations = {
        passwordsMustMatch: '{{ __("lang.passwords_must_match") }}',
        creatingAccount: '{{ __("lang.creating_account") }}',
        emailCheckRoute: '{{ route("check.email") }}',
        csrfToken: '{{ csrf_token() }}'
    };
</script>
@vite('resources/js/register.js')
@endpush