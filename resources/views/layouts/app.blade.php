<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{app()->getLocale() == 'ar'? 'rtl' : 'ltr'}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
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
        
        @if(app()->getLocale() == 'ar')
        .sidebar-link:hover {
            transform: translateX(-3px);
        }
        @endif
        
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
        
        @if(app()->getLocale() == 'ar')
        .sidebar-link i {
            margin-right: 0;
            margin-left: 0.75rem;
        }
        @endif
        
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
        
        @if(app()->getLocale() == 'ar')
        .sidebar.collapsed .sidebar-title,
        .sidebar.collapsed .sidebar-subtitle,
        .sidebar.collapsed .link-text {
            transform: translateX(-20px);
        }
        @endif
        
        .sidebar.collapsed .sidebar-link {
            justify-content: center;
            padding: 0.75rem;
        }
        
        .sidebar.collapsed .sidebar-link i {
            margin-right: 0;
        }
        
        @if(app()->getLocale() == 'ar')
        .sidebar.collapsed .sidebar-link i {
            margin-left: 0;
        }
        @endif
        
        /* Toggle Button */
        .sidebar-toggle {
            position: fixed;
            top: 4.3rem;
            left: 260px;
            transform: translateY(4px);
            width: 36px;
            height: 36px;
            background: white;
            border: 2px solid var(--primary-blue);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--primary-blue);
            font-size: 0.8rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1002;
        }
        
        @if(app()->getLocale() == 'ar')
        .sidebar-toggle {
            right: auto;
            left: -14px;
        }
        @endif
        
        .sidebar-toggle:hover {
            background: var(--primary-blue);
            color: white;
            transform: scale(1.1);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        }
        
        /* Student sidebar toggle specific styles */
        .student-sidebar .sidebar-toggle {
            background: rgba(255, 255, 255, 0.9);
            border-color: rgba(255, 255, 255, 0.3);
            color: #667eea;
        }
        
        .student-sidebar .sidebar-toggle:hover {
            background: white;
            color: #667eea;
        }
        
        /* Student Sidebar Specific Styles - Breadcrumbs Style */
        .student-sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            width: var(--sidebar-width-collapsed) !important;
            transition: width var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
            /* border-radius: 0 15px 15px 0; */
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }
        
        @if(app()->getLocale() == 'ar')
        .student-sidebar {
            border-radius: 15px 0 0 15px;
        }
        @endif
        
        .student-sidebar.expanded {
            width: var(--sidebar-width) !important;
        }
        
        .student-sidebar .sidebar-logo {
            background: rgba(255, 255, 255, 0.25);
            color: white;
        }
        
        /* Student sidebar collapsed state */
        .student-sidebar .sidebar-title,
        .student-sidebar .sidebar-subtitle,
        .student-sidebar .link-text {
            opacity: 0;
            transform: translateX(20px);
            display: none;
            transition: all var(--transition-speed) ease;
        }
        
        @if(app()->getLocale() == 'ar')
        .student-sidebar .sidebar-title,
        .student-sidebar .sidebar-subtitle,
        .student-sidebar .link-text {
            transform: translateX(-20px);
        }
        @endif
        
        .student-sidebar.expanded .sidebar-title,
        .student-sidebar.expanded .sidebar-subtitle,
        .student-sidebar.expanded .link-text {
            opacity: 1;
            transform: translateX(0);
            display: block;
        }
        
        .student-sidebar .sidebar-link {
            justify-content: center;
            padding: 0.75rem;
            transition: all var(--transition-speed) ease;
            border-radius: 8px;
            margin: 0.25rem 0.5rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .student-sidebar .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        .student-sidebar .sidebar-link.active {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }
        
        .student-sidebar.expanded .sidebar-link {
            justify-content: flex-start;
            padding: 0.75rem 1rem;
        }
        
        @if(app()->getLocale() == 'ar')
        .student-sidebar.expanded .sidebar-link {
            justify-content: flex-end;
        }
        @endif
        
        .student-sidebar .sidebar-link i {
            margin: 0;
            transition: all var(--transition-speed) ease;
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }
        
        .student-sidebar.expanded .sidebar-link i {
            margin-right: 0.75rem;
        }
        
        @if(app()->getLocale() == 'ar')
        .student-sidebar.expanded .sidebar-link i {
            margin-right: 0;
            margin-left: 0.75rem;
        }
        @endif
        
        /* Breadcrumb-style spacing */
        .student-sidebar .sidebar-menu {
            padding: 0.5rem;
        }
        
        .student-sidebar .sidebar-header {
            padding: 1rem 0.5rem;
            margin-bottom: 0.25rem;
        }
        
        /* Student sidebar hover tooltip */
        .student-sidebar .sidebar-link {
            position: relative;
        }
        
        .student-sidebar .sidebar-link::after {
            content: attr(data-title);
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            font-size: 0.8rem;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1001;
            margin-left: 10px;
        }
        
        @if(app()->getLocale() == 'ar')
        .student-sidebar .sidebar-link::after {
            left: auto;
            right: 100%;
            margin-left: 0;
            margin-right: 10px;
        }
        @endif
        
        .student-sidebar .sidebar-link:hover::after {
            opacity: 1;
            visibility: visible;
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
                [dir="ltr"] .main-content {
                    margin-left: var(--sidebar-width-collapsed);
                    transition: margin-left var(--transition-speed) ease;
                }
                [dir="rtl"] .main-content {
                    margin-right: var(--sidebar-width-collapsed);
                    transition: margin-right var(--transition-speed) ease;
                }
                [dir="ltr"] .main-content.sidebar-expanded {
                    margin-left: var(--sidebar-width);
                }
                [dir="rtl"] .main-content.sidebar-expanded {
                    margin-right: var(--sidebar-width);
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
            
            @if(app()->getLocale() == 'ar')
                .sidebar {
                    transform: translateX(100%) !important;
                }
            @endif
            
            .sidebar.show {
                transform: translateX(0) !important;
            }
            
            .main-content {
                margin-left: 0 !important;
                margin-right: 0 !important;
                padding-top: var(--navbar-height);
                padding-bottom: 80px; /* Space for bottom navigation */
            }
            
            [dir="rtl"] .main-content { 
                margin-right: 0 !important; 
            }
            
            .content-container {
                margin: 0.75rem;
                padding: 1rem;
                border-radius: 8px;
            }
            
            /* Mobile card padding for student pages */
            .main-content {
                padding: 6px !important;
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
            
            /* Mobile bottom navigation for student sidebar */
            .student-sidebar {
                position: fixed !important;
                bottom: 0 !important;
                left: 0 !important;
                right: 0 !important;
                top: auto !important;
                width: 100% !important;
                height: 70px !important;
                min-height: auto !important;
                transform: none !important;
                border-radius: 15px 15px 0 0 !important;
                box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1) !important;
                z-index: 1000 !important;
                overflow: hidden !important;
            }
            
            .student-sidebar .sidebar-header {
                display: none !important;
            }
            
            .student-sidebar .sidebar-menu {
                display: flex !important;
                flex-direction: row !important;
                justify-content: space-around !important;
                align-items: center !important;
                padding: 0.5rem !important;
                height: 100% !important;
                margin: 0 !important;
            }
            
            .student-sidebar .sidebar-link {
                flex: 1 !important;
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                justify-content: center !important;
                padding: 0.5rem !important;
                margin: 0 0.25rem !important;
                border-radius: 12px !important;
                background: rgba(255, 255, 255, 0.1) !important;
                border: 1px solid rgba(255, 255, 255, 0.2) !important;
                text-decoration: none !important;
                transition: all 0.3s ease !important;
                min-height: 50px !important;
            }
            
            .student-sidebar .sidebar-link:hover {
                background: rgba(255, 255, 255, 0.2) !important;
                transform: translateY(-2px) !important;
            }
            
            .student-sidebar .sidebar-link.active {
                background: rgba(255, 255, 255, 0.3) !important;
                border-color: rgba(255, 255, 255, 0.5) !important;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2) !important;
            }
            
            .student-sidebar .sidebar-link i {
                font-size: 1.2rem !important;
                margin: 0 !important;
                margin-bottom: 0.25rem !important;
                width: auto !important;
            }
            
            .student-sidebar .link-text {
                font-size: 0.7rem !important;
                opacity: 1 !important;
                transform: none !important;
                display: block !important;
                text-align: center !important;
                line-height: 1 !important;
                white-space: nowrap !important;
            }
            
            /* Hide tooltips on mobile */
            .student-sidebar .sidebar-link::after {
                display: none !important;
            }
            
            /* Hide toggle button on mobile */
            .student-sidebar .sidebar-toggle {
                display: none !important;
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
        
        /* Language Switcher Styles */
        .language-switcher {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 1rem;
        }
        
        /* Navbar Language Switcher */
        .navbar-language-switcher .btn-group {
            border-radius: 6px;
            overflow: hidden;
        }
        
        .navbar-language-switcher .btn {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
            border: 1px solid #dee2e6;
            transition: all 0.3s ease;
        }
        
        .navbar-language-switcher .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }
        
        .navbar-language-switcher .btn:first-child {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
        
        .navbar-language-switcher .btn:last-child {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            border-left: none;
        }
        
        .language-switcher .btn {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        
        .language-switcher .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }
        
        /* Student Sidebar Language Switcher */
        .language-switcher-link {
            position: relative;
        }
        
        .language-dropdown {
            position: absolute;
            left: 100%;
            top: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            padding: 0.5rem 0;
            min-width: 120px;
            opacity: 0;
            visibility: hidden;
            transform: translateX(-10px);
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        @if(app()->getLocale() == 'ar')
        .language-dropdown {
            left: auto;
            right: 100%;
            transform: translateX(10px);
        }
        @endif
        
        .language-switcher-link:hover .language-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateX(0);
        }
        
        @if(app()->getLocale() == 'ar')
        .language-switcher-link:hover .language-dropdown {
            transform: translateX(0);
        }
        @endif
        
        .language-dropdown .dropdown-item {
            display: block;
            padding: 0.5rem 1rem;
            color: #333;
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 0.9rem;
        }
        
        .language-dropdown .dropdown-item:hover {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }
        
        .language-dropdown .dropdown-item.active {
            background: #667eea;
            color: white;
            font-weight: 600;
        }
        
        /* Collapsed state for language switcher */
        .student-sidebar.collapsed .language-dropdown {
            left: 100%;
            top: 50%;
            transform: translateY(-50%) translateX(-10px);
        }
        
        @if(app()->getLocale() == 'ar')
        .student-sidebar.collapsed .language-dropdown {
            left: auto;
            right: 100%;
            transform: translateY(-50%) translateX(10px);
        }
        @endif
        
        .student-sidebar.collapsed .language-switcher-link:hover .language-dropdown {
            transform: translateY(-50%) translateX(0);
        }
        
        @if(app()->getLocale() == 'ar')
        .student-sidebar.collapsed .language-switcher-link:hover .language-dropdown {
            transform: translateY(-50%) translateX(0);
        }
        @endif
        
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
        
        @if(app()->getLocale() == 'ar')
        @keyframes slideInRTL {
            from {
                opacity: 0;
                transform: translateX(15px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        @endif
        
        .sidebar-link {
            animation: slideIn 0.3s ease-out;
        }
        
        @if(app()->getLocale() == 'ar')
        .sidebar-link {
            animation: slideInRTL 0.3s ease-out;
        }
        @endif
        
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

        /* Mobile sidebar positioning for RTL */
        @if(app()->getLocale() == 'ar')
            .sidebar {
                transform: translateX(100%) !important;
            }
            .student-sidebar {
                transform: translateX(100%) !important;
            }
        @endif

        /* Toggle button positioning for RTL */
        @if(app()->getLocale() == 'ar')
        .sidebar-toggle {
            right: auto;
            left: -14px;
        }
        @endif

        /* Hover effects for RTL */
        @if(app()->getLocale() == 'ar')
        .sidebar-link:hover {
            transform: translateX(-3px);
        }
        @endif

        /* Icon margins for RTL */
        @if(app()->getLocale() == 'ar')
        .sidebar-link i {
            margin-right: 0;
            margin-left: 0.75rem;
        }
        @endif

        @media (max-width: 991.98px) {
            .student-sidebar {
                display: none !important;
            }
            .mobile-bottom-nav {
                position: fixed;
                left: 50%;
                bottom: 24px;
                transform: translateX(-50%);
                z-index: 2000;
                display: flex;
                gap: 1.2rem;
                background: rgba(255,255,255,0.15);
                box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                border-radius: 2rem;
                padding: 0.5rem 1.2rem;
                border: 1.5px solid rgba(255,255,255,0.18);
                min-width: 240px;
                max-width: 90vw;
            }
            .mobile-bottom-nav .mobile-nav-icon {
                display: flex;
                align-items: center;
                justify-content: center;
                text-decoration: none;
                outline: none;
                border: none;
                background: none;
                padding: 0;
            }
            .mobile-bottom-nav .icon-bg {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 44px;
                height: 44px;
                border-radius: 50%;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: #fff;
                font-size: 1.35rem;
                box-shadow: 0 2px 8px rgba(100,100,200,0.10);
                transition: background 0.2s, box-shadow 0.2s;
            }
            .mobile-bottom-nav .mobile-nav-icon.active .icon-bg,
            .mobile-bottom-nav .mobile-nav-icon:active .icon-bg {
                background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
                box-shadow: 0 4px 16px rgba(76,175,254,0.18);
            }
            .mobile-bottom-nav .icon-bg i {
                font-size: 1.35rem;
            }
            
            /* Mobile Language Switcher */
            .language-switcher-mobile {
                position: relative;
            }
            
            .mobile-language-dropdown {
                position: absolute;
                bottom: 100%;
                left: 50%;
                transform: translateX(-50%);
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border-radius: 12px;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
                padding: 0.5rem 0;
                min-width: 120px;
                opacity: 0;
                visibility: hidden;
                transform: translateX(-50%) translateY(10px);
                transition: all 0.3s ease;
                z-index: 2001;
                margin-bottom: 0.5rem;
            }
            
            .language-switcher-mobile:hover .mobile-language-dropdown {
                opacity: 1;
                visibility: visible;
                transform: translateX(-50%) translateY(0);
            }
            
            .mobile-dropdown-item {
                display: block;
                padding: 0.75rem 1rem;
                color: #333;
                text-decoration: none;
                transition: all 0.2s ease;
                font-size: 0.9rem;
                text-align: center;
            }
            
            .mobile-dropdown-item:hover {
                background: rgba(102, 126, 234, 0.1);
                color: #667eea;
            }
            
            .mobile-dropdown-item.active {
                background: #667eea;
                color: white;
                font-weight: 600;
            }

            #studentSidebarToggleBtn {
                /* position: absolute; */
                self_align: left;
            }
        }

        .language-switcher-link {
            position: relative;
            cursor: pointer;
            user-select: none;
        }

        .language-dropdown {
            position: absolute;
            left: 100%;
            top: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            padding: 0.5rem 0;
            min-width: 120px;
            opacity: 0;
            visibility: hidden;
            transform: translateX(-10px);
            transition: all 0.3s ease;
            z-index: 1000;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        @if(app()->getLocale() == 'ar')
        .language-dropdown {
            left: auto;
            right: 100%;
            transform: translateX(10px);
        }
        @endif

        /* Show dropdown when active */
        .language-switcher-link.active .language-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateX(0);
        }

        @if(app()->getLocale() == 'ar')
        .language-switcher-link.active .language-dropdown {
            transform: translateX(0);
        }
        @endif

        .language-dropdown .dropdown-item {
            display: block;
            padding: 0.75rem 1rem;
            color: #333;
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 0.9rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .language-dropdown .dropdown-item:last-child {
            border-bottom: none;
        }

        .language-dropdown .dropdown-item:hover {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }

        .language-dropdown .dropdown-item.active {
            background: #667eea;
            color: white;
            font-weight: 600;
        }

        /* Collapsed state for language switcher */
        .student-sidebar.collapsed .language-dropdown {
            left: 100%;
            top: 50%;
            transform: translateY(-50%) translateX(-10px);
        }

        @if(app()->getLocale() == 'ar')
        .student-sidebar.collapsed .language-dropdown {
            left: auto;
            right: 100%;
            transform: translateY(-50%) translateX(10px);
        }
        @endif

        .student-sidebar.collapsed .language-switcher-link.active .language-dropdown {
            transform: translateY(-50%) translateX(0);
        }

        @if(app()->getLocale() == 'ar')
        .student-sidebar.collapsed .language-switcher-link.active .language-dropdown {
            transform: translateY(-50%) translateX(0);
        }
        @endif

        /* Arrow indicator for language menu */
        .language-switcher-link::before {
            content: '';
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%) rotate(0deg);
            width: 0;
            height: 0;
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            border-top: 4px solid currentColor;
            transition: transform 0.3s ease;
            opacity: 0.7;
        }

        @if(app()->getLocale() == 'ar')
        .language-switcher-link::before {
            right: auto;
            left: 1rem;
        }
        @endif

        .language-switcher-link.active::before {
            transform: translateY(-50%) rotate(180deg);
        }

        /* Hide arrow when collapsed */
        .student-sidebar:not(.expanded) .language-switcher-link::before {
            display: none;
        }

        /* Mobile language dropdown adjustments */
        @media (max-width: 991.98px) {
            .language-dropdown {
                position: fixed;
                left: 50% !important;
                right: auto !important;
                top: auto !important;
                bottom: 100px;
                transform: translateX(-50%) translateY(10px) !important;
                min-width: 140px;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
                border-radius: 12px;
            }

            .language-switcher-link.active .language-dropdown {
                transform: translateX(-50%) translateY(0) !important;
            }

            .language-dropdown .dropdown-item {
                padding: 1rem;
                text-align: center;
                font-size: 1rem;
            }
        }

        @media (max-width: 991.98px) {
    .mobile-language-dropdown {
        position: fixed;
        left: 50% !important;
        right: auto !important;
        top: auto !important;
        bottom: 80px;
        transform: translateX(-50%) translateY(10px) !important;
        min-width: 160px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        padding: 0.5rem 0;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 2001;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    .mobile-language-dropdown.show,
    .language-switcher-mobile.active .mobile-language-dropdown {
        opacity: 1 !important;
        visibility: visible !important;
        transform: translateX(-50%) translateY(0) !important;
    }
    
    .mobile-dropdown-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        color: #333;
        text-decoration: none;
        transition: all 0.2s ease;
        font-size: 1rem;
        text-align: center;
        justify-content: center;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .mobile-dropdown-item:last-child {
        border-bottom: none;
    }
    
    .mobile-dropdown-item:hover {
        background: rgba(102, 126, 234, 0.1);
        color: #667eea;
    }
    
    .mobile-dropdown-item.active {
        background: #667eea;
        color: white;
        font-weight: 600;
    }
    
    .mobile-dropdown-item i {
        margin-right: 0.5rem;
        width: 16px;
        text-align: center;
    }
    
    /* Ensure mobile bottom nav icons work properly */
    .mobile-bottom-nav .language-switcher-mobile {
        position: relative;
        cursor: pointer;
    }
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
                    <!-- Language Switcher -->
                    <div class="language-switcher mb-3">
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('locale.set', 'ar') }}" 
                               class="btn btn-sm {{ app()->getLocale() == 'ar' ? 'btn-primary' : 'btn-outline-light' }}"
                               title="العربية">
                                العربية
                            </a>
                            <a href="{{ route('locale.set', 'en') }}" 
                               class="btn btn-sm {{ app()->getLocale() == 'en' ? 'btn-primary' : 'btn-outline-light' }}"
                               title="English">
                                EN
                            </a>
                        </div>
                    </div>
                    
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
        <!-- Toggle Button -->
                <button class="sidebar-toggle" id="studentSidebarToggleBtn" title="Toggle Sidebar">
                    <i class="fas fa-bars"></i>
                </button>

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

                    <a href="{{ route('student.sections') }}" class="sidebar-link {{ request()->routeIs('student.sections*') ? 'active' : '' }}" data-title="{{ __('lang.sections') }}">
                        <i class="fas fa-book"></i>
                        <span class="link-text">{{ __('lang.sections') }}</span>
                    </a>
                    <a href="{{ route('student.achievements') }}" class="sidebar-link {{ request()->routeIs('student.achievements*') ? 'active' : '' }}" data-title="{{ __('lang.achievements') }}">
                        <i class="fas fa-trophy"></i>
                        <span class="link-text">{{ __('lang.achievements') }}</span>
                    </a>
                    <a href="{{ route('student.setting') }}" class="sidebar-link {{ request()->routeIs('student.account*') ? 'active' : '' }}">
                        <i class="fas fa-user-cog"></i>
                        <span class="link-text">{{ __('lang.my_account') }}</span>
                    </a>
                    
                    <!-- Language Switcher -->
                    <div class="sidebar-link language-switcher-link" data-title="{{ __('lang.language') }}">
                        <i class="fas fa-language"></i>
                        <span class="link-text">{{ __('lang.language') }}</span>
                        <div class="language-dropdown">
                            <a href="{{ route('locale.set', 'ar') }}" class="dropdown-item {{ app()->getLocale() == 'ar' ? 'active' : '' }}">
                                العربية
                            </a>
                            <a href="{{ route('locale.set', 'en') }}" class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}">
                                English
                            </a>
                        </div>
                    </div>
                    
                    <a href="{{ route('logout') }}" class="sidebar-link" data-title="{{ __('lang.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-student').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="link-text">{{ __('lang.logout') }}</span>
                    </a>
                    <form id="logout-form-student" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
            <!-- Floating Mobile Bottom Nav -->
            <nav class="mobile-bottom-nav d-lg-none d-md-block">
                <a href="{{ route('student.sections') }}" class="mobile-nav-icon {{ request()->routeIs('student.sections*') ? 'active' : '' }}" title="{{ __('lang.sections') }}">
                    <span class="icon-bg"><i class="fas fa-book"></i></span>

                </a>
                <a href="{{ route('student.achievements') }}" class="sidebar-link {{ request()->routeIs('student.achievements*') ? 'active' : '' }}" data-title="{{ __('lang.achievements') }}">
                    <i class="fas fa-trophy"></i>
                    <span class="link-text">{{ __('lang.achievements') }}</span>
                </a>
                <a href="{{ route('student.setting') }}" class="mobile-nav-icon {{ request()->routeIs('student.account*') ? 'active' : '' }}" title="{{ __('lang.my_account') }}">
                    <span class="icon-bg"><i class="fas fa-user-cog"></i></span>

                </a>

                <!-- Language Switcher: always visible, no dropdown -->
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

                <a href="{{ route('logout') }}" class="sidebar-link" data-title="{{ __('lang.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-student').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="link-text">{{ __('lang.logout') }}</span>
                </a>
                <form id="logout-form-student" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
            </div>
            <!-- Floating Mobile Bottom Nav -->
            <nav class="mobile-bottom-nav d-lg-none d-md-block">
    <a href="{{ route('student.sections') }}" class="mobile-nav-icon {{ request()->routeIs('student.sections*') ? 'active' : '' }}" title="{{ __('lang.sections') }}">
        <span class="icon-bg"><i class="fas fa-book"></i></span>
    </a>
    <a href="{{ route('student.achievements') }}" class="mobile-nav-icon {{ request()->routeIs('student.achievements*') ? 'active' : '' }}" title="{{ __('lang.achievements') }}">
        <span class="icon-bg"><i class="fas fa-trophy"></i></span>
    </a>
    <a href="{{ route('student.setting') }}" class="mobile-nav-icon {{ request()->routeIs('student.account*') ? 'active' : '' }}" title="{{ __('lang.my_account') }}">
        <span class="icon-bg"><i class="fas fa-user-cog"></i></span>
    </a>
    <!-- Mobile Language Switcher: always visible, no toggle -->
    <div class="mobile-nav-icon" title="{{ __('lang.language') }}">
        <span class="icon-bg"><i class="fas fa-language"></i></span>
        <div class="d-flex flex-column gap-1 mt-2" style="display: none;position: fixed; bottom: 80px; left: 50%; transform: translateX(-50%); background: white; padding: 1rem; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.3); z-index: 2;">
            <a href="{{ route('locale.set', 'ar') }}" class="btn btn-sm {{ app()->getLocale() == 'ar' ? 'btn-primary' : 'btn-outline-primary' }}" style="width: 100px;">
                العربية
            </a>
            <a href="{{ route('locale.set', 'en') }}" class="btn btn-sm {{ app()->getLocale() == 'en' ? 'btn-primary' : 'btn-outline-primary' }}" style="width: 100px;">
                English
            </a>
        </div>
    </div>
    <a href="{{ route('logout') }}" class="mobile-nav-icon" title="{{ __('lang.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-student-mobile').submit();">
        <span class="icon-bg"><i class="fas fa-sign-out-alt"></i></span>
    </a>
    <form id="logout-form-student-mobile" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</nav>
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
                let sidebarExpanded = localStorage.getItem('studentSidebarExpanded') === 'true';
                const studentSidebarToggleBtn = document.getElementById('studentSidebarToggleBtn');
                
                function updateToggleIcon(expanded) {
                    const icon = studentSidebarToggleBtn?.querySelector('i');
                    if (icon) {
                        icon.className = expanded ? 'fas fa-times' : 'fas fa-bars';
                        studentSidebarToggleBtn.style.left = expanded ? '260px' : '54px';
                    }
                }

                function toggleStudentSidebar() {
                    if (window.innerWidth < 992) {
                        // On mobile, keep bottom navigation visible
                        // No need to hide/show
                    } else {
                        sidebarExpanded = !sidebarExpanded;
                        studentSidebar.classList.toggle('expanded', sidebarExpanded);
                        mainContent.classList.toggle('sidebar-expanded', sidebarExpanded);
                        updateToggleIcon(sidebarExpanded);
                        localStorage.setItem('studentSidebarExpanded', sidebarExpanded);
                    }
                }

                // Initialize sidebar state
                if (window.innerWidth >= 992) {
                    studentSidebar.classList.add('show');
                    if (sidebarExpanded) {
                        studentSidebar.classList.add('expanded');
                        mainContent.classList.add('sidebar-expanded');
                    }
                    updateToggleIcon(sidebarExpanded);
                } else {
                    // On mobile, bottom navigation is always visible
                    studentSidebar.classList.remove('expanded');
                    mainContent.classList.remove('sidebar-expanded');
                }

                if (studentSidebarToggle) {
                    studentSidebarToggle.addEventListener('click', toggleStudentSidebar);
                }

                if (studentSidebarToggleBtn) {
                    studentSidebarToggleBtn.addEventListener('click', toggleStudentSidebar);
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
                        if (sidebarExpanded) {
                            studentSidebar.classList.add('expanded');
                            mainContent.classList.add('sidebar-expanded');
                        } else {
                            studentSidebar.classList.remove('expanded');
                            mainContent.classList.remove('sidebar-expanded');
                        }
                        updateToggleIcon(sidebarExpanded);
                        studentOverlay.classList.remove('show');
                    } else {
                        // On mobile, bottom navigation is always visible
                        studentSidebar.classList.remove('expanded');
                        mainContent.classList.remove('sidebar-expanded');
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
                            // On mobile, keep bottom navigation visible
                            // No need to hide/show
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
                        if (text && !studentSidebar.classList.contains('expanded')) {
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