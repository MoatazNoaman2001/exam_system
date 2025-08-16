@extends('layouts.app')

@section('title', __('Certificates'))

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

        .certificate-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .certificate-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--edu-gray-900);
            font-family: 'Cairo', 'Tajawal', sans-serif;
            margin-bottom: 0.5rem;
            position: relative;
        }

        .certificate-title::after {
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

        .certificate-subtitle {
            font-size: 1.2rem;
            color: var(--edu-gray-600);
            margin-bottom: 2rem;
        }

        .certificate-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .certificate-card {
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

        .certificate-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .certificate-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: var(--certificate-color);
            border-radius: 1.5rem 1.5rem 0 0;
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
            background: var(--certificate-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            color: var(--certificate-color);
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .certificate-card:hover .card-icon {
            transform: scale(1.1);
            background: var(--certificate-color);
            color: white;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--edu-gray-900);
            margin: 0;
            font-family: 'Cairo', 'Tajawal', sans-serif;
        }

        .card-code {
            font-size: 1rem;
            color: var(--edu-gray-500);
            font-weight: 500;
            margin-top: 0.25rem;
        }

        .card-description {
            color: var(--edu-gray-600);
            font-size: 1rem;
            line-height: 1.6;
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

        .progress-container {
            flex: 1;
            margin-right: 1rem;
        }

        .progress-bar {
            height: 8px;
            border-radius: 4px;
            background: var(--edu-gray-200);
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: var(--certificate-color);
            transition: width 0.3s ease;
        }

        .progress-text {
            font-size: 0.9rem;
            color: var(--edu-gray-500);
            margin-top: 0.5rem;
            text-align: center;
        }

        .start-button {
            background: var(--certificate-color);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .start-button:hover {
            background: var(--certificate-dark);
            color: white;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .certificate-container {
                margin: 1rem;
                border-radius: 1rem;
            }

            .certificate-title {
                font-size: 2rem;
            }

            .certificate-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .certificate-card {
                padding: 1.5rem;
            }

            .card-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .card-title {
                font-size: 1.3rem;
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

        .certificate-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .certificate-card:nth-child(1) { animation-delay: 0.1s; }
        .certificate-card:nth-child(2) { animation-delay: 0.2s; }
        .certificate-card:nth-child(3) { animation-delay: 0.3s; }
        .certificate-card:nth-child(4) { animation-delay: 0.4s; }
        .certificate-card:nth-child(5) { animation-delay: 0.5s; }
        .certificate-card:nth-child(6) { animation-delay: 0.6s; }
        .certificate-card:nth-child(7) { animation-delay: 0.7s; }
        .certificate-card:nth-child(8) { animation-delay: 0.8s; }
        .certificate-card:nth-child(9) { animation-delay: 0.9s; }
        .certificate-card:nth-child(10) { animation-delay: 1.0s; }
    </style>

    <div class="container my-4" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
        <div class="certificate-container">
            <div class="certificate-header">
                <h2 class="certificate-title">{{ __('Choose Your Certification Path') }}</h2>
                <p class="certificate-subtitle">{{ __('Select a certification to start your learning journey') }}</p>
            </div>

            <div class="certificate-grid">
                @foreach($certificates as $certificate)
                    <div class="certificate-card" 
                         style="--certificate-color: {{ $certificate->color }}; --certificate-bg: {{ $certificate->color }}20; --certificate-dark: {{ $certificate->color }}dd;"
                         role="button" 
                         tabindex="0" 
                         data-href="{{ route('student.certificate.show', $certificate->id) }}"
                         onclick="navigateToCertificate(this)">
                        
                        <div class="card-header-content">
                            <div class="card-icon">
                                <i class="{{ $certificate->icon }}"></i>
                            </div>
                            <div>
                                <h3 class="card-title">{{ $certificate->localized_name }}</h3>
                                <div class="card-code">{{ $certificate->code }}</div>
                            </div>
                        </div>
                        
                        <div class="card-description">
                            {{ $certificate->localized_description }}
                        </div>
                        
                        <div class="card-stats">
                            <div class="progress-container">
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ $certificateProgress[$certificate->id] ?? 0 }}%"></div>
                                </div>
                                <div class="progress-text">{{ __('Progress') }}: {{ $certificateProgress[$certificate->id] ?? 0 }}%</div>
                            </div>
                            <a href="{{ route('student.certificate.show', $certificate->id) }}" class="start-button">
                                {{ __('Start') }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Add keyboard navigation support
            $('.certificate-card').on('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    navigateToCertificate(this);
                }
            });

            // Add focus and blur effects
            $('.certificate-card').on('focus', function() {
                $(this).css('transform', 'translateY(-4px)');
            }).on('blur', function() {
                $(this).css('transform', 'translateY(0)');
            });

            // Add loading animation for smooth transitions
            $('.certificate-card').each(function(index) {
                $(this).css('animation-delay', (index * 0.1) + 's');
            });
        });

        function navigateToCertificate(element) {
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
            }
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
            
            $('.certificate-card').each(function() {
                observer.observe(this);
            });
        }
    </script>
@endsection 