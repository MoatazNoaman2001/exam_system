@extends('layouts.app')

@section('title', 'تواصل معنا')

@section('content')
<link rel="stylesheet" href="{{ asset('css/ContactUs.css') }}">

<div class="container py-5" dir="rtl">
    <div class="contact-us-wrapper p-4 rounded-4" style="max-width: 650px; margin: 0 auto; background: linear-gradient(135deg, #ffffff 0%, #f0f4ff 100%); box-shadow: 0 0.5rem 2rem rgba(47, 128, 237, 0.1);">
        <div class="text-end mb-4">
            <h2 class="text-dark fw-bold" style="font-family: 'Tajawal', sans-serif; font-size: 1.3rem;">تواصل معنا</h2>
            <p class="text-secondary" style="font-family: 'Tajawal', sans-serif; font-size: 1rem;">هل تحتاج إلى المساعدة؟ لا تتردد في الاتصال بنا.</p>
        </div>

        <div class="row g-4">
            <!-- قسم البريد الإلكتروني -->
            <div class="col-12">
                <div class="contact-section p-3 rounded-3" style="background: #ffffff; border-left: 5px solid #2f80ed; box-shadow: 0 0.25rem 1rem rgba(47, 128, 237, 0.05);">
                    <h4 class="text-dark fw-bold mb-2" style="font-family: 'Tajawal', sans-serif; font-size: 1rem;">البريد الإلكتروني</h4>
                    <p class="text-secondary mb-2" style="font-family: 'Tajawal', sans-serif; font-size: 0.875rem;">admin@example.com</p>
                   <a href="https://mail.google.com/mail/?view=cm&fs=1&to=admin@example.com&su={{ urlencode('رسالة من ' . auth()->user()->email) }}&body={{ urlencode('مرحبًا، أنا ' . auth()->user()->username . '، أود مناقشة...') }}"
                       class="btn btn-outline-primary w-100 text-center py-2" 
                       style="font-family: 'Tajawal', sans-serif; font-size: 1rem; border-color: #2f80ed; color: #2f80ed; transition: all 0.3s;">
                        الانتقال إلى البريد الإلكتروني
                        <i class="bi bi-envelope-fill ms-2"></i>
                    </a>


                    
                </div>
            </div>

            <!-- قسم الواتساب -->
            <div class="col-12">
                <div class="contact-section p-3 rounded-3" style="background: #ffffff; border-left: 5px solid #2f80ed; box-shadow: 0 0.25rem 1rem rgba(47, 128, 237, 0.05);">
                    <h4 class="text-dark fw-bold mb-2" style="font-family: 'Tajawal', sans-serif; font-size: 1rem;">الواتساب</h4>
                    <p class="text-secondary mb-2" style="font-family: 'Tajawal', sans-serif; font-size: 0.875rem;">+201024102574</p>
                    <a href="https://wa.me/201024102574?text={{ urlencode('مرحبًا، أنا ' . auth()->user()->username . ' من ' . auth()->user()->email . '، أود مناقشة...') }}" 
                       class="btn btn-outline-primary w-100 text-center py-2" 
                       style="font-family: 'Tajawal', sans-serif; font-size: 1rem; border-color: #2f80ed; color: #2f80ed; transition: all 0.3s;">
                        الانتقال إلى الواتساب
                        <i class="bi bi-whatsapp ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection