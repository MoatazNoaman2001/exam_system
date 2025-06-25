@extends('layouts.app')

@section('title', 'FAQ')

@section('content')
    <!-- ربط Bootstrap وخطوط Tajawal وأيقونات وjQuery -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/faq.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <div class="container my-5" dir="rtl">
        <div class="faq-container p-4 bg-white rounded-3 shadow-sm">
            <div class="faq-header mb-4">
                <h2 class="faq-title text-dark fw-bold">FAQ</h2>
            </div>

            <div class="input-group mb-4" style="max-width: 500px;">
                <input type="text" class="form-control faq-input border-end-0" id="searchInput" placeholder="ابحث عن سؤالك" aria-label="Search">
                <span class="input-group-text bg-white border-start-0">
                    <img src="{{ asset('images/search0.svg') }}" alt="Search" style="width: 1.5rem; height: 1.5rem;">
                </span>
            </div>

            <div class="faq-items" id="faqItems">
                @foreach($faqs as $faq)
                    <div class="faq-item mb-3">
                        <div class="faq-question d-flex justify-content-between align-items-center p-3 @if($loop->first) bg-soft-primary @else bg-light @endif rounded" data-bs-toggle="collapse" data-bs-target="#answer{{ $faq->id }}">
                            <span class="faq-text text-dark fw-medium">{{ $faq->question }}</span>
                            <span class="faq-icon"><i class="bi bi-plus"></i></span>
                        </div>
                        <div class="collapse" id="answer{{ $faq->id }}">
                            <div class="faq-answer p-3 bg-light text-secondary">
                                {{ $faq->answer }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#searchInput').on('keyup', function() {
                var query = $(this).val();
                $.ajax({
                    url: '{{ route("faq.search") }}',
                    method: 'GET',
                    data: { search: query },
                    success: function(data) {
                        $('#faqItems').html(data);
                    }
                });
            });

            // تحديث الأيقونات عند النقر
            document.querySelectorAll('.faq-question').forEach(item => {
                item.addEventListener('click', () => {
                    const icon = item.querySelector('.faq-icon i');
                    const collapse = new bootstrap.Collapse(document.querySelector(item.getAttribute('data-bs-target')));
                    if (collapse._element.classList.contains('show')) {
                        icon.classList.remove('bi-dash');
                        icon.classList.add('bi-plus');
                    } else {
                        icon.classList.remove('bi-plus');
                        icon.classList.add('bi-dash');
                    }
                });
            });
        });
    </script>
@endsection