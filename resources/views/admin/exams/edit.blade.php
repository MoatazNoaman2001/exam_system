@extends('layouts.admin')

@section('title', __('exams.edit.page_title'))

@section('page-title', __('exams.edit.page_title'))

@section('content')

<link rel="stylesheet" href="{{ asset('css/exam-create.css') }}">
<div class="exam-create-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <h1 class="page-title">{{ __('exams.edit.page_title') }}</h1>
                <div class="breadcrumb">
                    <span class="exam-id">{{ __('exams.edit.exam_id') }}: {{ $exam->id }}</span>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.exams') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}"></i>
                    {{ __('exams.edit.back_to_exams') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="alert-header">
                <i class="fas fa-exclamation-triangle"></i>
                <h6 class="alert-title">{{ __('exams.edit.errors.validation_title') }}</h6>
            </div>
            <ul class="alert-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('exams.edit.close') }}"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="alert-header">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('exams.edit.close') }}"></button>
        </div>
    @endif

    <!-- Main Form -->
    <form method="POST" action="{{ route('admin.exams.update', $exam->id) }}" id="examForm" class="exam-form" novalidate>
        @csrf
        @method('PUT')

        <!-- Basic Information Card -->
        <div class="form-card">
            <div class="card-header">
                <div class="card-header-content">
                    <i class="fas fa-info-circle"></i>
                    <h3 class="card-title">{{ __('exams.edit.basic_information') }}</h3>
                </div>
            </div>
            <div class="card-body">
                <!-- Bilingual Titles -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="title_en" class="form-label required">
                            {{ __('exams.edit.fields.title_en') }}
                        </label>
                        <input type="text" 
                               class="form-control @error('title_en') is-invalid @enderror"
                               id="title_en" 
                               name="title_en" 
                               value="{{ old('title_en', $exam->text) }}"
                               placeholder="{{ __('exams.edit.placeholders.title_en') }}" 
                               required>
                        @error('title_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="title_ar" class="form-label required">
                            {{ __('exams.edit.fields.title_ar') }}
                        </label>
                        <input type="text" 
                               class="form-control @error('title_ar') is-invalid @enderror"
                               id="title_ar" 
                               name="title_ar" 
                               value="{{ old('title_ar', $exam->{'text-ar'}) }}"
                               placeholder="{{ __('exams.edit.placeholders.title_ar') }}" 
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
                            {{ __('exams.edit.fields.description_en') }}
                        </label>
                        <textarea class="form-control @error('description_en') is-invalid @enderror" 
                                  id="description_en" 
                                  name="description_en"
                                  rows="4" 
                                  placeholder="{{ __('exams.edit.placeholders.description_en') }}">{{ old('description_en', $exam->description) }}</textarea>
                        @error('description_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description_ar" class="form-label">
                            {{ __('exams.edit.fields.description_ar') }}
                        </label>
                        <textarea class="form-control @error('description_ar') is-invalid @enderror" 
                                  id="description_ar" 
                                  name="description_ar"
                                  rows="4" 
                                  placeholder="{{ __('exams.edit.placeholders.description_ar') }}" 
                                  dir="rtl">{{ old('description_ar', $exam->{'description-ar'}) }}</textarea>
                        @error('description_ar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Duration -->
                <div class="form-row">
                    <div class="form-group form-group-quarter">
                        <label for="duration" class="form-label required">
                            {{ __('exams.edit.fields.duration') }}
                        </label>
                        <div class="input-group">
                            <input type="number" 
                                   class="form-control @error('duration') is-invalid @enderror"
                                   id="duration" 
                                   name="duration" 
                                   value="{{ old('duration', $exam->time) }}" 
                                   min="1"
                                   max="300" 
                                   placeholder="30" 
                                   required>
                            <span class="input-group-text">{{ __('exams.edit.minutes') }}</span>
                        </div>
                        <div class="form-help">{{ __('exams.edit.hints.duration') }}</div>
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
                    <h3 class="card-title">{{ __('exams.edit.questions_section') }}</h3>
                    <span class="questions-count">{{ count($exam->examQuestions) }} {{ __('exams.edit.questions') }}</span>
                </div>
                <div class="card-actions">
                    <button type="button" class="btn btn-success" id="add-question">
                        <i class="fas fa-plus"></i>
                        {{ __('exams.edit.buttons.add_question') }}
                    </button>
                </div>
            </div>
            <div class="card-body">
                <p class="section-help">{{ __('exams.edit.questions_help') }}</p>
                
                <!-- Questions Container -->
                <div id="questions-container" class="questions-container">
                    <!-- Pre-populate existing questions -->
                    @foreach($exam->examQuestions as $questionIndex => $question)
                        <div class="question-card" data-question-index="{{ $questionIndex }}">
                            <div class="question-header">
                                <div class="question-info">
                                    <h4 class="question-title">
                                        <span class="question-number">{{ $questionIndex + 1 }}</span>
                                        {{ __('exams.edit.question') }}
                                    </h4>
                                </div>
                                <div class="question-actions">
                                    <button type="button" class="btn btn-sm btn-danger remove-question">
                                        <i class="fas fa-trash"></i>
                                        {{ __('exams.edit.buttons.remove_question') }}
                                    </button>
                                </div>
                            </div>

                            <div class="question-body">
                                <!-- Question Type and Points Row -->
                                <div class="form-row">
                                    <div class="form-group form-group-half">
                                        <label for="questions[{{ $questionIndex }}][type]" class="form-label required">
                                            {{ __('exams.edit.fields.question_type') }}
                                        </label>
                                        <select class="form-control question-type @error('questions.'.$questionIndex.'.type') is-invalid @enderror" 
                                                name="questions[{{ $questionIndex }}][type]" required>
                                            <option value="">{{ __('exams.edit.select_type') }}</option>
                                            <option value="single_choice" {{ old('questions.'.$questionIndex.'.type', $question->type) == 'single_choice' ? 'selected' : '' }}>
                                                {{ __('exams.edit.single_choice') }}
                                            </option>
                                            <option value="multiple_choice" {{ old('questions.'.$questionIndex.'.type', $question->type) == 'multiple_choice' ? 'selected' : '' }}>
                                                {{ __('exams.edit.multiple_choice') }}
                                            </option>
                                        </select>
                                        @error('questions.'.$questionIndex.'.type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group form-group-quarter">
                                        <label for="questions[{{ $questionIndex }}][points]" class="form-label required">
                                            {{ __('exams.edit.fields.points') }}
                                        </label>
                                        <input type="number" 
                                               class="form-control question-points @error('questions.'.$questionIndex.'.points') is-invalid @enderror"
                                               name="questions[{{ $questionIndex }}][points]" 
                                               value="{{ old('questions.'.$questionIndex.'.points', $question->marks) }}" 
                                               min="1" 
                                               max="100" 
                                               placeholder="1" 
                                               required>
                                        @error('questions.'.$questionIndex.'.points')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Question Text -->
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="questions[{{ $questionIndex }}][text_en]" class="form-label required">
                                            {{ __('exams.edit.fields.question_text_en') }}
                                        </label>
                                        <textarea class="form-control question-text-en @error('questions.'.$questionIndex.'.text_en') is-invalid @enderror"
                                                  name="questions[{{ $questionIndex }}][text_en]" 
                                                  rows="3" 
                                                  placeholder="{{ __('exams.edit.placeholders.question_text_en') }}" 
                                                  required>{{ old('questions.'.$questionIndex.'.text_en', $question->question) }}</textarea>
                                        @error('questions.'.$questionIndex.'.text_en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="questions[{{ $questionIndex }}][text_ar]" class="form-label required">
                                            {{ __('exams.edit.fields.question_text_ar') }}
                                        </label>
                                        <textarea class="form-control question-text-ar @error('questions.'.$questionIndex.'.text_ar') is-invalid @enderror"
                                                  name="questions[{{ $questionIndex }}][text_ar]" 
                                                  rows="3" 
                                                  placeholder="{{ __('exams.edit.placeholders.question_text_ar') }}" 
                                                  dir="rtl" 
                                                  required>{{ old('questions.'.$questionIndex.'.text_ar', $question->{'question-ar'}) }}</textarea>
                                        @error('questions.'.$questionIndex.'.text_ar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Options Section -->
                                <div class="options-section">
                                    <div class="options-header">
                                        <h5 class="options-title">{{ __('exams.edit.answer_options') }}</h5>
                                        <button type="button" class="btn btn-sm btn-outline-primary add-option">
                                            <i class="fas fa-plus"></i>
                                            {{ __('exams.edit.buttons.add_option') }}
                                        </button>
                                    </div>

                                    <div class="options-container {{ $question->type === 'single_choice' ? 'single-choice-mode' : 'multiple-choice-mode' }}">
                                        @foreach($question->answers as $optionIndex => $answer)
                                            <div class="option-card">
                                                <div class="option-header">
                                                    <span class="option-number">{{ $optionIndex + 1 }}</span>
                                                    <div class="option-actions">
                                                        <div class="form-check">
                                                            @if($question->type === 'single_choice')
                                                                <input type="radio" 
                                                                       class="form-check-input is-correct" 
                                                                       name="questions[{{ $questionIndex }}][correct_answer]" 
                                                                       value="{{ $optionIndex }}"
                                                                       id="correct-option-{{ $questionIndex }}-{{ $optionIndex }}"
                                                                       {{ $answer->is_correct ? 'checked' : '' }}>
                                                            @else
                                                                <input type="checkbox" 
                                                                       class="form-check-input is-correct" 
                                                                       name="questions[{{ $questionIndex }}][options][{{ $optionIndex }}][is_correct]" 
                                                                       value="1"
                                                                       id="correct-option-{{ $questionIndex }}-{{ $optionIndex }}"
                                                                       {{ $answer->is_correct ? 'checked' : '' }}>
                                                            @endif
                                                            <label class="form-check-label" for="correct-option-{{ $questionIndex }}-{{ $optionIndex }}">
                                                                {{ __('exams.edit.correct') }}
                                                            </label>
                                                        </div>
                                                        <button type="button" class="btn btn-sm btn-outline-danger remove-option">
                                                            <i class="fas fa-trash"></i>
                                                            {{ __('exams.edit.buttons.remove_option') }}
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="option-body">
                                                    <!-- Option Text -->
                                                    <div class="form-row">
                                                        <div class="form-group">
                                                            <label class="form-label">{{ __('exams.edit.fields.option_text_en') }}</label>
                                                            <input type="text" 
                                                                   class="form-control option-text-en"
                                                                   name="questions[{{ $questionIndex }}][options][{{ $optionIndex }}][text_en]" 
                                                                   value="{{ old('questions.'.$questionIndex.'.options.'.$optionIndex.'.text_en', $answer->answer) }}"
                                                                   placeholder="{{ __('exams.edit.placeholders.option_text_en') }}" 
                                                                   required>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="form-label">{{ __('exams.edit.fields.option_text_ar') }}</label>
                                                            <input type="text" 
                                                                   class="form-control option-text-ar"
                                                                   name="questions[{{ $questionIndex }}][options][{{ $optionIndex }}][text_ar]" 
                                                                   value="{{ old('questions.'.$questionIndex.'.options.'.$optionIndex.'.text_ar', $answer->{'answer-ar'}) }}"
                                                                   placeholder="{{ __('exams.edit.placeholders.option_text_ar') }}" 
                                                                   dir="rtl" 
                                                                   required>
                                                        </div>
                                                    </div>

                                                    <!-- Reason Text -->
                                                    <div class="form-row">
                                                        <div class="form-group">
                                                            <label class="form-label">{{ __('exams.edit.fields.reason_en') }}</label>
                                                            <textarea class="form-control reason-text-en"
                                                                      name="questions[{{ $questionIndex }}][options][{{ $optionIndex }}][reason]" 
                                                                      rows="2"
                                                                      maxlength="2000"
                                                                      placeholder="{{ __('exams.edit.placeholders.reason_en') }}">{{ old('questions.'.$questionIndex.'.options.'.$optionIndex.'.reason', $answer->reason) }}</textarea>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="form-label">{{ __('exams.edit.fields.reason_ar') }}</label>
                                                            <textarea class="form-control reason-text-ar"
                                                                      name="questions[{{ $questionIndex }}][options][{{ $optionIndex }}][reason_ar]" 
                                                                      rows="2"
                                                                      maxlength="2000"
                                                                      placeholder="{{ __('exams.edit.placeholders.reason_ar') }}" 
                                                                      dir="rtl">{{ old('questions.'.$questionIndex.'.options.'.$optionIndex.'.reason_ar', $answer->{'reason-ar'}) }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Submit Section -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save"></i>
                {{ __('exams.edit.buttons.update_exam') }}
            </button>
            <a href="{{ route('admin.exams') }}" class="btn btn-secondary btn-lg">
                <i class="fas fa-times"></i>
                {{ __('exams.edit.buttons.cancel') }}
            </a>
        </div>
    </form>
</div>

@include('admin.exams.partials.question-templates')

<script src="{{ asset('js/exam-create.js') }}" defer></script>
<script>
    // Pass localized strings to JavaScript
    window.examCreateTranslations = {
        question: '{{ __('exams.edit.question') }}',
        removeQuestion: '{{ __('exams.edit.buttons.remove_question') }}',
        addOption: '{{ __('exams.edit.buttons.add_option') }}',
        removeOption: '{{ __('exams.edit.buttons.remove_option') }}',
        correct: '{{ __('exams.edit.correct') }}',
        questionNumber: '{{ __('exams.edit.question_number') }}',
        noQuestionsTitle: '{{ __('exams.edit.empty_state.title') }}',
        noQuestionsText: '{{ __('exams.edit.empty_state.text') }}',
        validationErrors: {
            minOptions: '{{ __('exams.edit.validation.min_options') }}',
            minQuestions: '{{ __('exams.edit.validation.min_questions') }}',
            requiredField: '{{ __('exams.edit.validation.required_field') }}',
            singleCorrectAnswer: '{{ __('exams.edit.validation.single_correct_answer') }}',
            atLeastOneCorrect: '{{ __('exams.edit.validation.at_least_one_correct') }}'
        }
    };

    // Initialize the form with existing data
    document.addEventListener('DOMContentLoaded', function() {
        // Update question count for existing questions
        const existingQuestions = document.querySelectorAll('.question-card');
        if (window.ExamQuestionManager && existingQuestions.length > 0) {
            // Set the question count to match existing questions
            const manager = new ExamQuestionManager();
            manager.questionCount = existingQuestions.length;
            
            // Setup event listeners for existing questions
            existingQuestions.forEach(questionCard => {
                manager.setupQuestionEvents(questionCard);
                
                // Setup character counters for existing options
                const options = questionCard.querySelectorAll('.option-card');
                options.forEach(option => {
                    manager.setupCharacterCounters(option);
                });
            });
        }
    });
</script>
@endsection 