@extends('layouts.app')

@section('title', __('lang.title'))

@section('content')
    <link rel="stylesheet" href="{{ asset('css/plan-selection.css') }}">
    
    <div class="container py-4" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
        <div class="plan-selection-container">
            <!-- Header Section -->
            <div class="plan-header text-center mb-5">
                <div class="plan-icon mb-3">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h1 class="plan-title">{{ __('lang.choose_your_plan') }}</h1>
                <p class="plan-subtitle">{{ __('lang.subtitle') }}</p>
            </div>

            <!-- PMI ATPs Recommendation Section -->
            <div class="recommendation-section mb-5">
                <div class="recommendation-card">
                    <div class="recommendation-header">
                        <i class="fas fa-lightbulb recommendation-icon"></i>
                        <h3>{{ __('lang.pmi_recommendation') }}</h3>
                    </div>
                    <div class="recommendation-content">
                        <p class="recommendation-text">{{ __('lang.pmi_recommendation_text') }}</p>
                        <div class="recommendation-details">
                            <div class="recommendation-item">
                                <i class="fas fa-clock"></i>
                                <span>{{ __('lang.experienced_learners') }}</span>
                                <strong>{{ __('lang.6_8_weeks') }}</strong>
                            </div>
                            <div class="recommendation-item">
                                <img class="logo-img" src="{{asset('images/Sprint_Skills_logo.png')}}" alt="logo">
                                <span>{{ __('lang.beginners') }}</span>
                                <strong>{{ __('lang.8_10_weeks') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Plan Options -->
            <div class="plan-options">
                <form action="{{ route('student.plan.store') }}" method="POST" id="planForm">
                    @csrf
                    
                    <div class="row g-4">
                        <!-- 10 Weeks Plan (Experienced) -->
                        <div class="col-md-6">
                            <div class="plan-option-card" data-plan="10_weeks">
                                <div class="plan-option-header">
                                    <div class="plan-option-icon">
                                        <i class="fas fa-rocket"></i>
                                    </div>
                                    <h3>{{ __('lang.experienced_title') }}</h3>
                                    <p class="plan-option-subtitle">{{ __('lang.experienced_subtitle') }}</p>
                                </div>
                                
                                <div class="plan-option-details">
                                    <div class="plan-duration">
                                        <i class="fas fa-calendar"></i>
                                        <span>{{ __('lang.8_10_weeks') }}</span>
                                    </div>
                                    <div class="plan-features">
                                        <div class="feature-item">
                                            <i class="fas fa-check"></i>
                                            <span>{{ __('lang.fast_track') }}</span>
                                        </div>
                                        <div class="feature-item">
                                            <i class="fas fa-check"></i>
                                            <span>{{ __('lang.intensive_practice') }}</span>
                                        </div>
                                        <div class="feature-item">
                                            <i class="fas fa-check"></i>
                                            <span>{{ __('lang.focused_review') }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="plan-option-footer">
                                    <input type="radio" name="plan_type" value="10_weeks" id="10_weeks" class="plan-radio">
                                    <label for="10_weeks" class="plan-radio-label">
                                        {{ __('lang.select_plan') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- 6 Weeks Plan (Beginner) -->
                        <div class="col-md-6">
                            <div class="plan-option-card" data-plan="6_weeks">
                                <div class="plan-option-header">
                                    <div class="plan-option-icon">
                                        <i class="fas fa-seedling"></i>
                                    </div>
                                    <h3>{{ __('lang.beginner_title') }}</h3>
                                    <p class="plan-option-subtitle">{{ __('lang.beginner_subtitle') }}</p>
                                </div>
                                
                                <div class="plan-option-details">
                                    <div class="plan-duration">
                                        <i class="fas fa-calendar"></i>
                                        <span>{{ __('lang.6_8_weeks') }}</span>
                                    </div>
                                    <div class="plan-features">
                                        <div class="feature-item">
                                            <i class="fas fa-check"></i>
                                            <span>{{ __('lang.comprehensive_learning') }}</span>
                                        </div>
                                        <div class="feature-item">
                                            <i class="fas fa-check"></i>
                                            <span>{{ __('lang.gradual_progress') }}</span>
                                        </div>
                                        <div class="feature-item">
                                            <i class="fas fa-check"></i>
                                            <span>{{ __('lang.extra_support') }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="plan-option-footer">
                                    <input type="radio" name="plan_type" value="6_weeks" id="6_weeks" class="plan-radio">
                                    <label for="6_weeks" class="plan-radio-label">
                                        {{ __('lang.select_plan') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Custom Plan Option -->
                    <div class="custom-plan-section mt-4">
                        <div class="custom-plan-card">
                            <div class="custom-plan-header">
                                <h3>{{ __('lang.custom_plan') }}</h3>
                                <p>{{ __('lang.custom_plan_description') }}</p>
                            </div>
                            
                            <div class="custom-plan-form">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="start_date" class="form-label">{{ __('lang.start_date') }}</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date" 
                                               value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="end_date" class="form-label">{{ __('lang.end_date') }}</label>
                                        <input type="date" class="form-control" id="end_date" name="end_date" 
                                               value="{{ date('Y-m-d', strtotime('+8 weeks')) }}" min="{{ date('Y-m-d', strtotime('+1 week')) }}">
                                    </div>
                                </div>
                                
                                <div class="custom-plan-footer mt-3">
                                    <input type="radio" name="plan_type" value="custom" id="custom" class="plan-radio">
                                    <label for="custom" class="plan-radio-label">
                                        {{ __('lang.select_custom_plan') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="plan-submit-section text-center mt-5">
                        <button type="submit" class="btn btn-primary btn-lg plan-submit-btn" disabled>
                            <i class="fas fa-arrow-right"></i>
                            {{ __('lang.start_my_plan') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const planCards = document.querySelectorAll('.plan-option-card');
            const customPlanCard = document.querySelector('.custom-plan-card');
            const planRadios = document.querySelectorAll('input[name="plan_type"]');
            const submitBtn = document.querySelector('.plan-submit-btn');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            // Handle plan card selection
            planCards.forEach(card => {
                card.addEventListener('click', function() {
                    const planType = this.dataset.plan;
                    const radio = document.getElementById(planType);
                    radio.checked = true;
                    updateSelection();
                });
            });

            // Handle custom plan selection
            customPlanCard.addEventListener('click', function() {
                const customRadio = document.getElementById('custom');
                customRadio.checked = true;
                updateSelection();
            });

            // Handle radio button changes
            planRadios.forEach(radio => {
                radio.addEventListener('change', updateSelection);
            });

            // Update visual selection
            function updateSelection() {
                // Remove active class from all cards
                planCards.forEach(card => card.classList.remove('active'));
                customPlanCard.classList.remove('active');

                // Add active class to selected card
                const selectedRadio = document.querySelector('input[name="plan_type"]:checked');
                if (selectedRadio) {
                    if (selectedRadio.value === 'custom') {
                        customPlanCard.classList.add('active');
                    } else {
                        const selectedCard = document.querySelector(`[data-plan="${selectedRadio.value}"]`);
                        if (selectedCard) {
                            selectedCard.classList.add('active');
                        }
                    }
                    submitBtn.disabled = false;
                } else {
                    submitBtn.disabled = true;
                }
            }

            // Validate custom plan dates
            function validateCustomDates() {
                const startDate = new Date(startDateInput.value);
                const endDate = new Date(endDateInput.value);
                const minDuration = 7; // Minimum 1 week
                const maxDuration = 84; // Maximum 12 weeks

                const diffTime = endDate - startDate;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                if (diffDays < minDuration) {
                    endDateInput.setCustomValidity('{{ __("lang.min_duration_error") }}');
                } else if (diffDays > maxDuration) {
                    endDateInput.setCustomValidity('{{ __("lang.max_duration_error") }}');
                } else {
                    endDateInput.setCustomValidity('');
                }
            }

            startDateInput.addEventListener('change', validateCustomDates);
            endDateInput.addEventListener('change', validateCustomDates);

            // Initialize
            updateSelection();
        });
    </script>
@endsection 