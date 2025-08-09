@extends('layouts.app')

@section('title', __('lang.logo'))

@section('content')
    <link rel="stylesheet" href="{{ asset('css/logo.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    
    <div class="background">
        <img src="/images/70e80d344c1c52645d9005d789c5a192bb7f2c85.png" alt="Background" class="background-img" />
        <div class="frame10">
            <div class="frame8">
                <h1 class="logo-text">{{ __('lang.logo') }}</h1>
                <p class="ready">{{ __('lang.ready_to_start') }}</p>
                <p class="start">{{ __('lang.start_journey') }}</p>
                <button class="use" onclick="window.location.href='{{ route('student.feature') }}'">
                    {{ __('lang.start_using') }}
                </button>    
            </div>
        </div>
    </div>
@endsection