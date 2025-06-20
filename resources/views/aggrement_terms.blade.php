<div>
    <!-- Walk as if you are kissing the Earth with your feet. - Thich Nhat Hanh -->
</div>
@extends('layouts.app')

@section('content')
<div class="terms-container bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Header -->
                <div class="text-center mb-5">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <i class="fas fa-graduation-cap fa-2x text-primary me-2"></i>
                        <span class="h3 fw-bold text-primary mb-0">PMP Master</span>
                    </div>
                    <h1 class="display-5 fw-bold mb-3">الشروط والأحكام</h1>
                    <p class="lead text-muted">آخر تحديث: 20 يونيو 2025</p>
                </div>

                <!-- Terms Content -->
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-md-5">
                        <div class="terms-content" style="text-align: right; direction: rtl;">
                            <section class="mb-5">
                                <p class="text-justify">
                                    لوريم ايبسوم هو نموذج افتراضي يوضع في التصاميم لتعرض على العميل ليتصور طريقه وضع النصوص بالتصاميم سواء كانت تصاميم مطبوعه … بروشور او فلاير على سبيل المثال … او نماذج مواقع انترنت … لوريم ايبسوم هو نموذج افتراضي يوضع في التصاميم لتعرض على العميل ليتصور طريقه وضع النصوص بالتصاميم سواء كانت تصاميم مطبوعه … بروشور او فلاير على سبيل المثال … او نماذج مواقع انترنت … وريم ايبسوم هو نموذج افتراضي يوضع في التصاميم لتعرض على العميل ليتصور طريقه وضع النصوص بالتصاميم سواء كانت تصاميم مطبوعه … بروشور او فلاير على سبيل المثال … او نماذج مواقع انترنت … لوريم ايبسوم هو نموذج افتراضي يوضع في التصاميم لتعرض على العميل ليتصور طريقه وضع النصوص بالتصاميم سواء كانت تصاميم مطبوعه … بروشور او فلاير على سبيل المثال … او نماذج مواقع انترنت … لوريم ايبسوم هو نموذج افتراضي يوضع في التصاميم لتعرض على العميل ليتصور طريقه وضع النصوص بالتصاميم سواء كانت تصاميم مطبوعه … بروشور او فلاير على سبيل المثال … او نماذج مواقع انترنت …
                                </p>
                            </section>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .terms-container {
        min-height: 100vh;
    }
    
    .terms-content {
        font-size: 1.1rem;
        line-height: 1.8;
    }
    
    @media (max-width: 767.98px) {
        .terms-container {
            padding-top: 2rem;
            padding-bottom: 2rem;
        }
        
        .card-body {
            padding: 1.5rem;
        }
    }
</style>
@endpush