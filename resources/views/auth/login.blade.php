@extends('layouts.app')

@section('content')
<div class="login-container">
    <div class="login-wrapper">
        <div class="login-header">
            <div class="logo">
                <i class="fas fa-graduation-cap"></i>
                <span>PMP Master</span>
            </div>
            <h1>Welcome Back</h1>
            <p>Sign in to continue your PMP exam preparation</p>
        </div>

        <div class="login-card">
            @if ($errors->any())
            <div class="login-alert">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="login-form">
                @csrf

                <div class="form-group">
                    <label for="email">{{ __('Email Address') }}</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input id="email" type="email" @error('email') is-invalid @enderror 
                               name="email" value="{{ old('email') }}" required autocomplete="email" 
                               autofocus placeholder="Enter your email">
                            
                    </div>
                    @error('email')
                        <span class="error-message">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">{{ __('Password') }}</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input id="password" type="password" class="@error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Enter your password">
                        <button type="button" class="toggle-password" onclick="togglePasswordVisibility()">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="error-message">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-options">
                    <div class="remember-me">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">{{ __('Remember Me') }}</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-password">
                            {{ __('Forgot Password?') }}
                        </a>
                    @endif
                </div>

                <button type="submit" class="login-button">
                    {{ __('Login') }}
                </button>

                <div class="login-footer">
                    <p>Don't have an account? <a href="{{ route('register') }}">Sign up</a></p>
                </div>
            </form>
        </div>

        <div class="login-image">
            <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="PMP Exam Preparation">
            <div class="image-overlay"></div>
        </div>
    </div>
</div>

<style>
    :root {
        --primary: #4a6bff;
        --secondary: #ff6b6b;
        --dark: #2d3748;
        --light: #f8f9fa;
        --gray: #718096;
        --light-gray: #e2e8f0;
        --success: #48bb78;
    }

    .login-container {
        display: flex;
        min-height: 100vh;
        background-color: #f9faff;
        font-family: 'Poppins', sans-serif;
    }

    .login-wrapper {
        display: flex;
        width: 100%;
        max-width: 1200px;
        margin: auto;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border-radius: 15px;
        overflow: hidden;
    }

    .login-header {
        padding: 40px;
        background: linear-gradient(135deg, var(--primary) 0%, #6c8aff 100%);
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .login-header .logo {
        display: flex;
        align-items: center;
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 30px;
    }

    .login-header .logo i {
        margin-right: 10px;
        color: var(--secondary);
    }

    .login-header h1 {
        font-size: 32px;
        margin-bottom: 10px;
    }

    .login-header p {
        opacity: 0.9;
    }

    .login-card {
        flex: 1;
        padding: 40px;
        background-color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .login-alert {
        background-color: #fff3f3;
        border-left: 4px solid var(--secondary);
        padding: 15px;
        margin-bottom: 25px;
        display: flex;
        border-radius: 4px;
    }

    .login-alert i {
        color: var(--secondary);
        margin-right: 10px;
        font-size: 20px;
    }

    .login-alert p {
        margin: 0;
        color: var(--dark);
        font-size: 14px;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: var(--dark);
    }

    .input-with-icon {
        position: relative;
        display: flex;
        align-items: center;
        width: 100%;
    }

    .input-with-icon i.fa-lock {
        position: absolute;
        left: 15px;
        color: var(--gray);
        z-index: 2; /* Ensure it stays above the input */
    }

    .input-with-icon input {
        width: 100%;
        padding: 12px 45px 12px 45px; /* Equal padding on both sides for icons */
        border: 1px solid var(--light-gray);
        border-radius: 8px;
        font-size: 15px;
        transition: all 0.3s;
    }

    .input-with-icon .toggle-password {
        position: absolute;
        right: 15px;
        background: none;
        border: none;
        cursor: pointer;
        color: var(--gray);
        z-index: 2;
    }

    .input-with-icon .toggle-password i {
        margin: 0;
    }

    .input-with-icon input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(74, 107, 255, 0.2);
        outline: none;
    }

    .toggle-password {
        position: absolute;
        right: 15px;
        background: none;
        border: none;
        color: var(--gray);
        cursor: pointer;
        padding: 0;
    }
    
    
    .error-message {
        display: flex;
        align-items: center;
        color: var(--secondary);
        font-size: 13px;
        margin-top: 5px;
    }

    .error-message i {
        margin-right: 5px;
    }

    .form-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 20px 0 30px;
    }

    .remember-me {
        display: flex;
        align-items: center;
    }

    .remember-me input {
        margin-right: 8px;
        accent-color: var(--primary);
    }

    .forgot-password {
        color: var(--primary);
        text-decoration: none;
        font-size: 14px;
        transition: color 0.3s;
    }

    .forgot-password:hover {
        color: #3a56d4;
    }

    .login-button {
        width: 100%;
        padding: 14px;
        background-color: var(--primary);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .login-button:hover {
        background-color: #3a56d4;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(74, 107, 255, 0.3);
    }

    .login-footer {
        text-align: center;
        margin-top: 30px;
        color: var(--gray);
        font-size: 14px;
    }

    .login-footer a {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
    }

    .login-image {
        flex: 1;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--dark);
    }

    .login-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(45, 55, 72, 0.6);
    }

    @media (max-width: 992px) {
        .login-image {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .login-wrapper {
            flex-direction: column;
        }
        
        .login-header {
            padding: 30px;
        }
        
        .login-card {
            padding: 30px;
        }
    }
</style>

<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const icon = document.querySelector('.toggle-password i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>
@endsection