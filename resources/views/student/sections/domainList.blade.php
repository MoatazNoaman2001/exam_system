<div>
    <!-- When there is no desire, all things are at peace. - Laozi -->
</div>
@extends('layouts.app')

@section('title', __('All domains'))

@section('content')
    <!-- Link fonts and icons -->
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
            --success-green-light: #34d399;
            --success-green-bg: #ecfdf5;
            --warning-amber: #f59e0b;
            --warning-amber-light: #fbbf24;
            --warning-amber-bg: #fffbeb;
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
            --space-xs: 0.5rem;
            --space-sm: 1rem;
            --space-md: 1.5rem;
            --space-lg: 2rem;
            --space-xl: 3rem;
            --space-2xl: 4rem;
            --radius-sm: 0.375rem;
            --radius-md: 0.5rem;
            --radius-lg: 0.75rem;
            --radius-xl: 1rem;
            --radius-2xl: 1.5rem;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        body {
            font-family: 'Tajawal', 'Cairo', sans-serif;
            background-color: var(--gray-50);
        }

        .domains-container {
            padding: var(--space-md);
        }

        .domains-header {
            text-align: center;
            margin-bottom: var(--space-2xl);
            padding-bottom: var(--space-lg);
            border-bottom: 2px solid var(--gray-100);
        }

        .domains-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--gray-900);
            font-family: 'Cairo', 'Tajawal', sans-serif;
            margin-bottom: var(--space-sm);
            position: relative;
        }

        .domains-title::after {
            content: '';
            position: absolute;
            bottom: -12px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-blue), var(--primary-blue-light));
            border-radius: 2px;
        }

        .search-box {
            position: relative;
            max-width: 400px;
            margin: 0 auto var(--space-xl);
        }

        .search-input {
            width: 100%;
            padding: var(--space-sm) var(--space-md) var(--space-sm) 3rem;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-xl);
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .search-icon {
            position: absolute;
            left: var(--space-md);
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            font-size: 1.1rem;
        }

        .domains-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: var(--space-xl);
        }

        .domains-list {
            display: none;
            flex-direction: column;
            gap: var(--space-md);
        }

        .domain-card {
            background: white;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-md);
            border: 2px solid var(--gray-100);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            overflow: hidden;
            position: relative;
            padding: var(--space-lg);
        }

        .domain-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary-blue-light);
        }

        .domain-card.completed {
            border-color: var(--success-green);
            background: linear-gradient(135deg, white 0%, var(--success-green-bg) 100%);
        }

        .domain-icon {
            width: 60px;
            height: 60px;
            border-radius: var(--radius-lg);
            background: var(--primary-blue-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--primary-blue);
            margin-bottom: var(--space-md);
        }

        .domain-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: var(--space-sm);
            font-family: 'Cairo', 'Tajawal', sans-serif;
        }

        .domain-description {
            color: var(--gray-600);
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: var(--space-md);
        }

        .domain-progress {
            margin-bottom: var(--space-md);
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--space-sm);
        }

        .progress-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--gray-800);
        }

        .progress-stats {
            font-size: 0.85rem;
            color: var(--gray-600);
        }

        .progress-bar-container {
            background: var(--gray-200);
            border-radius: var(--radius-lg);
            height: 8px;
            overflow: hidden;
        }

        .progress-bar-fill {
            background: linear-gradient(90deg, var(--primary-blue), var(--primary-blue-light));
            height: 100%;
            border-radius: var(--radius-lg);
            transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .domain-actions {
            display: flex;
            justify-content: flex-end;
        }

        .action-btn {
            padding: var(--space-sm) var(--space-md);
            border: none;
            border-radius: var(--radius-lg);
            font-weight: 600;
            font-size: 0.875rem;
            background: var(--primary-blue);
            color: white;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .action-btn:hover {
            background: var(--primary-blue-dark);
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            .domains-grid {
                display: none;
            }

            .domains-list {
                display: flex;
            }

            .domain-card {
                padding: var(--space-md);
            }

            .domain-icon {
                width: 50px;
                height: 50px;
                font-size: 1.25rem;
            }

            .domain-title {
                font-size: 1.25rem;
            }

            .domain-description {
                font-size: 0.875rem;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .domains-container {
                border-radius: var(--radius-xl);
            }

            .domains-title {
                font-size: 2rem;
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

        .domain-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .domain-card:nth-child(1) { animation-delay: 0.1s; }
        .domain-card:nth-child(2) { animation-delay: 0.2s; }
        .domain-card:nth-child(3) { animation-delay: 0.3s; }

        .domain-card:focus {
            outline: 2px solid var(--primary-blue);
            outline-offset: 2px;
        }

        @media (prefers-reduced-motion: reduce) {
            .domain-card {
                animation: none !important;
                transition: none !important;
            }
        }
    </style>

    <div class="container-fluid" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
        <div class="domains-container">
            <div class="domains-header">
                <h1 class="domains-title">{{ __('lang.all_domains') }}</h1>
            </div>

            <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" placeholder="{{ __('lang.search_domains') }}" id="searchdomains">
            </div>

            @if ($domains->isEmpty())
                <div class="text-center text-muted">
                    {{ __('lang.no_domain_available') }}
                </div>
            @else
                <!-- Desktop Grid View -->
                <div class="domains-grid" id="domainsGrid">
                    @foreach ($domains as $domain)
                        <div class="domain-card {{ $domain['completed_slides'] == $domain['total_slides'] && $domain['total_slides'] > 0 ? 'completed' : '' }}"
                             role="button"
                             tabindex="0"
                             data-href="{{ route('student.domain.slides', $domain['id']) }}"
                             onclick="navigateTodomain(this)">
                            <div class="domain-icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <h3 class="domain-title">{{ __($domain['name']) }}</h3>
                            <p class="domain-description">{{ __($domain['description']) }}</p>
                            <div class="domain-progress">
                                <div class="progress-header">
                                    <span class="progress-title">{{ __('lang.domain_progress') }}</span>
                                    <span class="progress-stats">{{ trans_choice($domain['completed_slides'], ['count' => $domain['completed_slides']]) }} / {{ $domain['total_slides'] }}</span>
                                </div>
                                <div class="progress-bar-container">
                                    <div class="progress-bar-fill" style="width: {{ $domain['total_slides'] > 0 ? ($domain['completed_slides'] / $domain['total_slides'] * 100) : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Mobile List View -->
                <div class="domains-list" id="domainsList">
                    @foreach ($domains as $domain)
                        <div class="domain-card {{ $domain['completed_slides'] == $domain['total_slides'] && $domain['total_slides'] > 0 ? 'completed' : '' }}"
                             role="button"
                             tabindex="0"
                             data-href="{{ route('student.domain.slides', $domain['id']) }}"
                             onclick="navigateTodomain(this)">
                            <div class="domain-icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <h3 class="domain-title">{{ __($domain['name']) }}</h3>
                            <div class="domain-progress">
                                <div class="progress-header">
                                    <span class="progress-title">{{ __('domain Progress') }}</span>
                                    <span class="progress-stats">{{ trans_choice('Slides Completed', $domain['completed_slides'], ['count' => $domain['completed_slides']]) }} / {{ $domain['total_slides'] }}</span>
                                </div>
                                <div class="progress-bar-container">
                                    <div class="progress-bar-fill" style="width: {{ $domain['total_slides'] > 0 ? ($domain['completed_slides'] / $domain['total_slides'] * 100) : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Search functionality
            $('#searchdomains').on('input', function() {
                const searchTerm = $(this).val().toLowerCase();
                $('.domain-card').each(function() {
                    const title = $(this).find('.domain-title').text().toLowerCase();
                    const description = $(this).find('.domain-description').text().toLowerCase();
                    $(this).toggle(title.includes(searchTerm) || description.includes(searchTerm));
                });
            });

            // Responsive behavior
            function handleResize() {
                if ($(window).width() <= 768) {
                    $('#domainsGrid').hide();
                    $('#domainsList').show();
                } else {
                    $('#domainsGrid').show();
                    $('#domainsList').hide();
                }
            }

            $(window).on('resize', handleResize);
            handleResize();

            // Keyboard navigation
            $('.domain-card').on('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    navigateTodomain(this);
                }
            });

            // Intersection Observer for animations
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
                $('.domain-card').each(function() {
                    observer.observe(this);
                });
            }

            // Hover effects
            $('.domain-card').hover(
                function() {
                    $(this).find('.domain-icon i').addClass('fa-bounce');
                },
                function() {
                    $(this).find('.domain-icon i').removeClass('fa-bounce');
                }
            );
        });

        function navigateTodomain(element) {
            const href = $(element).data('href');
            $(element).css({
                'opacity': '0.7',
                'pointer-events': 'none'
            });
            const originalContent = $(element).find('.domain-icon').html();
            $(element).find('.domain-icon').html('<i class="fas fa-spinner fa-spin"></i>');
            setTimeout(function() {
                window.location.href = href;
            }, 300);
        }
    </script>
@endsection