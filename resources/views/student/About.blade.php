@extends('layouts.app')

@section('title', 'About')

@section('content')
    <!-- ربط Bootstrap وخطوط Tajawal -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/About.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">

  
        <div class="terms-container p-4 bg-white "dir="rtl">
            <div class="terms-header mb-4">
                <h2 class="terms-title text-dark fw-bold">عن الموقع</h2>
            </div>

   
                <div class="user-info mb-3 text-dark fw-medium">
              </div>
                <p class="terms-subtitle mb-4">
                    مرحبًا {{ Auth::user()->username ?? 'المستخدم' }}!
                </p>
                <div class="terms-content ">
                    لوريم ايبسوم هو نموذج افتراضي يوضع في التصاميم لتعرض على العميل ليتصور
                    طريقة وضع النصوص بالتصاميم سواء كانت تصاميم مطبوعة … بروشور أو فلاير على
                    سبيل المثال … أو نماذج مواقع إنترنت … لوريم ايبسوم هو نموذج افتراضي يوضع
                    في التصاميم لتعرض على العميل ليتصور طريقة وضع النصوص بالتصاميم سواء كانت
                    تصاميم مطبوعة … بروشور أو فلاير على سبيل المثال … أو نماذج مواقع إنترنت…
                    لوريم ايبسوم هو نموذج افتراضي يوضع في التصاميم لتعرض على العميل ليتصور
                    طريقة وضع النصوص بالتصاميم سواء كانت تصاميم مطبوعة … بروشور أو فلاير على
                    سبيل المثال … أو نماذج مواقع إنترنت…
         
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

 
@endsection