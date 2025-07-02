<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{app()->getLocale() == 'ar'? 'rtl' : 'ltr'}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;600;700&family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        :root {
            /* Enhanced Color Scheme */
            --sidebar-width: 280px;
            --sidebar-width-collapsed: 70px;
            --navbar-height: 64px;
            --transition-speed: 0.3s;
            --content-padding: 2rem;
            
            /* Modern Color Palette */
            --primary-blue: #3b82f6;
            --primary-blue-light: #60a5fa;
            --primary-blue-dark: #2563eb;
            --success-green: #10b981;
            --success-green-light: #34d399;
            --warning-amber: #f59e0b;
            --warning-amber-light: #fbbf24;
            --sidebar-bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --sidebar-bg-alt: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --sidebar-color: #ffffff;
            --sidebar-active-bg: rgba(255, 255, 255, 0.2);
            --sidebar-hover-bg: rgba(255, 255, 255, 0.1);
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
        }

        body { 
            overflow-x: hidden;
            font-family: 'Tajawal', 'Cairo', sans-serif;
            background-color: var(--gray-50);
            margin: 0;
        }
        
        .container-fluid { 
            padding-left: 0; 
            padding-right: 0; 
        }
        
        /* Enhanced Navbar */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: var(--navbar-height);
            z-index: 1030;
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--gray-200);
            padding: 0 1rem;
        }
        
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 999;
            display: none;
            backdrop-filter: blur(2px);
        }
        
        .overlay.show { 
            display: block; 
        }
        
        /* Modern Sidebar Design */
        .sidebar {
            min-height: 100vh;
            width: var(--sidebar-width);
            position: fixed;
            top: 0;
            background: var(--sidebar-bg);
            color: var(--sidebar-color);
            transition: all var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            overflow-y: auto;
            overflow-x: hidden;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
        }

        /* Sidebar positioning for RTL/LTR */
        @if(app()->getLocale() == 'ar')
            .sidebar {
                right: 0;
                left: auto;
                transform: translateX(100%);
                box-shadow: -4px 0 15px rgba(0, 0, 0, 0.1);
            }
        @else
            .sidebar {
                left: 0;
                right: auto;
                transform: translateX(-100%);
            }
        @endif
        
        .sidebar.show { 
            transform: translateX(0); 
        }
        
        .sidebar.collapsed { 
            width: var(--sidebar-width-collapsed); 
        }
        
        /* Sidebar Header */
        .sidebar-header {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 0.5rem;
            text-align: center;
            position: relative;
        }
        
        .sidebar-logo {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.5rem;
            font-size: 1.4rem;
            transition: all var(--transition-speed);
        }
        
        .sidebar-title {
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
            font-family: 'Cairo', 'Tajawal', sans-serif;
            transition: all var(--transition-speed);
        }
        
        .sidebar-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.85rem;
            font-weight: 400;
            margin: 0.25rem 0 0 0;
            transition: all var(--transition-speed);
        }
        
        /* Navigation Menu */
        .sidebar-menu {
            padding: 0 0.75rem;
        }
        
        .sidebar-link {
            color: var(--sidebar-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            margin: 0.2rem 0;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            font-weight: 500;
            font-size: 0.95rem;
        }
        
        .sidebar-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transition: width 0.3s ease;
            border-radius: 8px;
        }
        
        .sidebar-link:hover::before {
            width: 100%;
        }
        
        .sidebar-link:hover {
            background: var(--sidebar-hover-bg);
            color: white;
            transform: translateX(3px);
        }
        
        .sidebar-link.active {
            background: var(--sidebar-active-bg);
            color: white;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-link.active::before {
            width: 100%;
        }
        
        .sidebar-link i {
            width: 24px;
            text-align: center;
            margin-right: 0.75rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .sidebar-link:hover i {
            transform: scale(1.05);
        }
        
        .link-text {
            transition: all var(--transition-speed);
            white-space: nowrap;
        }
        
        /* Collapsed state */
        .sidebar.collapsed .sidebar-title,
        .sidebar.collapsed .sidebar-subtitle,
        .sidebar.collapsed .link-text {
            opacity: 0;
            transform: translateX(20px);
            display: none;
        }
        
        .sidebar.collapsed .sidebar-link {
            justify-content: center;
            padding: 0.75rem;
        }
        
        .sidebar.collapsed .sidebar-link i {
            margin-right: 0;
        }
        
        /* Toggle Button */
        .sidebar-toggle {
            position: absolute;
            top: 1rem;
            right: -14px;
            transform: translateY(0);
            width: 28px;
            height: 28px;
            background: white;
            border: 2px solid var(--primary-blue);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--primary-blue);
            font-size: 0.7rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-toggle:hover {
            background: var(--primary-blue);
            color: white;
            transform: scale(1.1);
        }
        
        /* Student Sidebar Specific Styles */
        .student-sidebar {
            background: var(--sidebar-bg-alt);
        }
        
        .student-sidebar .sidebar-logo {
            background: rgba(255, 255, 255, 0.25);
            color: white;
        }
        
        /* Sidebar Footer */
        .sidebar-footer {
            position: sticky;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-footer .btn {
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 8px;
            padding: 0.5rem;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        
        .sidebar-footer .btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
        }
        
        /* Main Content Adjustments */
        .main-content {
            padding-top: var(--navbar-height);
            transition: all var(--transition-speed) ease;
            max-height: 100%;
            padding: 10px;
            
        }
        
        @auth
            @if (Auth::user()->role === 'admin')
                .main-content {
                    padding-top: calc(var(--navbar-height) + 2.0rem);
                }
            @endif
        @endauth
        
        /* Content margins for sidebar */
        @auth
            @if(Auth::user()->role != 'student')
                [dir="ltr"] .main-content {
                    margin-left: var(--sidebar-width);
                }
                [dir="rtl"] .main-content {
                    margin-right: var(--sidebar-width);
                }
            @endif
            
            @if(Auth::user()->role === 'student')
                [dir="ltr"] .main-content.sidebar-open {
                    margin-left: var(--sidebar-width);
                }
                [dir="rtl"] .main-content.sidebar-open {
                    margin-right: var(--sidebar-width);
                }
                [dir="ltr"] .main-content.sidebar-collapsed {
                    margin-left: var(--sidebar-width-collapsed);
                }
                [dir="rtl"] .main-content.sidebar-collapsed {
                    margin-right: var(--sidebar-width-collapsed);
                }
            @endif
        @endauth
        
        .content-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            margin: 1rem;
            min-height: calc(100vh - var(--navbar-height) - 2rem);
        }
        
        /* Navbar adjustments */
        .navbar-brand { 
            display: flex; 
            align-items: center;
            font-weight: 600;
            color: var(--primary-blue) !important;
            font-size: 1.1rem;
        }
        
        [dir="rtl"] #navbarCollapse .d-flex.ms-auto {
            margin-right: auto !important;
            margin-left: 0 !important;
        }
        
        [dir="rtl"] .navbar .d-flex[style*="left: 12px"] {
            left: auto !important;
            right: 12px !important;
        }
        
        /* Responsive Design */
        @media (min-width: 992px) {
            .sidebar {
                transform: translateX(0) !important;
            }
            .sidebar.show {
                transform: translateX(0) !important;
            }
            
            @auth
                @if(Auth::user()->role === 'student')
                    .sidebar {
                        transform: translateX(0) !important;
                    }
                @endif
            @endauth
        }
        
        @media (max-width: 991.98px) {
            :root { 
                --content-padding: 1rem; 
            }
            
            .sidebar {
                width: 100%;
                max-width: 280px;
                transform: translateX(-100%) !important;
            }
            
            .sidebar.show {
                transform: translateX(0) !important;
            }
            
            .main-content {
                margin-left: 0 !important;
                margin-right: 0 !important;
                padding-top: var(--navbar-height);
            }
            
            [dir="rtl"] .main-content { 
                margin-right: 0 !important; 
            }
            
            .content-container {
                margin: 0.75rem;
                padding: 1rem;
                border-radius: 8px;
            }
            
            #navbarCollapse .d-flex.ms-auto {
                flex-direction: column;
                align-items: flex-start;
                width: 100%;
            }
            
            #navbarCollapse [dir="ltr"], #navbarCollapse [dir="rtl"] {
                position: static !important;
                margin-top: 0.5rem;
            }
            
            #navbarCollapse .text-truncate { 
                max-width: 180px !important; 
            }
            
            .sidebar-toggle {
                display: none;
            }
            
            .student-sidebar {
                transform: translateX(-100%) !important;
            }
            
            .student-sidebar.show {
                transform: translateX(0) !important;
            }
        }
        
        @media (max-width: 767.98px) {
            :root { 
                --content-padding: 0.5rem; 
            }
            
            .sidebar-header { 
                padding: 1rem 0.75rem; 
            }
            
            .sidebar-title { 
                font-size: 1rem; 
            }
            
            .sidebar-link { 
                font-size: 0.85rem; 
                padding: 0.6rem 0.75rem; 
            }
            
            .sidebar-link i { 
                font-size: 0.9rem; 
                margin-right: 0.5rem; 
            }
            
            .content-container {
                margin: 0.5rem;
                padding: 0.75rem;
                border-radius: 6px;
            }
        }
        
        /* Loading Animation */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-15px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .sidebar-link {
            animation: slideIn 0.3s ease-out;
        }
        
        .sidebar-link:nth-child(1) { animation-delay: 0.1s; }
        .sidebar-link:nth-child(2) { animation-delay: 0.2s; }
        .sidebar-link:nth-child(3) { animation-delay: 0.3s; }
        .sidebar-link:nth-child(4) { animation-delay: 0.4s; }
        .sidebar-link:nth-child(5) { animation-delay: 0.5s; }
        .sidebar-link:nth-child(6) { animation-delay: 0.6s; }
        
        /* Custom Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 5px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }
        
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body>
    <div id="app">
        @if (Auth::user() !== null && Auth::user()->role !== "student")
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm px-2">
            <div class="container-fluid">
                @auth
                    @if(Auth::user()->role === 'admin' && request()->is('admin/*'))
                        <button class="btn btn-sm me-2 d-lg-none" id="sidebarToggle">
                            <i class="fas fa-bars"></i>
                        </button>
                    @endif
                    @if(Auth::user()->role === 'student')
                        <button class="btn btn-sm me-2 d-lg-none" id="studentSidebarToggle">
                            <i class="fas fa-bars text-primary"></i>
                        </button>
                    @endif
                @endauth
                <a class="navbar-brand" href="{{ url('/') }}">
                    <i class="fas fa-graduation-cap me-1"></i>
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="d-flex ms-auto align-items-center">
                        @guest
                            @if (Route::has('login'))
                                <a class="nav-link me-2" href="{{ route('login') }}">{{ __('lang.login') }}</a>
                            @endif
                            @if (Route::has('register'))
                                <a class="nav-link" href="{{ route('register') }}">{{ __('lang.register') }}</a>
                            @endif
                        @else
                            <div class="d-flex align-items-center gap-2" dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}"
                                style="{{ app()->isLocale('ar') ? 'left: 10px' : 'right: 10px' }}">
                                <div class="d-flex align-items-center gap-1">
                                    <span class="badge bg-primary rounded-pill text-uppercase">
                                        {{ Auth::user()->role }}
                                    </span>
                                    <span class="fw-semibold text-truncate" style="max-width: 100px;">
                                        {{ Auth::user()->username }}
                                    </span>
                                </div>
                                <div class="vr d-none d-sm-inline-block" style="height: 20px;"></div>
                                <a class="nav-link p-0" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <span class="d-flex align-items-center gap-1 text-danger">
                                        <i class="fas fa-sign-out-alt fa-sm"></i>
                                        <span class="d-none d-sm-inline">{{ __('lang.logout') }}</span>
                                    </span>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>
        @endif

        
        @auth
        @if (Auth::user()->role === 'admin')
            <div class="overlay" id="overlay"></div>
            <div class="sidebar" id="sidebar">
                <div class="sidebar-header mt-5">
                    <div class="sidebar-logo">
                        <i class="fas fa-cog"></i>
                    </div>
                    <h3 class="sidebar-title">{{__('lang.admin_panel')}}</h3>
                </div>
                <div class="sidebar-menu">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="link-text">{{__('lang.dashboard')}}</span>
                    </a>
                    <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span class="link-text">{{__('lang.users')}}</span>
                    </a>
                    <a href="{{ route('admin.domains') }}" class="sidebar-link {{ request()->routeIs('admin.domains*') ? 'active' : '' }}">
                        <i class="fas fa-globe"></i>
                        <span class="link-text">{{__('lang.domains')}}</span>
                    </a>
                    <a href="{{ route('admin.chapters') }}" class="sidebar-link {{ request()->routeIs('admin.chapter*') ? 'active' : '' }}">
                        <i class="fas fa-building"></i>
                        <span class="link-text">{{__('lang.chapters')}}</span>
                    </a>
                    <a href="{{ route('admin.slides') }}" class="sidebar-link {{ request()->routeIs('admin.slides*') ? 'active' : '' }}">
                        <i class="fas fa-images"></i>
                        <span class="link-text">{{__('lang.slides')}}</span>
                    </a>
                    <a href="{{ route('admin.exams') }}" class="sidebar-link {{ request()->routeIs('admin.exams*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i>
                        <span class="link-text">{{__('lang.exams')}}</span>
                    </a>
                    <a href="{{ route('admin.quiz-attempts') }}" class="sidebar-link {{ request()->routeIs('admin.quiz-attempts*') ? 'active' : '' }}">
                        <i class="fas fa-question-circle"></i>
                        <span class="link-text">{{__('lang.quiz_attempts')}}</span>
                    </a>
                    <a href="{{ route('admin.test-attempts') }}" class="sidebar-link {{ request()->routeIs('admin.test-attempts*') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-check"></i>
                        <span class="link-text">{{__('lang.test_attempts')}}</span>
                    </a>
                    <a href="{{ route('admin.notifications') }}" class="sidebar-link {{ request()->routeIs('admin.notifications*') ? 'active' : '' }}">
                        <i class="fas fa-bell"></i>
                        <span class="link-text">{{__('lang.notifications')}}</span>
                    </a>
                </div>
                <div class="sidebar-footer">
                    <a class="btn btn-outline-light w-100" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i> {{ __('lang.logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        @endif
        
        @if (Auth::user()->role === 'student')
            <div class="overlay" id="studentOverlay"></div>
            <div class="sidebar student-sidebar" id="studentSidebar">

                <div class="sidebar-header">
                    <div class="sidebar-logo">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h3 class="sidebar-title">{{__('lang.student_panel')}}</h3>
                    <p class="sidebar-subtitle">مرحباً {{ Auth::user()->username }}</p>
                </div>
                
                <div class="sidebar-menu">
                    <a href="{{ route('student.sections') }}" class="sidebar-link {{ request()->routeIs('student.sections*') ? 'active' : '' }}">
                        <i class="fas fa-book"></i>
                        <span class="link-text">{{ __('lang.sections') }}</span>
                    </a>
                    <a href="{{ route('student.achievements') }}" class="sidebar-link {{ request()->routeIs('student.achievements*') ? 'active' : '' }}">
                        <i class="fas fa-trophy"></i>
                        <span class="link-text">{{ __('lang.achievements') }}</span>
                    </a>
                    <a href="{{ route('student.account') }}" class="sidebar-link {{ request()->routeIs('student.account*') ? 'active' : '' }}">
                        <i class="fas fa-user-cog"></i>
                        <span class="link-text">{{ __('lang.my_account') }}</span>
                    </a>
                    <a href="{{ route('logout') }}" class="sidebar-link" onclick="event.preventDefault(); document.getElementById('logout-form-student').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="link-text">{{ __('lang.logout') }}</span>
                    </a>
                    <form id="logout-form-student" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        @endif
        @endauth
        
        <main class="main-content" id="mainContent">
            @yield('content')
        </main>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const studentSidebar = document.getElementById('studentSidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const studentSidebarToggle = document.getElementById('studentSidebarToggle');
            const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
            const overlay = document.getElementById('overlay');
            const studentOverlay = document.getElementById('studentOverlay');
            const mainContent = document.getElementById('mainContent');
            
            const isAdmin = @json(auth()->check() && (auth()->user()->role ?? null) === 'admin');
            const isStudent = @json(auth()->check() && (auth()->user()->role ?? null) === 'student');
            const isRTL = @json(app()->isLocale('ar'));

            // Admin sidebar functionality
            if (isAdmin && sidebar && overlay) {
                function toggleSidebar() {
                    if (window.innerWidth < 992) {
                        sidebar.classList.toggle('show');
                        overlay.classList.toggle('show');
                    }
                }

                if (sidebarToggle) {
                    sidebarToggle.addEventListener('click', toggleSidebar);
                }
                overlay.addEventListener('click', toggleSidebar);

                if (window.innerWidth >= 992) {
                    sidebar.classList.add('show');
                }

                window.addEventListener('resize', function() {
                    if (window.innerWidth >= 992) {
                        sidebar.classList.add('show');
                        overlay.classList.remove('show');
                    } else {
                        sidebar.classList.remove('show');
                        overlay.classList.remove('show');
                    }
                });
            }

            // Student sidebar functionality
            if (isStudent && studentSidebar && studentOverlay) {
                let sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                
                function updateToggleIcon(collapsed) {
                    const icon = sidebarToggleBtn?.querySelector('i');
                    if (icon) {
                        icon.className = isRTL 
                            ? (collapsed ? 'fas fa-chevron-right' : 'fas fa-chevron-left')
                            : (collapsed ? 'fas fa-chevron-right' : 'fas fa-chevron-left');
                    }
                }

                function toggleStudentSidebar() {
                    if (window.innerWidth < 992) {
                        studentSidebar.classList.toggle('show');
                        studentOverlay.classList.toggle('show');
                        studentSidebar.classList.remove('collapsed');
                        mainContent.classList.remove('sidebar-open', 'sidebar-collapsed');
                    } else {
                        sidebarCollapsed = !sidebarCollapsed;
                        studentSidebar.classList.toggle('collapsed', sidebarCollapsed);
                        mainContent.classList.toggle('sidebar-collapsed', sidebarCollapsed);
                        mainContent.classList.toggle('sidebar-open', !sidebarCollapsed);
                        updateToggleIcon(sidebarCollapsed);
                        localStorage.setItem('sidebarCollapsed', sidebarCollapsed);
                    }
                }

                // Initialize sidebar state
                if (window.innerWidth >= 992) {
                    studentSidebar.classList.add('show');
                    if (sidebarCollapsed) {
                        studentSidebar.classList.add('collapsed');
                        mainContent.classList.add('sidebar-collapsed');
                    } else {
                        mainContent.classList.add('sidebar-open');
                    }
                    updateToggleIcon(sidebarCollapsed);
                } else {
                    studentSidebar.classList.remove('show', 'collapsed');
                    mainContent.classList.remove('sidebar-open', 'sidebar-collapsed');
                }

                if (studentSidebarToggle) {
                    studentSidebarToggle.addEventListener('click', toggleStudentSidebar);
                }

                if (sidebarToggleBtn) {
                    sidebarToggleBtn.addEventListener('click', toggleStudentSidebar);
                }

                if (studentOverlay) {
                    studentOverlay.addEventListener('click', function() {
                        if (window.innerWidth < 992) {
                            studentSidebar.classList.remove('show');
                            studentOverlay.classList.remove('show');
                        }
                    });
                }

                // Handle window resize
                window.addEventListener('resize', function() {
                    if (window.innerWidth >= 992) {
                        studentSidebar.classList.add('show');
                        if (sidebarCollapsed) {
                            studentSidebar.classList.add('collapsed');
                            mainContent.classList.add('sidebar-collapsed');
                            mainContent.classList.remove('sidebar-open');
                        } else {
                            studentSidebar.classList.remove('collapsed');
                            mainContent.classList.add('sidebar-open');
                            mainContent.classList.remove('sidebar-collapsed');
                        }
                        updateToggleIcon(sidebarCollapsed);
                        studentOverlay.classList.remove('show');
                    } else {
                        studentSidebar.classList.remove('show', 'collapsed');
                        mainContent.classList.remove('sidebar-open', 'sidebar-collapsed');
                        studentOverlay.classList.remove('show');
                    }
                });

                // Link navigation with loading states
                document.querySelectorAll('.student-sidebar .sidebar-link').forEach(link => {
                    link.addEventListener('click', function(e) {
                        if (this.href.includes('#') || this.target) return;
                        
                        e.preventDefault();
                        const url = this.href;
                        
                        const icon = this.querySelector('i');
                        const originalIcon = icon.className;
                        icon.className = 'fas fa-spinner fa-spin';
                        this.style.opacity = '0.7';
                        
                        if (window.innerWidth < 992) {
                            studentSidebar.classList.remove('show');
                            studentOverlay.classList.remove('show');
                        }
                        
                        document.querySelectorAll('.student-sidebar .sidebar-link').forEach(item => {
                            item.classList.remove('active');
                        });
                        this.classList.add('active');
                        
                        setTimeout(() => {
                            window.location.href = url;
                        }, 300);
                    });
                });
            }

            // Smooth page transitions
            function addPageTransition() {
                if (mainContent) {
                    mainContent.style.opacity = '0';
                    mainContent.style.transform = 'translateY(10px)';
                    setTimeout(() => {
                        mainContent.style.transition = 'all 0.3s ease';
                        mainContent.style.opacity = '1';
                        mainContent.style.transform = 'translateY(0)';
                    }, 100);
                }
            }

            addPageTransition();

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
                    e.preventDefault();
                    if (isStudent) {
                        toggleStudentSidebar();
                    } else if (isAdmin && window.innerWidth < 992) {
                        toggleSidebar();
                    }
                }
            });

            // Tooltips for collapsed sidebar
            if (isStudent) {
                function updateTooltips() {
                    document.querySelectorAll('.student-sidebar .sidebar-link').forEach(link => {
                        const text = link.querySelector('.link-text')?.textContent;
                        if (text && studentSidebar.classList.contains('collapsed')) {
                            link.setAttribute('title', text);
                        } else {
                            link.removeAttribute('title');
                        }
                    });
                }

                const sidebarObserver = new MutationObserver(updateTooltips);
                if (studentSidebar) {
                    sidebarObserver.observe(studentSidebar, {
                        attributes: true,
                        attributeFilter: ['class']
                    });
                    updateTooltips();
                }
            }

            // Admin AJAX navigation
            if (isAdmin && sidebar && overlay) {
                document.querySelectorAll('.sidebar-link').forEach(link => {
                    link.addEventListener('click', function(e) {
                        if (this.target || this.href.startsWith('http') && !this.href.includes(window.location.host)) {
                            return;
                        }
                        e.preventDefault();
                        const url = this.href;
                        if (window.innerWidth < 992) {
                            sidebar.classList.remove('show');
                            overlay.classList.remove('show');
                        }
                        
                        const icon = this.querySelector('i');
                        const originalIcon = icon.className;
                        icon.className = 'fas fa-spinner fa-spin';
                        
                        fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'text/html'
                            }
                        })
                        .then(response => response.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const newContent = doc.querySelector('.content-container')?.innerHTML;
                            if (newContent) {
                                document.querySelector('.content-container').innerHTML = newContent;
                                window.history.pushState({}, '', url);
                                document.querySelectorAll('.sidebar-link').forEach(item => {
                                    item.classList.remove('active');
                                });
                                this.classList.add('active');
                                const title = doc.querySelector('title');
                                if (title) {
                                    document.title = title.textContent;
                                }
                                addPageTransition();
                            } else {
                                window.location.href = url;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            window.location.href = url;
                        });
                    });
                });

                window.addEventListener('popstate', function() {
                    window.location.reload();
                });
            }
        });
    </script>
</body>
</html>