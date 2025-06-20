@extends('layouts.app')

@section('content')
<div class="container-fluid p-0 min-vh-90 bg-light ">
    <div class="row g-0">
        <!-- Left Column - Form -->
        <div class="col-lg-6 d-flex align-items-center justify-content-center py-5">
            <div class="w-100 px-4 px-md-5" style="max-width: 600px;">
                <!-- Header -->
                <div class="text-center mb-5">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <i class="fas fa-graduation-cap fa-2x text-primary me-2"></i>
                        <span class="h3 fw-bold text-primary mb-0">PMP Master</span>
                    </div>
                    <h1 class="h2 fw-bold">Create Professional Account</h1>
                    <p class="text-muted">Join our community of certified project management professionals</p>
                </div>

                <!-- Error Messages -->
                @if ($errors->any())
                <div class="alert alert-danger d-flex align-items-center mb-4">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Registration Form -->
                <form method="POST" action="{{ route('register') }}" class="needs-validation" novalidate>
                    @csrf

                    <div class="row g-3">
                        <!-- Username -->
                        <div class="col-12">
                            <label for="username" class="form-label fw-medium">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                       id="username" name="username" value="{{ old('username') }}" 
                                       placeholder="Enter your preferred username" required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-12">
                            <label for="email" class="form-label fw-medium">Professional Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" 
                                       placeholder="your.email@company.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Passwords -->
                        <div class="col-md-6">
                            <label for="password" class="form-label fw-medium">Secure Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" placeholder="Create secure password" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label fw-medium">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" 
                                       placeholder="Re-enter password" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="col-12">
                            <label for="phone" class="form-label fw-medium">Contact Number</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}" 
                                       placeholder="+1 (555) 123-4567" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Role & Language -->
                        <div class="col-md-6 d-none">
                            <label for="role" class="form-label fw-medium">Professional Role</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                <select class="form-select @error('role') is-invalid @enderror" 
                                        id="role" name="role" required>
                                    <option value="" disabled >Select professional role</option>
                                    <option value="student" selected {{ old('role') == 'student' ? 'selected' : '' }}>PMP Candidate</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="preferred_language" class="form-label fw-medium">Preferred Language</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-language"></i></span>
                                <select class="form-select @error('preferred_language') is-invalid @enderror" 
                                        id="preferred_language" name="preferred_language" required>
                                    <option value="" disabled selected>Choose preferred language</option>
                                    <option value="ar" {{ old('preferred_language') == 'ar' ? 'selected' : '' }}>العربية (Arabic)</option>
                                    <option value="en" {{ old('preferred_language') == 'en' ? 'selected' : '' }}>English</option>
                                </select>
                                @error('preferred_language')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Terms Checkbox -->
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input @error('is_agree') is-invalid @enderror" 
                                       type="checkbox" name="is_agree" id="is_agree" required 
                                       {{ old('is_agree') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_agree">
                                    I acknowledge and agree to the <a href="{{route('terms')}}" class="text-decoration-none fw-medium">Terms of Service</a> and Privacy Policy
                                </label>
                                @error('is_agree')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-12 mt-4">
                            <button class="btn btn-primary w-100 py-3" type="submit">
                                <i class="fas fa-user-plus me-2"></i> Create Professional Account
                            </button>
                        </div>

                        <!-- Login Link -->
                        <div class="col-12 text-center mt-4">
                            <p class="text-muted mb-0">Already registered? 
                                <a href="{{ route('login') }}" class="text-decoration-none fw-medium">Sign in to your account</a>
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column - Image -->
        <div class="col-lg-6 d-none d-lg-block">
            <div class="position-relative h-100">
                <img src="https://images.unsplash.com/photo-1546410531-bb4caa6b424d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80" 
                     alt="PMP Professional Certification" class="w-100 h-100 object-fit-cover">
                <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50"></div>
                <div class="position-absolute bottom-0 start-0 p-5 text-white">
                    <h2 class="fw-bold">Advance Your Project Management Career</h2>
                    <p class="mb-0">Access industry-leading study resources, practice assessments, and career development tools</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Toggle password visibility
document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function() {
        const input = this.closest('.input-group').querySelector('input');
        const icon = this.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    });
});

// Form validation
(function () {
    'use strict'
    
    const forms = document.querySelectorAll('.needs-validation')
    
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            
            form.classList.add('was-validated')
        }, false)
    })
})()
</script>
@endpush

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

.input-group-text {
    background-color: #f1f5f9;
    border-color: #e2e8f0;
    color: #64748b;
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

.toggle-password {
    cursor: pointer;
    border-color: #e2e8f0;
    color: #64748b;
    transition: all 0.2s ease;
}

.toggle-password:hover {
    background-color: #f8fafc;
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

/* Enhanced responsive design */
@media (max-width: 767.98px) {
    .min-vh-100 {
        min-height: auto !important;
    }
    
    .form-control, .form-select {
        font-size: 1rem; /* Better mobile usability */
    }
}
</style>
@endsection