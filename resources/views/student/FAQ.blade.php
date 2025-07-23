@extends('layouts.app')

@section('title', __('lang.title'))

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link rel="stylesheet" href="{{ asset('css/FAQ.css') }}">

<div class="container py-5">
    <!-- Header Section -->
    <div class="faq-header text-center">
        <h1 class="fw-bold mb-3">{{ __('lang.title') }}</h1>
        <p class="lead mb-4">{{ __('lang.subtitle') }}</p>
        
        <div class="search-container">
            <div class="input-group">
                <input type="text" class="form-control search-input" id="faqSearch" placeholder="{{ __('faq.search_placeholder') }}">
                <button class="btn btn-light text-primary" type="button" style="border-radius: 0 50px 50px 0">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- FAQ List -->
    <div id="faqList">
        @for ($i = 1; $i <= 6; $i++)
            <div class="faq-card">
                <div class="faq-question" onclick="toggleFAQ({{ $i }})">
                    <span>{{ $i }}. {{ __('lang.q' . $i) }}</span>
                    <i class="bi bi-chevron-down" id="icon-{{ $i }}"></i>
                </div>
                <div class="faq-answer" id="answer-{{ $i }}">
                    <ul class="answer-steps">
                        @foreach (__('lang.a' . $i) as $step)
                            <li>{{ $step }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endfor
    </div>

    <!-- No Results -->
    <div id="noResults" class="no-result-card d-none">
        <i class="bi bi-search display-4 text-primary mb-3"></i>
        <h4 class="text-primary mb-2">{{ __('lang.no_results.title') }}</h4>
        <p class="text-muted mb-4">{{ __('lang.no_results.subtitle') }}</p>
        <button id="resetSearch" class="btn btn-primary px-4">{{ __('lang.no_results.button') }}</button>
    </div>

    <!-- Help Section -->
    <div class="help-section text-center">
        <h3 class="text-primary mb-3">{{ __('lang.need_more_help') }}</h3>
        <p class="mb-4">{{ __('lang.support_available') }}</p>
        <a href="{{ route('student.contact.us') }}" class="btn btn-primary mx-2">
            <i class="bi bi-headset me-2"></i> {{ __('lang.contact_us') }}
        </a>
    </div>
</div>

<script>
    function toggleFAQ(index) {
        const icon = document.getElementById(`icon-${index}`);
        const answer = document.getElementById(`answer-${index}`);
        
        if (answer.classList.contains('show')) {
            answer.classList.remove('show');
            icon.classList.remove('rotate-icon');
        } else {
            document.querySelectorAll('.faq-answer').forEach(el => el.classList.remove('show'));
            document.querySelectorAll('.faq-card i').forEach(el => el.classList.remove('rotate-icon'));

            answer.classList.add('show');
            icon.classList.add('rotate-icon');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('faqSearch');
        const faqCards = document.querySelectorAll('.faq-card');
        const noResults = document.getElementById('noResults');
        const resetBtn = document.getElementById('resetSearch');

        searchInput.addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase();
            let anyVisible = false;

            faqCards.forEach(card => {
                const question = card.querySelector('.faq-question span').textContent.toLowerCase();
                const answer = card.querySelector('.faq-answer').textContent.toLowerCase();

                if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                    card.style.display = 'block';
                    anyVisible = true;
                } else {
                    card.style.display = 'none';
                }
            });

            noResults.classList.toggle('d-none', anyVisible);
        });

        resetBtn.addEventListener('click', function() {
            searchInput.value = '';
            faqCards.forEach(card => card.style.display = 'block');
            noResults.classList.add('d-none');
        });
    });
</script>
@endsection
