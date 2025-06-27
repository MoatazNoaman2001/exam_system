@extends('layouts.app')

@section('title', 'تواصل معنا')

@section('content')
<link rel="stylesheet" href="{{ asset('css/ContactUs.css') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">

<div class="contact-container my-5" dir="rtl">
    <div class="contact-header">
        <h1 class="contact-title">تواصل معنا</h1>
        <p class="contact-subtitle">هل لديك استفسار أو تحتاج إلى مساعدة؟ فريق الدعم لديك متاح لمساعدتك</p>
        <div class="header-ornament"></div>
    </div>

    <div class="contact-cards">
        <!-- Email Card -->
        <div class="contact-card email-card">
            <div class="card-icon">
                <i class="bi bi-envelope-fill"></i>
            </div>
            <div class="card-content">
                <h3 class="card-title">البريد الإلكتروني</h3>
                <p class="card-detail">admin@example.com</p>
                <a href="https://mail.google.com/mail/?view=cm&fs=1&to=admin@example.com&su={{ urlencode('رسالة من ' . auth()->user()->email) }}&body={{ urlencode('مرحبًا، أنا ' . auth()->user()->username . '، أود مناقشة...') }}"
                   class="action-btn email-btn">
                    <span>إرسال بريد</span>
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
                <h3 class="card-title">الواتساب</h3>
                <p class="card-detail">+201024102574</p>
                <a href="https://wa.me/201024102574?text={{ urlencode('مرحبًا، أنا ' . auth()->user()->username . ' من ' . auth()->user()->email . '، أود مناقشة...') }}" 
                   class="action-btn whatsapp-btn">
                    <span>محادثة مباشرة</span>
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div>
            <div class="card-decoration"></div>
        </div>

        <!-- Support Card -->
       
    </div>
</div>
@endsection