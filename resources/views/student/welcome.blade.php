@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    
    <div class="welcome-container">
        
        <!-- Skip Button -->
        <div class="skip-btn" onclick="window.location.href='{{ route('splash') }}'">
            <img src="/images/arrow-left.png" alt="Back arrow" class="arrow-icon" />
            <span>تخطي</span>
        </div>
        
        <!-- Main Content -->
        <div class="welcome-content">
            <h1 class="welcome-title">
                أهلاً بك في<br>
                <span class="app-name">PMP App</span>
            </h1>
            <p class="welcome-subtitle">
                رفيقك الذكي<br>
                في طريقك للاحتراف والاعتماد المهني
            </p>
        </div>
        
        <!-- Next Button -->
        <button class="next-btn" onclick="window.location.href='{{ route('feature') }}'">
            التالي
        </button>
        
        <!-- Decorative Graphic -->
        <div class="decorative-graphic">
            <img src="/images/Frame 9.png" alt="Decorative element" />
        </div>
    </div>
@endsection