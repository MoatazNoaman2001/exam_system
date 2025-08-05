@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    :root {
        --primary-blue: #3b82f6;
        --primary-blue-light: #60a5fa;
        --primary-blue-dark: #2563eb;
        --primary-blue-bg: #eff6ff;
        --success-green: #10b981;
        --success-green-bg: #ecfdf5;
        --warning-amber: #f59e0b;
        --warning-amber-bg: #fffbeb;
        --danger-red: #ef4444;
        --danger-red-bg: #fef2f2;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-300: #d1d5db;
        --gray-400: #9ca3af;
        --gray-500: #6b7280;
        --gray-600: #4b5563;
        --gray-700: #374151;
        --gray-800: #1f2937;
        --gray-900: #111827;
        --radius-xl: 1rem;
        --radius-2xl: 1.5rem;
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    body {
        font-family: 'Tajawal', 'Cairo', sans-serif;
        background: var(--gray-50);
        margin: 0;
        padding: 0;
        overflow: hidden;
        height: 100%;
        width: 100%;
    }

    .login-container {
        height: 100%;
        width: 100%;
        display: flex;
        padding: 0;
        margin: 0;
        overflow: hidden;
    }

    .login-wrapper {
        width: 100%;
        height: 100%;
        display: flex;
        position: relative;
    }

    /* Beautiful Design Half */
    .design-half {
        flex: 1;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: white;
        text-align: center;
        height: 100vh;
        width: 50%;
    }

    .design-half::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" patternUnits="userSpaceOnUse" width="100" height="100"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="25" r="1.5" fill="rgba(255,255,255,0.08)"/><circle cx="25" cy="75" r="1.2" fill="rgba(255,255,255,0.12)"/><circle cx="75" cy="75" r="0.8" fill="rgba(255,255,255,0.15)"/><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
        animation: float 30s linear infinite;
        opacity: 0.6;
    }

    @keyframes float {
        0% { transform: translateY(0) rotate(0deg); }
        100% { transform: translateY(-100px) rotate(360deg); }
    }

    .floating-shapes {
        position: absolute;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }

    .shape {
        position: absolute;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        animation: floatShape 15s infinite ease-in-out;
    }

    .shape:nth-child(1) {
        width: 100px;
        height: 100px;
        top: 20%;
        left: 10%;
        animation-delay: 0s;
        background: linear-gradient(45deg, rgba(255,255,255,0.2), rgba(255,255,255,0.05));
    }

    .shape:nth-child(2) {
        width: 150px;
        height: 150px;
        top: 60%;
        right: 20%;
        animation-delay: -5s;
        background: linear-gradient(45deg, rgba(255,255,255,0.15), rgba(255,255,255,0.03));
    }

    .shape:nth-child(3) {
        width: 80px;
        height: 80px;
        bottom: 30%;
        left: 20%;
        animation-delay: -10s;
        background: linear-gradient(45deg, rgba(255,255,255,0.25), rgba(255,255,255,0.08));
    }

    .shape:nth-child(4) {
        width: 120px;
        height: 120px;
        top: 10%;
        right: 30%;
        animation-delay: -7s;
        background: linear-gradient(45deg, rgba(255,255,255,0.18), rgba(255,255,255,0.06));
    }

    @keyframes floatShape {
        0%, 100% { transform: translateY(0) rotate(0deg) scale(1); }
        25% { transform: translateY(-20px) rotate(90deg) scale(1.1); }
        50% { transform: translateY(-40px) rotate(180deg) scale(0.9); }
        75% { transform: translateY(-20px) rotate(270deg) scale(1.05); }
    }

    .brand-section {
        position: relative;
        z-index: 10;
        margin-bottom: 3rem;
    }

    .brand-logo {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .brand-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
        border-radius: var(--radius-2xl);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: white;
        box-shadow: 0 15px 35px rgba(255, 107, 107, 0.4);
        animation: pulse 3s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); box-shadow: 0 15px 35px rgba(255, 107, 107, 0.4); }
        50% { transform: scale(1.05); box-shadow: 0 20px 45px rgba(255, 107, 107, 0.6); }
    }

    .brand-name {
        font-size: 3rem;
        font-weight: 800;
        font-family: 'Cairo', 'Tajawal', sans-serif;
        background: linear-gradient(135deg, #ffffff 0%, #f1f2f6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .welcome-content {
        position: relative;
        z-index: 10;
    }

    .welcome-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        font-family: 'Cairo', 'Tajawal', sans-serif;
        text-shadow: 0 3px 10px rgba(0, 0, 0, 0.3);
        line-height: 1.2;
    }

    .welcome-subtitle {
        font-size: 1.2rem;
        opacity: 0.95;
        line-height: 1.6;
        font-weight: 400;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .design-features {
        position: relative;
        z-index: 10;
        margin-top: 3rem;
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        justify-content: center;
    }

    .feature-badge {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 25px;
        padding: 0.75rem 1.5rem;
        font-size: 0.9rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .feature-badge:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    /* Form Half */
    .form-half {
        flex: 1;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        position: relative;
        height: 100vh;
        width: 50%;
        overflow-y: auto;
    }

    .form-container {
        width: 100%;
        max-width: 400px;
        position: relative;
        top:50px
    }

    .form-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .form-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--gray-900);
        margin-bottom: 0.5rem;
        font-family: 'Cairo', 'Tajawal', sans-serif;
    }

    .form-subtitle {
        color: var(--gray-600);
        font-size: 1rem;
        font-weight: 400;
    }

    .input-group-modern {
        margin-bottom: 1.5rem;
    }

    .form-label-modern {
        display: block;
        font-weight: 600;
        color: var(--gray-700);
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .input-container {
        position: relative;
        display: flex;
        align-items: center;
    }

    .form-control-modern {
        width: 100%;
        padding: 1rem 1rem 1rem 3.5rem;
        border: 2px solid var(--gray-200);
        border-radius: var(--radius-xl);
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
        font-family: 'Tajawal', sans-serif;
    }

    .form-control-modern:focus {
        outline: none;
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        transform: translateY(-1px);
    }

    .form-control-modern.is-invalid {
        border-color: var(--danger-red);
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        animation: shake 0.5s ease-in-out;
    }

    .input-icon {
        position: absolute;
        left: 1.2rem;
        color: var(--gray-400);
        font-size: 1.1rem;
        z-index: 2;
        transition: color 0.3s ease;
    }

    .form-control-modern:focus + .input-icon {
        color: var(--primary-blue);
    }

    .password-toggle {
        position: absolute;
        right: 1.2rem;
        color: var(--gray-400);
        cursor: pointer;
        font-size: 1.1rem;
        z-index: 2;
        transition: all 0.3s ease;
        padding: 0.5rem;
    }

    .password-toggle:hover {
        color: var(--gray-600);
        transform: scale(1.1);
    }

    .form-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .form-check-modern {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .form-check-input-modern {
        width: 20px;
        height: 20px;
        border: 2px solid var(--gray-300);
        border-radius: 4px;
        background: white;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .form-check-input-modern:checked {
        background: var(--primary-blue);
        border-color: var(--primary-blue);
    }

    .form-check-input-modern:checked::after {
        content: '\f00c';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        color: white;
        font-size: 0.75rem;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .form-check-label-modern {
        color: var(--gray-700);
        font-size: 0.95rem;
        font-weight: 500;
        cursor: pointer;
    }

    .forgot-link {
        color: var(--primary-blue);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        position: relative;
    }

    .forgot-link:hover {
        color: var(--primary-blue-dark);
        transform: translateY(-1px);
    }

    .forgot-link::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: var(--primary-blue);
        transition: width 0.3s ease;
    }

    .forgot-link:hover::after {
        width: 100%;
    }

    .btn-login {
        width: 100%;
        padding: 1.2rem 2rem;
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-blue-light) 100%);
        border: none;
        border-radius: var(--radius-xl);
        color: white;
        font-weight: 700;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
        font-family: 'Cairo', 'Tajawal', sans-serif;
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }

    .btn-login:hover {
        background: linear-gradient(135deg, var(--primary-blue-dark) 0%, var(--primary-blue) 100%);
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(59, 130, 246, 0.4);
        color: white;
    }

    .btn-login:active {
        transform: translateY(-1px);
    }

    .signup-link {
        text-align: center;
        color: var(--gray-600);
        font-size: 0.95rem;
        margin-top: 1rem;
    }

    .signup-link a {
        color: var(--primary-blue);
        text-decoration: none;
        font-weight: 600;
        margin-right: 0.25rem;
        transition: color 0.3s ease;
    }

    .signup-link a:hover {
        color: var(--primary-blue-dark);
    }

    .alert-modern {
        border: none;
        border-radius: var(--radius-xl);
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        animation: slideDown 0.5s ease-out;
    }

    .alert-danger-modern {
        background: var(--danger-red-bg);
        color: var(--danger-red);
        border: 2px solid var(--danger-red);
    }

    .invalid-feedback-modern {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--danger-red);
        font-size: 0.875rem;
        margin-top: 0.5rem;
        font-weight: 500;
    }

    /* Animations */
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }

    /* Loading state */
    .btn-login.loading {
        pointer-events: none;
        opacity: 0.8;
    }

    .btn-login.loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 20px;
        height: 20px;
        border: 2px solid transparent;
        border-top: 2px solid white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: translate(-50%, -50%) rotate(0deg); }
        100% { transform: translate(-50%, -50%) rotate(360deg); }
    }

    /* Mobile-First Responsive Design */
    @media (max-width: 992px) {
        body {
            overflow-y: auto;
            height: auto;
        }

        .login-container {
            height: auto;
            min-height: 100vh;
            overflow-y: auto;
        }

        .login-wrapper {
            flex-direction: column;
            height: auto;
            min-height: 100vh;
        }

        .design-half {
            padding: 2rem 1rem 1rem;
            height: auto;
            min-height: 200px;
            width: 100%;
            flex: none;
        }

        .form-half {
            padding: 1rem 1rem 2rem;
            height: auto;
            min-height: calc(100vh - 200px);
            width: 100%;
            flex: 1;
            overflow-y: visible;
        }

        .brand-section {
            margin-bottom: 1rem;
        }

        .brand-logo {
            margin-bottom: 1rem;
            flex-direction: row;
            gap: 1rem;
        }

        .brand-name {
            font-size: 2rem;
        }

        .welcome-content {
            margin-bottom: 1rem;
        }

        .welcome-title {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .welcome-subtitle {
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        .brand-icon {
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
        }

        .design-features {
            display: none; /* Hide on mobile to save space */
        }

        .form-title {
            font-size: 1.75rem;
        }

        .form-header {
            margin-bottom: 1.5rem;
        }

        .input-group-modern {
            margin-bottom: 1.25rem;
        }

        .form-control-modern {
            padding: 0.875rem 0.875rem 0.875rem 3rem;
            font-size: 1rem;
        }

        .form-options {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .btn-login {
            padding: 1rem;
            font-size: 1rem;
        }
    }

    @media (max-width: 768px) {
        .design-half {
            padding: 1.5rem 1rem 0.5rem;
            min-height: 150px;
        }

        .form-half {
            padding: 0.5rem 1rem 1.5rem;
            min-height: calc(100vh - 150px);
        }

        .brand-logo {
            flex-direction: column;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }

        .brand-name {
            font-size: 1.5rem;
        }

        .welcome-title {
            font-size: 1.25rem;
            margin-bottom: 0.25rem;
        }

        .welcome-subtitle {
            font-size: 0.85rem;
        }

        .brand-icon {
            width: 40px;
            height: 40px;
            font-size: 1.25rem;
        }

        .form-title {
            font-size: 1.5rem;
        }

        .form-subtitle {
            font-size: 0.9rem;
        }

        .form-header {
            margin-bottom: 1.25rem;
        }

        .input-group-modern {
            margin-bottom: 1rem;
        }

        .form-control-modern {
            padding: 0.75rem 0.75rem 0.75rem 2.5rem;
            font-size: 0.95rem;
        }

        .input-icon {
            left: 0.75rem;
            font-size: 1rem;
        }

        .password-toggle {
            right: 0.75rem;
            font-size: 1rem;
        }

        .form-label-modern {
            font-size: 0.9rem;
            margin-bottom: 0.375rem;
        }

        .btn-login {
            padding: 0.875rem;
            font-size: 0.95rem;
            margin-bottom: 1rem;
        }

        .signup-link {
            font-size: 0.9rem;
        }

        .floating-shapes {
            display: none;
        }

        .design-half::before {
            animation: none;
        }
    }

    @media (max-width: 480px) {
        .design-half {
            padding: 1rem 0.75rem 0.25rem;
            min-height: 120px;
        }

        .form-half {
            padding: 0.25rem 0.75rem 1rem;
            min-height: calc(100vh - 120px);
        }

        .brand-name {
            font-size: 1.25rem;
        }

        .welcome-title {
            font-size: 1.1rem;
        }

        .welcome-subtitle {
            font-size: 0.8rem;
        }

        .brand-icon {
            width: 35px;
            height: 35px;
            font-size: 1.1rem;
        }

        .form-title {
            font-size: 1.25rem;
        }

        .form-subtitle {
            font-size: 0.85rem;
        }

        .form-container {
            max-width: 100%;
        }

        .form-control-modern {
            padding: 0.7rem 0.7rem 0.7rem 2.25rem;
            font-size: 0.9rem;
        }

        .input-icon {
            left: 0.7rem;
            font-size: 0.9rem;
        }

        .password-toggle {
            right: 0.7rem;
            font-size: 0.9rem;
        }

        .btn-login {
            padding: 0.8rem;
            font-size: 0.9rem;
        }

        .form-options {
            gap: 0.5rem;
            margin-bottom: 1.25rem;
        }

        .form-check-label-modern {
            font-size: 0.85rem;
        }

        .forgot-link {
            font-size: 0.85rem;
        }

        .signup-link {
            font-size: 0.85rem;
        }
    }

    @media (max-width: 992px) and (orientation: landscape) {
        .login-wrapper {
            flex-direction: row;
        }

        .design-half {
            width: 40%;
            height: 100vh;
            padding: 1rem;
        }

        .form-half {
            width: 60%;
            height: 100vh;
            padding: 1rem;
        }

        .design-features {
            display: none;
        }

        .brand-logo {
            flex-direction: row;
            gap: 0.75rem;
        }

        .welcome-title {
            font-size: 1.25rem;
        }

        .welcome-subtitle {
            font-size: 0.9rem;
        }
    }

    /* RTL Support */
    [dir="rtl"] .form-control-modern {
        padding: 1rem 3.5rem 1rem 1rem;
    }

    [dir="rtl"] .input-icon {
        left: auto;
        right: 1.2rem;
    }

    [dir="rtl"] .password-toggle {
        right: auto;
        left: 1.2rem;
    }

    [dir="rtl"] .signup-link a {
        margin-right: 0;
        margin-left: 0.25rem;
    }

    @media (max-width: 768px) {
        [dir="rtl"] .form-control-modern {
            padding: 0.75rem 2.5rem 0.75rem 0.75rem;
        }

        [dir="rtl"] .input-icon {
            right: 0.75rem;
        }

        [dir="rtl"] .password-toggle {
            left: 0.75rem;
        }
    }

    @media (max-width: 480px) {
        [dir="rtl"] .form-control-modern {
            padding: 0.7rem 2.25rem 0.7rem 0.7rem;
        }

        [dir="rtl"] .input-icon {
            right: 0.7rem;
        }

        [dir="rtl"] .password-toggle {
            left: 0.7rem;
        }
        .form-container{
            top:20px
        }
    }

    /* Top Bar and Language Switcher */
    .top-bar {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 1rem;
        justify-content: flex-start;
        position: absolute;
        top:15px;
        left: 15px;
    
    }

    .backcontainer {
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        background: var(--gray-100);
        color: var(--gray-700);
        transition: all 0.3s ease;
        font-size: 0.9rem;
        font-weight: 500;
        min-width: 40px;
        text-align: center;
    }

    .backcontainer:hover {
        background: var(--gray-200);
        color: var(--primary-blue);
        transform: translateY(-1px);
    }

    .backcontainer.active {
        background: var(--primary-blue);
        color: white;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .backcontainer p {
        margin: 0;
        white-space: nowrap;
    }

    [dir="rtl"] .backcontainer {
        font-family: 'Tajawal', 'Cairo', sans-serif;
    }

    /* Responsive adjustments */
    @media (max-width: 767.98px) {
        .top-bar {
            width: 70%;
            height: inherit;
            gap: 2px;
            padding: 0.75rem;
            flex-wrap: wrap;
          
           
        }

        .backcontainer {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
            min-width: 35px;
        }
    }

    /* Remove conflicting styles */
    [dir='rtl'] .backcontainer,
    [dir='ltr'] .backcontainer {
        position: static;
        border: none;
    }

    .language-switcher {
        display: none; /* Hide the old language switcher */
    }

    .logo-img {
        width: 240px !important;
        height: 240px !important;
        color: white;
        -webkit-user-drag: none !important;
        user-select: none !important;
        -moz-user-select: none !important;
        -webkit-user-select: none !important;
        -ms-user-select: none !important;
    }

    .top-bar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 10px 15px;
    z-index: 2000;
}
.form-half {
    padding-top: 60px; 
}
.lang-dropdown {
    display: none;
}

@media (max-width: 600px) {
    .lang-menu {
        display: none;
    }
    .lang-dropdown {
        display: block;
        width: 100%;
    }
    .lang-dropdown select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
    }
}

</style>

<div class="login-container">
    <div class="login-wrapper" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
        
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
                    <h2 class="form-title">{{ __('lang.sign_in') }}</h2>
                    <p class="form-subtitle">{{ __('lang.enter_credentials_to_access') }}</p>
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

                <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
                    @csrf

                    <!-- Email Field -->
                    <div class="input-group-modern">
                        <label for="email" class="form-label-modern">{{ __('lang.email_address') }}</label>
                        <div class="input-container">
                            <input id="email" 
                                   type="email" 
                                   class="form-control-modern @error('email') is-invalid @enderror" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autocomplete="email" 
                                   autofocus 
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

                    <!-- Password Field -->
                    <div class="input-group-modern">
                        <label for="password" class="form-label-modern">{{ __('lang.password') }}</label>
                        <div class="input-container">
                            <input id="password" 
                                   type="password" 
                                   class="form-control-modern @error('password') is-invalid @enderror" 
                                   name="password" 
                                   required 
                                   autocomplete="current-password" 
                                   placeholder="{{ __('lang.enter_your_password') }}">
                            <i class="fas fa-lock input-icon"></i>
                            <i class="fas fa-eye password-toggle" onclick="togglePassword('password')"></i>
                        </div>
                        @error('password')
                            <div class="invalid-feedback-modern">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Form Options -->
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

                    <!-- Submit Button -->
                    <button type="submit" class="btn-login" id="loginBtn">
                        <span class="btn-text">{{ __('lang.login') }}</span>
                    </button>

                    <!-- Sign Up Link -->
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
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const toggle = field.parentElement.querySelector('.password-toggle');
    
    if (field.type === 'password') {
        field.type = 'text';
        toggle.classList.remove('fa-eye');
        toggle.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        toggle.classList.remove('fa-eye-slash');
        toggle.classList.add('fa-eye');
    }
}

// Enhanced form interactions
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.needs-validation');
    const loginBtn = document.getElementById('loginBtn');
    
    // Form submission
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            // Add loading state
            loginBtn.classList.add('loading');
            loginBtn.querySelector('.btn-text').textContent = '{{ __("lang.signing_in") }}';
        }
        
        form.classList.add('was-validated');
    });

    // Real-time validation feedback
    const inputs = document.querySelectorAll('.form-control-modern');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            if (this.checkValidity()) {
                this.classList.remove('is-invalid');
            }
        });

        // Enhanced focus effects
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'translateY(-2px)';
        });

        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'translateY(0)';
        });
    });

    // Enhanced button interactions
    loginBtn.addEventListener('click', function(e) {
        // Ripple effect
        const ripple = document.createElement('span');
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        
        ripple.style.cssText = `
            position: absolute;
            width: ${size}px;
            height: ${size}px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: scale(0);
            animation: ripple 0.6s linear;
            left: ${x}px;
            top: ${y}px;
            pointer-events: none;
        `;
        
        this.style.position = 'relative';
        this.style.overflow = 'hidden';
        this.appendChild(ripple);
        
        setTimeout(() => {
            ripple.remove();
        }, 600);
    });

    // Custom checkbox functionality
    const checkbox = document.getElementById('remember');
    if (checkbox) {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                this.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
            }
        });
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 'Enter') {
            form.submit();
        }
    });

    // Add entrance animations
    const formElements = document.querySelectorAll('.input-group-modern, .form-options, .btn-login, .signup-link');
    formElements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        element.style.transition = 'all 0.6s ease';
        
        setTimeout(() => {
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, 200 + (index * 100));
    });

    // Parallax effect for floating shapes
    document.addEventListener('mousemove', function(e) {
        const shapes = document.querySelectorAll('.shape');
        const mouseX = e.clientX / window.innerWidth;
        const mouseY = e.clientY / window.innerHeight;
        
        shapes.forEach((shape, index) => {
            const speed = (index + 1) * 0.5;
            const x = (mouseX - 0.5) * speed * 20;
            const y = (mouseY - 0.5) * speed * 20;
            
            shape.style.transform = `translate(${x}px, ${y}px)`;
        });
    });

    // Enhanced error handling
    const inputs = document.querySelectorAll('.form-control-modern');
    inputs.forEach(input => {
        input.addEventListener('invalid', function() {
            this.classList.add('is-invalid');
            
            // Add shake animation
            setTimeout(() => {
                this.focus();
            }, 100);
        });
    });

    // Add CSS for ripple animation if not exists
    if (!document.querySelector('#ripple-styles')) {
        const style = document.createElement('style');
        style.id = 'ripple-styles';
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }

    // Auto-focus email field on page load
    setTimeout(() => {
        const emailField = document.getElementById('email');
        if (emailField && !emailField.value) {
            emailField.focus();
        }
    }, 500);

    // Add smooth transitions for form validation states
    const style = document.createElement('style');
    style.textContent = `
        .form-control-modern {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .form-control-modern:focus {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .alert-modern {
            transition: all 0.3s ease;
        }
        
        .feature-badge {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        
        .design-half::before {
            transition: opacity 0.5s ease;
        }
        
        .form-half {
            transition: all 0.3s ease;
        }
        
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    `;
    document.head.appendChild(style);
});
</script>
@endsection