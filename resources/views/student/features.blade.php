@extends('layouts.app')

@section('title', 'Features')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/features.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <div class="background">
        <img src="/images/Web-Onboarding-Features.png" alt="Background" class="background-img" />
        <div class="frame10">
            <div class="frame8">
                <h1 class="feature-title">تعلم، اختبر، وتقدم بثقة</h1>
                <p class="feature-subtitle">.محتوى محدث، تمارين واقعية، وتتبع لتقدمك خطوة بخطوة</p>
                
                <div class="button-group">
                    <button class="primary-btn" onclick="window.location.href='{{ route('student.welcome') }}'">التالي</button>
                    <button class="secondary-btn" onclick="window.location.href='{{ route('student.index') }}'">السابق</button>
                </div>
                
                <div class="skip-container" onclick="window.location.href='{{ route('student.splash') }}'">
                    <i class="fas fa-arrow-left"></i>
                    <span>تخطي</span>
                </div>
            </div>
        </div>
    </div>
@endsection