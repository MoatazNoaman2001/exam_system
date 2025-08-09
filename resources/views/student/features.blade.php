@extends('layouts.app')

@section('title', __('lang.features'))

@section('content')
    <link rel="stylesheet" href="{{ asset('css/features.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <div class="background">
        <img src="/images/Web-Onboarding-Features.png" alt="Background" class="background-img" />
        <div class="frame10">
            <div class="frame8">
                <h1 class="feature-title">{{ __('lang.learn_test_progress') }}</h1>
                <p class="feature-subtitle">{{ __('lang.updated_content_exercises') }}</p>
                
                <div class="button-group">
                    <button class="primary-btn" onclick="window.location.href='{{ route('student.welcome') }}'">
                        {{ __('lang.next') }}
                    </button>
                    <button class="secondary-btn" onclick="window.location.href='{{ route('student.index') }}'">
                        {{ __('lang.previous') }}
                    </button>
                </div>
                
                <div class="skip-container" onclick="window.location.href='{{ route('student.splash') }}'">
                    <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}"></i>
                    <span>{{ __('lang.skip') }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection