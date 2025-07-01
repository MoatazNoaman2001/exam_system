@extends('layouts.app')

@section('title', __('Chapter: Project Management Fundamentals'))

@section('content')
    <!-- Link fonts and icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


    <style>
        :root {
            /* Modern Color Palette */
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
            --error-red: #ef4444;
            --error-red-light: #f87171;
            --error-red-bg: #fef2f2;
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

        .slides-container {
            background: white;
            border-radius: var(--radius-2xl);
            box-shadow: var(--shadow-lg);
            padding: var(--space-xl);
            margin: var(--space-lg) auto;
            max-width: 1400px;
        }

        .chapter-header {
            text-align: center;
            margin-bottom: var(--space-2xl);
            padding-bottom: var(--space-lg);
            border-bottom: 2px solid var(--gray-100);
        }

        .chapter-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--gray-900);
            font-family: 'Cairo', 'Tajawal', sans-serif;
            margin-bottom: var(--space-sm);
        }

        .chapter-subtitle {
            font-size: 1.25rem;
            color: var(--gray-600);
            margin-bottom: var(--space-lg);
        }

        .overall-progress {
            background: var(--gray-100);
            border-radius: var(--radius-xl);
            padding: var(--space-md);
            margin-bottom: var(--space-xl);
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--space-sm);
        }

        .progress-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--gray-800);
        }

        .progress-stats {
            font-size: 0.9rem;
            color: var(--gray-600);
        }

        .progress-bar-container {
            background: var(--gray-200);
            border-radius: var(--radius-lg);
            height: 12px;
            overflow: hidden;
            position: relative;
        }

        .progress-bar-fill {
            background: linear-gradient(90deg, var(--primary-blue), var(--primary-blue-light));
            height: 100%;
            border-radius: var(--radius-lg);
            transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .progress-bar-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .slides-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--space-xl);
            gap: var(--space-md);
        }

        .search-box {
            position: relative;
            flex: 1;
            max-width: 400px;
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

        .filter-buttons {
            display: flex;
            gap: var(--space-sm);
        }

        .filter-btn {
            padding: var(--space-sm) var(--space-md);
            border: 2px solid var(--gray-200);
            background: white;
            color: var(--gray-600);
            border-radius: var(--radius-lg);
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .filter-btn:hover,
        .filter-btn.active {
            border-color: var(--primary-blue);
            background: var(--primary-blue);
            color: white;
        }

        .slides-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: var(--space-xl);
        }

        .slides-list {
            display: none;
            flex-direction: column;
            gap: var(--space-md);
        }

        .slide-card {
            background: white;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-md);
            border: 2px solid var(--gray-100);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            overflow: hidden;
            position: relative;
        }

        .slide-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary-blue-light);
        }

        .slide-card.completed {
            border-color: var(--success-green);
            background: linear-gradient(135deg, white 0%, var(--success-green-bg) 100%);
        }

        .slide-card.in-progress {
            border-color: var(--warning-amber);
            background: linear-gradient(135deg, white 0%, var(--warning-amber-bg) 100%);
        }

        .slide-card.locked {
            opacity: 0.6;
            cursor: not-allowed;
            background: var(--gray-50);
        }

        .slide-thumbnail {
            height: 200px;
            background: linear-gradient(135deg, var(--primary-blue-bg) 0%, var(--primary-blue-light) 100%);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .slide-preview {
            width: 80%;
            height: 80%;
            background: white;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-md);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .slide-preview i {
            font-size: 2.5rem;
            color: var(--primary-blue);
        }

        .slide-number {
            position: absolute;
            top: var(--space-sm);
            right: var(--space-sm);
            background: rgba(255, 255, 255, 0.9);
            color: var(--primary-blue);
            padding: 0.25rem 0.75rem;
            border-radius: var(--radius-lg);
            font-weight: 600;
            font-size: 0.875rem;
        }

        .status-badge {
            position: absolute;
            top: var(--space-sm);
            left: var(--space-sm);
            padding: 0.375rem 0.75rem;
            border-radius: var(--radius-lg);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-badge.completed {
            background: var(--success-green);
            color: white;
        }

        .status-badge.in-progress {
            background: var(--warning-amber);
            color: white;
        }

        .status-badge.locked {
            background: var(--gray-400);
            color: white;
        }

        .slide-content {
            padding: var(--space-lg);
        }

        .slide-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: var(--space-sm);
            line-height: 1.4;
            font-family: 'Cairo', 'Tajawal', sans-serif;
        }

        .slide-description {
            color: var(--gray-600);
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: var(--space-md);
        }

        .slide-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--space-md);
        }

        .slide-duration {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--gray-500);
            font-size: 0.875rem;
        }

        .slide-difficulty {
            padding: 0.25rem 0.75rem;
            border-radius: var(--radius-md);
            font-size: 0.75rem;
            font-weight: 600;
        }

        .slide-difficulty.easy {
            background: var(--success-green-bg);
            color: var(--success-green);
        }

        .slide-difficulty.medium {
            background: var(--warning-amber-bg);
            color: var(--warning-amber);
        }

        .slide-difficulty.hard {
            background: var(--error-red-bg);
            color: var(--error-red);
        }

        .slide-progress {
            margin-bottom: var(--space-md);
        }

        .slide-progress-bar {
            background: var(--gray-200);
            border-radius: var(--radius-md);
            height: 6px;
            overflow: hidden;
        }

        .slide-progress-fill {
            height: 100%;
            border-radius: var(--radius-md);
            transition: width 0.5s ease;
        }

        .slide-progress-fill.completed {
            background: var(--success-green);
            width: 100%;
        }

        .slide-progress-fill.in-progress {
            background: var(--warning-amber);
        }

        .slide-progress-fill.not-started {
            background: var(--gray-300);
            width: 0%;
        }

        .slide-actions {
            display: flex;
            gap: var(--space-sm);
        }

        .action-btn {
            flex: 1;
            padding: var(--space-sm) var(--space-md);
            border: none;
            border-radius: var(--radius-lg);
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .action-btn.primary {
            background: var(--primary-blue);
            color: white;
        }

        .action-btn.primary:hover {
            background: var(--primary-blue-dark);
            transform: translateY(-1px);
        }

        .action-btn.secondary {
            background: var(--gray-100);
            color: var(--gray-700);
        }

        .action-btn.secondary:hover {
            background: var(--gray-200);
        }

        .action-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        @media (max-width: 768px) {
            .slides-grid {
                display: none;
            }

            .slides-list {
                display: flex;
            }

            .slide-card {
                border-radius: var(--radius-lg);
            }

            .slide-card.mobile {
                display: flex;
                align-items: center;
                padding: var(--space-md);
                gap: var(--space-md);
            }

            .slide-thumbnail.mobile {
                width: 80px;
                height: 80px;
                flex-shrink: 0;
                border-radius: var(--radius-lg);
            }

            .slide-preview.mobile {
                width: 100%;
                height: 100%;
                border-radius: var(--radius-md);
            }

            .slide-preview.mobile i {
                font-size: 1.5rem;
            }

            .slide-content.mobile {
                flex: 1;
                padding: 0;
            }

            .slide-title.mobile {
                font-size: 1.1rem;
                margin-bottom: 0.25rem;
            }

            .slide-description.mobile {
                font-size: 0.875rem;
                margin-bottom: var(--space-sm);
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .slide-meta.mobile {
                margin-bottom: var(--space-sm);
            }

            .slide-actions.mobile {
                flex-direction: column;
                gap: 0.5rem;
            }

            .slides-container {
                margin: var(--space-sm);
                padding: var(--space-lg);
                border-radius: var(--radius-xl);
            }

            .chapter-title {
                font-size: 2rem;
            }

            .slides-controls {
                flex-direction: column;
                gap: var(--space-md);
            }

            .filter-buttons {
                width: 100%;
                justify-content: center;
                flex-wrap: wrap;
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .slide-card {
            animation: slideInUp 0.6s ease-out;
        }

        .slide-card:nth-child(1) { animation-delay: 0.1s; }
        .slide-card:nth-child(2) { animation-delay: 0.2s; }
        .slide-card:nth-child(3) { animation-delay: 0.3s; }
        .slide-card:nth-child(4) { animation-delay: 0.4s; }
        .slide-card:nth-child(5) { animation-delay: 0.5s; }
        .slide-card:nth-child(6) { animation-delay: 0.6s; }

        .loading-skeleton {
            background: linear-gradient(90deg, var(--gray-200) 25%, var(--gray-100) 50%, var(--gray-200) 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }

        .slide-card:focus {
            outline: 2px solid var(--primary-blue);
            outline-offset: 2px;
        }
    </style>
    <div class="container-fluid" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
        <div class="slides-container">
            <!-- Chapter/Domain Header -->
            <div class="chapter-header">
                <h1 class="chapter-title">{{ $title }}</h1>
                <p class="chapter-subtitle">{{ $subtitle }}</p>
                
                <!-- Overall Progress -->
                <div class="overall-progress">
                    <div class="progress-header">
                        <span class="progress-title">{{ __('Overall Progress') }}</span>
                        <span class="progress-stats">{{ trans_choice('Slides Completed', $completedSlides, ['count' => $completedSlides]) }} / {{ $totalSlides }}</span>
                    </div>
                    <div class="progress-bar-container">
                        <div class="progress-bar-fill" style="width: {{ $totalSlides > 0 ? ($completedSlides / $totalSlides * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Controls -->
            <div class="slides-controls">
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="{{ __('Search Slides') }}" id="searchSlides">
                </div>
                
                <div class="filter-buttons">
                    <button class="filter-btn active" data-filter="all">{{ __('All') }}</button>
                    <button class="filter-btn" data-filter="completed">{{ __('Completed') }}</button>
                    <button class="filter-btn" data-filter="in-progress">{{ __('In Progress') }}</button>
                    <button class="filter-btn" data-filter="not-started">{{ __('Not Started') }}</button>
                </div>
            </div>

            <!-- Desktop Grid View -->
            <div class="slides-grid" id="slidesGrid">
                @foreach ($slides as $index => $slide)
                    <div class="slide-card {{ $slide['status'] }} {{ $slide['locked'] ? 'locked' : '' }}"
                         data-status="{{ $slide['status'] }}"
                         data-slide-id="{{ $slide['id'] }}"
                         data-slide-url="{{ route('student.sections.slides', $slide['id']) }}"
                         role="button"
                         tabindex="0">
                        <div class="slide-thumbnail">
                            <div class="slide-preview">
                                <i class="fas {{ $slide['icon'] }}"></i>
                            </div>
                            <div class="slide-number">{{ $index + 1 }}</div>
                            @if ($slide['status'] === 'completed' || $slide['status'] === 'in-progress' || $slide['locked'])
                                <div class="status-badge {{ $slide['status'] }}{{ $slide['locked'] ? ' locked' : '' }}">{{ $slide['locked'] ? __('Locked') : __($slide['status']) }}</div>
                            @endif
                        </div>
                        <div class="slide-content">
                            <h3 class="slide-title">{{ __($slide['title']) }}</h3>
                            <p class="slide-description">{{ __($slide['description']) }}</p>
                            <div class="slide-meta">
                                <div class="slide-duration">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ trans_choice('Minutes', $slide['duration'], ['count' => $slide['duration']]) }}</span>
                                </div>
                                <div class="slide-difficulty {{ $slide['difficulty'] }}">{{ __($slide['difficulty']) }}</div>
                            </div>
                            <div class="slide-progress">
                                <div class="slide-progress-bar">
                                    <div class="slide-progress-fill {{ $slide['status'] }}" style="width: {{ $slide['progress'] }}%"></div>
                                </div>
                            </div>
                            <div class="slide-actions">
                                <button class="action-btn primary" {{ $slide['locked'] ? 'disabled' : '' }}>
                                    <i class="fas {{ $slide['locked'] ? 'fa-lock' : 'fa-play' }}"></i>
                                    {{ $slide['locked'] ? __('Locked') : __($slide['action']) }}
                                </button>
                                <button class="action-btn secondary" {{ $slide['locked'] ? 'disabled' : '' }}>
                                    <i class="fas {{ $slide['secondary_icon'] }}"></i>
                                    {{ __($slide['secondary_action']) }}
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Mobile List View -->
            <div class="slides-list" id="slidesList">
                @foreach ($slides as $index => $slide)
                    <div class="slide-card mobile {{ $slide['status'] }} {{ $slide['locked'] ? 'locked' : '' }}"
                         data-status="{{ $slide['status'] }}"
                         data-slide-id="{{ $slide['id'] }}"
                         data-slide-url="{{ route('student.sections.slides', $slide['id']) }}"
                         role="button"
                         tabindex="0">
                        <div class="slide-thumbnail mobile">
                            <div class="slide-preview mobile">
                                <i class="fas {{ $slide['icon'] }}"></i>
                            </div>
                        </div>
                        <div class="slide-content mobile">
                            <h3 class="slide-title mobile">{{ __($slide['title']) }}</h3>
                            <p class="slide-description mobile">{{ __($slide['description']) }}</p>
                            <div class="slide-meta mobile">
                                <div class="slide-duration">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ trans_choice('Minutes', $slide['duration'], ['count' => $slide['duration']]) }}</span>
                                </div>
                                <div class="slide-difficulty {{ $slide['difficulty'] }}">{{ __($slide['difficulty']) }}</div>
                            </div>
                            <div class="slide-progress">
                                <div class="slide-progress-bar">
                                    <div class="slide-progress-fill {{ $slide['status'] }}" style="width: {{ $slide['progress'] }}%"></div>
                                </div>
                            </div>
                        </div>
                        @if ($slide['status'] === 'completed' || $slide['status'] === 'in-progress' || $slide['locked'])
                            <div class="status-badge {{ $slide['status'] }}{{ $slide['locked'] ? ' locked' : '' }}">{{ $slide['locked'] ? __('Locked') : __($slide['status']) }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Search functionality
            $('#searchSlides').on('input', function() {
                const searchTerm = $(this).val().toLowerCase();
                $('.slide-card').each(function() {
                    const title = $(this).find('.slide-title').text().toLowerCase();
                    const description = $(this).find('.slide-description').text().toLowerCase();
                    $(this).toggle(title.includes(searchTerm) || description.includes(searchTerm));
                });
            });

            // Filter functionality
            $('.filter-btn').on('click', function() {
                $('.filter-btn').removeClass('active');
                $(this).addClass('active');
                const filter = $(this).data('filter');
                $('.slide-card').each(function() {
                    $(this).toggle(filter === 'all' || $(this).data('status') === filter);
                });
            });

            // Slide card click handler
            $('.slide-card').on('click', function(e) {
                if (!$(this).hasClass('locked') && !$(e.target).is('button, i')) {
                    const slideUrl = $(this).data('slide-url');
                    openSlide(slideUrl);
                }
            });

            // Primary action button click handler
            $('.action-btn.primary').on('click', function(e) {
                e.stopPropagation();
                const $card = $(this).closest('.slide-card');
                if (!$card.hasClass('locked')) {
                    const slideUrl = $card.data('slide-url');
                    openSlide(slideUrl);
                }
            });

            // Keyboard navigation
            $('.slide-card').on('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    if (!$(this).hasClass('locked')) {
                        const slideUrl = $(this).data('slide-url');
                        openSlide(slideUrl);
                    }
                }
            });

            // Progress bar animations
            function animateProgressBars() {
                $('.slide-progress-fill').each(function() {
                    const $this = $(this);
                    const width = $this.css('width');
                    $this.css('width', '0');
                    setTimeout(() => {
                        $this.css('width', width);
                    }, 100);
                });
            }

            setTimeout(animateProgressBars, 500);

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
                $('.slide-card').each(function() {
                    observer.observe(this);
                });
            }

            // Enhanced hover effects
            $('.slide-card').hover(
                function() {
                    if (!$(this).hasClass('locked')) {
                        $(this).find('.slide-preview i').addClass('fa-spin');
                    }
                },
                function() {
                    $(this).find('.slide-preview i').removeClass('fa-spin');
                }
            );

            // Loading state for action buttons
            $('.action-btn').on('click', function(e) {
                if (!$(this).prop('disabled')) {
                    const $btn = $(this);
                    const originalText = $btn.html();
                    const originalWidth = $btn.width();
                    $btn.html('<i class="fas fa-spinner fa-spin"></i>');
                    $btn.width(originalWidth);
                    $btn.prop('disabled', true);
                    setTimeout(() => {
                        $btn.html(originalText);
                        $btn.prop('disabled', false);
                        $btn.css('width', 'auto');
                    }, 1500);
                }
            });

            // Responsive behavior
            function handleResize() {
                if ($(window).width() <= 768) {
                    $('#slidesGrid').hide();
                    $('#slidesList').show();
                } else {
                    $('#slidesGrid').show();
                    $('#slidesList').hide();
                }
            }

            $(window).on('resize', handleResize);
            handleResize();

            // Touch gestures for mobile
            let startX, startY, distX, distY;
            $('.slide-card.mobile').on('touchstart', function(e) {
                startX = e.originalEvent.touches[0].pageX;
                startY = e.originalEvent.touches[0].pageY;
            });

            $('.slide-card.mobile').on('touchmove', function(e) {
                e.preventDefault();
            });

            $('.slide-card.mobile').on('touchend', function(e) {
                distX = e.originalEvent.changedTouches[0].pageX - startX;
                distY = e.originalEvent.changedTouches[0].pageY - startY;
                if (Math.abs(distX) > Math.abs(distY) && distX > 100) {
                    if (!$(this).hasClass('completed') && !$(this).hasClass('locked')) {
                        markAsCompleted($(this));
                    }
                } else if (Math.abs(distX) > Math.abs(distY) && distX < -100) {
                    bookmarkSlide($(this));
                } else if (Math.abs(distX) < 10 && Math.abs(distY) < 10) {
                    if (!$(this).hasClass('locked')) {
                        const slideUrl = $(this).data('slide-url');
                        openSlide(slideUrl);
                    }
                }
            });

            function markAsCompleted($card) {
                $card.removeClass('in-progress')
                     .addClass('completed')
                     .find('.slide-progress-fill')
                     .removeClass('in-progress not-started')
                     .addClass('completed')
                     .css('width', '100%');
                $card.find('.status-badge')
                     .removeClass('in-progress')
                     .addClass('completed')
                     .text('{{ __('Completed') }}');
                showToast('{{ __('Slide Marked as Completed') }}', 'success');
            }

            function bookmarkSlide($card) {
                showToast('{{ __('Slide Saved to Favorites') }}', 'info');
            }

            function showToast(message, type) {
                const toast = $(`
                    <div class="toast-notification ${type}" style="
                        position: fixed;
                        top: 20px;
                        right: 20px;
                        background: ${type === 'success' ? 'var(--success-green)' : 'var(--primary-blue)'};
                        color: white;
                        padding: 1rem 1.5rem;
                        border-radius: var(--radius-lg);
                        box-shadow: var(--shadow-lg);
                        z-index: 9999;
                        transform: translateX(100%);
                        transition: transform 0.3s ease;
                    ">
                        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-info-circle'} me-2"></i>
                        ${message}
                    </div>
                `);
                $('body').append(toast);
                setTimeout(() => {
                    toast.css('transform', 'translateX(0)');
                }, 100);
                setTimeout(() => {
                    toast.css('transform', 'translateX(100%)');
                    setTimeout(() => {
                        toast.remove();
                    }, 300);
                }, 3000);
            }

            function openSlide(slideUrl) {
                const overlay = $(`
                    <div class="slide-loading-overlay" style="
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(59, 130, 246, 0.9);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        z-index: 9999;
                        color: white;
                        font-size: 1.5rem;
                        backdrop-filter: blur(5px);
                    ">
                        <div style="text-align: center;">
                            <i class="fas fa-spinner fa-spin fa-3x mb-3"></i>
                            <div>{{ __('Loading Slide') }}</div>
                        </div>
                    </div>
                `);
                $('body').append(overlay);
                setTimeout(() => {
                    window.location.href = slideUrl;
                }, 1500);
            }
        });

        $(function () {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });

        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js')
                .then(registration => console.log('SW registered'))
                .catch(error => console.log('SW registration failed'));
        }
    </script>
@endsection