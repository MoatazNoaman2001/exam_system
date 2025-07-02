@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1></h1>
        <h1 class="h3 mb-0 text-gray-800" style="font-size: 28px;">Edit Slide</h1>
        <div class="btn-group">
            @if($slide->content)
                <a href="{{ Storage::url($slide->content) }}" style="margin: 0 10px 0 0" target="_blank" class="btn btn-sm btn-success shadow-sm">
                    <i class="fas fa-file-pdf fa-sm text-white-50"></i> View PDF
                </a>
            @endif
            <a href="{{ route('admin.slides') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">Edit Slide Details</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.slides.update', $slide) }}" enctype="multipart/form-data" id="slideForm">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label for="text" class="font-weight-bold">Slide Title*</label>
                            <input type="text" class="form-control @error('text') is-invalid @enderror" 
                                   id="text" name="text" value="{{ old('text', $slide->text) }}" required>
                            @error('text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-4">
                            <label for="content" class="font-weight-bold">PDF File</label>
                            <div class="mb-3">
                                <input class="form-control form-control-sm @error('content') is-invalid @enderror" 
                                       id="formFileSm" type="file" accept="application/pdf" name="content">
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($slide->content)
                                    <small class="form-text text-success">
                                        <i class="fas fa-check-circle"></i> Current: {{ basename($slide->content) }}
                                    </small>
                                @endif
                            </div>
                            <small class="form-text text-muted">Leave empty to keep current PDF. Maximum file size: 5MB</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label for="domain_id" class="font-weight-bold">Domain</label>
                            <select class="form-control @error('domain_id') is-invalid @enderror" 
                                    id="domain_id" name="domain_id" onchange="toggleQuestionsSection()">
                                <option value="">-- Select Domain --</option>
                                @foreach($domains as $domain)
                                    <option value="{{ $domain->id }}" 
                                            {{ old('domain_id', $slide->domain_id) == $domain->id ? 'selected' : '' }}>
                                        {{ $domain->text }}
                                    </option>
                                @endforeach
                            </select>
                            @error('domain_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                
                        </div>
                        
                        <div class="form-group mb-4">
                            <label for="chapter_id" class="font-weight-bold">Chapter</label>
                            <select class="form-control @error('chapter_id') is-invalid @enderror" 
                                    id="chapter_id" name="chapter_id">
                                <option value="">-- Select Chapter --</option>
                                @foreach($chapters as $chapter)
                                    <option value="{{ $chapter->id }}" 
                                            {{ old('chapter_id', $slide->chapter_id) == $chapter->id ? 'selected' : '' }}>
                                        {{ $chapter->text }}
                                    </option>
                                @endforeach
                            </select>
                            @error('chapter_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Current Questions Display -->
                @if($slide->tests->count() > 0)
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle"></i>
                        <strong>Current Status:</strong> This slide has {{ $slide->tests->count() }} existing question(s). 
                        Adding new questions below will replace all existing questions.
                    </div>
                @endif

                <!-- Validation Messages -->
                @if($errors->any())
                    <div class="alert alert-warning mb-4">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Please check the following:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Questions Section -->
                <div id="questionsSection" class="mt-4" style="display: {{ $slide->domain_id ? 'block' : 'none' }};">
                    <div class="card border-success">
                        <div class="card-header bg-success text-white">
                            <h6 class="m-0 font-weight-bold">
                                <i class="fas fa-question-circle"></i> Questions for this Slide
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info mb-3">
                                <i class="fas fa-info-circle"></i>
                                <strong>Instructions:</strong> You can edit existing questions or add new ones. 
                                Note: Adding questions here will replace all existing questions.
                            </div>
                            
                            <div id="questionsContainer">
                                <!-- Existing questions will be loaded here or new ones added -->
                            </div>
                            
                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-success btn-sm" onclick="addQuestion()">
                                    <i class="fas fa-plus"></i> Add Question
                                </button>
                                @if($slide->tests->count() > 0)
                                    <button type="button" class="btn btn-info btn-sm ml-2" onclick="loadExistingQuestions()">
                                        <i class="fas fa-download"></i> Load Existing Questions
                                    </button>
                                @endif
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
                        <i class="fas fa-save mr-2"></i> Update Slide
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Current Questions Preview (if any) -->
    @if($slide->tests->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-eye"></i> Current Questions Preview
                </h6>
            </div>
            <div class="card-body">
                @foreach($slide->tests as $index => $test)
                    <div class="existing-question-preview mb-3 p-3 border rounded bg-light">
                        <h6 class="text-primary">Question {{ $index + 1 }}</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Arabic:</strong>
                                <p class="text-right" dir="rtl">{{ $test->question_ar }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>English:</strong>
                                <p>{{ $test->question_en }}</p>
                            </div>
                        </div>
                        <strong>Answers:</strong>
                        <div class="row">
                            @foreach($test->answers as $answer)
                                <div class="col-md-6 mb-1">
                                    <small class="badge text-black {{ $answer->is_correct ? 'text-primary' : 'text-black' }}">
                                        {{ $answer->text_en }} / {{ $answer->text_ar }}
                                        @if($answer->is_correct) ✓ @endif
                                    </small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<style>
    .question-item {
        background: linear-gradient(135deg, #f8f9fc 0%, #ffffff 100%);
        border-left: 4px solid #4e73df !important;
        margin-bottom: 1rem;
        padding: 1rem;
        border-radius: 0.35rem;
        border: 1px solid #e3e6f0;
    }

    .answer-item {
        background-color: #fff;
        border: 1px solid #e3e6f0;
        margin-bottom: 0.5rem;
        padding: 0.5rem;
        border-radius: 0.25rem;
    }

    .highlight-success {
        background-color: #d4edda !important;
        border-color: #c3e6cb !important;
    }

    .text-right-arabic {
        text-align: right;
        direction: rtl;
    }

    .existing-question-preview {
        border-left: 4px solid #17a2b8 !important;
    }

    .question-item:hover {
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
</style>


<script>
let questionIndex = 0;
const existingQuestions = @json($slide->tests->load('answers'));

function toggleQuestionsSection() {
    const domainSelect = document.getElementById('domain_id');
    const questionsSection = document.getElementById('questionsSection');
    
    if (domainSelect.value) {
        questionsSection.style.display = 'block';
    } else {
        questionsSection.style.display = 'none';
        document.getElementById('questionsContainer').innerHTML = '';
        questionIndex = 0;
    }
}

function addQuestion() {
    const questionsContainer = document.getElementById('questionsContainer');
    
    const questionHtml = `
        <div class="question-item" data-question-index="${questionIndex}">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="text-primary mb-0">
                    <i class="fas fa-question-circle"></i> Question ${questionIndex + 1}
                </h6>
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeQuestion(this)">
                    <i class="fas fa-trash"></i> Remove
                </button>
            </div>

            <div class="form-group">
                <label class="font-weight-bold">
                    <span class="text-danger">*</span> السؤال بالعربية
                </label>
                <textarea class="form-control question-ar text-right-arabic" 
                          name="questions[${questionIndex}][question_ar]" 
                          rows="2" required placeholder="اكتب السؤال باللغة العربية..."></textarea>
            </div>

            <div class="form-group">
                <label class="font-weight-bold">
                    <span class="text-danger">*</span> Question in English
                </label>
                <textarea class="form-control question-en" 
                          name="questions[${questionIndex}][question_en]" 
                          rows="2" required placeholder="Write the question in English..."></textarea>
            </div>

            <div class="answers-section">
                <label class="font-weight-bold d-block mb-2">
                    <span class="text-danger">*</span> Answers
                </label>
                <div class="answers-container" data-question-index="${questionIndex}">
                    <!-- Answers will be added here -->
                </div>
                <div class="text-center mt-2">
                    <button type="button" class="btn btn-outline-success btn-sm" onclick="addAnswer(${questionIndex})">
                        <i class="fas fa-plus"></i> Add Answer
                    </button>
                </div>
            </div>
        </div>
    `;
    
    questionsContainer.insertAdjacentHTML('beforeend', questionHtml);
    
    // Add default answers
    for (let i = 0; i < 4; i++) {
        addAnswer(questionIndex);
    }
    
    questionIndex++;
}

function addAnswer(questionIdx) {
    const answersContainer = document.querySelector(`[data-question-index="${questionIdx}"].answers-container`);
    const answerIndex = answersContainer.children.length;
    
    if (answerIndex >= 6) {
        alert('Maximum 6 answers allowed per question.');
        return;
    }
    
    const answerHtml = `
        <div class="answer-item row" data-answer-index="${answerIndex}">
            <div class="col-5">
                <input type="text" class="form-control answer-ar text-right-arabic" 
                       name="questions[${questionIdx}][answers][${answerIndex}][text_ar]" 
                       placeholder="الإجابة بالعربية..." required>
            </div>
            <div class="col-5">
                <input type="text" class="form-control answer-en" 
                       name="questions[${questionIdx}][answers][${answerIndex}][text_en]" 
                       placeholder="Answer in English..." required>
            </div>
            <div class="col-2">
                <div class="d-flex align-items-center justify-content-center h-100">
                    <div class="form-check mr-2">
                        <input class="form-check-input is-correct-radio" type="radio" 
                               name="questions[${questionIdx}][correct_answer]" value="${answerIndex}"
                               onchange="highlightCorrectAnswer(this)">
                        <label class="form-check-label text-success" title="Mark as correct answer">
                            <i class="fas fa-check-circle"></i>
                        </label>
                    </div>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeAnswer(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    answersContainer.insertAdjacentHTML('beforeend', answerHtml);
}

function removeQuestion(button) {
    if (confirm('Are you sure you want to remove this question?')) {
        button.closest('.question-item').remove();
        updateQuestionNumbers();
    }
}

function removeAnswer(button) {
    const answerItem = button.closest('.answer-item');
    const answersContainer = answerItem.closest('.answers-container');
    
    if (answersContainer.children.length > 2) {
        answerItem.remove();
    } else {
        alert('Each question must have at least 2 answers.');
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
    const questions = document.querySelectorAll('.question-item');
    questions.forEach((question, index) => {
        question.querySelector('h6').innerHTML = `<i class="fas fa-question-circle"></i> Question ${index + 1}`;
    });
}

function loadExistingQuestions() {
    if (confirm('This will load existing questions for editing. Any current form data will be replaced. Continue?')) {
        document.getElementById('questionsContainer').innerHTML = '';
        questionIndex = 0;
        
        existingQuestions.forEach((test, index) => {
            addQuestion();
            
            // Fill question data
            const questionItem = document.querySelector(`[data-question-index="${index}"]`);
            questionItem.querySelector('.question-ar').value = test.question_ar || '';
            questionItem.querySelector('.question-en').value = test.question_en || '';
            
            // Clear default answers and add existing ones
            const answersContainer = questionItem.querySelector('.answers-container');
            answersContainer.innerHTML = '';
            
            test.answers.forEach((answer, answerIndex) => {
                addAnswer(index);
                const answerItem = answersContainer.children[answerIndex];
                answerItem.querySelector('.answer-ar').value = answer.text_ar || '';
                answerItem.querySelector('.answer-en').value = answer.text_en || '';
                
                if (answer.is_correct) {
                    const radio = answerItem.querySelector('.is-correct-radio');
                    radio.checked = true;
                    highlightCorrectAnswer(radio);
                }
            });
        });
    }
}

// Form validation
document.getElementById('slideForm').addEventListener('submit', function(e) {
    let isValid = true;
    const selectedDomain = document.getElementById('domain_id').value;
    const selectedChapter = document.getElementById('chapter_id').value;
    
    // Check if either domain or chapter is selected
    if (!selectedDomain && !selectedChapter) {
        alert('Either Domain or Chapter must be selected.');
        isValid = false;
    }
    
    if (selectedDomain) {
        const questions = document.querySelectorAll('.question-item');
        
        if (questions.length > 0) {
            questions.forEach((question, index) => {
                // Check if correct answer is selected
                const correctAnswer = question.querySelector('.is-correct-radio:checked');
                if (!correctAnswer) {
                    alert(`Question ${index + 1}: Please select a correct answer.`);
                    isValid = false;
                    return;
                }
                
                // Check if questions are filled
                const questionAr = question.querySelector('.question-ar').value.trim();
                const questionEn = question.querySelector('.question-en').value.trim();
                if (!questionAr || !questionEn) {
                    alert(`Question ${index + 1}: Please fill both Arabic and English questions.`);
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
                        alert(`Question ${index + 1}, Answer ${answerIndex + 1}: Please fill both Arabic and English answers.`);
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
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Updating...';
    }
});

// Initialize if domain is already selected
document.addEventListener('DOMContentLoaded', function() {
    const domainSelect = document.getElementById('domain_id');
    if (domainSelect.value) {
        document.getElementById('questionsSection').style.display = 'block';
    }
});
</script>
@endsection