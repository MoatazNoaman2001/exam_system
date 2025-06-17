@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('Create New Exam') }}</h1>
        <a href="{{ route('admin.exams') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> {{ __('Back') }}
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('Exam Details') }}</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.exams.store') }}" id="examForm">
                @csrf
                
                <!-- Bilingual Exam Details -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title_en">{{ __('Exam Title (English)') }}</label>
                            <input type="text" class="form-control @error('title_en') is-invalid @enderror" 
                                   id="title_en" name="title_en" value="{{ old('title_en') }}" required>
                            @error('title_en')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title_ar">{{ __('Exam Title (Arabic)') }} <span class="text-muted">(العنوان بالعربية)</span></label>
                            <input type="text" class="form-control @error('title_ar') is-invalid @enderror" 
                                   id="title_ar" name="title_ar" value="{{ old('title_ar') }}" required dir="rtl">
                            @error('title_ar')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="description_en">{{ __('Description (English)') }}</label>
                            <textarea class="form-control" id="description_en" name="description_en" rows="3">{{ old('description_en') }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="description_ar">{{ __('Description (Arabic)') }} <span class="text-muted">(الوصف بالعربية)</span></label>
                            <textarea class="form-control" id="description_ar" name="description_ar" rows="3" dir="rtl">{{ old('description_ar') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="duration">{{ __('Duration (minutes)') }}</label>
                    <input type="number" class="form-control @error('duration') is-invalid @enderror" 
                           id="duration" name="duration" value="{{ old('duration', 30) }}" min="1" required>
                    @error('duration')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <hr class="my-4">

                <!-- Questions Section -->
                <h4 class="mb-3">{{ __('Exam Questions') }}</h4>
                <div id="questions-container">
                    <!-- Questions will be added here dynamically -->
                </div>

                <button type="button" class="btn btn-success mb-3" id="add-question">
                    <i class="fas fa-plus"></i> {{ __('Add Question') }}
                </button>

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ __('Create Exam') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Question Template (Hidden) -->
<div id="question-template" class="d-none">
    <div class="card mb-4 question-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('Question') }} <span class="question-number">1</span></h5>
            <button type="button" class="btn btn-sm btn-danger remove-question">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('Question Text (English)') }}</label>
                        <textarea class="form-control question-text-en" name="questions[0][text_en]" rows="2" required></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('Question Text (Arabic)') }} <span class="text-muted">(النص بالعربية)</span></label>
                        <textarea class="form-control question-text-ar" name="questions[0][text_ar]" rows="2" dir="rtl" required></textarea>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>{{ __('Question Type') }}</label>
                <select class="form-control question-type" name="questions[0][type]" required>
                    <option value="single_choice">{{ __('Single Choice') }}</option>
                    <option value="multiple_choice">{{ __('Multiple Choice') }}</option>
                    <option value="true_false">{{ __('True/False') }}</option>
                </select>
            </div>

            <div class="form-group">
                <label>{{ __('Points') }}</label>
                <input type="number" class="form-control question-points" name="questions[0][points]" min="1" value="1" required>
            </div>

            <div class="options-container">
                <!-- Options will be added here based on question type -->
            </div>

            <button type="button" class="btn btn-sm btn-outline-primary add-option mt-2">
                <i class="fas fa-plus"></i> {{ __('Add Option') }}
            </button>
        </div>
    </div>
</div>

<!-- Option Template (Hidden) -->
<div id="option-template" style="display: none;">
    <div class="option-item mb-2">
        <div class="row">
            <div class="col-md-5">
                <input type="text" class="form-control option-text-en" name="questions[0][options][0][text_en]" placeholder="Option (English)" required>
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control option-text-ar" name="questions[0][options][0][text_ar]" placeholder="الخيار (العربية)" dir="rtl" required>
            </div>
            <div class="col-md-2 d-flex align-items-center">
                <div class="form-check me-2">
                    <input class="form-check-input is-correct" type="checkbox" name="questions[0][options][0][is_correct]">
                    <label class="form-check-label">{{ __('Correct') }}</label>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger remove-option">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    let questionCount = 0;
    const questionsContainer = document.getElementById('questions-container');
    const questionTemplate = document.getElementById('question-template');
    const optionTemplate = document.getElementById('option-template');
    const addQuestionBtn = document.getElementById('add-question');

    console.log(`questionTemplate: ${questionTemplate}, questionContainer: ${questionsContainer}`);
    

    // Add first question by default
    addQuestion();

    // Add question event
    addQuestionBtn.addEventListener('click', function() {
        addQuestion();
    });

    function addQuestion() {
        questionCount++;
        
        // Clone the inner card from the template
        const questionCard = questionTemplate.querySelector('.question-card');
        const newQuestion = questionCard.cloneNode(true);
        
        // Update question number
        newQuestion.querySelector('.question-number').textContent = questionCount;
        
        // Update all name attributes with the new index
        const questionElements = newQuestion.querySelectorAll('[name]');
        questionElements.forEach(el => {
            const name = el.getAttribute('name').replace(/questions\[\d+\]/g, `questions[${questionCount}]`);
            el.setAttribute('name', name);
        });

        // Add to container
        questionsContainer.appendChild(newQuestion);

        // Add event listeners for the new question
        setupQuestionEvents(newQuestion);

        // Trigger change to setup options based on type
        newQuestion.querySelector('.question-type').dispatchEvent(new Event('change'));
    }

    // Setup question events
    function setupQuestionEvents(questionElement) {
        // Remove question button
        const removeButton = questionElement.querySelector('.remove-question');
        if (removeButton) {
            removeButton.addEventListener('click', function() {
                questionElement.remove();
                updateQuestionNumbers();
            });
        }
        
        // Question type change
        const questionTypeSelect = questionElement.querySelector('.question-type');
        if (questionTypeSelect) {
            questionTypeSelect.addEventListener('change', function() {
                const optionsContainer = questionElement.querySelector('.options-container');
                optionsContainer.innerHTML = '';
                
                const questionType = this.value;
                const questionIndex = this.name.match(/questions\[(\d+)\]/)[1];
                
                if (questionType === 'true_false') {
                    // Add predefined true/false options
                    addOption(questionElement, questionIndex, 'True', 'صحيح', true);
                    addOption(questionElement, questionIndex, 'False', 'خطأ', false);
                    questionElement.querySelector('.add-option').style.display = 'none';
                } else {
                    // For single/multiple choice, add 2 empty options by default
                    addOption(questionElement, questionIndex);
                    addOption(questionElement, questionIndex);
                    questionElement.querySelector('.add-option').style.display = 'block';
                }
            });
        }
        
        // Add option button
        const addOptionButton = questionElement.querySelector('.add-option');
        if (addOptionButton) {
            addOptionButton.addEventListener('click', function() {
                const questionIndex = this.closest('.question-card').querySelector('.question-type')
                    .name.match(/questions\[(\d+)\]/)[1];
                addOption(questionElement, questionIndex);
            });
        }
    }

    // Add option to question
    function addOption(questionElement, questionIndex, textEn = '', textAr = '', isCorrect = false) {
        const optionsContainer = questionElement.querySelector('.options-container');
        const newOption = optionTemplate.cloneNode(true);
        newOption.style.display = 'block';
        
        // Update name attributes with the current question index and new option index
        const optionIndex = optionsContainer.querySelectorAll('.option-item').length;
        const optionElements = newOption.querySelectorAll('[name]');
        
        optionElements.forEach(el => {
            const name = el.getAttribute('name')
                .replace(/questions\[\d+\]/, `questions[${questionIndex}]`)
                .replace(/options\[\d+\]/, `options[${optionIndex}]`);
            el.setAttribute('name', name);
        });
        
        // Set initial values if provided
        if (textEn) {
            newOption.querySelector('.option-text-en').value = textEn;
        }
        if (textAr) {
            newOption.querySelector('.option-text-ar').value = textAr;
        }
        if (isCorrect) {
            newOption.querySelector('.is-correct').checked = true;
        }
        
        // Add remove option event
        newOption.querySelector('.remove-option').addEventListener('click', function() {
            newOption.remove();
        });
        
        optionsContainer.appendChild(newOption);
    }

    // Update question numbers after deletion
    function updateQuestionNumbers() {
        const questions = questionsContainer.querySelectorAll('.question-card');
        questions.forEach((question, index) => {
            question.querySelector('.question-number').textContent = index + 1;
            // Update name attributes for all elements in the question
            const questionElements = question.querySelectorAll('[name]');
            questionElements.forEach(el => {
                const name = el.getAttribute('name').replace(/questions\[\d+\]/g, `questions[${index + 1}]`);
                el.setAttribute('name', name);
            });
        });
    }
    });
</script>

<style>
    .question-card {
        border-left: 4px solid #4e73df;
    }
    .option-item {
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 5px;
    }
    [dir="rtl"] {
        text-align: right;
    }
</style>
@endsection