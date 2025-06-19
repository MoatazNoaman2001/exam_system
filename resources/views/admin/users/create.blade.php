@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between mb-4">
        <div class="mb-3 mb-md-0">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-student-plus mr-2" style="color: var(--sidebar-bg);"></i> Create New student
            </h1>
            <nav aria-label="breadcrumb" class="d-none d-md-block mt-2">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users') }}">students</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left mr-2"></i> Back to students
        </a>
    </div>

    <!-- Form Card -->
    <div class="card border-0 shadow-lg rounded-lg overflow-hidden">
        <div class="card-header text-white py-3" style="background-color: var(--sidebar-bg);">
            <div class="d-flex align-items-center gap-3">
                <i class="fas fa-student-plus mr-2"></i> 
                <h5 class="m-0">student Information</h5>
            </div>
        </div>
        <div class="card-body p-4 p-md-5">
            <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf
                <div class="row">
                    <!-- Left Column - Personal Info -->
                    <div class="col-lg-6">
                        <div class="mb-4">
                            <h6 class="mb-3 pb-2 border-bottom " style="color: var(--sidebar-bg);">
                                <i class="fas fa-id-card mr-2" style="color: var(--sidebar-bg);"></i> Personal Information
                            </h6>
                            
                            <!-- Profile Picture -->
                            <div class="text-center mb-4">
                                <div class="position-relative d-inline-block">
                                    <img id="image-preview" src="https://ui-avatars.com/api/?name=New+student&size=200&background=random" 
                                         class="rounded-circle shadow-sm border" width="150" height="150">
                                    <label for="image" class="btn btn-sm  rounded-circle position-absolute d-flex align-items-center justify-content-center" 
                                           style="bottom: 10px; right: 10px; width: 36px; height: 36px; line-height: 36px;
                                           background-color: var(--sidebar-bg) !important; color:white">
                                        <i class="fas fa-camera"></i>
                                        <input type="file" class="d-none" id="image" name="image" accept="image/*">
                                    </label>
                                </div>
                                @error('image')
                                    <div class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                                <small class="text-muted d-block mt-2">JPG, PNG or GIF (Max 2MB)</small>
                            </div>
                            
                            <!-- username -->
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                       id="username" name="username" value="{{ old('username') }}" 
                                       placeholder="username" required>
                                <label for="username">username</label>
                                @error('username')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <!-- Email -->
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" 
                                       placeholder="Email Address" required>
                                <label for="email">Email Address</label>
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <!-- Phone -->
                            <div class="form-floating mb-3">
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}" 
                                       placeholder="Phone Number">
                                <label for="phone">Phone Number</label>
                                @error('phone')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column - Account Info -->
                    <div class="col-lg-6">
                        <div class="mb-4">
                            <h6 class="mb-3 pb-2 border-bottom" style="color: var(--sidebar-bg);">
                                <i class="fas fa-lock mr-2" style="color: var(--sidebar-bg);"></i> Account Settings
                            </h6>
                            
                            <!-- Password -->
                            <div class="form-floating mb-3 position-relative">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" placeholder="Password" required>
                                <label for="password">Password</label>
                                <button type="button" class="btn btn-link text-muted position-absolute" 
                                        style="right: 10px; top: 50%; transform: translateY(-50%);"
                                        onclick="togglePassword('password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="text-muted">Minimum 8 characters with at least one number and special character</small>
                            </div>
                            
                            <!-- Confirm Password -->
                            <div class="form-floating mb-3 position-relative">
                                <input type="password" class="form-control" 
                                       id="password-confirm" name="password_confirmation" 
                                       placeholder="Confirm Password" required>
                                <label for="password-confirm">Confirm Password</label>
                                <button type="button" class="btn btn-link text-muted position-absolute" 
                                        style="right: 10px; top: 50%; transform: translateY(-50%);"
                                        onclick="togglePassword('password-confirm')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            
                            <!-- Role -->
                            <div class="mb-3">
                                <label class="form-label">Role</label>
                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check" 
                                           name="role" id="role-student" 
                                           value="student" {{ old('role', 'student') == 'student' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-warning" for="role-student">
                                        <i class="fas fa-student me-2"></i> student
                                    </label>
                                    
                                    <input type="radio" class="btn-check" name="role" id="role-admin" 
                                           value="admin" {{ old('role') == 'admin' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-warning" for="role-admin">
                                        <i class="fas fa-shield-alt me-2"></i> Admin
                                    </label>
                                </div>
                                @error('role')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <!-- Language -->
                            <div class="form-floating mb-3">
                                <select class="form-select" id="preferred_language" name="preferred_language">
                                    <option value="en" {{ old('preferred_language', 'en') == 'en' ? 'selected' : '' }}>English</option>
                                    <option value="fr" {{ old('preferred_language') == 'ar' ? 'selected' : '' }}>Arabic</option>
                                </select>
                                <label for="preferred_language">Preferred Language</label>
                            </div>
                            
                            <!-- Email Verification -->
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="verified" name="verified" 
                                       {{ old('verified') ? 'checked' : '' }}>
                                <label class="form-check-label" for="verified">
                                    Email Verified
                                </label>
                                <small class="text-muted d-block">If checked, student won't need to verify their email</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="d-flex justify-content-between border-top pt-4 mt-3">
                    <button type="reset" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-undo mr-2"></i> Reset
                    </button>
                    <button type="submit" class="btn px-4" style="background-color: var(--sidebar-bg); color: white">
                        <i class="fas fa-student-plus mr-2" style="color: white"></i> Create student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    
    .card {
        border: none;
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08);
    }

    .form-floating label {
        color: #6c757d;
    }
    
    .form-control, .form-select {
        border-radius: 0.375rem;
        padding: 1rem 1.25rem;
        border: 1px solid #e0e0e0;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
    }
    
    .btn-check:checked + .btn-outline-primary {
        background-color: #4e73df;
        color: white;
        border-color: #4e73df;
    }
    
    .form-switch .form-check-input {
        width: 2.5em;
        height: 1.5em;
        margin-left: -2.5em;
    }
    
    #image-preview {
        object-fit: cover;
        border: 3px solid #fff;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .breadcrumb {
        background: none;
        padding: 0;
    }
    
    .breadcrumb-item a {
        color: #4e73df;
        text-decoration: none;
    }
    :root{
        --sidebar-bg: #2c3e50;
    }
    .card-header{
        background-color: var(--sidebar-bg) !important;
    }
    .btn-outline-primary {
        color: #2c3e50 !important;
        border-color: #2c3e50 !important;
    }
    
    .btn-outline-primary:hover,
    .btn-check:checked + .btn-outline-primary {
        background-color: #2c3e50 !important;
        color: white !important;
        border-color: #2c3e50 !important;
    }
    
    .btn-check:focus + .btn-outline-primary {
        box-shadow: 0 0 0 0.25rem rgba(44, 62, 80, 0.25) !important;
    }
</style>
@endpush

@push('scripts')
<script>
    // Image preview
    document.getElementById('image').addEventListener('change', function(e) {
        const preview = document.getElementById('image-preview');
        const file = e.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            
            reader.readAsDataURL(file);
        }
    });
    
    // Toggle password visibility
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = field.nextElementSibling.querySelector('i');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
    
    // Form validation
    (function() {
        'use strict';
        
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        const forms = document.querySelectorAll('.needs-validation');
        
        // Loop over them and prevent submission
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>
@endpush