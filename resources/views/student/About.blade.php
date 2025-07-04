@extends('layouts.app')

@section('title', 'عن الموقع')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/About.css') }}">

<div class="about-page">
    <div class="about-container">
        <div class="about-card">
            <!-- Header Section -->
            <div class="about-header">
                <h1 class="about-title">
                    <i class="bi bi-info-circle"></i>
                    عن الموقع
                </h1>
                
            </div>

            <!-- Main Content Section -->
            <div class="about-content">
                <div class="about-text">
                    <p>لوريم ايبسوم هو نموذج افتراضي يوضع في التصاميم لتعرض على العميل ليتصور طريقة وضع النصوص بالتصاميم سواء كانت تصاميم مطبوعة … بروشور أو فلاير على سبيل المثال … أو نماذج مواقع إنترنت…</p>
                    
                    <p>هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربي.</p>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection