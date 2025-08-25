{{-- resources/views/student/certificates/index.blade.php --}}
@extends('layouts.app')

@section('title', __('lang.Select Certificate'))


@section('content')
    <meta name="description" content="{{ __('lang.Choose the certification program you want to study') }}">
    <meta name="keywords" content="certificates, certification, learning, PMP, Agile, CAPM">
    <!-- External Dependencies -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <link rel="stylesheet" href="{{ asset('css/certificates.css') }}">

    <div class="main-container" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
        
        {{-- Header Section --}}
        @include('student.certificates.partials.header')

        {{-- Content Section --}}
        <div class="content-section">
            <div class="certificates-container">
            

                {{-- Certificates Grid --}}
                @if($certificates->count() > 0)
                    <div class="certificates-grid fade-in">
                        @foreach($certificates as $index => $certificate)
                            @include('student.certificates.partials.certificate-card', [
                                'certificate' => $certificate,
                                'index' => $index
                            ])
                        @endforeach
                    </div>
                @else
                    @include('student.certificates.partials.empty-state')
                @endif

            </div>
        </div>

    </div>

    {{-- Loading Overlay --}}
    @include('student.certificates.partials.loading-overlay')

    <!-- Core Scripts -->
    <script src="{{ asset('js/certificates.js') }}"></script>
        
    <!-- Pass translations to JavaScript -->
    <script>
        window.translations = {
            loading: @json(__('lang.Loading...')),
            startLearning: @json(__('lang.Start Learning')),
            selectCertificate: @json(__('lang.Select Certificate')),
            processing: @json(__('lang.Processing...')),
            error: @json(__('lang.An error occurred. Please try again.')),
            success: @json(__('lang.Certificate selected successfully!'))
        };
        
        // Initialize animations
        document.addEventListener('DOMContentLoaded', function() {
            // Add staggered animation delays to cards
            const cards = document.querySelectorAll('.certificate-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('slide-up');
            });
        });
    </script>
    <script>
        // Page-specific functionality
        function initializeCertificateSelection() {
            // Add any page-specific initialization here
        }
        
        // Call initialization when DOM is ready
        document.addEventListener('DOMContentLoaded', initializeCertificateSelection);
    </script>

@endsection
