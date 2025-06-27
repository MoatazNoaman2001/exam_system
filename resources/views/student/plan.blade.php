@extends('layouts.app')

@section('title', __('plan.title'))

@section('content')
    <link rel="stylesheet" href="{{ asset('css/plan.css') }}">
    <div class="container py-4" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
        <div class="mobile-plan-setup-custom">
            <form action="{{ route('plan.update') }}" method="POST">
                @csrf
                <fieldset @if(!Auth::check()) disabled @endif>

                    @if(session('success'))
                        <div id="custom-success" class="custom-success fade-in">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(!Auth::check() && session('error'))
                        <div class="alert alert-warning">{{ session('error') }}</div>
                    @endif

                    <div class="mobile-plan-setup-custom__main-content">
                        <div class="row justify-content-start align-items-center mb-3">
                            <div class="col-auto">
                                <img src="{{ asset('images/arrow-right.png') }}" alt="{{ __('plan.arrow_alt') }}" class="arrow-icon">
                            </div>
                            <div class="col-auto">
                                <h5 class="text-primary fw-bold mb-0">{{ __('plan.choose_duration') }}</h5>
                            </div>
                        </div>

                        {{-- Duration Options --}}
                        <div class="card custom-card surface p-3">
                            <div class="row g-3">
                                @foreach ([
                                    30 => 'duration_1_month',
                                    60 => 'duration_2_months',
                                    90 => 'duration_3_months',
                                    0 => 'duration_custom',
                                ] as $value => $translationKey)
                                    <div class="col-12 col-md-6">
                                        <label class="d-flex align-items-center gap-2">
                                            <input type="radio" name="plan_duration" value="{{ $value }}" 
                                                {{ (old('plan_duration', $progress->plan_duration ?? '') == $value) ? 'checked' : '' }}>
                                            <span class="text-primary">{{ __('plan.' . $translationKey) }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Custom Dates --}}
                        <div class="card custom-card surface p-3 mt-3" style="display: {{ isset($progress->plan_duration) && $progress->plan_duration == 0 ? 'block' : 'none' }};" id="custom-dates">
                            <div class="d-flex flex-column flex-md-row gap-3">
                                <div class="custom-date" style="position: relative;">
                                    <span class="text-secondary">{{ __('plan.from') }}</span>
                                    <input type="date" name="start_date"
                                        value="{{ isset($progress->start_date) ? \Carbon\Carbon::parse($progress->start_date)->format('Y-m-d') : '' }}"
                                        id="start-date"
                                        style="border: none; outline: none; padding: 8px 12px; border-radius: 8px; background-color: #f5f5f5; font-family: 'Tajawal', sans-serif;">
                                </div>

                                <div class="custom-date" style="position: relative;">
                                    <span class="text-secondary">{{ __('plan.to') }}</span>
                                    <input type="date" name="end_date"
                                        value="{{ isset($progress->plan_end_date) ? \Carbon\Carbon::parse($progress->plan_end_date)->format('Y-m-d') : '' }}"
                                        id="end-date"
                                        style="border: none; outline: none; padding: 8px 12px; border-radius: 8px; background-color: #f5f5f5; font-family: 'Tajawal', sans-serif;">
                                </div>
                            </div>
                        </div>

                        {{-- Display Lessons and Questions --}}
                        <div class="card dashed-card p-3 mt-3">
                            <p class="text-primary fw-medium mb-2">{{ __('plan.study_content_info') }}</p>
                            <p class="text-primary mb-1" id="weekly-lessons">📘 {{ $weeklyLessons ?? 0 }} {{ __('plan.lessons_per_week') }}</p>
                            <p class="text-primary" id="weekly-questions">💡 {{ __('plan.solve_questions') }} {{ $weeklyQuestions ?? 0 }} {{ __('plan.per_week') }}</p>
                        </div>
                    </div>

                    {{-- Save Button --}}
                    <button type="submit" class="button-plan text-white text-center fw-bold w-100 mt-4">{{ __('plan.save_update') }}</button>

                </fieldset>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const planOptions = document.querySelectorAll('input[name="plan_duration"]');
            const customDates = document.getElementById('custom-dates');
            const startDateInput = document.getElementById('start-date');
            const endDateInput = document.getElementById('end-date');
            const weeklyLessons = document.getElementById('weekly-lessons');
            const weeklyQuestions = document.getElementById('weekly-questions');
            const container = document.querySelector('.container');
            const customSuccess = document.getElementById('custom-success');

            planOptions.forEach(option => {
                option.addEventListener('change', function () {
                    customDates.style.display = this.value === '0' ? 'block' : 'none';
                    calculatePlan();
                });
            });

            function calculatePlan() {
                let startDate = new Date(startDateInput.value || new Date());
                let endDate = new Date(endDateInput.value || new Date(startDate.getTime() + (parseInt(document.querySelector('input[name="plan_duration"]:checked')?.value || 30) * 24 * 60 * 60 * 1000)));

                if (startDate > endDate) [startDate, endDate] = [endDate, startDate];

                const diffDays = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
                const weeks = Math.max(1, Math.ceil(diffDays / 7));

                const lessons = Math.ceil(40 / weeks);
                const questions = Math.ceil(300 / weeks);

                weeklyLessons.textContent = `📘 ${lessons} {{ __('plan.lessons_per_week') }}`;
                weeklyQuestions.textContent = `💡 {{ __('plan.solve_questions') }} ${questions} {{ __('plan.per_week') }}`;
            }

            planOptions.forEach(option => option.addEventListener('change', calculatePlan));
            startDateInput.addEventListener('change', calculatePlan);
            endDateInput.addEventListener('change', calculatePlan);

            calculatePlan();

            if (customSuccess) {
                container.style.opacity = '0.5';
                setTimeout(() => {
                    customSuccess.classList.add('fade-out');
                    setTimeout(() => {
                        customSuccess.style.display = 'none';
                        container.style.opacity = '1';
                    }, 500);
                }, 10000);
            }
        });
    </script>
@endsection
