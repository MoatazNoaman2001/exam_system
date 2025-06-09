<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    <style>
        .sidebar {
            min-height: 100vh;
            width: 250px;
            position: fixed;
            left: 0;
            top: 0;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            z-index: 1000;
            background: #fff;
            padding-top: 56px;
        }
        
        .main-content {
            margin-left: 250px;
            transition: all 0.3s ease;
            min-height: calc(100vh - 56px);
            padding-top: 56px;
        }
        
        .sidebar-link {
            border-radius: 5px;
            transition: all 0.2s;
            color: #333;
            text-decoration: none;
            display: block;
            padding: 0.5rem 1rem;
        }
        
        .sidebar-link:hover, .sidebar-link.active {
            background: #f8f9fa;
            color: #0d6efd;
        }
        
        .sidebar-link i {
            width: 24px;
            text-align: center;
        }
        
        .sidebar-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            border-top: 1px solid #eee;
            padding: 1rem;
        }
        
        /* Mobile styles */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .overlay {
                position: fixed;
                top: 0;
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
        }
        
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1100;
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
            <div class="">
                <div class="list-group list-group-flush">
                    <a href="#users" class="list-group-item list-group-item-action sidebar-link mb-1">
                        <i class="fas fa-users me-2"></i> Manage Users
                    </a>
                    <a href="#exams" class="list-group-item list-group-item-action sidebar-link mb-1">
                        <i class="fas fa-file-alt me-2"></i> Manage Exams
                    </a>
                    <a href="#quizzes" class="list-group-item list-group-item-action sidebar-link mb-1">
                        <i class="fas fa-question-circle me-2"></i> Manage Quizzes
                    </a>
                    <a href="#tests" class="list-group-item list-group-item-action sidebar-link mb-1">
                        <i class="fas fa-clipboard-check me-2"></i> Manage Tests
                    </a>
                    <a href="#missions" class="list-group-item list-group-item-action sidebar-link mb-1">
                        <i class="fas fa-tasks me-2"></i> Manage Missions
                    </a>
                    <a href="#domains" class="list-group-item list-group-item-action sidebar-link mb-1">
                        <i class="fas fa-globe me-2"></i> Manage Domains
                    </a>
                    <a href="#slides" class="list-group-item list-group-item-action sidebar-link mb-1">
                        <i class="fas fa-images me-2"></i> Manage Slides
                    </a>
                    <a href="#chapters" class="list-group-item list-group-item-action sidebar-link mb-1">
                        <i class="fas fa-book me-2"></i> Manage Chapters
                    </a>
                    <a href="#notifications" class="list-group-item list-group-item-action sidebar-link mb-3">
                        <i class="fas fa-bell me-2"></i> Manage Notifications
                    </a>
                    
                    <div class="sidebar-footer p-3">
                        <a class="btn btn-outline-danger w-100" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
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
            const mainContent = document.getElementById('mainContent');
            const isAdmin = @json(auth()->check() && auth()->user()->role === 'admin');
            
            // Check if we're on mobile
            function checkMobile() {
                return window.innerWidth <= 992;
            }
            
            
            // Toggle sidebar
            function toggleSidebar() {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            }
            
            // Close sidebar when clicking overlay
            overlay.addEventListener('click', toggleSidebar);
            
            // Close sidebar when clicking a link on mobile
            if (isAdmin) {
                document.querySelectorAll('.sidebar-link').forEach(link => {
                    link.addEventListener('click', function() {
                        if (checkMobile()) {
                            toggleSidebar();
                        }
                    });
                });
            }
            
            // Initialize
            if (isAdmin && sidebarToggle) {
                sidebarToggle.addEventListener('click', toggleSidebar);
                initSidebar();
                
                // Handle window resize
                window.addEventListener('resize', function() {
                    if (!checkMobile()) {
                        sidebar.classList.add('show');
                    } else if (!sidebar.classList.contains('show')) {
                        overlay.classList.remove('show');
                    }
                });
            }
        });
    </script>
</body>
</html>