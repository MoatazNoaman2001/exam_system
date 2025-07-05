@extends('layouts.app')

@section('title', __('lang.contact_us'))

@section('content')
<link rel="stylesheet" href="{{ asset('css/ContactUs.css') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">

@php
    $dir = app()->getLocale() === 'ar' ? 'rtl' : 'ltr';
@endphp

<div class="contact-container my-5" dir="{{ $dir }}">
    <div class="contact-header">
        <h1 class="contact-title">{{ __('lang.contact_us') }}</h1>
        <p class="contact-subtitle">{{ __('lang.contact_subtitle') }}</p>
        <div class="header-ornament"></div>
    </div>

    <div class="contact-cards">
        <!-- Email Card -->
        <div class="contact-card email-card">
            <div class="card-icon">
                <i class="bi bi-envelope-fill"></i>
            </div>
            <div class="card-content">
                <h3 class="card-title">{{ __('lang.email') }}</h3>
                <p class="card-detail">admin@example.com</p>
                <a href="https://mail.google.com/mail/?view=cm&fs=1&to=admin@example.com&su={{ urlencode(__('lang.email_subject', ['email' => auth()->user()->email])) }}&body={{ urlencode(__('lang.email_body', ['name' => auth()->user()->username])) }}"
                   class="action-btn email-btn">
                    <span>{{ __('lang.send_email') }}</span>
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div>
            <div class="card-decoration"></div>
        </div>

        <!-- WhatsApp Card -->
        <div class="contact-card whatsapp-card">
            <div class="card-icon">
                <i class="bi bi-whatsapp"></i>
            </div>
            <div class="card-content">
                <h3 class="card-title">{{ __('lang.whatsapp') }}</h3>
                <p class="card-detail">+201024102574</p>
                <a href="https://wa.me/201024102574?text={{ urlencode(__('lang.whatsapp_msg', ['name' => auth()->user()->username, 'email' => auth()->user()->email])) }}" 
                   class="action-btn whatsapp-btn">
                    <span>{{ __('lang.start_chat') }}</span>
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div>
            <div class="card-decoration"></div>
        </div>
    </div>
</div>
@endsection
