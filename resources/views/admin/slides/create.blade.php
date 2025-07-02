@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create New Slide</h1>
        <a href="{{ route('admin.slides') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">Slide Details</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.slides.store') }}" enctype="multipart/form-data" id="slideForm">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label for="text" class="font-weight-bold">Slide Title*</label>
                            <input type="text" class="form-control @error('text') is-invalid @enderror" 
                                   id="text" name="text" value="{{ old('text') }}" required>
                            @error('text')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-4">
                            <label for="content" class="font-weight-bold">PDF File*</label>
                            <div class="mb-3">
                                <input class="form-control form-control-sm @error('content') is-invalid @enderror" 
                                       id="formFileSm" type="file" accept="application/pdf" name="content" required>
                                @error('content')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">Maximum file size: 5MB</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label for="domain_id" class="font-weight-bold">Domain</label>
                            <select class="form-control @error('domain_id') is-invalid @enderror" 
                                    id="domain_id" name="domain_id">
                                <option value="">-- Select Domain --</option>
                                @foreach($domains as $domain)
                                    <option value="{{ $domain->id }}" {{ old('domain_id') == $domain->id ? 'selected' : '' }}>
                                        {{ $domain->text }}
                                    </option>
                                @endforeach
                            </select>
                            @error('domain_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-4">
                            <label for="chapter_id" class="font-weight-bold">Chapter</label>
                            <select class="form-control @error('chapter_id') is-invalid @enderror" 
                                    id="chapter_id" name="chapter_id">
                                <option value="">-- Select Chapter --</option>
                                @foreach($chapters as $chapter)
                                    <option value="{{ $chapter->id }}" {{ old('chapter_id') == $chapter->id ? 'selected' : '' }}>
                                        {{ $chapter->text }}
                                    </option>
                                @endforeach
                            </select>
                            @error('chapter_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Validation Message for Domain/Chapter -->
                @if($errors->has('domain_id') && str_contains($errors->first('domain_id'), 'Either Domain or Chapter'))
                    <div class="alert alert-warning mb-4">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Notice:</strong> Either Domain or Chapter must be selected.
                    </div>
                @endif

                <!-- Questions Section (Initially Hidden) -->
                <div id="questionsSection" class="mt-4" style="display: none;">
                    <div class="card border-success">
                        <div class="card-header bg-success text-white">
                            <h6 class="m-0 font-weight-bold">
                                <i class="fas fa-question-circle"></i> Add Questions for this Slide
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info mb-3">
                                <i class="fas fa-info-circle"></i>
                                <strong>Instructions:</strong> Add questions that will be used to test knowledge about this slide content. 
                                Each question must have at least 2 answers and exactly one correct answer selected.
                            </div>
                            
                            @error('questions')
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror

                            <div id="questionsContainer">
                                <!-- Questions will be added here dynamically -->
                            </div>
                            
                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-success btn-sm" id="addQuestionBtn">
                                    <i class="fas fa-plus"></i> Add Question
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="mt-4">
                <div class="form-group mt-4 text-right">
                    <button type="button" class="btn btn-secondary mr-2" onclick="window.history.back()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save mr-2"></i> Create Slide
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Question Template (Hidden) -->
<template id="questionTemplate">
    <div class="question-item border rounded p-3 mb-3 shadow-sm" data-question-index="">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="text-primary mb-0">
                <i class="fas fa-question-circle"></i> Question <span class="question-number"></span>
            </h6>
            <button type="button" class="btn btn-outline-danger btn-sm remove-question btn-remove">
                <i class="fas fa-trash"></i> Remove
            </button>
        </div>

        <!-- Question in Arabic -->
        <div class="form-group mb-3">
            <label class="font-weight-bold text-right">
                <span class="text-danger">*</span> السؤال بالعربية
            </label>
            <textarea class="form-control question-ar" name="questions[INDEX][question_ar]" 
                      rows="3" required placeholder="اكتب السؤال باللغة العربية..." 
                      dir="rtl" style="text-align: right;"></textarea>
        </div>

        <!-- Question in English -->
        <div class="form-group mb-3">
            <label class="font-weight-bold">
                <span class="text-danger">*</span> Question in English
            </label>
            <textarea class="form-control question-en" name="questions[INDEX][question_en]" 
                      rows="3" required placeholder="Write the question in English..."></textarea>
        </div>

        <!-- Answers Section -->
        <div class="answers-section">
            <label class="font-weight-bold d-block mb-2">
                <span class="text-danger">*</span> Answers (minimum 2 required)
            </label>
            <div class="answers-container">
                <!-- Answers will be added here -->
            </div>
            <div class="text-center mt-2">
                <button type="button" class="btn btn-outline-success btn-sm add-answer">
                    <i class="fas fa-plus"></i> Add Answer
                </button>
            </div>
        </div>
    </div>
</template>

<!-- Answer Template (Hidden) -->
<template id="answerTemplate">
    <div class="answer-item d-flex align-items-center mb-2 p-2 border rounded" data-answer-index="">
        <div class="flex-grow-1 me-2">
            <input type="text" class="form-control answer-ar" 
                   name="questions[QUESTION_INDEX][answers][ANSWER_INDEX][text_ar]" 
                   placeholder="الإجابة بالعربية..." required dir="rtl" style="text-align: right;">
        </div>
        <div class="flex-grow-1 me-2">
            <input type="text" class="form-control answer-en" 
                   name="questions[QUESTION_INDEX][answers][ANSWER_INDEX][text_en]" 
                   placeholder="Answer in English..." required>
        </div>
        <div class="d-flex align-items-center">
            <div class="form-check me-2">
                <input class="form-check-input is-correct-radio" type="radio" 
                       name="questions[QUESTION_INDEX][correct_answer]" value="ANSWER_INDEX">
                <label class="form-check-label text-success" title="Mark as correct answer">
                    <i class="fas fa-check-circle"></i>
                </label>
            </div>
            <button type="button" class="btn btn-outline-danger btn-sm remove-answer btn-remove">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</template>

<style>
    <style>
    .question-item {
        background: linear-gradient(135deg, #f8f9fc 0%, #ffffff 100%);
        border-left: 4px solid #4e73df !important;
        transition: all 0.3s ease;
        position: relative;
        padding: 1.5rem !important;
    }

    .question-item:hover {
        box-shadow: 0 0.25rem 2rem 0 rgba(58, 59, 69, 0.2);
        transform: translateY(-2px);
    }

    .answer-item {
        background-color: #fff;
        border: 1px solid #e3e6f0 !important;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 0.75rem;
    }

    .answer-item:hover {
        border-color: #5a5c69 !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .answer-item .flex-grow-1 {
        flex: 1;
        min-width: 0;
    }

    .answer-item .form-check {
        margin-bottom: 0;
    }

    .form-check-input:checked {
        background-color: #1cc88a;
        border-color: #1cc88a;
    }

    .text-danger {
        color: #e74a3b !important;
    }

    .text-success {
        color: #1cc88a !important;
    }

    #questionsSection {
        animation: slideDown 0.4s ease-in-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .btn-remove {
        transition: all 0.2s ease;
    }

    .btn-remove:hover {
        transform: scale(1.1);
    }

    .border-success {
        border-color: #1cc88a !important;
    }

    .shadow-sm {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }

    /* Arabic text styling */
    textarea[dir="rtl"], input[dir="rtl"] {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        text-align: right;
    }

    /* Loading state */
    .loading {
        opacity: 0.6;
        pointer-events: none;
    }

    /* Success highlight */
    .highlight-success {
        background-color: #d4edda !important;
        border-color: #c3e6cb !important;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .answer-item {
            flex-direction: column;
            align-items: stretch;
        }

        .answer-item .flex-grow-1 {
            margin-right: 0 !important;
            margin-bottom: 10px;
        }

        .answer-item .d-flex {
            justify-content: center;
        }
    }
</style>

<script>
// Use vanilla JavaScript if jQuery issues persist
document.addEventListener('DOMContentLoaded', function() {
    let questionIndex = 0;

    console.log('dkjsahkjhf');
    
    // Get elements
    const domainSelect = document.getElementById('domain_id');
    const questionsSection = document.getElementById('questionsSection');
    const questionsContainer = document.getElementById('questionsContainer');
    const addQuestionBtn = document.getElementById('addQuestionBtn');
    const slideForm = document.getElementById('slideForm');

    // Show questions section when domain is selected
    domainSelect.addEventListener('change', function() {
        if (this.value) {
            questionsSection.style.display = 'block';
            // Add a default question if none exists
            if (questionsContainer.children.length === 0) {
                setTimeout(() => addQuestion(), 200);
            }
        } else {
            questionsSection.style.display = 'none';
            questionsContainer.innerHTML = '';
            questionIndex = 0;
        }
    });

    // Add question button
    addQuestionBtn.addEventListener('click', addQuestion);

    // Event delegation for dynamic elements
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-question') || e.target.closest('.remove-question')) {
            removeQuestion(e.target.closest('.question-item'));
        }
        
        if (e.target.classList.contains('add-answer') || e.target.closest('.add-answer')) {
            const questionItem = e.target.closest('.question-item');
            const questionIdx = questionItem.dataset.questionIndex;
            addAnswer(questionItem, questionIdx);
        }
        
        if (e.target.classList.contains('remove-answer') || e.target.closest('.remove-answer')) {
            removeAnswer(e.target.closest('.answer-item'));
        }
    });

    // Event delegation for radio buttons
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('is-correct-radio')) {
            highlightCorrectAnswer(e.target);
        }
    });

    function addQuestion() {
        const template = document.getElementById('questionTemplate');
        const questionHtml = template.innerHTML.replace(/INDEX/g, questionIndex);
        
        const questionDiv = document.createElement('div');
        questionDiv.innerHTML = questionHtml;
        const questionElement = questionDiv.firstElementChild;
        
        questionElement.dataset.questionIndex = questionIndex;
        questionElement.querySelector('.question-number').textContent = questionIndex + 1;
        
        questionsContainer.appendChild(questionElement);
        
        // Add default answers (4 for multiple choice)
        for (let i = 0; i < 4; i++) {
            addAnswer(questionElement, questionIndex);
        }
        
        questionIndex++;
    }
    function addAnswer(questionElement, questionIdx) {
        const answersContainer = questionElement.querySelector('.answers-container');
        const answerIndex = answersContainer.children.length;
        
        if (answerIndex >= 6) {
            showAlert('Maximum 6 answers allowed per question.', 'warning');
            return;
        }
        
        const template = document.getElementById('answerTemplate');
        let answerHtml = template.innerHTML.replace(/QUESTION_INDEX/g, questionIdx);
        answerHtml = answerHtml.replace(/ANSWER_INDEX/g, answerIndex);
        
        const answerDiv = document.createElement('div');
        answerDiv.innerHTML = answerHtml;
        const answerElement = answerDiv.firstElementChild;
        
        answerElement.dataset.answerIndex = answerIndex;
        answersContainer.appendChild(answerElement);
    }

    function removeQuestion(questionItem) {
        if (confirm('Are you sure you want to remove this question?')) {
            questionItem.remove();
            updateQuestionNumbers();
            
            if (questionsContainer.children.length === 0) {
                questionsSection.style.display = 'none';
            }
        }
    }

    function removeAnswer(answerItem) {
        const answersContainer = answerItem.closest('.answers-container');
        
        if (answersContainer.children.length > 2) {
            answerItem.remove();
        } else {
            showAlert('Each question must have at least 2 answers.', 'warning');
        }
    }

    function highlightCorrectAnswer(radio) {
        const questionItem = radio.closest('.question-item');
        
        // Remove highlight from all answers in this question
        questionItem.querySelectorAll('.answer-item').forEach(item => {
            item.classList.remove('highlight-success');
        });
        
        // Highlight selected answer
        radio.closest('.answer-item').classList.add('highlight-success');
    }

    function updateQuestionNumbers() {
        const questions = questionsContainer.querySelectorAll('.question-item');
        questions.forEach((question, index) => {
            question.querySelector('.question-number').textContent = index + 1;
        });
    }

    function showAlert(message, type = 'info') {
        const alertClass = `alert-${type}`;
        const iconClass = type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle';
        
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert ${alertClass} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            <i class="fas ${iconClass}"></i> ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        `;
        
        questionsSection.insertBefore(alertDiv, questionsSection.firstChild);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 4000);
    }

    // Form validation
    slideForm.addEventListener('submit', function(e) {
        let isValid = true;
        const selectedDomain = domainSelect.value;
        const selectedChapter = document.getElementById('chapter_id').value;
        
        // Check if either domain or chapter is selected
        if (!selectedDomain && !selectedChapter) {
            showAlert('Either Domain or Chapter must be selected.', 'warning');
            isValid = false;
        }
        
        if (selectedDomain) {
            const questions = questionsContainer.querySelectorAll('.question-item');
            
            if (questions.length > 0) {
                questions.forEach((question, index) => {
                    // Check if correct answer is selected
                    const correctAnswer = question.querySelector('.is-correct-radio:checked');
                    if (!correctAnswer) {
                        showAlert(`Question ${index + 1}: Please select a correct answer.`, 'warning');
                        isValid = false;
                        return;
                    }
                    
                    // Check if questions are filled
                    const questionAr = question.querySelector('.question-ar').value.trim();
                    const questionEn = question.querySelector('.question-en').value.trim();
                    if (!questionAr || !questionEn) {
                        showAlert(`Question ${index + 1}: Please fill both Arabic and English questions.`, 'warning');
                        isValid = false;
                        return;
                    }
                    
                    // Check if all answers are filled
                    const answers = question.querySelectorAll('.answer-item');
                    let emptyAnswers = false;
                    
                    answers.forEach((answer, answerIndex) => {
                        const answerAr = answer.querySelector('.answer-ar').value.trim();
                        const answerEn = answer.querySelector('.answer-en').value.trim();
                        if (!answerAr || !answerEn) {
                            showAlert(`Question ${index + 1}, Answer ${answerIndex + 1}: Please fill both Arabic and English answers.`, 'warning');
                            emptyAnswers = true;
                        }
                    });
                    
                    if (emptyAnswers) {
                        isValid = false;
                    }
                });
            }
        }
        
        if (!isValid) {
            e.preventDefault();
        } else {
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Creating...';
        }
    });

    // Show questions section if domain was previously selected (validation errors)
    @if(old('domain_id'))
        questionsSection.style.display = 'block';
    @endif
});
</script>
@endsection