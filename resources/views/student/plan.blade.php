@extends('layouts.app')

@section('title', 'plan')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/plan.css') }}">

    <div class="container py-4">
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
                        <div class="row justify-content-end align-items-center mb-3">
                            <div class="col-auto">
                                <h5 class="text-primary fw-bold mb-0">اختر مدة الخطة</h5>
                            </div>
                            <div class="col-auto">
                                <img src="{{ asset('vuesax-linear-arrow-right1.svg') }}" alt="Arrow" class="arrow-icon">
                            </div>
                        </div>

                        {{-- خيارات المدة --}}
                        <div class="card custom-card surface p-3">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="d-flex align-items-center gap-2">
                                        <input type="radio" name="plan_duration" value="30" {{ (old('plan_duration', $progress->plan_duration ?? '') == 30) ? 'checked' : '' }}>
                                        <span class="text-primary">شهر واحد</span>
                                    </label>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="d-flex align-items-center gap-2">
                                        <input type="radio" name="plan_duration" value="60" {{ (old('plan_duration', $progress->plan_duration ?? '') == 60) ? 'checked' : '' }}>
                                        <span class="text-primary">شهرين</span>
                                    </label>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="d-flex align-items-center gap-2">
                                        <input type="radio" name="plan_duration" value="90" {{ (old('plan_duration', $progress->plan_duration ?? '') == 90) ? 'checked' : '' }}>
                                        <span class="text-primary">3 شهور</span>
                                    </label>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="d-flex align-items-center gap-2">
                                        <input type="radio" name="plan_duration" value="0" {{ (old('plan_duration', $progress->plan_duration ?? '') == 0 && $progress->plan_end_date) ? 'checked' : '' }}>
                                        <span class="text-primary">مخصص</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- التواريخ المخصصة --}}
                        <div class="card custom-card surface p-3 mt-3" style="display: {{ isset($progress->plan_duration) && $progress->plan_duration == 0 ? 'block' : 'none' }}" id="custom-dates">
                            <div class="d-flex flex-column flex-md-row gap-3">
                                <div class="custom-date">
                                    <span class="text-secondary">من</span>
                                    <input type="date" name="start_date" value="{{ isset($progress->start_date) ? \Carbon\Carbon::parse($progress->start_date)->format('Y-m-d') : '' }}" id="start-date">
                                    <img src="{{ asset('vuesax-linear-calendar0.svg') }}" alt="Calendar" class="calendar-icon">
                                </div>
                                <div class="custom-date">
                                    <span class="text-secondary">إلى</span>
                                    <input type="date" name="end_date" value="{{ isset($progress->plan_end_date) ? \Carbon\Carbon::parse($progress->plan_end_date)->format('Y-m-d') : '' }}" id="end-date">
                                    <img src="{{ asset('vuesax-linear-calendar1.svg') }}" alt="Calendar" class="calendar-icon">
                                </div>
                            </div>
                        </div>

                        {{-- عرض عدد الدروس والأسئلة --}}
                        <div class="card dashed-card p-3 mt-3">
                            <p class="text-primary fw-medium mb-2">لإنهاء المحتوى خلال هذه المدة، تحتاج إلى دراسة:</p>
                            <p class="text-primary mb-1" id="weekly-lessons">📘 {{ $weeklyLessons ?? 0 }} دروس أسبوعيًا</p>
                            <p class="text-primary" id="weekly-questions">💡 حل {{ $weeklyQuestions ?? 0 }} سؤال تدريبي في الأسبوع</p>
                        </div>
                    </div>

                    {{-- زر الحفظ --}}
                    <button type="submit" class="button-plan text-white text-center fw-bold w-100 mt-4">حفظ وتحديث الخطة</button>

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

                weeklyLessons.textContent = `📘 ${lessons} دروس أسبوعيًا`;
                weeklyQuestions.textContent = `💡 حل ${questions} سؤال تدريبي في الأسبوع`;
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
