@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('Edit Exam') }}</h1>
        <a href="{{ route('admin.exams') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> {{ __('Back') }}
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('Exam Details') }}</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.exams.update', $exam->id) }}" id="examForm">
                @csrf
                @method('PUT')
                
                <!-- Bilingual Exam Details -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title_en">{{ __('Exam Title (English)') }}</label>
                            <input type="text" class="form-control @error('title_en') is-invalid @enderror" 
                                   id="title_en" name="title_en" value="{{ $exam->text }}" required>
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
                                   id="title_ar" name="title_ar" value="{{ old('text-ar', $exam->{'text-ar'}) }}" required dir="rtl">
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
                            <textarea class="form-control" id="description_en" name="description_en" rows="3">{{ old('description', $exam->description) }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="description_ar">{{ __('Description (Arabic)') }} <span class="text-muted">(الوصف بالعربية)</span></label>
                            <textarea class="form-control" id="description_ar" name="description_ar" rows="3" dir="rtl">{{ old('description-ar', $exam['description-ar'] ) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="duration">{{ __('Duration (minutes)') }}</label>
                    <input type="number" class="form-control @error('duration') is-invalid @enderror" 
                           id="duration" name="duration" value="{{ old('time', $exam->time) }}" min="1" required>
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
                    @foreach($exam->questions as $index => $question)
                        <div class="card mb-4 question-card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">{{ __('Question') }} <span class="question-number">{{ $index + 1 }}</span></h5>
                                <button type="button" class="btn btn-sm btn-danger remove-question">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="questions[{{ $index }}][id]" value="{{ $question->id }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('Question Text (English)') }}</label>
                                            <textarea class="form-control question-text-en" name="questions[{{ $index }}][question]" rows="2" required>{{ old("questions.$index.question", $question->question) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('Question Text (Arabic)') }} <span class="text-muted">(النص بالعربية)</span></label>
                                            <textarea class="form-control question-text-ar" name="questions[{{ $index }}][question-ar]" rows="2" dir="rtl" required>{{ old("questions.$index.question-ar", $question['question-ar']) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Question Type') }}</label>
                                    <select class="form-control question-type" name="questions[{{ $index }}][type]" required>
                                        <option value="single_choice" {{ $question->type == 'single_choice' ? 'selected' : '' }}>{{ __('Single Choice') }}</option>
                                        <option value="multiple_choice" {{ $question->type == 'multiple_choice' ? 'selected' : '' }}>{{ __('Multiple Choice') }}</option>
                                        <option value="true_false" {{ $question->type == 'true_false' ? 'selected' : '' }}>{{ __('True/False') }}</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Points') }}</label>
                                    <input type="number" class="form-control question-points" name="questions[{{ $index }}][marks]" min="1" value="{{ old("questions.$index.marks", $question->marks) }}" required>
                                </div>

                                <div class="options-container">
                                    @foreach($question->answers as $optionIndex => $option)

                                    <div class="option-item mb-2">
                                        <input type="hidden" name="questions[{{ $index }}][answers][{{ $optionIndex }}][id]" value="{{ $option->id }}">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <input type="text" class="form-control option-text-en" 
                                                       name="questions[{{ $index }}][answers][{{ $optionIndex }}][answer]" 
                                                       value="{{ old("questions.$index.answers.$optionIndex.answer", $option->answer) }}" 
                                                       placeholder="{{ __('Option (English)') }}" required>
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" class="form-control option-text-ar" 
                                                       name="questions[{{ $index }}][answers][{{ $optionIndex }}][answer-ar]" 
                                                       value="{{ old("questions.$index.answers.$optionIndex.answer-ar", $option['answer-ar']) }}" 
                                                       placeholder="{{ __('الخيار (العربية)') }}" 
                                                       dir="rtl" required>
                                            </div>
                                            <div class="col-md-2 d-flex align-items-center">
                                                <div class="form-check me-2">
                                                    <input class="form-check-input is-correct" 
                                                           type="{{ $question->type == 'multiple_choice' ? 'checkbox' : 'radio' }}" 
                                                           name="{{ $question->type == 'multiple_choice' ? 'questions['.$index.'][answers]['.$optionIndex.'][is_correct]' : 'questions['.$index.'][correct_answer]' }}" 
                                                           value="{{ $optionIndex }}"
                                                           {{ $option->is_correct ? 'checked' : '' }}>
                                                    <label class="form-check-label">{{ __('Correct') }}</label>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-option">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <button type="button" class="btn btn-sm btn-outline-primary add-option mt-2">
                                    <i class="fas fa-plus"></i> {{ __('Add Option') }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" class="btn btn-success mb-3" id="add-question">
                    <i class="fas fa-plus"></i> {{ __('Add Question') }}
                </button>

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ __('Edit Exam') }}
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
                        <textarea class="form-control question-text-en" rows="2" required></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('Question Text (Arabic)') }} <span class="text-muted">(النص بالعربية)</span></label>
                        <textarea class="form-control question-text-ar" rows="2" dir="rtl" required></textarea>
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
                <input type="text" class="form-control option-text-en" placeholder="Option (English)" required>
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control option-text-ar" placeholder="الخيار (العربية)" dir="rtl" required>
            </div>
            <div class="col-md-2 d-flex align-items-center">
                <div class="form-check me-2">
                    <input class="form-check-input is-correct" type="checkbox" >
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
        // Initialize counters
        let questionCount = {{ count($exam->questions) }};
        let answersIndex = {!! json_encode(
            $exam->questions->mapWithKeys(function($question, $qIndex) {
                return [
                    $question->id ?? ($qIndex + 1) => $question->answers->count()
                ];
            })
        ) !!};
        
        const questionsContainer = document.getElementById('questions-container');
        const questionTemplate = document.getElementById('question-template');
        const optionTemplate = document.getElementById('option-template');
        const addQuestionBtn = document.getElementById('add-question');

        // Initialize existing questions
        document.querySelectorAll('.question-card').forEach(question => {
            setupQuestionEvents(question);
            const typeSelect = question.querySelector('.question-type');
            typeSelect.dispatchEvent(new Event('change'));
        });

        // Add new question
        addQuestionBtn.addEventListener('click', addQuestion);

        function addQuestion() {
            questionCount++;
            answersIndex[questionCount] = 0;

            const newQuestion = questionTemplate.querySelector('.question-card').cloneNode(true);
            newQuestion.querySelector('.question-number').textContent = questionCount;

            // Update all name attributes
            const questionElements = newQuestion.querySelectorAll('[name]');
            questionElements.forEach(el => {
                el.setAttribute('name', el.name.replace(/questions\[\d+\]/, `questions[${questionCount}]`));
            });

            questionsContainer.appendChild(newQuestion);
            setupQuestionEvents(newQuestion);
            newQuestion.querySelector('.question-type').dispatchEvent(new Event('change'));
        }

        function setupQuestionEvents(questionElement) {
            // Remove question
            questionElement.querySelector('.remove-question')?.addEventListener('click', function() {
                questionElement.remove();
                updateQuestionNumbers();
            });

            // Add option
            questionElement.querySelector('.add-option')?.addEventListener('click', function() {
                const questionId = this.closest('.question-card').querySelector('[name^="questions["]').name.match(/questions\[(\d+)\]/)[1];
                addOption(questionElement, questionId);
            });

        }
        function addOption(questionElement, questionId, textEn = '', textAr = '', isCorrect = false) {
            // Get current options count for this question
            const currentOptions = questionElement.querySelectorAll('.option-item').length;

            // Use currentOptions as the base index (0-based)
            const optionIndex = currentOptions;

            const newOption = optionTemplate.cloneNode(true);
            newOption.style.display = 'block';

            // Set option text fields (0-based indexing)
            newOption.querySelector('.option-text-en').name = `questions[${questionId}][answers][${optionIndex}][answer]`;
            newOption.querySelector('.option-text-ar').name = `questions[${questionId}][answers][${optionIndex}][answer-ar]`;

            // Set correct answer field based on question type
            const questionType = questionElement.querySelector('.question-type').value;
            const correctAnswerField = newOption.querySelector('.is-correct');

            if (questionType === 'multiple_choice') {
                correctAnswerField.name = `questions[${questionId}][answers][${optionIndex}][is_correct]`;
                correctAnswerField.type = 'checkbox';
            } else {
                correctAnswerField.name = `questions[${questionId}][correct_answer]`;
                correctAnswerField.type = 'radio';
                correctAnswerField.value = optionIndex;
            }
        
            // Set initial values
            if (textEn) newOption.querySelector('.option-text-en').value = textEn;
            if (textAr) newOption.querySelector('.option-text-ar').value = textAr;
            correctAnswerField.checked = isCorrect;
        
            // Remove option
            newOption.querySelector('.remove-option').addEventListener('click', function() {
                newOption.remove();
                // Re-index remaining options
                reindexOptions(questionElement, questionId);
            });
        
            questionElement.querySelector('.options-container').appendChild(newOption);
        }

        function reindexOptions(questionElement, questionId) {
            const options = questionElement.querySelectorAll('.option-item');
            options.forEach((option, index) => {
                // Update all name attributes with new index
                option.querySelector('.option-text-en').name = `questions[${questionId}][answers][${index}][text_en]`;
                option.querySelector('.option-text-ar').name = `questions[${questionId}][answers][${index}][text_ar]`;

                // Update correct answer field
                const correctField = option.querySelector('.is-correct');
                const questionType = questionElement.querySelector('.question-type').value;

                if (questionType === 'multiple_choice') {
                    correctField.name = `questions[${questionId}][answers][${index}][is_correct]`;
                } else {
                    correctField.value = index;
                }
            });
        }

        function updateQuestionNumbers() {
            document.querySelectorAll('.question-card').forEach((question, index) => {
                question.querySelector('.question-number').textContent = index + 1;
                question.querySelectorAll('[name]').forEach(el => {
                    el.name = el.name.replace(/questions\[\d+\]/, `questions[${index + 1}]`);
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