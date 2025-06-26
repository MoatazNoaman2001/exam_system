<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{app()->getLocale() == 'ar'? 'rtl' : 'ltr'}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal&display=swap" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        :root {
            --sidebar-width: 280px;
            --sidebar-bg: #2c3e50;
            --sidebar-color: #ecf0f1;
            --sidebar-active-bg: #3498db;
            --sidebar-hover-bg: #34495e;
            --navbar-height: 56px;
            --transition-speed: 0.3s;
            --content-padding: 4rem;
        }
        body { overflow-x: hidden; }
        .container-fluid { padding-left: 0; padding-right: 0; }
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1030;
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }
        .overlay.show { display: block; }
        .sidebar {
            min-height: 100%;
            width: var(--sidebar-width);
            position: fixed;
            top: var(--navbar-height);
            background: var(--sidebar-bg);
            color: var(--sidebar-color);
            transition: all var(--transition-speed) ease;
            z-index: 1000;
            overflow-y: auto;
            padding-bottom: 20px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        @if(app()->getLocale() == 'ar')
            .sidebar {
                right: 0;
                left: auto;
                transform: translateX(100%);
                box-shadow: -2px 0 10px rgba(0,0,0,0.1);
            }
        @else
            .sidebar {
                left: 0;
                right: auto;
                transform: translateX(-100%);
            }
        @endif
        .sidebar.show { transform: translateX(0); }
        .sidebar-header {
            padding: 20px 15px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 10px;
        }
        .sidebar-header h3 {
            color: white;
            font-size: 1.2rem;
            margin: 0;
            display: flex;
            align-items: center;
        }
        .sidebar-header h3 i { margin-right: 10px; }
        .sidebar-link {
            color: var(--sidebar-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 12px 20px;
            margin: 0 10px;
            border-radius: 6px;
            transition: all 0.2s;
        }
        @auth
            @if (Auth::user()->role === 'admin')
                .main-content{
                    padding-top: var(--content-padding);
                }
            @endif
        @endauth
        .sidebar-link:hover {
            background: var(--sidebar-hover-bg);
            color: white;
        }
        .sidebar-link.active {
            background: var(--sidebar-active-bg);
            color: white;
            font-weight: 500;
        }
        .sidebar-link i {
            width: 24px;
            text-align: center;
            margin-right: 12px;
            font-size: 1.1rem;
        }
        .sidebar-footer {
            position: fixed;
            bottom: 0;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            padding: 15px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        @auth
            @if(Auth::user()->role != 'student')
                [dir="ltr"] .main-content {
                    margin-left: var(--sidebar-width);
                }
                [dir="rtl"] .main-content {
                    margin-right: var(--sidebar-width);
                }
                .main-content{
                    padding: calc(var(--content-padding) + 10px) 20px;
                }
            @endif
        @endauth
        .content-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: calc(var(--content-padding) * 1.5);
            margin-bottom: var(--content-padding);
        }
        .navbar-brand { display: flex; align-items: center; }
        [dir="rtl"] #navbarCollapse .d-flex.ms-auto {
            margin-right: auto !important;
            margin-left: 0 !important;
        }
        [dir="rtl"] .navbar .d-flex[style*="left: 12px"] {
            left: auto !important;
            right: 12px !important;
        }
        @media (min-width: 992px) {
            .sidebar {
                transform: translateX(0) !important;
            }
            .sidebar.show {
                transform: translateX(0) !important;
            }
        }
        @media (max-width: 992px) {
            :root { --content-padding: 1rem; }
            .sidebar {
                width: 100%;
                max-width: 280px;
            }
            .main-content {
                margin-left: 0 !important;
                margin-right: 0 !important;
                padding-top: var(--content-padding);
            }
            [dir="rtl"] .main-content { margin-right: 0 !important; }
            #navbarCollapse .d-flex.ms-auto {
                flex-direction: column;
                align-items: flex-start;
                width: 100%;
            }
            #navbarCollapse [dir="ltr"], #navbarCollapse [dir="rtl"] {
                position: static !important;
                margin-top: 1rem;
            }
            #navbarCollapse .text-truncate { max-width: 200px !important; }
        }
        @media (max-width: 768px) {
            :root { --content-padding: 0.75rem; }
            .sidebar-header h3 { font-size: 1rem; }
            .sidebar-link { font-size: 0.9rem; padding: 10px 15px; }
            .sidebar-link i { font-size: 1rem; margin-right: 8px; }
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm px-3">
            <div class="container-fluid">
                @auth
                    @if(Auth::user()->role === 'admin' && request()->is('admin/*'))
                        <button class="btn btn-sm me-2 d-lg-none" id="sidebarToggle">
                            <i class="fas fa-bars"></i>
                        </button>
                    @endif
                @endauth
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="d-flex ms-auto align-items-center">
                        @guest
                            @if (Route::has('login'))
                                <a class="nav-link me-3" href="{{ route('login') }}">{{ __('lang.login') }}</a>
                            @endif
                            @if (Route::has('register'))
                                <a class="nav-link" href="{{ route('register') }}">{{ __('lang.register') }}</a>
                            @endif
                        @else
                            <div class="d-flex align-items-center gap-3" dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}"
                                style="{{ app()->isLocale('ar') ? 'left: 12px' : 'right: 12px' }}">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-primary rounded-pill text-uppercase ms-2 ms-sm-0">
                                        {{ Auth::user()->role }}
                                    </span>
                                    <span class="fw-semibold text-truncate" style="max-width: 120px;">
                                        {{ Auth::user()->username }}
                                    </span>
                                </div>
                                <div class="vr d-none d-sm-inline-block" style="height: 24px;"></div>
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
        @auth
        @if (Auth::user()->role === 'admin')
            <div class="overlay" id="overlay"></div>
            <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h3><i class="fas fa-cog"></i> {{__('lang.admin_panel')}}</h3>
            </div>
            <div class="sidebar-menu">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>{{__('lang.dashboard')}}</span>
                </a>
                <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>{{__('lang.users')}}</span>
                </a>
                <a href="{{ route('admin.domains') }}" class="sidebar-link {{ request()->routeIs('admin.domains*') ? 'active' : '' }}">
                    <i class="fas fa-globe"></i>
                    <span>{{__('lang.domains')}}</span>
                </a>
                <a href="{{ route('admin.chapters') }}" class="sidebar-link {{ request()->routeIs('admin.chapter*') ? 'active' : '' }}">
                    <i class="fas fa-building"></i>
                    <span>{{__('lang.chapters')}}</span>
                </a>
                <a href="{{ route('admin.slides') }}" class="sidebar-link {{ request()->routeIs('admin.slides*') ? 'active' : '' }}">
                    <i class="fas fa-images"></i>
                    <span>{{__('lang.slides')}}</span>
                </a>
                <a href="{{ route('admin.exams') }}" class="sidebar-link {{ request()->routeIs('admin.exams*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>{{__('lang.exams')}}</span>
                </a>
                <a href="{{ route('admin.quiz-attempts') }}" class="sidebar-link {{ request()->routeIs('admin.quiz-attempts*') ? 'active' : '' }}">
                    <i class="fas fa-question-circle"></i>
                    <span>{{__('lang.quiz_attempts')}}</span>
                </a>
                <a href="{{ route('admin.test-attempts') }}" class="sidebar-link {{ request()->routeIs('admin.test-attempts*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-check"></i>
                    <span>{{__('lang.test_attempts')}}</span>
                </a>
                <a href="{{ route('admin.notifications') }}" class="sidebar-link {{ request()->routeIs('admin.notifications*') ? 'active' : '' }}">
                    <i class="fas fa-bell"></i>
                    <span>{{__('lang.notifications')}}</span>
                </a>
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
            </div>
        @endif
        @if (Auth::user()->role === 'student')
            <div class="sidebar" id="sidebar">
                <div class="sidebar-header">
                    <h3><i class="fas fa-cog"></i> {{ __('lang.student_panel') }}</h3>
                </div>
                <div class="sidebar-menu">
                    <a href="{{ route('student.home') }}" class="sidebar-link {{ request()->routeIs('student.home') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>{{ __('lang.home') }}</span>
                    </a>
                    <a href="{{ route('student.sections') }}" class="sidebar-link {{ request()->routeIs('student.sections*') ? 'active' : '' }}">
                        <i class="fas fa-book"></i>
                        <span>{{ __('lang.sections') }}</span>
                    </a>
                    <a href="{{ route('student.achievements') }}" class="sidebar-link {{ request()->routeIs('student.achievements*') ? 'active' : '' }}">
                        <i class="fas fa-trophy"></i>
                        <span>{{ __('lang.achievements') }}</span>
                    </a>
                    <a href="{{ route('student.account') }}" class="sidebar-link {{ request()->routeIs('student.account*') ? 'active' : '' }}">
                        <i class="fas fa-user"></i>
                        <span>{{ __('lang.my_account') }}</span>
                    </a>
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
            const sidebarToggle = document.getElementById('sidebarToggle');
            const overlay = document.getElementById('overlay');
            const isAdmin = @json(auth()->check() && (auth()->user()->role ?? null) === 'admin');

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

                document.querySelectorAll('.sidebar-link').forEach(link => {
                    link.addEventListener('click', function(e) {
                        if (this.target || this.href.startsWith('http') && !this.href.includes(window.location.host)) {
                            return;
                        }
                        e.preventDefault();
                        const url = this.href;
                        if (window.innerWidth < 992) {
                            toggleSidebar();
                        }
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
                            const newContent = doc.querySelector('.content-container').innerHTML;
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
        });
    </script>
</body>
</html>