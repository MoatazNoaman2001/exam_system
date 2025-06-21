
@extends('layouts.app')

@section('title', 'splash')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/setting.css') }}">


<div class="container-md py-4">
        <div class="profile-header text-center mb-4">
            <img src="{{ auth()->user()->image ?? 'https://via.placeholder.com/64' }}" alt="صورة المستخدم" class="rounded-circle" width="64" height="64">
            <div class="name fs-5 fw-bold mt-2">{{ auth()->user()->name }}</div>
            <div class="email text-secondary fs-6">{{ auth()->user()->email }}</div>
        </div>

        <div class="card custom-card mb-3">
            <div class="custom-item">
                <div class="d-flex align-items-center">
                    <span class="me-2">📄</span>
                    <span>شهاداتي</span>
                </div>
                <span>›</span>
            </div>
            <div class="custom-item">
                <div class="d-flex align-items-center">
                    <span class="me-2">🏅</span>
                    <span>قائمة المتصدرين</span>
                </div>
                <span>›</span>
            </div>
        </div>

        <h5 class="text-secondary mb-2">الحساب</h5>
        <div class="card custom-card mb-3">
            <div class="custom-item">
                <div class="d-flex align-items-center">
                    <span class="me-2">👤</span>
                    <span>حسابي</span>
                </div>
                <span>›</span>
            </div>
            <div class="custom-item">
                <div class="d-flex align-items-center">
                    <span class="me-2">🛡️</span>
                    <span>الأمان</span>
                </div>
                <span>›</span>
            </div>
            <div class="custom-item">
                <div class="d-flex align-items-center">
                    <span class="me-2">🗑️</span>
                    <span class="text-danger">حذف حسابي</span>
                </div>
                <span>›</span>
            </div>
        </div>

        <h5 class="text-secondary mb-2">إعدادات التطبيق</h5>
        <div class="card custom-card mb-3">
            <div class="d-flex justify-content-between align-items-center py-3 px-4 border-bottom">
                <div class="d-flex align-items-center">
                    <span class="me-2">🔔</span>
                    <span>الإشعارات</span>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="notifications" checked>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center py-3 px-4">
                <div class="d-flex align-items-center">
                    <span class="me-2">🌐</span>
                    <span>اللغة</span>
                </div>
                <span>{{ auth()->user()->preferred_language == 'ar' ? 'العربية' : 'الإنجليزية' }}</span>
            </div>
        </div>

        <h5 class="text-secondary mb-2">الدعم</h5>
        <div class="card custom-card mb-3">
            <div class="custom-item">
                <div class="d-flex align-items-center">
                    <span class="me-2">📄</span>
                    <span>شروط وسياسة الاستخدام</span>
                </div>
                <span>›</span>
            </div>
            <div class="custom-item">
                <div class="d-flex align-items-center">
                    <span class="me-2">❗</span>
                    <span>عن التطبيق</span>
                </div>
                <span>›</span>
            </div>
            <div class="custom-item">
                <div class="d-flex align-items-center">
                    <span class="me-2">❓</span>
                    <span>FAQ</span>
                </div>
                <span>›</span>
            </div>
            <div class="custom-item">
                <div class="d-flex align-items-center">
                    <span class="me-2">📞</span>
                    <span>تواصل معنا</span>
                </div>
                <span>›</span>
            </div>
        </div>

        <div class="logout">
            تسجيل الخروج
        </div>
    </div>
@endsection