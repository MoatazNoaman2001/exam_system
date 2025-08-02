@extends('layouts.admin')

@section('title', __('exams.create.page_title'))

@section('page-title', __('exams.create.page_title'))

@section('content')

<link rel="stylesheet" href="{{ asset('css/exam-create.css') }}">
<div class="exam-create-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <h1 class="page-title">{{ __('exams.create.page_title') }}</h1>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.exams') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}"></i>
                    {{ __('exams.create.back_to_exams') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="alert-header">
                <i class="fas fa-exclamation-triangle"></i>
                <h6 class="alert-title">{{ __('exams.create.errors.validation_title') }}</h6>
            </div>
            <ul class="alert-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('exams.create.close') }}"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="alert-header">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('exams.create.close') }}"></button>
        </div>
    @endif

    <!-- Main Form -->
    <form method="POST" action="{{ route('admin.exams.store') }}" id="examForm" class="exam-form" novalidate>
        @csrf

        <!-- Basic Information Card -->
        <div class="form-card">
            <div class="card-header">
                <div class="card-header-content">
                    <i class="fas fa-info-circle"></i>
                    <h3 class="card-title">{{ __('exams.create.basic_information') }}</h3>
                </div>
            </div>
            <div class="card-body">
                <!-- Bilingual Titles -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="title_en" class="form-label required">
                            {{ __('exams.create.fields.title_en') }}
                        </label>
                        <input type="text" 
                               class="form-control @error('title_en') is-invalid @enderror"
                               id="title_en" 
                               name="title_en" 
                               value="{{ old('title_en') }}"
                               placeholder="{{ __('exams.create.placeholders.title_en') }}" 
                               required>
                        @error('title_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="title_ar" class="form-label required">
                            {{ __('exams.create.fields.title_ar') }}
                        </label>
                        <input type="text" 
                               class="form-control @error('title_ar') is-invalid @enderror"
                               id="title_ar" 
                               name="title_ar" 
                               value="{{ old('title_ar') }}"
                               placeholder="{{ __('exams.create.placeholders.title_ar') }}" 
                               dir="rtl" 
                               required>
                        @error('title_ar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Bilingual Descriptions -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="description_en" class="form-label">
                            {{ __('exams.create.fields.description_en') }}
                        </label>
                        <textarea class="form-control @error('description_en') is-invalid @enderror" 
                                  id="description_en" 
                                  name="description_en"
                                  rows="4" 
                                  placeholder="{{ __('exams.create.placeholders.description_en') }}">{{ old('description_en') }}</textarea>
                        @error('description_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description_ar" class="form-label">
                            {{ __('exams.create.fields.description_ar') }}
                        </label>
                        <textarea class="form-control @error('description_ar') is-invalid @enderror" 
                                  id="description_ar" 
                                  name="description_ar"
                                  rows="4" 
                                  placeholder="{{ __('exams.create.placeholders.description_ar') }}" 
                                  dir="rtl">{{ old('description_ar') }}</textarea>
                        @error('description_ar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Duration -->
                <div class="form-row">
                    <div class="form-group form-group-quarter">
                        <label for="duration" class="form-label required">
                            {{ __('exams.create.fields.duration') }}
                        </label>
                        <div class="input-group">
                            <input type="number" 
                                   class="form-control @error('duration') is-invalid @enderror"
                                   id="duration" 
                                   name="duration" 
                                   value="{{ old('duration', 30) }}" 
                                   min="1"
                                   max="300" 
                                   placeholder="30" 
                                   required>
                            <span class="input-group-text">{{ __('exams.create.minutes') }}</span>
                        </div>
                        <div class="form-help">{{ __('exams.create.hints.duration') }}</div>
                        @error('duration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Questions Section -->
        <div class="form-card">
            <div class="card-header">
                <div class="card-header-content">
                    <i class="fas fa-question-circle"></i>
                    <h3 class="card-title">{{ __('exams.create.questions_section') }}</h3>
                </div>
                <div class="card-actions">
                    <button type="button" class="btn btn-success" id="add-question">
                        <i class="fas fa-plus"></i>
                        {{ __('exams.create.buttons.add_question') }}
                    </button>
                </div>
            </div>
            <div class="card-body">
                <p class="section-help">{{ __('exams.create.questions_help') }}</p>
                
                <!-- Questions Container -->
                <div id="questions-container" class="questions-container">
                    <!-- Questions will be dynamically added here -->
                </div>
            </div>
        </div>

        <!-- Submit Section -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save"></i>
                {{ __('exams.create.buttons.create_exam') }}
            </button>
            <a href="{{ route('admin.exams') }}" class="btn btn-secondary btn-lg">
                <i class="fas fa-times"></i>
                {{ __('exams.create.buttons.cancel') }}
            </a>
        </div>
    </form>
</div>

@include('admin.exams.partials.question-templates')

<script src="{{ asset('js/exam-create.js') }}" defer></script>
<script>
    // Pass localized strings to JavaScript
    window.examCreateTranslations = {
        question: '{{ __('exams.create.question') }}',
        removeQuestion: '{{ __('exams.create.buttons.remove_question') }}',
        addOption: '{{ __('exams.create.buttons.add_option') }}',
        removeOption: '{{ __('exams.create.buttons.remove_option') }}',
        correct: '{{ __('exams.create.correct') }}',
        questionNumber: '{{ __('exams.create.question_number') }}',
        noQuestionsTitle: '{{ __('exams.create.empty_state.title') }}',
        noQuestionsText: '{{ __('exams.create.empty_state.text') }}',
        validationErrors: {
            minOptions: '{{ __('exams.create.validation.min_options') }}',
            minQuestions: '{{ __('exams.create.validation.min_questions') }}',
            requiredField: '{{ __('exams.create.validation.required_field') }}',
            singleCorrectAnswer: '{{ __('exams.create.validation.single_correct_answer') }}',
            atLeastOneCorrect: '{{ __('exams.create.validation.at_least_one_correct') }}'
        }
    };
</script>
@endsection
