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
        <div class="card-body">
            <form method="POST" action="{{ route('admin.exams.update', $exam) }}" id="examForm">
                @csrf
                @method('PUT')
                
                <!-- Basic Information -->
                <x-exam.basic-info :exam="$exam" />
                
                <hr class="my-4">
                
                <!-- Questions Section -->
                <h4 class="mb-3">{{ __('Exam Questions') }}</h4>
                <div id="questions-container">
                    @foreach($exam->questions as $index => $question)
                        <x-exam.question-card :question="$question" :index="$index" />
                    @endforeach
                </div>

                <button type="button" class="btn btn-success mb-3" onclick="addNewQuestion()">
                    <i class="fas fa-plus"></i> {{ __('Add Question') }}
                </button>
                
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ __('Update Exam') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Question Template (Hidden) -->
<div id="question-template" style="display: none;">
    <div class="card mb-4 question-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-white">{{ __('Question') }} <span class="question-number">1</span></h5>
            <button type="button" class="btn btn-sm btn-danger" onclick="removeQuestion(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('Question Text (English)') }}</label>
                        <textarea class="form-control" name="questions[INDEX][question]" rows="2" required></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('Question Text (Arabic)') }} <span class="text-muted">(النص بالعربية)</span></label>
                        <textarea class="form-control" name="questions[INDEX][question-ar]" rows="2" dir="rtl" required></textarea>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>{{ __('Question Type') }}</label>
                <select class="form-control" name="questions[INDEX][type]" onchange="changeQuestionType(this)" required>
                    <option value="single_choice">{{ __('Single Choice') }}</option>
                    <option value="multiple_choice">{{ __('Multiple Choice') }}</option>
                    <option value="true_false">{{ __('True/False') }}</option>
                </select>
            </div>

            <div class="form-group">
                <label>{{ __('Points') }}</label>
                <input type="number" class="form-control" name="questions[INDEX][marks]" min="1" value="1" required>
            </div>

            <div class="options-section">
                <!-- Options will be inserted here -->
            </div>

            <button type="button" class="btn btn-sm btn-outline-primary add-option-btn" onclick="addOption(this)" style="display: none;">
                <i class="fas fa-plus"></i> {{ __('Add Option') }}
            </button>
        </div>
    </div>
</div>

<style>
    .question-card {
        border-left: 4px solid #4e73df;
        transition: all 0.3s ease;
    }
    
    .question-card:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .option-item {
        background-color: #f8f9fa;
        border: 1px solid #e3e6f0;
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    
    .option-item:hover {
        background-color: #f1f3f4;
        border-color: #d1d3e2;
    }
    
    [dir="rtl"] {
        text-align: right;
        font-family: 'Arial', sans-serif;
    }
    
    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .question-number {
        background: rgba(255,255,255,0.2);
        padding: 2px 8px;
        border-radius: 12px;
        font-weight: bold;
    }
    
    .true-false-container .option-item {
        background-color: #e8f5e8;
        border-color: #c3e6c3;
    }
    
    .true-false-container input[readonly] {
        background-color: #f8f9fa;
        font-weight: bold;
    }
</style>
<script>
let questionCounter = {{ count($exam->questions) }};

function addNewQuestion() {
    questionCounter++;
    const template = document.getElementById('question-template').innerHTML;
    const newQuestion = template.replace(/INDEX/g, questionCounter - 1);
    document.getElementById('questions-container').insertAdjacentHTML('beforeend', newQuestion);
    
    // Initialize the new question
    const newQuestionElement = document.getElementById('questions-container').lastElementChild;
    const typeSelect = newQuestionElement.querySelector('select[name*="[type]"]');
    changeQuestionType(typeSelect);
    
    updateQuestionNumbers();
}

function removeQuestion(button) {
    if (confirm('{{ __("Are you sure you want to delete this question?") }}')) {
        button.closest('.question-card').remove();
        updateQuestionNumbers();
    }
}

function changeQuestionType(selectElement) {
    const questionCard = selectElement.closest('.question-card');
    const optionsSection = questionCard.querySelector('.options-section');
    const addOptionBtn = questionCard.querySelector('.add-option-btn');
    const questionType = selectElement.value;
    
    // Clear existing options
    optionsSection.innerHTML = '';
    
    if (questionType === 'true_false') {
        // Add True/False options
        optionsSection.innerHTML = createTrueFalseOptions(selectElement);
        addOptionBtn.style.display = 'none';
    } else {
        // Add default options for single/multiple choice
        optionsSection.innerHTML = createChoiceOptions(selectElement, 2);
        addOptionBtn.style.display = 'block';
    }
}

function createTrueFalseOptions(selectElement) {
    const questionIndex = getQuestionIndex(selectElement);
    return `
        <h6 class="mb-3">{{ __('True/False Options') }}</h6>
        <div class="option-item mb-2 p-3 border rounded">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="questions[${questionIndex}][answers][0][answer]" value="True" readonly>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="questions[${questionIndex}][answers][0][answer-ar]" value="صحيح" dir="rtl" readonly>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="questions[${questionIndex}][correct_answer]" value="0">
                        <label class="form-check-label">{{ __('Correct') }}</label>
                    </div>
                    <input type="hidden" name="questions[${questionIndex}][answers][0][is_correct]" value="0">
                </div>
            </div>
        </div>
        <div class="option-item mb-2 p-3 border rounded">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="questions[${questionIndex}][answers][1][answer]" value="False" readonly>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="questions[${questionIndex}][answers][1][answer-ar]" value="خطأ" dir="rtl" readonly>
                </div>
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="questions[${questionIndex}][correct_answer]" value="1">
                        <label class="form-check-label">{{ __('Correct') }}</label>
                    </div>
                    <input type="hidden" name="questions[${questionIndex}][answers][1][is_correct]" value="0">
                </div>
            </div>
        </div>
    `;
}

function createChoiceOptions(selectElement, optionCount = 2) {
    const questionIndex = getQuestionIndex(selectElement);
    const questionType = selectElement.value;
    const inputType = questionType === 'multiple_choice' ? 'checkbox' : 'radio';

    console.log('as,mdf');
    
    
    let html = '<h6 class="mb-3">{{ __("Answer Options") }}</h6>';
    
    for (let i = 0; i < optionCount; i++) {
        html += `
            <div class="option-item mb-2 p-3 border rounded">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="questions[${questionIndex}][answers][${i}][answer]" placeholder="{{ __('Option (English)') }}" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="questions[${questionIndex}][answers][${i}][answer-ar]" placeholder="{{ __('الخيار (العربية)') }}" dir="rtl" required>
                    </div>
                    <div class="col-md-4 d-flex align-items-center justify-content-between">
                        <div class="form-check">
                            <input class="form-check-input" type="${inputType}" name="questions[${questionIndex}][answers][${i}][is_correct]" value="1">
                            <label class="form-check-label">{{ __('Correct') }}</label>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeOption(this)">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    }
    
    return html;
}

function addOption(button) {
    const questionCard = button.closest('.question-card');
    const optionsSection = questionCard.querySelector('.options-section');
    const existingOptions = optionsSection.querySelectorAll('.option-item');
    const optionIndex = existingOptions.length;
    const typeSelect = questionCard.querySelector('select[name*="[type]"]');
    const questionIndex = getQuestionIndex(typeSelect);
    const questionType = typeSelect.value;
    const inputType = questionType === 'multiple_choice' ? 'checkbox' : 'radio';
    
    const newOption = `
        <div class="option-item mb-2 p-3 border rounded">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="questions[${questionIndex}][answers][${optionIndex}][answer]" placeholder="{{ __('Option (English)') }}" required>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="questions[${questionIndex}][answers][${optionIndex}][answer-ar]" placeholder="{{ __('الخيار (العربية)') }}" dir="rtl" required>
                </div>
                <div class="col-md-4 d-flex align-items-center justify-content-between">
                    <div class="form-check">
                        <input class="form-check-input" type="${inputType}" name="questions[${questionIndex}][answers][${optionIndex}][is_correct]" value="1">
                        <label class="form-check-label">{{ __('Correct') }}</label>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeOption(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    optionsSection.insertAdjacentHTML('beforeend', newOption);
}

function removeOption(button) {
    const questionCard = button.closest('.question-card');
    const optionsSection = questionCard.querySelector('.options-section');
    const options = optionsSection.querySelectorAll('.option-item');
    
    if (options.length > 2) {
        button.closest('.option-item').remove();
        reindexOptions(questionCard);
    } else {
        alert('{{ __("Each question must have at least 2 options") }}');
    }
}

function reindexOptions(questionCard) {
    const typeSelect = questionCard.querySelector('select[name*="[type]"]');
    const questionIndex = getQuestionIndex(typeSelect);
    const options = questionCard.querySelectorAll('.option-item');
    
    options.forEach((option, index) => {
        const inputs = option.querySelectorAll('input');
        inputs.forEach(input => {
            if (input.name.includes('[answer]')) {
                input.name = `questions[${questionIndex}][answers][${index}][answer]`;
            } else if (input.name.includes('[answer-ar]')) {
                input.name = `questions[${questionIndex}][answers][${index}][answer-ar]`;
            } else if (input.name.includes('[is_correct]')) {
                input.name = `questions[${questionIndex}][answers][${index}][is_correct]`;
            }
        });
    });
}

function getQuestionIndex(element) {
    const questionCard = element.closest('.question-card');
    const allQuestions = document.querySelectorAll('.question-card');
    return Array.from(allQuestions).indexOf(questionCard);
}

function updateQuestionNumbers() {
    const questions = document.querySelectorAll('.question-card');
    questions.forEach((question, index) => {
        // Update question number
        const numberSpan = question.querySelector('.question-number');
        if (numberSpan) numberSpan.textContent = index + 1;
        
        // Update all name attributes
        const inputs = question.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            if (input.name) {
                input.name = input.name.replace(/questions\[\d+\]/, `questions[${index}]`);
            }
        });
    });
}

// Initialize existing questions
document.addEventListener('DOMContentLoaded', function() {
    const existingQuestions = document.querySelectorAll('.question-card select[name*="[type]"]');
    existingQuestions.forEach(select => {
        // Don't reinitialize existing questions, they're already rendered correctly
    });
});

// Handle true/false radio button changes
document.addEventListener('change', function(e) {
    if (e.target.name && e.target.name.includes('[correct_answer]')) {
        const questionIndex = e.target.name.match(/questions\[(\d+)\]/)[1];
        const selectedValue = e.target.value;
        
        // Set hidden is_correct fields for true/false
        const questionCard = e.target.closest('.question-card');
        const hiddenInputs = questionCard.querySelectorAll('input[type="hidden"][name*="[is_correct]"]');
        hiddenInputs.forEach((input, index) => {
            input.value = index == selectedValue ? '1' : '0';
        });
    }
});
</script>

@endsection