<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
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
        }
        
        /* Sidebar Styles */
        .sidebar {
            min-height: 100vh;
            width: var(--sidebar-width);
            position: fixed;
            left: 0;
            top: var(--navbar-height);
            background: var(--sidebar-bg);
            color: var(--sidebar-color);
            transition: all var(--transition-speed) ease;
            z-index: 1000;
            overflow-y: auto;
            padding-bottom: 20px;
            transform: translateX(-100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar.show {
            transform: translateX(0);
        }
        
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
        
        .sidebar-header h3 i {
            margin-right: 10px;
        }
        
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
        
        /* Main Content */
        @auth
            @if(Auth::user()->role === 'admin')
                .main-content {
                    margin-left: var(--sidebar-width);
                }
            @endif
        @endauth

        .main-content {
            transition: all var(--transition-speed) ease;
            min-height: calc(100vh - var(--navbar-height));
            padding-top: var(--navbar-height);
        }
        
        /* Overlay */
        .overlay {
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0,0,0,0.5);
            z-index: 999;
            display: none;
        }
        
        .overlay.show {
            display: block;
        }
        
        /* Mobile styles */
        @media (min-width: 992px) {
            .sidebar {
                transform: translateX(0);
            }
            
            .overlay {
                display: none !important;
            }
        }
        
        @media (max-width: 992px) {
            @auth
                @if(Auth::user()->role === 'admin')
                    .main-content {
                        margin-left: 0;
                    }
                @endif
            @endauth
        }
        
        /* Navbar fix */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1100;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        /* Better scrollbar for sidebar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 3px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container-fluid">
                @auth
                @if (Auth::user()->role === 'admin')
                <button class="btn btn-sm me-2" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                @endif
                @endauth
                
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                
                <div class="d-flex align-items-center">
                    @guest
                        @if (Route::has('login'))
                            <a class="nav-link me-3" href="{{ route('login') }}">{{ __('Login') }}</a>
                        @endif

                        @if (Route::has('register'))
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        @endif
                    @else
                        <span class="navbar-text me-3 d-none d-sm-inline">
                            {{ Auth::user()->name }}
                        </span>
                        <a class="btn btn-outline-danger btn-sm" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i>
                        </a>
                    @endguest
                </div>
            </div>
        </nav>

        @auth
        @if (Auth::user()->role === 'admin')
        <div class="overlay" id="overlay"></div>
        
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h3><i class="fas fa-cog"></i> Admin Panel</h3>
            </div>
            
            <div class="sidebar-menu">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                </a>
                
                <a href="{{ route('admin.domains') }}" class="sidebar-link {{ request()->routeIs('admin.domains*') ? 'active' : '' }}">
                    <i class="fas fa-globe"></i>
                    <span>Domains</span>
                </a>
                
                <a href="{{ route('admin.slides') }}" class="sidebar-link {{ request()->routeIs('admin.slides*') ? 'active' : '' }}">
                    <i class="fas fa-images"></i>
                    <span>Slides</span>
                </a>
                
                <a href="{{ route('admin.exams') }}" class="sidebar-link {{ request()->routeIs('admin.exams*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Exams</span>
                </a>
                
                <a href="{{ route('admin.quiz-attempts') }}" class="sidebar-link {{ request()->routeIs('admin.quiz-attempts*') ? 'active' : '' }}">
                    <i class="fas fa-question-circle"></i>
                    <span>Quiz Attempts</span>
                </a>
                
                <a href="{{ route('admin.test-attempts') }}" class="sidebar-link {{ request()->routeIs('admin.test-attempts*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-check"></i>
                    <span>Test Attempts</span>
                </a>
                
                <a href="{{ route('admin.notifications') }}" class="sidebar-link {{ request()->routeIs('admin.notifications*') ? 'active' : '' }}">
                    <i class="fas fa-bell"></i>
                    <span>Notifications</span>
                </a>
            </div>
            
            <div class="sidebar-footer">
                <a class="btn btn-outline-light w-100" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
        @endif
        @endauth

        <main class="main-content" id="mainContent">
            <div class="container-fluid p-4">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const overlay = document.getElementById('overlay');
            const isAdmin = @json(auth()->check() && (auth()->user()->role ?? null) === 'admin');
            
            if (isAdmin) {
                // Toggle sidebar
                function toggleSidebar() {
                    sidebar.classList.toggle('show');
                    overlay.classList.toggle('show');
                    
                    // Store preference in localStorage
                    if (window.innerWidth >= 992) {
                        const isOpen = sidebar.classList.contains('show');
                        localStorage.setItem('sidebarCollapsed', !isOpen);
                    }
                }
                
                // Close sidebar when clicking overlay
                /* overlay.addEventListener('click', toggleSidebar); */
                
                /* // Close sidebar when clicking a link on mobile
                document.querySelectorAll('.sidebar-link').forEach(link => {
                    link.addEventListener('click', function() {
                        if (window.innerWidth <= 992) {
                            toggleSidebar();
                        }
                    });
                }); */
                
                // Initialize
                if (sidebarToggle) {
                    sidebarToggle.addEventListener('click', toggleSidebar);
                    
                    // Check localStorage for sidebar state
                    if (window.innerWidth >= 992) {
                        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                        if (isCollapsed) {
                            sidebar.classList.remove('show');
                        } else {
                            sidebar.classList.add('show');
                        }
                    }
                    
                    // Handle window resize
                    window.addEventListener('resize', function() {
                        if (window.innerWidth >= 992) {
                            overlay.classList.remove('show');
                        } else if (!sidebar.classList.contains('show')) {
                            overlay.classList.remove('show');
                        }
                    });
                }
            }
        });
    </script>
</body>
</html>