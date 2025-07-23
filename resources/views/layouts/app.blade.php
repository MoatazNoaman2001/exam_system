<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- External Scripts and Fonts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;600;700&family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <!-- Vite Assets -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    <!-- Custom CSS File -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- App Configuration for JavaScript -->
    <script>
        window.appConfig = {
            isAdmin: @json(auth()->check() && (auth()->user()->role ?? null) === 'admin'),
            isStudent: @json(auth()->check() && (auth()->user()->role ?? null) === 'student'),
            isRTL: @json(app()->isLocale('ar')),
            routes: {
                notificationUnreadCount: '{{ route('notification.unread-count') }}',
                notificationMarkAsRead: '{{ route('notification.markAsRead') }}',
                studentNotifications: '{{ route('student.notifications.show') }}'
            }
        };
    </script>
</head>

<body>
    <div id="app">
        @if (Auth::user() !== null && Auth::user()->role !== 'student')
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm px-2">
                <div class="container-fluid">
                    @auth
                        @if (Auth::user()->role === 'admin' && request()->is('admin/*'))
                            <button class="btn btn-sm me-2 d-lg-none" id="sidebarToggle">
                                <i class="fas fa-bars"></i>
                            </button>
                        @endif
                        @if (Auth::user()->role === 'student')
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
                            <!-- Language Switcher for Navbar -->
                            <div class="navbar-language-switcher me-3">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('locale.set', 'ar') }}"
                                        class="btn btn-sm {{ app()->getLocale() == 'ar' ? 'btn-primary' : 'btn-outline-primary' }}"
                                        title="العربية">
                                        العربية
                                    </a>
                                    <a href="{{ route('locale.set', 'en') }}"
                                        class="btn btn-sm {{ app()->getLocale() == 'en' ? 'btn-primary' : 'btn-outline-primary' }}"
                                        title="English">
                                        EN
                                    </a>
                                </div>
                            </div>

                            @guest
                                @if (Route::has('login'))
                                    <a class="nav-link me-2" href="{{ route('login') }}">{{ __('lang.login') }}</a>
                                @endif
                                @if (Route::has('register'))
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('lang.register') }}</a>
                                @endif
                            @else
                                <div class="d-flex align-items-center gap-2"
                                    dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}"
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
                <div class="sidebar {{ app()->getLocale() == 'ar' ? 'rtl' : '' }}" id="sidebar">
                    <div class="sidebar-header mt-5">
                        <div class="sidebar-logo">
                            <i class="fas fa-cog"></i>
                        </div>
                        <h3 class="sidebar-title">{{ __('lang.admin_panel') }}</h3>
                    </div>
                    <div class="sidebar-menu">
                        <a href="{{ route('admin.dashboard') }}"
                            class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i>
                            <span class="link-text">{{ __('lang.dashboard') }}</span>
                        </a>
                        <a href="{{ route('admin.users') }}"
                            class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                            <i class="fas fa-users"></i>
                            <span class="link-text">{{ __('lang.users') }}</span>
                        </a>
                        <a href="{{ route('admin.domains') }}"
                            class="sidebar-link {{ request()->routeIs('admin.domains*') ? 'active' : '' }}">
                            <i class="fas fa-globe"></i>
                            <span class="link-text">{{ __('lang.domains') }}</span>
                        </a>
                        <a href="{{ route('admin.chapters') }}"
                            class="sidebar-link {{ request()->routeIs('admin.chapter*') ? 'active' : '' }}">
                            <i class="fas fa-building"></i>
                            <span class="link-text">{{ __('lang.chapters') }}</span>
                        </a>
                        <a href="{{ route('admin.slides') }}"
                            class="sidebar-link {{ request()->routeIs('admin.slides*') ? 'active' : '' }}">
                            <i class="fas fa-images"></i>
                            <span class="link-text">{{ __('lang.slides') }}</span>
                        </a>
                        <a href="{{ route('admin.exams') }}"
                            class="sidebar-link {{ request()->routeIs('admin.exams*') ? 'active' : '' }}">
                            <i class="fas fa-file-alt"></i>
                            <span class="link-text">{{ __('lang.exams') }}</span>
                        </a>
                        <a href="{{ route('admin.quiz-attempts') }}"
                            class="sidebar-link {{ request()->routeIs('admin.quiz-attempts*') ? 'active' : '' }}">
                            <i class="fas fa-question-circle"></i>
                            <span class="link-text">{{ __('lang.quiz_attempts') }}</span>
                        </a>
                        <a href="{{ route('admin.test-attempts') }}"
                            class="sidebar-link {{ request()->routeIs('admin.test-attempts*') ? 'active' : '' }}">
                            <i class="fas fa-clipboard-check"></i>
                            <span class="link-text">{{ __('lang.test_attempts') }}</span>
                        </a>
                        <a href="{{ route('admin.notifications') }}"
                            class="sidebar-link {{ request()->routeIs('admin.notifications*') ? 'active' : '' }}">
                            <i class="fas fa-bell"></i>
                            <span class="link-text">{{ __('lang.notifications') }}</span>
                        </a>
                    </div>
                    <!-- Sidebar Footer for Admin -->
                    <div class="sidebar-footer">
                        <!-- Language Switcher: Simple and always visible -->
                        <div class="sidebar-link" data-title="{{ __('lang.language') }}">
                            <i class="fas fa-language"></i>
                            <span class="link-text">{{ __('lang.language') }}</span>
                            <div class="d-flex flex-column gap-1 mt-2">
                                <a href="{{ route('locale.set', 'ar') }}" class="btn btn-sm {{ app()->getLocale() == 'ar' ? 'btn-primary' : 'btn-outline-light' }}" style="width: 100px;">
                                    العربية
                                </a>
                                <a href="{{ route('locale.set', 'en') }}" class="btn btn-sm {{ app()->getLocale() == 'en' ? 'btn-primary' : 'btn-outline-light' }}" style="width: 100px;">
                                    English
                                </a>
                            </div>
                        </div>
                        <a class="btn btn-outline-light w-100" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form-admin').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i> {{ __('lang.logout') }}
                        </a>
                        <form id="logout-form-admin" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            @endif

            @if (Auth::user()->role === 'student' && !array_key_exists(request()->route()->getName(), ["student.index" , "student.feature"]))
                <!-- Toggle Button -->
                <button class="sidebar-toggle {{ app()->getLocale() == 'ar' ? 'rtl' : '' }}" 
                        id="studentSidebarToggleBtn" title="Toggle Sidebar">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="overlay" id="studentOverlay"></div>
                <div class="sidebar student-sidebar {{ app()->getLocale() == 'ar' ? 'rtl' : '' }}" id="studentSidebar">
                    <div class="sidebar-header">
                        <div class="sidebar-logo">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <h3 class="sidebar-title">{{ __('lang.student_panel') }}</h3>
                        <p class="sidebar-subtitle">مرحباً {{ Auth::user()->username }}</p> 
                    </div>
                    <div class="sidebar-menu">
                        
                        <a href="{{ route('student.sections') }}"
                            class="sidebar-link {{ request()->routeIs('student.sections*') ? 'active' : '' }}"
                            data-title="{{ __('lang.sections') }}">
                            <i class="fas fa-book"></i>
                            <span class="link-text">{{ __('lang.sections') }}</span>
                        </a>
                        <a href="{{ route('student.achievements') }}"
                            class="sidebar-link {{ request()->routeIs('student.achievements*') ? 'active' : '' }}"
                            data-title="{{ __('lang.achievements') }}">
                            <i class="fas fa-trophy"></i>
                            <span class="link-text">{{ __('lang.achievements') }}</span>
                        </a>
                        <a href="{{ route('student.settings.index') }}"
                            class="sidebar-link {{ request()->routeIs('student.account*') ? 'active' : '' }}">
                            <i class="fas fa-user-cog"></i>
                            <span class="link-text">{{ __('lang.my_account') }}</span>
                        </a>
                        <a href="{{ route('student.notifications.show') }}"
                            class="sidebar-link {{ request()->routeIs('student.notifications*') ? 'active' : '' }}"
                            data-title="{{ __('lang.notifications') }}">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge {{ app()->getLocale() == 'ar' ? 'rtl' : '' }}" id="sidebarNotificationBadge">0</span>
                            <span class="link-text">{{ __('lang.notifications') }}</span>
                        </a>
                    
                        <div class="language-section border-top border-light border-opacity-25 mt-3 pt-3 mx-2 my-3">
                            <small class="text-white-50 ps-3 d-block mb-2">{{ __('Language') }}</small>
                            
                            <a href="{{ route('locale.set', 'ar') }}" 
                               class="sidebar-link {{ app()->getLocale() == 'ar' ? 'active' : '' }}">
                                <i class="fas fa-language me-3"></i>العربية
                            </a>

                            
                            <a href="{{ route('locale.set', 'en') }}" 
                               class="sidebar-link {{ app()->getLocale() == 'en' ? 'active' : '' }}">
                                <i class="fas fa-language me-3"></i>English
                            </a>
                        </div>
                        

                        <a href="{{ route('logout') }}" class="sidebar-link" data-title="{{ __('lang.logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form-student').submit();">
                            <i class="fas fa-sign-out-alt"></i>
                            <span class="link-text">{{ __('lang.logout') }}</span>
                        </a>
                        <form id="logout-form-student" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>

                <!-- Floating Mobile Bottom Nav -->
                <nav class="mobile-bottom-nav">
                    <a href="{{ route('student.sections') }}"
                        class="mobile-nav-icon {{ request()->routeIs('student.sections*') ? 'active' : '' }}"
                        title="{{ __('lang.sections') }}">
                        <span class="icon-bg"><i class="fas fa-book"></i></span>
                    </a>
                    <a href="{{ route('student.achievements') }}"
                        class="mobile-nav-icon {{ request()->routeIs('student.achievements*') ? 'active' : '' }}"
                        title="{{ __('lang.achievements') }}">
                        <span class="icon-bg"><i class="fas fa-trophy"></i></span>
                    </a>
                    <a href="{{ route('student.settings.index') }}"
                        class="mobile-nav-icon {{ request()->routeIs('student.account*') ? 'active' : '' }}"
                        title="{{ __('lang.my_account') }}">
                        <span class="icon-bg"><i class="fas fa-user-cog"></i></span>
                    </a>
                    
                    <!-- Mobile Language Switcher: Simple and always visible -->
                    <div id='mobileLanguageSwitcher' class="mobile-nav-icon" title="{{ __('lang.language') }}">
                        <span class="icon-bg"><i class="fas fa-language"></i></span>
                        <div id='mobileLanguageDropdown' class="d-flex flex-column gap-1 mt-2" style="position: fixed; bottom: 80px; left: 50%; transform: translateX(-50%); background: white; padding: 1rem;  border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.3); z-index: 3000;">
                            <a href="{{ route('locale.set', 'ar') }}" class="btn btn-sm {{ app()->getLocale() == 'ar' ? 'btn-primary' : 'btn-outline-primary' }}" style="width: 100px;">
                                العربية
                            </a>
                            <a href="{{ route('locale.set', 'en') }}" class="btn btn-sm {{ app()->getLocale() == 'en' ? 'btn-primary' : 'btn-outline-primary' }}" style="width: 100px;">
                                English
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('student.notifications.show') }}"
                        class="mobile-nav-icon {{ request()->routeIs('student.notifications*') ? 'active' : '' }}"
                        title="{{ __('lang.notifications') }}">
                        <span class="icon-bg">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge {{ app()->getLocale() == 'ar' ? 'rtl' : '' }}" id="mobileNotificationBadge">0</span>
                        </span>
                    </a>

                    <a href="{{ route('logout') }}" class="mobile-nav-icon" title="{{ __('lang.logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form-student-mobile').submit();">
                        <span class="icon-bg"><i class="fas fa-sign-out-alt"></i></span>
                    </a>
                    <form id="logout-form-student-mobile" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </nav>
            @endif
        @endauth

        <main class="main-content {{ Auth::check() && Auth::user()->role === 'student' ? 'student-layout' : '' }}" id="mainContent">
            @yield('content')
        </main>
    </div>

    <!-- Custom JavaScript File -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>