@extends('layouts.app')

@section('title', 'Acheivement-Point')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/Achievement-point.css') }}">
    <div class="container py-4 text-center">
        <div class="mobile-achievement-points-guide">
            <div class="mobile-achievement-points-guide__main-content">
                <div class="row justify-content-end align-items-center mb-4">
                   
                    <div class="col-auto">
                        <h5 class="fw-bold mb-0">طرق الحصول على النقاط</h5>
                    </div>
                     <div class="col-auto">
                        <img src="{{ asset('images/arrow-right.png') }}" alt="رجوع" class="arrow-icon">
                    </div>
                </div>
                <div class="justify-content-start">
                   <h3 class="primary fw-bold mb-5 text-primary ">🎯 كيف أكسب النقاط؟ 🎯</h3>
                </div>
                <div class="card custom-card surface p-3">
                    <div class="card-body p-0">
                        <div class="custom-item d-flex justify-content-between align-items-center fw-bold text-secondary py-3">
                            <span>النقاط</span>
                            <span>النشاط</span>
                        </div>
                        <div class="custom-item d-flex justify-content-between align-items-center py-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-placeholder ms-2">نقطة</span>
                                <span class="primary fw-bold text-primary">50+</span>
                            </div>
                            <span class="text-primary d-flex align-items-center gap-2">
                                إكمال درس <span class="icon me-2">📘</span>
                            </span>
                        </div>
                        <div class="custom-item d-flex justify-content-between align-items-center py-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-placeholder ms-2">نقطة</span>
                                <span class="primary fw-bold text-primary">100+</span>
                            </div>
                            <span class="text-primary d-flex align-items-center gap-2">
                                إكمال اختبار <span class="icon me-2">📝</span>
                            </span>
                        </div>
                        <div class="custom-item d-flex justify-content-between align-items-center py-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-placeholder ms-2">نقطة</span>
                                <span class="primary fw-bold text-primary">75+</span>
                            </div>
                            <span class="text-primary d-flex align-items-center gap-2">
                                حل 20 سؤال تدريبي <span class="icon me-2">💡</span>
                            </span>
                        </div>
                        <div class="custom-item d-flex justify-content-between align-items-center py-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-placeholder ms-2">نقطة</span>
                                <span class="primary fw-bold text-primary">30+</span>
                            </div>
                            <span class="text-primary d-flex align-items-center gap-2">
                                تحقيق إنجاز <span class="icon me-2">🏅</span>
                            </span>
                        </div>
                        <div class="custom-item d-flex justify-content-between align-items-center py-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-placeholder ms-2">نقطة</span>
                                <span class="primary fw-bold text-primary">20+</span>
                            </div>
                            <span class="text-primary d-flex align-items-center gap-2">
                                دراسة يومية متتالية (3 أيام) <span class="icon me-2">📅</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection