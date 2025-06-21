@extends('layouts.app')

@section('title', 'Completed_Action')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/certification.css') }}">

<div class="container-md py-4">
        @if($progress->lessons_completed == $progress->lessons_total && $progress->exams_completed == $progress->exams_total && $progress->questions_completed == $progress->questions_total)
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="card custom-card mb-4">
                        <div class="card-body text-center">
                            <div class="d-flex justify-content-end align-items-center mb-3">
                                <h5 class="text-primary fw-bold mb-0 me-2">شهادتي</h5>
                                <img src="{{ asset('images/vuesax-outline-arrow-right0.svg') }}" alt="Arrow" width="24" height="24">
                            </div>
                            <h3 class="primary fw-bold mb-3">🎉 مبروك!</h3>
                            <p class="text-primary mb-4">
                                لقد أنجزت جميع محتويات التطبيق وحصلت على شهادتك الرسمية، {{ auth()->user()->name }}!
                            </p>
                            <img src="{{ asset('images/canva-blue-and-gold-simple-certificate-zxaa-6-y-b-ua-u-10.png') }}" alt="Certificate" class="certificate-img">
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-3">
                        <a href="{{ route('certificate.download') }}" class="custom-button">تحميل الشهادة</a>
                        <button class="custom-button" data-bs-toggle="modal" data-bs-target="#shareModal">مشاركة الشهادة</button>
                    </div>
                </div>
            </div>

            <!-- Share Modal -->
            <div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content share-modal">
                        <div class="modal-header">
                            <h5 class="modal-title text-primary" id="shareModalLabel">مشاركة الشهادة</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <a href="https://wa.me/?text={{ urlencode('تحقق من شهادتي: ' . route('certificate.view')) }}" class="btn btn-share" target="_blank">مشاركة على واتساب</a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('certificate.view')) }}" class="btn btn-share" target="_blank">مشاركة على فيسبوك</a>
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(route('certificate.view')) }}" class="btn btn-share" target="_blank">مشاركة على لينكدان</a>
                            <button class="btn btn-share" onclick="navigator.clipboard.writeText('{{ route('certificate.view') }}'); alert('تم نسخ الرابط!')">نسخ الرابط</button>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-warning text-center" role="alert">
                يجب عليك إكمال جميع الدروس، الاختبارات، والأسئلة للحصول على شهادتك!
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.custom-button').forEach(button => {
            button.addEventListener('click', () => {
                if (button.getAttribute('data-bs-toggle')) return;
                alert('تم الضغط على الزر! يرجى تفعيل الوظيفة في الـ Controller أو JavaScript.');
            });
        });
    </script>
@endsection






