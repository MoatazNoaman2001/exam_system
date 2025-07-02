@extends('layouts.app')

@section('title', 'About')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/About.css') }}">

    <div class="container my-5">
        <div class="about-card card border-0 rounded-4 shadow-sm p-4" dir="rtl">
            <div class="card-body">
                <h2 class="card-title fw-bold text-dark text-center mb-3">عن الموقع</h2>
                <p class="text-muted text-end mb-4 fw-medium">
                    مرحبًا {{ Auth::user()->username ?? 'المستخدم' }}!
                </p>
                <div class="about-text text-muted fs-6 lh-lg">
                    لوريم ايبسوم هو نموذج افتراضي يوضع في التصاميم لتعرض على العميل ليتصور طريقة وضع النصوص
                    بالتصاميم سواء كانت تصاميم مطبوعة … بروشور أو فلاير على سبيل المثال … أو نماذج مواقع إنترنت…
                    <br><br>
                    هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربي.
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
