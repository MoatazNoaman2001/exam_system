@extends('layouts.app')

@section('title', __('Sections'))

@section('content')
    <!-- ربط الخطوط والأيقونات -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --edu-primary-blue: #3b82f6;
            --edu-primary-blue-light: #60a5fa;
            --edu-primary-blue-bg: #eff6ff;
            --edu-success-green: #10b981;
            --edu-success-green-light: #34d399;
            --edu-success-green-bg: #ecfdf5;
            --edu-warning-amber: #f59e0b;
            --edu-warning-amber-light: #fbbf24;
            --edu-warning-amber-bg: #fffbeb;
            --edu-gray-50: #f9fafb;
            --edu-gray-100: #f3f4f6;
            --edu-gray-200: #e5e7eb;
            --edu-gray-300: #d1d5db;
            --edu-gray-400: #9ca3af;
            --edu-gray-500: #6b7280;
            --edu-gray-600: #4b5563;
            --edu-gray-700: #374151;
            --edu-gray-800: #1f2937;
            --edu-gray-900: #111827;
        }

        body {
            font-family: 'Tajawal', 'Cairo', sans-serif;
            background-color: var(--edu-gray-50);
        }



        .education-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .education-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--edu-gray-900);
            font-family: 'Cairo', 'Tajawal', sans-serif;
            margin-bottom: 0.5rem;
            position: relative;
        }

        .education-title::after {
            content: '';
            position: absolute;
            bottom: -12px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--edu-primary-blue), var(--edu-primary-blue-light));
            border-radius: 2px;
        }

        .education-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .education-card {
            background: white;
            border-radius: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
            border: 1px solid var(--edu-gray-100);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .education-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-color: var(--card-color);
        }

        .education-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--card-color), var(--card-color-light));
            border-radius: 1.5rem 1.5rem 0 0;
        }

        .education-card--chapters {
            --card-color: var(--edu-primary-blue);
            --card-color-light: var(--edu-primary-blue-light);
            --card-bg: var(--edu-primary-blue-bg);
        }

        .education-card--fields {
            --card-color: var(--edu-success-green);
            --card-color-light: var(--edu-success-green-light);
            --card-bg: var(--edu-success-green-bg);
        }

        .education-card--tests {
            --card-color: var(--edu-warning-amber);
            --card-color-light: var(--edu-warning-amber-light);
            --card-bg: var(--edu-warning-amber-bg);
        }

        .card-header-content {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            padding-top: 0.5rem;
        }

        .card-icon {
            width: 70px;
            height: 70px;
            border-radius: 1rem;
            background: var(--card-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            color: var(--card-color);
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .education-card:hover .card-icon {
            transform: scale(1.1);
            background: var(--card-color);
            color: white;
        }

        .card-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--edu-gray-900);
            margin: 0;
            font-family: 'Cairo', 'Tajawal', sans-serif;
        }

        .card-description {
            color: var(--edu-gray-600);
            font-size: 1.1rem;
            line-height: 1.7;
            margin-bottom: 1.5rem;
            flex-grow: 1;
        }

        .card-stats {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 1rem;
            border-top: 2px solid var(--edu-gray-100);
            margin-top: auto;
        }

        .stats-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .stats-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--card-color);
            line-height: 1;
        }

        .stats-label {
            font-size: 0.95rem;
            color: var(--edu-gray-500);
            font-weight: 500;
        }

        .progress-badge {
            background: var(--card-bg);
            color: var(--card-color);
            padding: 0.5rem 1rem;
            border-radius: 0.75rem;
            font-size: 0.9rem;
            font-weight: 600;
            border: 1px solid var(--card-color);
            transition: all 0.3s ease;
        }

        .education-card:hover .progress-badge {
            background: var(--card-color);
            color: white;
        }

        @media (max-width: 768px) {
            .education-container {
                margin: 1rem;
                border-radius: 1rem;
            }

            .education-title {
                font-size: 2rem;
            }

            .education-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .education-card {
                padding: 1.5rem;
            }

            .card-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .card-title {
                font-size: 1.5rem;
            }

            .card-description {
                font-size: 1rem;
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .education-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .education-card:nth-child(1) { animation-delay: 0.1s; }
        .education-card:nth-child(2) { animation-delay: 0.2s; }
        .education-card:nth-child(3) { animation-delay: 0.3s; }

        .education-card:focus {
            outline: 2px solid var(--card-color);
            outline-offset: 2px;
        }

        @media (prefers-reduced-motion: reduce) {
            .education-card,
            .card-icon,
            .progress-badge {
                transition: none !important;
                animation: none !important;
            }
        }
    </style>

    <div class="container my-4" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
        <div class="education-container">
            <div class="education-header">
                <h2 class="education-title">{{ __('lang.Sections') }}</h2>
            </div>

            <div class="education-grid">
                <!-- Chapters Card -->
                <div class="education-card education-card--chapters" 
                     role="button" 
                     tabindex="0" 
                     data-href="{{ route('student.sections.chapters') }}"
                     onclick="navigateToSection(this)">
                    
                    <div class="card-header-content">
                        <div class="card-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <h3 class="card-title">{{ __('lang.Chapters') }}</h3>
                    </div>
                    
                    <div class="card-description">
                        {{ __('lang.Chapters Description') }}
                    </div>
                    
                    <div class="card-stats">
                        <div class="stats-item">
                            <span class="stats-number">{{ $totalChapters }}</span>
                            <span class="stats-label">{{ __('lang.Chapter') }}</span>
                        </div>
                        <div class="progress-badge">{{ __('lang.Completed') }} {{ $completedChapters }}</div>
                    </div>
                </div>

                <!-- Fields Card -->
                <div class="education-card education-card--fields" 
                     role="button" 
                     tabindex="0" 
                     data-href="{{ route('student.sections.domains') }}"
                     onclick="navigateToSection(this)">
                    
                    <div class="card-header-content">
                        <div class="card-icon">
                            <i class="fas fa-compass"></i>
                        </div>
                        <h3 class="card-title">{{ __('lang.Fields') }}</h3>
                    </div>
                    
                    <div class="card-description">
                        {{ __('lang.Fields Description') }}
                    </div>
                    
                    <div class="card-stats">
                        <div class="stats-item">
                            <span class="stats-number">{{ $totalDomains }}</span>
                            <span class="stats-label">{{ __('lang.Fields Label') }}</span>
                        </div>
                        <div class="progress-badge">{{ __('lang.Achieved') }} {{ $achievedDomains }}</div>
                    </div>
                </div>

                <!-- Tests Card -->
                <div class="education-card education-card--tests" 
                     role="button" 
                     tabindex="0" 
                     data-href="{{ route('student.exams.check-plan') }}"
                     onclick="navigateToSection(this)">
                    
                    <div class="card-header-content">
                        <div class="card-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h3 class="card-title">{{ __('lang.Tests') }}</h3>
                    </div>
                    
                    <div class="card-description">
                        {{ __('lang.Tests Description') }}
                    </div>
                    
                    <div class="card-stats">
                        <div class="stats-item">
                            <span class="stats-number">{{ $totalExams }}</span>
                            <span class="stats-label">{{ __('lang.Tests Label') }}</span>
                        </div>
                        <div class="progress-badge">{{ __('lang.Achieved') }} {{ $achievedExams }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Add keyboard navigation support
            $('.education-card').on('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    navigateToSection(this);
                }
            });

            // Add focus and blur effects
            $('.education-card').on('focus', function() {
                $(this).css('transform', 'translateY(-4px)');
            }).on('blur', function() {
                $(this).css('transform', 'translateY(0)');
            });

            // Add loading animation for smooth transitions
            $('.education-card').each(function(index) {
                $(this).css('animation-delay', (index * 0.1) + 's');
            });
        });

        function navigateToSection(element) {
            const href = $(element).data('href');
            
            if (href && href !== '#') {
                // Add loading state
                $(element).css({
                    'opacity': '0.7',
                    'pointer-events': 'none'
                });
                
                // Show loading feedback
                const originalContent = $(element).find('.card-icon').html();
                $(element).find('.card-icon').html('<i class="fas fa-spinner fa-spin"></i>');
                
                // Navigate after short delay for visual feedback
                setTimeout(function() {
                    window.location.href = href;
                }, 300);
            } else {
                // Show coming soon message for disabled links
                showComingSoonMessage();
            }
        }

        function showComingSoonMessage() {
            // Create a simple modal-like notification
            if (!$('#comingSoonAlert').length) {
                $('body').append(`
                    <div id="comingSoonAlert" class="alert alert-info position-fixed" 
                         style="top: 20px; right: 20px; z-index: 9999; display: none;">
                        <i class="fas fa-info-circle me-2"></i>
                        {{ __('Coming Soon Message') }}
                    </div>
                `);
            }
            
            $('#comingSoonAlert').fadeIn().delay(3000).fadeOut();
        }

        // Add intersection observer for scroll animations
        if ('IntersectionObserver' in window) {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        $(entry.target).addClass('animate__animated animate__fadeInUp');
                    }
                });
            }, observerOptions);
            
            $('.education-card').each(function() {
                observer.observe(this);
            });
        }

        // Add smooth scrolling for better UX
        if (window.location.hash) {
            const target = $(window.location.hash);
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 800);
            }
        }
    </script>
@endsection