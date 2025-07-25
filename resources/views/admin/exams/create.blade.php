@extends('layouts.admin')

@section('content')

<link href="{{ asset('css/exam-create.css') }}" rel="stylesheet">
<div class="container-fluid">
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-200">{{ __('exams.create.page_title') }}</h1>
            <a href="{{ route('admin.exams') }}" class="btn btn-secondary shadow-sm">
                {{-- <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }} fa-sm me-1"></i> --}}
                {{ __('exams.create.back_to_exams') }}
            </a>
        </div>

        <!-- Alert Messages -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h6 class="alert-heading">{{ __('exams.create.errors.validation_title') }}</h6>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"
                    aria-label="{{ __('exams.create.close') }}"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"
                    aria-label="{{ __('exams.create.close') }}"></button>
            </div>
        @endif

        <!-- Main Form Card -->
        <div class="card shadow-lg border-0">


            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.exams.store') }}" id="examForm" novalidate>
                    @csrf

                    <!-- Basic Exam Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-info-circle me-2"></i>{{ __('exams.create.basic_information') }}
                            </h5>
                        </div>
                    </div>

                    <!-- Bilingual Titles -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('title_en') is-invalid @enderror"
                                    id="title_en" name="title_en" value="{{ old('title_en') }}"
                                    placeholder="{{ __('exams.create.placeholders.title_en') }}" required>
                                <label for="title_en">{{ __('exams.create.fields.title_en') }} <span
                                        class="text-danger">*</span></label>
                                @error('title_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('title_ar') is-invalid @enderror"
                                    id="title_ar" name="title_ar" value="{{ old('title_ar') }}"
                                    placeholder="{{ __('exams.create.placeholders.title_ar') }}" dir="rtl" required>
                                <label for="title_ar">{{ __('exams.create.fields.title_ar') }} <span
                                        class="text-danger">*</span></label>
                                @error('title_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Bilingual Descriptions -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <textarea class="form-control @error('description_en') is-invalid @enderror" id="description_en" name="description_en"
                                    style="height: 100px" placeholder="{{ __('exams.create.placeholders.description_en') }}">{{ old('description_en') }}</textarea>
                                <label for="description_en">{{ __('exams.create.fields.description_en') }}</label>
                                @error('description_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <textarea class="form-control @error('description_ar') is-invalid @enderror" id="description_ar" name="description_ar"
                                    style="height: 100px" placeholder="{{ __('exams.create.placeholders.description_ar') }}" dir="rtl">{{ old('description_ar') }}</textarea>
                                <label for="description_ar">{{ __('exams.create.fields.description_ar') }}</label>
                                @error('description_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Duration -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="number" class="form-control @error('duration') is-invalid @enderror"
                                    id="duration" name="duration" value="{{ old('duration', 30) }}" min="1"
                                    max="300" placeholder="{{ __('exams.create.placeholders.duration') }}" required>
                                <label for="duration">{{ __('exams.create.fields.duration') }} <span
                                        class="text-danger">*</span></label>
                                <div class="form-text">{{ __('exams.create.hints.duration') }}</div>
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Questions Section -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <h5 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-question-circle me-2"></i>{{ __('exams.create.questions_section') }}
                            </h5>
                            <p class="text-muted">{{ __('exams.create.questions_help') }}</p>
                        </div>
                    </div>

                    <!-- Questions Container -->
                    <div id="questions-container" class="mb-4">
                        <!-- Questions will be dynamically added here -->
                    </div>

                    <!-- Add Question Button -->
                    <div class="text-center mb-4">
                        <button type="button" class="btn btn-success btn-lg" id="add-question">
                            <i class="fas fa-plus me-2"></i>{{ __('exams.create.buttons.add_question') }}
                        </button>
                    </div>

                    <!-- Submit Section -->
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary btn-lg me-3">
                                <i class="fas fa-save me-2"></i>{{ __('exams.create.buttons.create_exam') }}
                            </button>
                            <a href="{{ route('admin.exams') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-times me-2"></i>{{ __('exams.create.buttons.cancel') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
</div>

@include('admin.exams.partials.question-templates')
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

<script src="{{ asset('js/exam-create.js') }}" defer></script>

@endsection
