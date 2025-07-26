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
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">{{ __('Exam Questions') }}</h4>
                    <button type="button" class="btn btn-success" onclick="addNewQuestion()">
                        <i class="fas fa-plus"></i> {{ __('Add Question') }}
                    </button>
                </div>
                
                <div id="questions-container">
                    @foreach($exam->questions as $index => $question)
                        <x-exam.question-card :question="$question" :index="$index" />
                    @endforeach
                </div>

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> {{ __('Update Exam') }}
                    </button>   
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Question Template (Hidden) -->
<div id="question-template" style="display: none;">
    <div class="card mb-4 question-card" data-question-index="INDEX">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-white d-flex align-items-center">
                <span class="question-badge me-2">
                    <span class="question-number">1</span>
                </span>
                {{ __('Question') }} <span class="question-number ms-1">1</span>
                <span class="collapse-indicator ms-2">
                    <i class="fas fa-chevron-up"></i>
                </span>
            </h5>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-light" onclick="toggleQuestion(this)" title="{{ __('Collapse/Expand') }}">
                    <i class="fas fa-chevron-up"></i>
                </button>
                <button type="button" class="btn btn-sm btn-warning" onclick="duplicateQuestion(this)" title="{{ __('Duplicate') }}">
                    <i class="fas fa-copy"></i>
                </button>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeQuestion(this)" title="{{ __('Delete') }}">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        <div class="card-body question-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label fw-bold">
                            <i class="fas fa-question-circle me-1"></i>
                            {{ __('Question Text (English)') }}
                        </label>
                        <textarea class="form-control" name="questions[INDEX][question]" rows="3" required placeholder="{{ __('Enter your question in English...') }}"></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label fw-bold">
                            <i class="fas fa-question-circle me-1"></i>
                            {{ __('Question Text (Arabic)') }} <span class="text-muted">(النص بالعربية)</span>
                        </label>
                        <textarea class="form-control" name="questions[INDEX][question-ar]" rows="3" dir="rtl" required placeholder="{{ __('أدخل سؤالك بالعربية...') }}"></textarea>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label fw-bold">
                            <i class="fas fa-list-ul me-1"></i>
                            {{ __('Question Type') }}
                        </label>
                        <select class="form-control" name="questions[INDEX][type]" onchange="changeQuestionType(this)" required>
                            <option value="single_choice">{{ __('Single Choice') }}</option>
                            <option value="multiple_choice">{{ __('Multiple Choice') }}</option>
                            <option value="true_false">{{ __('True/False') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label fw-bold">
                            <i class="fas fa-star me-1"></i>
                            {{ __('Points') }}
                        </label>
                        <input type="number" class="form-control" name="questions[INDEX][marks]" min="1" max="100" value="1" required onchange="updateSummary()">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label fw-bold">
                            <i class="fas fa-clock me-1"></i>
                            {{ __('Difficulty') }}
                        </label>
                        <select class="form-control" name="questions[INDEX][difficulty]">
                            <option value="easy">{{ __('Easy') }}</option>
                            <option value="medium" selected>{{ __('Medium') }}</option>
                            <option value="hard">{{ __('Hard') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="options-section">
                <!-- Options will be inserted here -->
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <button type="button" class="btn btn-sm btn-outline-primary add-option-btn" onclick="addOption(this)" style="display: none;">
                    <i class="fas fa-plus"></i> {{ __('Add Option') }}
                </button>
                <div class="question-actions">
                    <button type="button" class="btn btn-sm btn-outline-info" onclick="validateQuestion(this)">
                        <i class="fas fa-check-circle"></i> {{ __('Validate') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .question-card {
        border-left: 4px solid #4e73df;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .question-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transform: translateY(-2px);
    }
    
    .question-card.collapsed .question-body {
        display: none;
    }
    
    .question-card.collapsed .collapse-indicator i {
        transform: rotate(180deg);
    }
    
    .question-badge {
        background: rgba(255,255,255,0.2);
        padding: 4px 8px;
        border-radius: 50%;
        font-weight: bold;
        min-width: 30px;
        text-align: center;
    }
    
    .option-item {
        background-color: #f8f9fa;
        border: 2px solid #e3e6f0;
        border-radius: 12px;
        transition: all 0.3s ease;
        position: relative;
        margin-bottom: 1rem;
    }
    
    .option-item:hover {
        background-color: #f1f3f4;
        border-color: #4e73df;
        transform: translateX(5px);
    }
    
    .option-item.correct-answer {
        border-color: #1cc88a;
        background-color: #f0f9f0;
    }
    
    .option-item .option-badge {
        position: absolute;
        top: -10px;
        left: -10px;
        background: #4e73df;
        color: white;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: bold;
    }
    
    .option-item.correct-answer .option-badge {
        background: #1cc88a;
    }
    
    [dir="rtl"] {
        text-align: right;
        font-family: 'Arial', sans-serif;
    }
    
    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        cursor: pointer;
    }
    
    .true-false-container .option-item {
        background: linear-gradient(135deg, #e8f5e8 0%, #f0f9f0 100%);
        border-color: #c3e6c3;
    }
    
    .form-label.fw-bold {
        color: #5a5c69;
        font-size: 0.9rem;
    }
    
    .collapse-indicator {
        transition: transform 0.3s ease;
    }
    
    .btn-group .btn {
        margin-left: 2px;
    }
    
    .validation-error {
        border-color: #e74a3b !important;
        background-color: #fdf2f2 !important;
    }
    
    .validation-success {
        border-color: #1cc88a !important;
        background-color: #f0f9f0 !important;
    }
    
    .explanation-field {
        background: #f8f9fc;
        border: 1px dashed #d1d3e2;
        border-radius: 8px;
        padding: 10px;
        margin-top: 10px;
    }
    
    .explanation-field textarea {
        border: none;
        background: transparent;
        resize: vertical;
    }
    
    .explanation-field textarea:focus {
        box-shadow: none;
        border: 1px solid #4e73df;
        background: white;
        border-radius: 6px;
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
    updateSummary();
    
    // Scroll to new question
    newQuestionElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function addMultipleQuestions() {
    if (confirm('{{ __("Add 5 new questions?") }}')) {
        for (let i = 0; i < 5; i++) {
            addNewQuestion();
        }
    }
}

function duplicateQuestion(button) {
    const questionCard = button.closest('.question-card');
    const clone = questionCard.cloneNode(true);
    
    // Update indices and clear values
    questionCounter++;
    const newIndex = questionCounter - 1;
    
    // Update all name attributes
    clone.querySelectorAll('[name]').forEach(input => {
        if (input.name) {
            input.name = input.name.replace(/questions\[\d+\]/, `questions[${newIndex}]`);
        }
        // Clear values for duplicated question
        if (input.type !== 'hidden' && input.tagName !== 'SELECT') {
            input.value = '';
        }
        if (input.type === 'checkbox' || input.type === 'radio') {
            input.checked = false;
        }
    });
    
    // Insert after current question
    questionCard.parentNode.insertBefore(clone, questionCard.nextSibling);
    
    updateQuestionNumbers();
    updateSummary();
}

function duplicateLastQuestion() {
    const questions = document.querySelectorAll('.question-card');
    if (questions.length > 0) {
        const lastQuestion = questions[questions.length - 1];
        const button = lastQuestion.querySelector('.btn-warning');
        duplicateQuestion(button);
    }
}

function removeQuestion(button) {
    if (confirm('{{ __("Are you sure you want to delete this question?") }}')) {
        button.closest('.question-card').remove();
        updateQuestionNumbers();
        updateSummary();
    }
}

function toggleQuestion(button) {
    const questionCard = button.closest('.question-card');
    questionCard.classList.toggle('collapsed');
    
    const icon = button.querySelector('i');
    if (questionCard.classList.contains('collapsed')) {
        icon.className = 'fas fa-chevron-down';
    } else {
        icon.className = 'fas fa-chevron-up';
    }
}

function toggleAllQuestions() {
    const questions = document.querySelectorAll('.question-card');
    const anyExpanded = Array.from(questions).some(q => !q.classList.contains('collapsed'));
    
    questions.forEach(question => {
        if (anyExpanded) {
            question.classList.add('collapsed');
        } else {
            question.classList.remove('collapsed');
        }
    });
    
    // Update all toggle buttons
    document.querySelectorAll('.question-card .btn-light i').forEach(icon => {
        icon.className = anyExpanded ? 'fas fa-chevron-down' : 'fas fa-chevron-up';
    });
}

function changeQuestionType(selectElement) {
    const questionCard = selectElement.closest('.question-card');
    const optionsSection = questionCard.querySelector('.options-section');
    const addOptionBtn = questionCard.querySelector('.add-option-btn');
    const questionType = selectElement.value;
    
    // Clear existing options
    optionsSection.innerHTML = '';
    
    if (questionType === 'true_false') {
        optionsSection.innerHTML = createTrueFalseOptions(selectElement);
        addOptionBtn.style.display = 'none';
    } else {
        optionsSection.innerHTML = createChoiceOptions(selectElement, 2);
        addOptionBtn.style.display = 'block';
    }
}

function createTrueFalseOptions(selectElement) {
    const questionIndex = getQuestionIndex(selectElement);
    return `
        <h6 class="mb-3">{{ __('True/False Options') }}</h6>
        
        <!-- True Option -->
        <div class="option-item mb-3 p-3 border rounded">
            <div class="row align-items-center mb-2">
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
            
            <!-- True Explanation -->
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label small text-muted">{{ __('Explanation (English)') }}</label>
                    <textarea class="form-control" name="questions[${questionIndex}][answers][0][reason]" placeholder="{{ __('Explain why this is true') }}" rows="2"></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label small text-muted">{{ __('التفسير (العربية)') }}</label>
                    <textarea class="form-control" name="questions[${questionIndex}][answers][0][reason-ar]" placeholder="{{ __('اشرح لماذا هذا صحيح') }}" dir="rtl" rows="2"></textarea>
                </div>
            </div>
        </div>
        
        <!-- False Option -->
        <div class="option-item mb-3 p-3 border rounded">
            <div class="row align-items-center mb-2">
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
            
            <!-- False Explanation -->
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label small text-muted">{{ __('Explanation (English)') }}</label>
                    <textarea class="form-control" name="questions[${questionIndex}][answers][1][reason]" placeholder="{{ __('Explain why this is false') }}" rows="2"></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label small text-muted">{{ __('التفسير (العربية)') }}</label>
                    <textarea class="form-control" name="questions[${questionIndex}][answers][1][reason-ar]" placeholder="{{ __('اشرح لماذا هذا خطأ') }}" dir="rtl" rows="2"></textarea>
                </div>
            </div>
        </div>
    `;
}
function createChoiceOptions(selectElement, optionCount = 2) {
    const questionIndex = getQuestionIndex(selectElement);
    const questionType = selectElement.value;
    const inputType = questionType === 'multiple_choice' ? 'checkbox' : 'radio';

    console.log('Creating choice options with reasons');
    
    let html = '<h6 class="mb-3">{{ __("Answer Options") }}</h6>';
    
    for (let i = 0; i < optionCount; i++) {
        html += `
            <div class="option-item mb-3 p-3 border rounded">
                <!-- Answer Text Row -->
                <div class="row align-items-center mb-2">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="questions[${questionIndex}][answers][${i}][answer]" placeholder="{{ __('Option (English)') }}" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="questions[${questionIndex}][answers][${i}][answer-ar]" placeholder="{{ __('الخيار (العربية)') }}" dir="rtl" required>
                    </div>
                    <div class="col-md-4 d-flex align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="${inputType}" name="questions[${questionIndex}][answers][${i}][is_correct]" value="1">
                            <label class="form-check-label">{{ __('Correct') }}</label>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="removeOption(this)">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Reason/Explanation Row -->
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label small text-muted">{{ __('Explanation (English)') }}</label>
                        <textarea class="form-control" name="questions[${questionIndex}][answers][${i}][reason]" placeholder="{{ __('Explain why this answer is correct or incorrect') }}" rows="2"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted">{{ __('التفسير (العربية)') }}</label>
                        <textarea class="form-control" name="questions[${questionIndex}][answers][${i}][reason-ar]" placeholder="{{ __('اشرح لماذا هذه الإجابة صحيحة أو خاطئة') }}" dir="rtl" rows="2"></textarea>
                    </div>
                </div>
            </div>
        `;
    }
    
    return html;
}

function markCorrectAnswer(checkbox) {
    const optionItem = checkbox.closest('.option-item');
    const questionCard = checkbox.closest('.question-card');
    
    if (checkbox.checked) {
        optionItem.classList.add('correct-answer');
    } else {
        optionItem.classList.remove('correct-answer');
    }
    
    // For single choice, uncheck others
    if (checkbox.type === 'radio') {
        questionCard.querySelectorAll('.option-item').forEach(item => {
            if (item !== optionItem) {
                item.classList.remove('correct-answer');
            }
        });
    }
}

function validateQuestion(button) {
    const questionCard = button.closest('.question-card');
    let isValid = true;
    let errors = [];
    
    // Check question text
    const questionTexts = questionCard.querySelectorAll('textarea[name*="[question]"]');
    questionTexts.forEach(textarea => {
        if (!textarea.value.trim()) {
            textarea.classList.add('validation-error');
            isValid = false;
            errors.push('{{ __("Question text is required") }}');
        } else {
            textarea.classList.add('validation-success');
        }
    });
    
    // Check if at least one correct answer is selected
    const correctAnswers = questionCard.querySelectorAll('input[type="checkbox"]:checked, input[type="radio"]:checked');
    if (correctAnswers.length === 0) {
        isValid = false;
        errors.push('{{ __("At least one correct answer must be selected") }}');
    }
    
    // Show validation result
    if (isValid) {
        button.className = 'btn btn-sm btn-success';
        button.innerHTML = '<i class="fas fa-check"></i> {{ __("Valid") }}';
        setTimeout(() => {
            button.className = 'btn btn-sm btn-outline-info';
            button.innerHTML = '<i class="fas fa-check-circle"></i> {{ __("Validate") }}';
        }, 2000);
    } else {
        alert('{{ __("Validation Errors:") }}\\n' + errors.join('\\n'));
    }
}

function updateSummary() {
    const questions = document.querySelectorAll('.question-card');
    const totalQuestions = questions.length;
    
    let totalPoints = 0;
    questions.forEach(question => {
        const pointsInput = question.querySelector('input[name*="[marks]"]');
        if (pointsInput) {
            totalPoints += parseInt(pointsInput.value) || 0;
        }
    });
    
    const avgPoints = totalQuestions > 0 ? (totalPoints / totalQuestions).toFixed(1) : 0;
    
    document.getElementById('totalQuestions').textContent = totalQuestions;
    document.getElementById('totalPoints').textContent = totalPoints;
    document.getElementById('avgPoints').textContent = avgPoints;
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
        <div class="option-item mb-3 p-3 border rounded position-relative" data-option-index="${optionIndex}">
            <div class="option-badge">${optionIndex + 1}</div>
            <div class="row align-items-center mb-3">
                <div class="col-md-4">
                    <label class="form-label small text-muted">{{ __('Option (English)') }}</label>
                    <input type="text" class="form-control" name="questions[${questionIndex}][answers][${optionIndex}][answer]" placeholder="{{ __('Enter answer option in English') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted">{{ __('الخيار (العربية)') }}</label>
                    <input type="text" class="form-control" name="questions[${questionIndex}][answers][${optionIndex}][answer-ar]" placeholder="{{ __('أدخل خيار الإجابة بالعربية') }}" dir="rtl" required>
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <div class="form-check">
                        <input class="form-check-input" type="${inputType}" name="questions[${questionIndex}][correct_answers]" value="${optionIndex}" onchange="markCorrectAnswer(this)">
                        <label class="form-check-label fw-bold text-success">
                            <i class="fas fa-check-circle me-1"></i>
                            {{ __('Correct') }}
                        </label>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeOption(this)" title="{{ __('Remove this option') }}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <div class="explanation-field">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label small text-muted">
                            <i class="fas fa-lightbulb me-1"></i>
                            {{ __('Explanation (English)') }}
                        </label>
                        <textarea class="form-control" name="questions[${questionIndex}][answers][${optionIndex}][reason]" placeholder="{{ __('Explain why this answer is correct or incorrect') }}" rows="2"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted">
                            <i class="fas fa-lightbulb me-1"></i>
                            {{ __('التفسير (العربية)') }}
                        </label>
                        <textarea class="form-control" name="questions[${questionIndex}][answers][${optionIndex}][reason-ar]" placeholder="{{ __('اشرح سبب كون هذه الإجابة صحيحة أو خاطئة') }}" dir="rtl" rows="2"></textarea>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    optionsSection.insertAdjacentHTML('beforeend', newOption);
    updateOptionNumbers(questionCard);
}

function removeOption(button) {
    const questionCard = button.closest('.question-card');
    const optionsSection = questionCard.querySelector('.options-section');
    const options = optionsSection.querySelectorAll('.option-item');
    
    if (options.length > 2) {
        button.closest('.option-item').remove();
        updateOptionNumbers(questionCard);
    } else {
        alert('{{ __("Each question must have at least 2 options") }}');
    }
}

function updateOptionNumbers(questionCard) {
    const options = questionCard.querySelectorAll('.option-item');
    const questionIndex = getQuestionIndex(questionCard.querySelector('select[name*="[type]"]'));
    
    options.forEach((option, index) => {
        // Update badge number
        const badge = option.querySelector('.option-badge');
        if (badge) badge.textContent = index + 1;
        
        // Update data attribute
        option.setAttribute('data-option-index', index);
        
        // Update input names
        const inputs = option.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            if (input.name) {
                input.name = input.name.replace(/\[answers\]\[\d+\]/, `[answers][${index}]`);
            }
        });
        
        // Update checkbox/radio values
        const correctCheckbox = option.querySelector('input[name*="correct_answers"]');
        if (correctCheckbox) {
            correctCheckbox.value = index;
        }
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
        // Update question number displays
        const numberSpans = question.querySelectorAll('.question-number');
        numberSpans.forEach(span => span.textContent = index + 1);
        
        // Update data attribute
        question.setAttribute('data-question-index', index);
        
        // Update all name attributes
        const inputs = question.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            if (input.name) {
                input.name = input.name.replace(/questions\[\d+\]/, `questions[${index}]`);
            }
        });
    });
}

function previewExam() {
    // Open exam preview in new window
    const examData = new FormData(document.getElementById('examForm'));
    const previewWindow = window.open('', '_blank', 'width=1200,height=800');
    
    previewWindow.document.write(`
        <html>
            <head>
                <title>{{ __('Exam Preview') }}</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body { padding: 20px; font-family: Arial, sans-serif; }
                    .question-card { border-left: 4px solid #007bff; margin-bottom: 20px; }
                    .option-item { padding: 10px; margin: 5px 0; background: #f8f9fa; border-radius: 5px; }
                    .correct-answer { background: #d4edda; border-left: 3px solid #28a745; }
                </style>
            </head>
            <body>
                <h1>{{ __('Exam Preview') }}</h1>
                <div id="preview-content">{{ __('Loading...') }}</div>
            </body>
        </html>
    `);
}

// Initialize existing questions
document.addEventListener('DOMContentLoaded', function() {
    updateSummary();
    
    // Make card headers clickable for collapse/expand
    document.querySelectorAll('.question-card .card-header').forEach(header => {
        header.addEventListener('click', function(e) {
            if (!e.target.closest('button')) {
                const toggleBtn = this.querySelector('.btn-light');
                if (toggleBtn) toggleBtn.click();
            }
        });
    });
    
    // Auto-save functionality
    let autoSaveTimeout;
    document.getElementById('examForm').addEventListener('input', function() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
            // Auto-save draft (you can implement this feature)
            console.log('Auto-saving draft...');
        }, 5000);
    });
});

// Handle form submission validation
document.getElementById('examForm').addEventListener('submit', function(e) {
    let isValid = true;
    const questions = document.querySelectorAll('.question-card');
    
    questions.forEach((question, index) => {
        // Validate each question has text
        const questionTexts = question.querySelectorAll('textarea[name*="[question]"]');
        let hasText = Array.from(questionTexts).some(textarea => textarea.value.trim());
        
        if (!hasText) {
            isValid = false;
            question.classList.add('validation-error');
            question.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        
        // Validate each question has at least one correct answer
        const correctAnswers = question.querySelectorAll('input[type="checkbox"]:checked, input[type="radio"]:checked');
        if (correctAnswers.length === 0) {
            isValid = false;
            question.classList.add('validation-error');
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        alert('{{ __("Please complete all questions and mark correct answers before submitting.") }}');
    }
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
        
        // Update visual feedback
        markCorrectAnswer(e.target);
    }
    
    // Update summary when points change
    if (e.target.name && e.target.name.includes('[marks]')) {
        updateSummary();
    }
});
</script>

@endsection