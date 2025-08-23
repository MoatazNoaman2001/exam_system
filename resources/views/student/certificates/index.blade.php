<div>
    <!-- The only way to do great work is to love what you do. - Steve Jobs -->
</div>
@extends('layouts.app')

@section('title', __('lang.Select Certificate'))

@section('content')
    <!-- External Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Component Styles -->
    <link rel="stylesheet" href="{{ asset('css/certificates.css') }}">

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

    <!-- Loading Overlay -->
    @include('student.certificates.partials.loading-overlay')

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/certificates.js') }}"></script>
    
    <!-- Pass translations to JavaScript -->
    <script>
        window.translations = {
            loading: @json(__('lang.Loading...')),
            startLearning: @json(__('lang.Start Learning'))
        };
    </script>
@endsection