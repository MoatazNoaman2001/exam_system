<div>
    <!-- The only way to do great work is to love what you do. - Steve Jobs -->
</div>
@extends('layouts.app')


@section('title', __('Select Certificate'))

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --primary: #3b82f6;
            --primary-light: #60a5fa;
            --primary-bg: #eff6ff;
            --success: #10b981;
            --warning: #f59e0b;
            --purple: #8b5cf6;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-600: #4b5563;
            --gray-800: #1f2937;
            --gray-900: #111827;
        }

        body {
            font-family: 'Tajawal', 'Cairo', sans-serif;
            background-color: var(--gray-50);
            margin: 0;
            padding: 0;
        }

        .main-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            padding: 2rem 0;
            text-align: center;
        }

        .header-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-family: 'Cairo', sans-serif;
        }

        .header-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }

        .content-section {
            flex: 1;
            padding: 3rem 0;
        }

        .certificates-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .certificates-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .certificate-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .certificate-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            border-color: var(--card-color);
        }

        .certificate-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--card-color);
        }

        .certificate-card--pmp {
            --card-color: var(--primary);
            --card-bg: var(--primary-bg);
        }

        .certificate-card--agile {
            --card-color: var(--success);
            --card-bg: #ecfdf5;
        }

        .certificate-card--capm {
            --card-color: var(--warning);
            --card-bg: #fffbeb;
        }

        .certificate-card--other {
            --card-color: var(--purple);
            --card-bg: #f5f3ff;
        }

        .certificate-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .certificate-icon {
            width: 60px;
            height: 60px;
            border-radius: 0.75rem;
            background: var(--card-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--card-color);
            flex-shrink: 0;
        }

        .certificate-info {
            flex: 1;
        }

        .certificate-name {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-900);
            margin: 0 0 0.25rem 0;
            font-family: 'Cairo', sans-serif;
        }

        .certificate-code {
            background: var(--card-bg);
            color: var(--card-color);
            padding: 0.25rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }

        .certificate-description {
            color: var(--gray-600);
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .certificate-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid var(--gray-200);
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--gray-600);
            font-size: 0.875rem;
        }

        .meta-icon {
            color: var(--card-color);
        }

        .select-btn {
            background: var(--card-color);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .select-btn:hover {
            opacity: 0.9;
            transform: scale(1.02);
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--gray-600);
        }

        .empty-icon {
            font-size: 4rem;
            color: var(--gray-400);
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .header-title {
                font-size: 2rem;
            }
            
            .certificates-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .certificate-card {
                padding: 1.5rem;
            }
            
            .certificate-header {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
            
            .certificate-meta {
                flex-direction: column;
                gap: 0.75rem;
                align-items: stretch;
            }
            
            .select-btn {
                width: 100%;
            }
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .loading-spinner {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 2rem;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
    </style>

    <div class="main-container" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
        <!-- Header Section -->
        <div class="header-section">
            <div class="container">
                <h1 class="header-title">{{ __('lang.Select Certificate') }}</h1>
                <p class="header-subtitle">
                    {{ __('lang.Choose the certification program you want to study') }}
                </p>
            </div>
        </div>

        <!-- Content Section -->
        <div class="content-section">
            <div class="certificates-container">
                @if($certificates->count() > 0)
                    <div class="certificates-grid">
                        @foreach($certificates as $index => $certificate)
                            @php
                                $cardClasses = [
                                    'certificate-card--pmp',
                                    'certificate-card--agile', 
                                    'certificate-card--capm',
                                    'certificate-card--other'
                                ];
                                $cardClass = $cardClasses[$index % count($cardClasses)];
                                
                                $icons = [
                                    'fas fa-project-diagram',
                                    'fas fa-sync-alt',
                                    'fas fa-certificate',
                                    'fas fa-graduation-cap'
                                ];
                                $icon = $icons[$index % count($icons)];
                            @endphp
                            
                            <form action="{{ route('student.certificates.select') }}" method="POST" class="certificate-form">
                                @csrf
                                <input type="hidden" name="certificate_id" value="{{ $certificate->id }}">
                                
                                <div class="certificate-card {{ $cardClass }}" onclick="selectCertificate(this)">
                                    <div class="certificate-header">
                                        <div class="certificate-icon">
                                            <i class="{{ $icon }}"></i>
                                        </div>
                                        <div class="certificate-info">
                                            <h3 class="certificate-name">
                                                {{ app()->getLocale() === 'ar' && $certificate->name_ar ? $certificate->name_ar : $certificate->name }}
                                            </h3>
                                            <div class="certificate-code">
                                                {{ strtoupper($certificate->code) }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="certificate-description">
                                        {{ app()->getLocale() === 'ar' && $certificate->description_ar ? $certificate->description_ar : $certificate->description }}
                                    </div>
                                    
                                    <div class="certificate-meta">
                                        <div class="meta-item">
                                            <i class="fas fa-book meta-icon"></i>
                                            <span>{{ __('lang.Professional Certification') }}</span>
                                        </div>
                                        <button type="submit" class="select-btn">
                                            {{ __('lang.Start Learning') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-certificate"></i>
                        </div>
                        <h3>{{ __('lang.No Certificates Available') }}</h3>
                        <p>{{ __('lang.No certification programs are currently available. Please contact support.') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner">
            <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
            <span>{{ __('lang.Loading...') }}</span>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function selectCertificate(cardElement) {
            const form = cardElement.closest('.certificate-form');
            if (form) {
                // Show loading overlay
                document.getElementById('loadingOverlay').style.display = 'flex';
                
                // Add visual feedback to selected card
                cardElement.style.opacity = '0.7';
                cardElement.style.pointerEvents = 'none';
                
                // Submit form
                form.submit();
            }
        }

        // Handle form submission with loading states
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('.certificate-form');
            
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const submitBtn = form.querySelector('.select-btn');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>{{ __('lang.Loading...') }}';
                        submitBtn.disabled = true;
                    }
                });
            });
            
            // Add keyboard support
            const cards = document.querySelectorAll('.certificate-card');
            cards.forEach(card => {
                card.setAttribute('tabindex', '0');
                card.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        selectCertificate(this);
                    }
                });
            });
        });

        // Hide loading overlay if page is refreshed/back button
        window.addEventListener('pageshow', function() {
            document.getElementById('loadingOverlay').style.display = 'none';
        });
    </script>
@endsection