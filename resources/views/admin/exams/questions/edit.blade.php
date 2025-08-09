@extends('layouts.admin')

@section('title', __('Edit Question') . ' - ' . $exam->text)

@section('page-title', __('Edit Question'))

@section('content')

<link rel="stylesheet" href="{{ asset('css/exam-create.css') }}">
<div class="exam-create-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <h1 class="page-title">{{ __('Edit Question') }}</h1>
                <div class="breadcrumb">
                    <span class="exam-title">{{ $exam->text }}</span>
                    <span class="separator">/</span>
                    <span class="current">{{ __('Edit Question') }}</span>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.exams.questions.index', $exam->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}"></i>
                    {{ __('Back to Questions') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="alert-header">
                <i class="fas fa-exclamation-triangle"></i>
                <h6 class="alert-title">{{ __('Please correct the following errors:') }}</h6>
            </div>
            <ul class="alert-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('Close') }}"></button>
        </div>
    @endif

    <!-- Main Form -->
    <form method="POST" action="{{ route('admin.exams.questions.update', [$exam->id, $question->id]) }}" id="questionForm" class="exam-form" novalidate>
        @csrf
        @method('PUT')

        <!-- Question Information Card -->
        <div class="form-card">
            <div class="card-header">
                <div class="card-header-content">
                    <i class="fas fa-question-circle"></i>
                    <h3 class="card-title">{{ __('Question Information') }}</h3>
                </div>
            </div>
            <div class="card-body">
                <!-- Question Type and Points -->
                <div class="form-row">
                    <div class="form-group form-group-half">
                        <label for="type" class="form-label required">
                            {{ __('Question Type') }}
                        </label>
                        <select class="form-control @error('type') is-invalid @enderror" 
                                id="type" 
                                name="type" 
                                required>
                            <option value="">{{ __('Select question type') }}</option>
                            <option value="single_choice" {{ old('type', $question->type) == 'single_choice' ? 'selected' : '' }}>
                                {{ __('Single Choice') }}
                            </option>
                            <option value="multiple_choice" {{ old('type', $question->type) == 'multiple_choice' ? 'selected' : '' }}>
                                {{ __('Multiple Choice') }}
                            </option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group form-group-quarter">
                        <label for="points" class="form-label required">
                            {{ __('Points') }}
                        </label>
                        <input type="number" 
                               class="form-control @error('points') is-invalid @enderror"
                               id="points" 
                               name="points" 
                               value="{{ old('points', $question->marks) }}" 
                               min="1" 
                               max="100" 
                               placeholder="1" 
                               required>
                        @error('points')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Question Text -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="text_en" class="form-label required">
                            {{ __('Question Text (English)') }}
                        </label>
                        <textarea class="form-control @error('text_en') is-invalid @enderror"
                                  id="text_en" 
                                  name="text_en" 
                                  rows="3" 
                                  placeholder="{{ __('Enter the question text in English') }}" 
                                  required>{{ old('text_en', $question->question) }}</textarea>
                        @error('text_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="text_ar" class="form-label required">
                            {{ __('Question Text (Arabic)') }}
                        </label>
                        <textarea class="form-control @error('text_ar') is-invalid @enderror"
                                  id="text_ar" 
                                  name="text_ar" 
                                  rows="3" 
                                  placeholder="{{ __('Enter the question text in Arabic') }}" 
                                  dir="rtl" 
                                  required>{{ old('text_ar', $question->{'question-ar'}) }}</textarea>
                        @error('text_ar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Answer Options Card -->
        <div class="form-card">
            <div class="card-header">
                <div class="card-header-content">
                    <i class="fas fa-list"></i>
                    <h3 class="card-title">{{ __('Answer Options') }}</h3>
                </div>
                <div class="card-actions">
                    <button type="button" class="btn btn-success btn-sm" id="add-option">
                        <i class="fas fa-plus"></i>
                        {{ __('Add Option') }}
                    </button>
                </div>
            </div>
            <div class="card-body">
                <p class="section-help">{{ __('Add at least 2 answer options. Mark the correct answer(s) accordingly.') }}</p>
                
                <!-- Options Container -->
                <div id="options-container" class="options-container">
                    @foreach($question->answers as $index => $answer)
                        <div class="option-card" data-option-index="{{ $index }}">
                            <div class="option-header">
                                <span class="option-number">{{ chr(65 + $index) }}</span>
                                <div class="option-actions">
                                    <div class="form-check">
                                        @if($question->type === 'single_choice')
                                            <input type="radio" 
                                                   class="form-check-input correct-input" 
                                                   name="correct_answer" 
                                                   value="{{ $index }}"
                                                   id="correct-{{ $index }}"
                                                   {{ $answer->is_correct ? 'checked' : '' }}>
                                        @else
                                            <input type="checkbox" 
                                                   class="form-check-input correct-input" 
                                                   name="options[{{ $index }}][is_correct]" 
                                                   value="1"
                                                   id="correct-{{ $index }}"
                                                   {{ $answer->is_correct ? 'checked' : '' }}>
                                        @endif
                                        <label class="form-check-label" for="correct-{{ $index }}">
                                            {{ __('Correct') }}
                                        </label>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-option">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="option-body">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">{{ __('Option Text (English)') }}</label>
                                        <input type="text" 
                                               class="form-control"
                                               name="options[{{ $index }}][text_en]" 
                                               value="{{ old('options.'.$index.'.text_en', $answer->answer) }}"
                                               placeholder="{{ __('Enter option text in English') }}" 
                                               required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">{{ __('Option Text (Arabic)') }}</label>
                                        <input type="text" 
                                               class="form-control"
                                               name="options[{{ $index }}][text_ar]" 
                                               value="{{ old('options.'.$index.'.text_ar', $answer->{'answer-ar'}) }}"
                                               placeholder="{{ __('Enter option text in Arabic') }}" 
                                               dir="rtl" 
                                               required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">{{ __('Explanation (English) - Optional') }}</label>
                                        <textarea class="form-control"
                                                  name="options[{{ $index }}][reason]" 
                                                  rows="2"
                                                  placeholder="{{ __('Why is this answer correct/incorrect?') }}">{{ old('options.'.$index.'.reason', $answer->reason) }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">{{ __('Explanation (Arabic) - Optional') }}</label>
                                        <textarea class="form-control"
                                                  name="options[{{ $index }}][reason_ar]" 
                                                  rows="2"
                                                  placeholder="{{ __('Why is this answer correct/incorrect?') }}" 
                                                  dir="rtl">{{ old('options.'.$index.'.reason_ar', $answer->{'reason-ar'}) }}</textarea>
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
                {{ __('Update Question') }}
            </button>
            <a href="{{ route('admin.exams.questions.index', $exam->id) }}" class="btn btn-secondary btn-lg">
                <i class="fas fa-times"></i>
                {{ __('Cancel') }}
            </a>
        </div>
    </form>
</div>

<style>
.breadcrumb {
    font-size: 0.9rem;
    color: #666;
    margin-top: 0.5rem;
}

.separator {
    margin: 0 0.5rem;
}

.section-help {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 1.5rem;
}

.options-container {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.option-card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    background: white;
}

.option-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    background: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
}

.option-number {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #0d6efd;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.option-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.option-body {
    padding: 1.5rem;
}

.form-check {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-check-input {
    margin: 0;
}

.remove-option {
    opacity: 0.7;
}

.remove-option:hover {
    opacity: 1;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let optionCount = {{ $question->answers->count() }};
    const maxOptions = 6;
    const optionLetters = ['A', 'B', 'C', 'D', 'E', 'F'];
    
    const typeSelect = document.getElementById('type');
    const addOptionBtn = document.getElementById('add-option');
    const optionsContainer = document.getElementById('options-container');
    
    // Handle question type change
    typeSelect.addEventListener('change', function() {
        updateCorrectInputs();
    });
    
    // Add option functionality
    addOptionBtn.addEventListener('click', function() {
        if (optionCount < maxOptions) {
            addOption();
        }
    });
    
    // Remove option functionality
    optionsContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-option')) {
            e.preventDefault();
            if (optionCount > 2) {
                const optionCard = e.target.closest('.option-card');
                optionCard.remove();
                optionCount--;
                updateOptionNumbers();
                updateCorrectInputs();
            }
        }
    });
    
    function addOption() {
        const optionHtml = `
            <div class="option-card" data-option-index="${optionCount}">
                <div class="option-header">
                    <span class="option-number">${optionLetters[optionCount]}</span>
                    <div class="option-actions">
                        <div class="form-check">
                            <input type="radio" 
                                   class="form-check-input correct-input" 
                                   name="correct_answer" 
                                   value="${optionCount}"
                                   id="correct-${optionCount}">
                            <label class="form-check-label" for="correct-${optionCount}">
                                {{ __('Correct') }}
                            </label>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-option">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="option-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">{{ __('Option Text (English)') }}</label>
                            <input type="text" 
                                   class="form-control"
                                   name="options[${optionCount}][text_en]" 
                                   placeholder="{{ __('Enter option text in English') }}" 
                                   required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">{{ __('Option Text (Arabic)') }}</label>
                            <input type="text" 
                                   class="form-control"
                                   name="options[${optionCount}][text_ar]" 
                                   placeholder="{{ __('Enter option text in Arabic') }}" 
                                   dir="rtl" 
                                   required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">{{ __('Explanation (English) - Optional') }}</label>
                            <textarea class="form-control"
                                      name="options[${optionCount}][reason]" 
                                      rows="2"
                                      placeholder="{{ __('Why is this answer correct/incorrect?') }}"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">{{ __('Explanation (Arabic) - Optional') }}</label>
                            <textarea class="form-control"
                                      name="options[${optionCount}][reason_ar]" 
                                      rows="2"
                                      placeholder="{{ __('Why is this answer correct/incorrect?') }}" 
                                      dir="rtl"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        optionsContainer.insertAdjacentHTML('beforeend', optionHtml);
        optionCount++;
        
        if (optionCount >= maxOptions) {
            addOptionBtn.disabled = true;
        }
        
        updateCorrectInputs();
    }
    
    function updateOptionNumbers() {
        const options = optionsContainer.querySelectorAll('.option-card');
        options.forEach((option, index) => {
            const numberSpan = option.querySelector('.option-number');
            const correctInput = option.querySelector('.correct-input');
            const correctLabel = option.querySelector('.form-check-label');
            
            numberSpan.textContent = optionLetters[index];
            correctInput.value = index;
            correctInput.id = `correct-${index}`;
            correctLabel.setAttribute('for', `correct-${index}`);
            
            // Update name attributes
            const inputs = option.querySelectorAll('input, textarea');
            inputs.forEach(input => {
                if (input.name && input.name.includes('[')) {
                    const nameParts = input.name.split('[');
                    if (nameParts.length > 2) {
                        const fieldName = nameParts[2];
                        input.name = `options[${index}][${fieldName}`;
                    }
                }
            });
        });
        
        optionCount = options.length;
    }
    
    function updateCorrectInputs() {
        const questionType = typeSelect.value;
        const correctInputs = optionsContainer.querySelectorAll('.correct-input');
        
        if (questionType === 'multiple_choice') {
            correctInputs.forEach((input, index) => {
                input.type = 'checkbox';
                input.name = `options[${index}][is_correct]`;
                input.value = '1';
            });
        } else {
            correctInputs.forEach((input, index) => {
                input.type = 'radio';
                input.name = 'correct_answer';
                input.value = index;
            });
        }
    }
    
    // Form validation
    document.getElementById('questionForm').addEventListener('submit', function(e) {
        const questionType = typeSelect.value;
        const correctInputs = optionsContainer.querySelectorAll('.correct-input:checked');
        
        if (correctInputs.length === 0) {
            e.preventDefault();
            alert('Please select at least one correct answer.');
            return;
        }
        
        if (questionType === 'single_choice' && correctInputs.length > 1) {
            e.preventDefault();
            alert('Single choice questions can only have one correct answer.');
            return;
        }
    });
});
</script>
@endsection