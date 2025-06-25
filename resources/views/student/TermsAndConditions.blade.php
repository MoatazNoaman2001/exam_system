@extends('layouts.app')

@section('title', 'TermsAndConditions')

@section('content')
<link rel="stylesheet" href="{{ asset('css/TermsAndConditions.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">

<div class="terms-container" dir=rtl>
    <div class="terms-header">
        <h2 class="terms-title">شروط وسياسة الاستخدام</h2>
       
    </div>

    <div id="pdf-content">
        <div class="user-info">
            مرحبًا {{ Auth::user()->username ?? 'المستخدم' }}! 
        </div>
        <p class="terms-subtitle">أهلاً وسهلاً بك في {{ config('app.name', 'اسم التطبيق') }}</p>
        <div class="terms-content">
            لوريم ايبسوم هو نموذج افتراضي يوضع في التصاميم لتعرض على العميل ليتصور
            طريقه وضع النصوص بالتصاميم سواء كانت تصاميم مطبوعه … بروشور أو فلاير على
            سبيل المثال … أو نماذج مواقع انترنت … لوريم ايبسوم هو نموذج افتراضي يوضع
            في التصاميم لتعرض على العميل ليتصور طريقه وضع النصوص بالتصاميم سواء كانت
            تصاميم مطبوعه … بروشور أو فلاير على سبيل المثال … أو نماذج مواقع انترنت…
            لوريم ايبسوم هو نموذج افتراضي يوضع في التصاميم لتعرض على العميل ليتصور
            طريقه وضع النصوص بالتصاميم سواء كانت تصاميم مطبوعه … بروشور أو فلاير على
            سبيل المثال … أو نماذج مواقع انترنت…
        </div>
    </div>
    <br>
    <br>
     <a href="#" id="downloadPdf" class="download-btn p-3 px-5">تحميل الشروط كـ PDF</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
    document.getElementById('downloadPdf').addEventListener('click', function(e) {
        e.preventDefault();
        const element = document.getElementById('pdf-content');

        const opt = {
            margin: 0.5,
            filename: 'TermsAndConditions_{{ Auth::user()->username ?? "User" }}_{{ date("Ymd") }}.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
        };

        html2pdf().set(opt).from(element).save();
    });
</script>
@endsection
